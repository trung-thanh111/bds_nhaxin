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

    public function __construct(
        PostCatalogueRepository $postCatalogueRepository,
        PostCatalogueService $postCatalogueService,
        PostService $postService,
        PostRepository $postRepository,
        WidgetService $widgetService,
        PropertyService $propertyService
    ) {
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->postCatalogueService = $postCatalogueService;
        $this->postService = $postService;
        $this->postRepository = $postRepository;
        $this->widgetService = $widgetService;
        $this->propertyService = $propertyService;
        parent::__construct();
    }


    public function index(Request $request, $page = 1)
    {
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
        )->take(4);

        $property = $this->propertyService->findByCondition([['publish', '=', 2]]);

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
            'property'
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
