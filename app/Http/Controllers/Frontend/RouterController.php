<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Core\RouterRepository;
use App\Models\Post;
use App\Models\PostCatalogue;
use App\Http\Controllers\Frontend\PostCatalogueController;

class RouterController extends FrontendController
{
    protected $language;
    protected $routerRepository;
    protected $router;

    public function __construct(
        RouterRepository $routerRepository,
    ) {
        $this->routerRepository = $routerRepository;
        parent::__construct();
    }


    public function index(string $canonical = '', Request $request)
    {
        $this->getRouter($canonical);
        if (!is_null($this->router) && !empty($this->router)) {
            $method = 'index';
            $controller = app($this->router->controllers);

            // Build argument list for the controller method by inspecting its parameters
            $args = [];
            try {
                $ref = new \ReflectionMethod($controller, $method);
                foreach ($ref->getParameters() as $param) {
                    $pname = $param->getName();
                    $ptype = $param->getType();

                    // Match common parameter names/types
                    if ($ptype && $ptype->getName() === \Illuminate\Http\Request::class) {
                        $args[] = $request;
                    } elseif (stripos($pname, 'page') !== false) {
                        $args[] = $request->query('page', 1);
                    } else {
                        // fallback to module_id for other scalar params
                        $args[] = $this->router->module_id;
                    }
                }
            } catch (\ReflectionException $e) {
                // on error, fall back to original ordering
                $args = [$this->router->module_id, $request];
            }

            $response = $controller->{$method}(...$args);
            if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
                return $response;
            }
            echo $response;
        } else {
            $slug = $canonical;
            $slugNoHtml = (str_ends_with($slug, '.html')) ? substr($slug, 0, -5) : $slug;

            $post = Post::whereHas('languages', function ($q) use ($slug, $slugNoHtml) {
                $q->where(function ($q2) use ($slug, $slugNoHtml) {
                    $q2->where('post_language.canonical', $slug)
                        ->orWhere('post_language.canonical', $slugNoHtml);
                });
            })->whereHas('languages', function ($q) {
                $q->where('post_language.language_id', $this->language);
            })->first();

            if ($post) {
                $controller = app(PostCatalogueController::class);
                echo $controller->detail($post->id, $request);
                return;
            }

            $postCatalogue = PostCatalogue::whereHas('languages', function ($q) use ($slug, $slugNoHtml) {
                $q->where(function ($q2) use ($slug, $slugNoHtml) {
                    $q2->where('post_catalogue_language.canonical', $slug)
                        ->orWhere('post_catalogue_language.canonical', $slugNoHtml);
                });
            })->whereHas('languages', function ($q) {
                $q->where('post_catalogue_language.language_id', $this->language);
            })->first();

            if ($postCatalogue) {
                $controller = app(PostCatalogueController::class);
                echo $controller->index($postCatalogue->id, $request);
                return;
            }

            // Project Fallback
            $project = \App\Models\Project::whereHas('languages', function ($q) use ($slug, $slugNoHtml) {
                $q->where(function ($q2) use ($slug, $slugNoHtml) {
                    $q2->where('project_language.canonical', $slug)
                        ->orWhere('project_language.canonical', $slugNoHtml);
                });
            })->whereHas('languages', function ($q) {
                $q->where('project_language.language_id', $this->language);
            })->first();

            if ($project) {
                $controller = app(\App\Http\Controllers\Frontend\ProjectController::class);
                echo $controller->index($project->id, $request);
                return;
            }

            // RealEstate Fallback
            $realEstate = \App\Models\RealEstate::whereHas('languages', function ($q) use ($slug, $slugNoHtml) {
                $q->where(function ($q2) use ($slug, $slugNoHtml) {
                    $q2->where('real_estate_language.canonical', $slug)
                        ->orWhere('real_estate_language.canonical', $slugNoHtml);
                });
            })->whereHas('languages', function ($q) {
                $q->where('real_estate_language.language_id', $this->language);
            })->first();

            if ($realEstate) {
                $controller = app(\App\Http\Controllers\Frontend\RealEstateController::class);
                echo $controller->index($realEstate->id, $request);
                return;
            }

            abort(404);
        }
    }

    public function page(string $canonical = '', $page = 1, Request $request)
    {
        $this->getRouter($canonical);
        $request->merge(['page' => $page]);
        $page = (!isset($page)) ? 1 : $page;
        if (!is_null($this->router) && !empty($this->router)) {
            $method = 'index';
            $controller = app($this->router->controllers);
            $args = [];
            try {
                $ref = new \ReflectionMethod($controller, $method);
                foreach ($ref->getParameters() as $param) {
                    $pname = $param->getName();
                    $ptype = $param->getType();

                    if ($ptype && $ptype->getName() === \Illuminate\Http\Request::class) {
                        $args[] = $request;
                    } elseif (stripos($pname, 'page') !== false) {
                        $args[] = $page;
                    } else {
                        $args[] = $this->router->module_id;
                    }
                }
            } catch (\ReflectionException $e) {
                $args = [$this->router->module_id, $request, $page];
            }

            $response = $controller->{$method}(...$args);
            if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
                return $response;
            }
            echo $response;
        } else {
            abort(404);
        }
    }

    public function getRouter($canonical)
    {
        $canonical = (str_ends_with($canonical, '.html')) ? substr($canonical, 0, -5) : $canonical;
        $this->router = $this->routerRepository->findByCondition(
            [
                ['canonical', '=', $canonical],
                ['language_id', '=', $this->language]
            ]
        );
    }
}
