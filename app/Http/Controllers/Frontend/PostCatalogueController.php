<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\Post\PostCatalogueRepository;
use App\Services\V1\Post\PostCatalogueService;
use App\Services\V1\Post\PostService;
use App\Services\V1\Core\WidgetService;
use App\Services\V1\Core\SlideService;

use App\Models\System;
use App\Enums\SlideEnum;
use Jenssegers\Agent\Facades\Agent;
use App\Models\Introduce;
use App\Models\Post;
use App\Repositories\Post\PostRepository;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\View\Components\TableOfContents;

class PostCatalogueController extends FrontendController
{
    protected $language;
    protected $system;
    protected $postCatalogueRepository;
    protected $postCatalogueService;
    protected $postService;
    protected $widgetService;
    protected $slideService;
    protected $postRepository;
    protected $propertyService;

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
        PostCatalogueService $postCatalogueService,
        PostService $postService,
        WidgetService $widgetService,
        SlideService $slideService,
        PostRepository $postRepository,
        PropertyService $propertyService
    ) {
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->postCatalogueService = $postCatalogueService;
        $this->postService = $postService;
        $this->widgetService = $widgetService;
        $this->slideService = $slideService;
        $this->postRepository = $postRepository;
        $this->propertyService = $propertyService;
        parent::__construct();
    }


    public function index($id, $request, $page = 1)
    {
        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $postCatalogue->children = $this->postCatalogueRepository->findByCondition(
            [
                ['publish', '=', 2],
                ['parent_id', '=', $postCatalogue->id]
            ],
            true,
            [],
            ['order', 'desc']
        );

        $breadcrumb = $this->postCatalogueRepository->breadcrumb($postCatalogue, $this->language);
        $posts = $this->postService->paginate(
            $request,
            $this->language,
            $postCatalogue,
            $page,
            ['path' => $postCatalogue->canonical],
            ['posts.recommend', 'desc']
        );

        $featuredPost = $this->postCatalogueRepository->getFeaturedPost($postCatalogue);

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-products'],
            ['keyword' => 'product-category', 'children' => true],
            ['keyword' => 'product-category-highlight', 'object' => true],
            ['keyword' => 'about-us-2'],
        ], $this->language);

        $slides = $this->slideService->getSlide([
            SlideEnum::MAIN
        ], $this->language);

        $lastestNews = Post::with(['languages'])->orderBy('order', 'desc')->orderBy('id', 'desc')->where(['publish' => 2])->limit(8)->get();

        $rootId = ($postCatalogue->parent_id == 0) ? $postCatalogue->id : $postCatalogue->parent_id;
        $categories = $this->postCatalogueRepository->findByCondition(
            [
                ['publish', '=', 2],
                ['parent_id', '=', $rootId]
            ],
            true,
            ['languages'],
            ['order', 'desc']
        )->take(4);

        $property = $this->propertyService->findByCondition([['publish', 2]]);

        $template = 'frontend.post.catalogue.index';

        $config = $this->config();
        $system = $this->system;
        $seo = seo($postCatalogue, $page);
        $introduce = convert_array(Introduce::where('language_id', $this->language)->get(), 'keyword', 'content');
        $schema = $this->schema($postCatalogue, $posts, $breadcrumb);

        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'postCatalogue',
            'posts',
            'widgets',
            'schema',
            'slides',
            'introduce',
            'lastestNews',
            'categories',
            'property'
        ));
    }

    public function detail($id, $request)
    {
        $language = $this->language;
        $post = $this->postRepository->getPostById($id, $this->language, config('apps.general.defaultPublish'));
        if (is_null($post)) {
            abort(404);
        }

        // increment viewed
        $viewed = $post->viewed;
        Post::where('id', $id)->update(['viewed' => $viewed + 1]);

        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($post->post_catalogue_id, $this->language);
        $breadcrumb = $this->postCatalogueRepository->breadcrumb($postCatalogue, $this->language);

        $asidePost = $this->postService->paginate(
            $request,
            $this->language,
            $postCatalogue,
            1,
            ['path' => $postCatalogue->canonical],
        );

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-products'],
            ['keyword' => 'product-category', 'children' => true],
            ['keyword' => 'product-category-highlight', 'object' => true],
            ['keyword' => 'about-us-2'],
        ], $this->language);

        $config = $this->config();
        $system = $this->system;
        $seo = seo($post);

        $lastestNews = Post::with(['languages'])->orderBy('order', 'desc')->orderBy('id', 'desc')->where(['publish' => 2])->limit(8)->get();

        $schema = $this->schema($postCatalogue, collect([$post]), $breadcrumb);

        $content = $post->languages->first()->pivot->content;
        $items = TableOfContents::extract($content);
        $contentWithToc = TableOfContents::injectIds($content, $items);

        $wordCount = str_word_count(strip_tags($content));
        $readingTime = max(1, (int) ceil($wordCount / 200));

        $previous = Post::where('post_catalogue_id', $post->post_catalogue_id)
            ->where('publish', config('apps.general.defaultPublish'))
            ->where('id', '<', $post->id)
            ->orderBy('id', 'desc')
            ->first();

        $next = Post::where('post_catalogue_id', $post->post_catalogue_id)
            ->where('publish', config('apps.general.defaultPublish'))
            ->where('id', '>', $post->id)
            ->orderBy('id', 'asc')
            ->first();

        $rootId = ($postCatalogue->parent_id == 0) ? $postCatalogue->id : $postCatalogue->parent_id;
        $categories = $this->postCatalogueRepository->findByCondition(
            [
                ['publish', '=', 2],
                ['parent_id', '=', $rootId]
            ],
            true,
            ['languages'],
            ['order', 'desc']
        )->take(10); // Take more for sidebar

        $template = 'frontend.post.post.index';

        return view($template, compact(
            'config',
            'seo',
            'system',
            'breadcrumb',
            'postCatalogue',
            'post',
            'asidePost',
            'widgets',
            'schema',
            'contentWithToc',
            'lastestNews',
            'readingTime',
            'previous',
            'next',
            'items',
            'categories' // Added categories
        ));
    }

    private function schema($postCatalogue, $posts, $breadcrumb)
    {
        $cat_name = $postCatalogue->languages->first()->pivot->name;

        $cat_canonical = write_url($postCatalogue->languages->first()->pivot->canonical);

        $cat_description = strip_tags($postCatalogue->languages->first()->pivot->description);

        $itemListElements = '';

        $position = 1;

        foreach ($posts as $post) {
            $name = $post->languages->first()->pivot->name;
            $canonical = write_url($post->languages->first()->pivot->canonical);
            $itemListElements .= "
                {
                    \"@type\": \"BlogPosting\",
                    \"headline\": \" " . $name . " \",
                    \"url\": \" " . $canonical . " \",
                    \"datePublished\": \" " . convertDateTime($post->created_at, 'd-m-Y') . " \",
                    \"author\": {
                        \"@type\": \" Person  \",
                        \"name\": \" An Hưng \",
                    }
                },";
            $position++;
        }

        $itemListElements = rtrim($itemListElements, ',');

        $itemBreadcrumbElements = '';

        $positionBreadcrumb = 2;

        foreach ($breadcrumb as $key => $item) {
            $name = $item->languages->first()->pivot->name;
            $canonical = write_url($item->languages->first()->pivot->canonical);
            $itemBreadcrumbElements .= "
                {
                    \"@type\": \"ListItem\",
                    \"position\": $positionBreadcrumb,
                    \"name\": \"" . $name . "\",
                    \"item\": \"" . $canonical . "\",
                },";
            $positionBreadcrumb++;
        }

        $itemBreadcrumbElements = rtrim($itemBreadcrumbElements, ',');

        $schema = "<script type='application/ld+json'>
            {
                \"@type\": \"BreadcrumbList\",
                \"itemListElement\": [
                    {
                        \"@type\": \"ListItem\",
                        \"position\": 1,
                        \"name\": \" Trang chủ  \",
                        \"item\": \" " . config('app.url') . " \"
                    },
                    $itemBreadcrumbElements
                ]
            },
            {
                \"@context\": \"https://schema.org\",
                \"@type\": \"Blog\",
                \"name\": \"" . $cat_name . "\",
                \"description\": \" " . $cat_description . " \",
                \"url\": \"" . $cat_canonical . "\",
                \"publisher\": [
                    \"@type\": \"Organization\",
                    \"name\": \" An Hưng \",
                ],
                \"blogPost\": {
                    $itemListElements
                }
            }
            </script>";
        return $schema;
    }

    private function config()
    {
        return [
            'language' => $this->language,
            'css' => [
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css',
                'frontend/resources/css/custom.css'
            ],
            'js' => [
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/owl.carousel.min.js',
                'frontend/resources/library/js/carousel.js',
                'https://getuikit.com/v2/src/js/components/sticky.js'
            ]
        ];
    }
}
