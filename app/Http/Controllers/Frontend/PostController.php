<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Repositories\Post\PostCatalogueRepository;
use App\Services\V1\Post\PostCatalogueService;
use App\Services\V1\Post\PostService;
use App\Repositories\Post\PostRepository;
use App\Services\V1\Core\WidgetService;
use App\Models\Post;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Repositories\RealEstate\RealEstateCatalogueRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use App\Repositories\RealEstate\ProjectCatalogueRepository as ProjectCatalogueRepo;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\RealEstate\AgentRepo;
use App\View\Components\TableOfContents;

class postController extends FrontendController
{
    protected $language;
    protected $system;
    protected $postCatalogueRepository;
    protected $postCatalogueService;
    protected $postService;
    protected $postRepository;
    protected $widgetService;
    protected $propertyService;
    protected $realEstateCatalogueRepository;
    protected $realEstateRepository;
    protected $projectCatalogueRepo;
    protected $projectRepository;
    protected $agentRepo;

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
        PostCatalogueService $postCatalogueService,
        PostService $postService,
        PostRepository $postRepository,
        WidgetService $widgetService,
        PropertyService $propertyService,
        RealEstateCatalogueRepository $realEstateCatalogueRepository,
        RealEstateRepository $realEstateRepository,
        ProjectCatalogueRepo $projectCatalogueRepo,
        ProjectRepository $projectRepository,
        AgentRepo $agentRepo
    ) {
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->postCatalogueService = $postCatalogueService;
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->widgetService = $widgetService;
        $this->propertyService = $propertyService;
        $this->realEstateCatalogueRepository = $realEstateCatalogueRepository;
        $this->realEstateRepository = $realEstateRepository;
        $this->projectCatalogueRepo = $projectCatalogueRepo;
        $this->projectRepository = $projectRepository;
        $this->agentRepo = $agentRepo;
        parent::__construct();
    }


    public function index(Request $request, $id = null, $page = 1)
    {
        if ($id) {
            return $this->detail($id, $request);
        }
        $posts = $this->postService->paginate($request, $this->language, null, $page, ['path' => 'bai-viet.html']);

        $config = $this->config();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Bài viết',
            'meta_description' => '',
            'canonical' => url('bai-viet.html'),
            'meta_image' => ''
        ];

        $lastestNews = Post::with(['languages'])->orderBy('order', 'desc')->orderBy('id', 'desc')->where(['publish' => 2])->limit(8)->get();

        $categories = $this->postCatalogueRepository->findByCondition(
            [
                ['publish', '=', 2],
                ['parent_id', '=', 0]
            ],
            true,
            ['languages'],
            ['order', 'desc']
        )->take(10);

        $property = $this->propertyService->findByCondition([['publish', '=', 2]]);
        
        // Sidebar data
        $realEstateCatalogues = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages']);

        $projectCatalogues = $this->projectCatalogueRepo->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages']);

        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        $newestRealEstates = $this->realEstateRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages'], ['id', 'DESC'])->take(8);

        $featuredProjects = $this->projectRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages'], ['id', 'DESC'])->take(8);

        $breadcrumb = [];
        $postCatalogue = null;

        return view('frontend.post.catalogue.index', compact(
            'config',
            'seo',
            'system',
            'posts',
            'lastestNews',
            'categories',
            'breadcrumb',
            'postCatalogue',
            'property',
            'realEstateCatalogues',
            'projectCatalogues',
            'agent',
            'newestRealEstates',
            'featuredProjects'
        ));
    }

    public function detail($id, Request $request)
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

        $schema = $this->schema($post, $postCatalogue, $breadcrumb);

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

        $categories = $this->postCatalogueRepository->findByCondition(
            [
                ['publish', '=', 2],
                ['parent_id', '=', 0]
            ],
            true,
            ['languages'],
            ['order', 'desc']
        )->take(10);

        // Sidebar data
        $realEstateCatalogues = $this->realEstateCatalogueRepository->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages']);

        $projectCatalogues = $this->projectCatalogueRepo->findByCondition([
            ['publish', '=', 2],
            ['parent_id', '=', 0]
        ], true, ['languages']);

        $agent = $this->agentRepo->findByCondition([
            ['is_primary', '=', 1],
            ['publish', '=', 2]
        ], false);

        $newestRealEstates = $this->realEstateRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages'], ['id', 'DESC'])->take(8);

        $featuredProjects = $this->projectRepository->findByCondition([
            ['publish', '=', 2]
        ], true, ['languages'], ['id', 'DESC'])->take(8);

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
            'categories', // Added categories
            'realEstateCatalogues',
            'projectCatalogues',
            'agent',
            'newestRealEstates',
            'featuredProjects'
        ));
    }

    private function schema($post, $postCatalogue, $breadcrumb)
    {

        $image = $post->image;

        $name = $post->languages->first()->pivot->name;

        $description = strip_tags($post->languages->first()->pivot->description);

        $canonical = write_url($post->languages->first()->pivot->canonical);

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

        $schema = "
            <script type=\"application/ld+json\">
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
                    \"@type\": \"BlogPosting\",
                    \"headline\": \" " . $name .  " \",
                    \"description\": \"  " . $description .  "  \",
                    \"image\": \"  " . $image .  "  \",
                    \"url\": \"  " . $canonical .  "  \",
                    \"datePublished\": \"  " . convertDateTime($post->created_at, 'd-m-y') .  "  \",
                    \"dateModified\": \"  " . convertDateTime($post->created_at, 'd-m-y') .  "  \",
                    \"author\": [
                        \"@type\": \"Person\",
                        \"name\": \"\",
                        \"url\": \"\",
                    ],
                    \"publisher\": [
                        \"@type\": \"Organization\",
                        \"name\": \" An Hưng  \",
                        \"logo\": [
                            \"@type\": \"ImageObject\",
                            \"url\": \"   \",
                        ],
                    ],
                    \"mainEntityOfPage\": [
                        \"@type\": \"Organization\",
                        \"@id\": \" " . $canonical . " \",
                    ],
                    \"articleSection\": \"  " . $postCatalogue->languages->first()->pivot->name .  "  \",
                    \" keywords \": \"  \",
                    \" timeRequired \": \"  \",
                    \"about\": [
                        \"@type\": \"Thing\",
                        \"name\": \" \",
                    ],
                    \"mentions\": [
                        {
                            \"@type\": \"Product\",
                            \"name\": \" \",
                        }
                    ],
                }
            </script>
        ";
        return $schema;
    }

    private function config()
    {
        return [
            'language' => $this->language,
            'js' => [
                'frontend/core/library/cart.js',
                'frontend/core/library/product.js',
                'frontend/core/library/review.js',
                'https://prohousevn.com/scripts/fancybox-3/dist/jquery.fancybox.min.js'
            ],
            'css' => [
                'frontend/core/css/product.css',
                'https://prohousevn.com/scripts/fancybox-3/dist/jquery.fancybox.min.css'
            ]
        ];
    }
}
