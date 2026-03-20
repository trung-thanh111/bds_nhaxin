<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Services\V2\Impl\RealEstate\LocationHighlightService;
use Illuminate\Http\Request;

class NeighbourhoodController extends FrontendController
{
    protected $propertyService;
    protected $locationHighlightService;

    public function __construct(
        PropertyService $propertyService,
        LocationHighlightService $locationHighlightService
    ) {
        $this->propertyService = $propertyService;
        $this->locationHighlightService = $locationHighlightService;
        parent::__construct();
    }

    public function index()
    {
        $property = $this->propertyService->findByCondition([['publish', '=', 2]]);
        $locationHighlights = $this->locationHighlightService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true,
            orderBy: ['id', 'desc']
        );

        $system = $this->system;
        $seo = $this->buildSeo('Xung Quanh — ' . ($property->name ?? 'Linden Vietnam'));
        $schema = $this->schema($seo);
        $config = $this->config();

        return view('frontend.neighbourhood.index', compact(
            'config',
            'seo',
            'system',
            'schema',
            'property',
            'locationHighlights'
        ));
    }

    private function buildSeo($title = null)
    {
        return [
            'meta_title' => $title ?? ($this->system['seo_meta_title'] ?? 'Linden Vietnam'),
            'meta_keyword' => $this->system['seo_meta_keyword'] ?? '',
            'meta_description' => $this->system['seo_meta_description'] ?? '',
            'meta_image' => $this->system['seo_meta_images'] ?? '',
            'canonical' => url('/xung-quanh.html'),
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
