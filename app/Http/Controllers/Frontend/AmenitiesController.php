<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Services\V2\Impl\RealEstate\PropertyFacilityService;
use App\Services\V2\Impl\RealEstate\GalleryService;
use Illuminate\Http\Request;

class AmenitiesController extends FrontendController
{
    protected $propertyService;
    protected $facilityService;
    protected $galleryService;

    public function __construct(
        PropertyService $propertyService,
        PropertyFacilityService $facilityService,
        GalleryService $galleryService
    ) {
        $this->propertyService = $propertyService;
        $this->facilityService = $facilityService;
        $this->galleryService = $galleryService;
        parent::__construct();
    }

    public function index()
    {
        $property = $this->propertyService->findByCondition([['publish', '=', 2]]);
        $facilities = $this->facilityService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['id', 'desc']
        );
        $galleries = $this->galleryService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['id', 'desc']
        );

        $system = $this->system;
        $seo = $this->buildSeo('Tiện Nghi — ' . ($property->name ?? 'Linden Vietnam'));
        $schema = $this->schema($seo);
        $config = $this->config();

        return view('frontend.amenities.index', compact(
            'config',
            'seo',
            'system',
            'schema',
            'property',
            'facilities',
            'galleries'
        ));
    }

    private function buildSeo($title = null)
    {
        return [
            'meta_title' => $title ?? ($this->system['seo_meta_title'] ?? 'Linden Vietnam'),
            'meta_keyword' => $this->system['seo_meta_keyword'] ?? '',
            'meta_description' => $this->system['seo_meta_description'] ?? '',
            'meta_image' => $this->system['seo_meta_images'] ?? '',
            'canonical' => url('/tien-nghi.html'),
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
