<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\V2\Impl\RealEstate\GalleryService;
use App\Services\V2\Impl\RealEstate\FloorplanService;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Services\V2\Impl\RealEstate\GalleryCatalogueService;
use Illuminate\Http\Request;

class GalleryController extends FrontendController
{
    protected $galleryService;
    protected $floorplanService;
    protected $propertyService;
    protected $galleryCatalogueService;

    public function __construct(
        GalleryService $galleryService,
        FloorplanService $floorplanService,
        PropertyService $propertyService,
        GalleryCatalogueService $galleryCatalogueService
    ) {
        $this->galleryService = $galleryService;
        $this->floorplanService = $floorplanService;
        $this->propertyService = $propertyService;
        $this->galleryCatalogueService = $galleryCatalogueService;
        parent::__construct();
    }

    /**
     * Gallery page
     */
    public function index()
    {
        $galleryCatalogues = $this->galleryCatalogueService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            relation: ['languages', 'galleries' => function ($query) {
                $query->where('publish', 2);
            }],
            orderBy: ['order', 'desc'] // or 'id' => 'desc'
        );

        $galleries = $this->galleryService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['id', 'desc']
        );
        $floorplans = $this->floorplanService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            relation: ['rooms'],
            orderBy: ['order', 'asc']
        );
        $property = $this->propertyService->findByCondition([['publish', '=', 2]]);

        $system = $this->system;
        $seo = $this->buildSeo('Thư Viện Ảnh — HomePark');
        $schema = $this->schema($seo);
        $config = $this->config();

        return view('frontend.gallery.index', compact(
            'config',
            'seo',
            'system',
            'schema',
            'galleryCatalogues',
            'galleries',
            'floorplans',
            'property'
        ));
    }

    // ------ Helpers ------

    private function buildSeo($title = null)
    {
        return [
            'meta_title' => $title ?? ($this->system['seo_meta_title'] ?? 'HomePark'),
            'meta_keyword' => $this->system['seo_meta_keyword'] ?? '',
            'meta_description' => $this->system['seo_meta_description'] ?? '',
            'meta_image' => $this->system['seo_meta_images'] ?? '',
            'canonical' => config('app.url'),
        ];
    }

    public function schema(array $seo = []): string
    {
        return "<script type='application/ld+json'>
            {
                \"@context\": \"https://schema.org\",
                \"@type\": \"WebSite\",
                \"name\": \"" . ($seo['meta_title'] ?? '') . "\",
                \"url\": \"" . ($seo['canonical'] ?? '') . "\",
                \"description\": \"" . ($seo['meta_description'] ?? '') . "\"
            }
        </script>";
    }

    private function config()
    {
        return [
            'language' => $this->language,
            'css' => [],
            'js' => []
        ];
    }
}
