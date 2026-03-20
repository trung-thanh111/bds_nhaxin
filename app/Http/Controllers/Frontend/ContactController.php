<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\V2\Impl\RealEstate\PropertyService;
use App\Services\V2\Impl\RealEstate\AgentService;
use Illuminate\Http\Request;

class ContactController extends FrontendController
{
    protected $propertyService;
    protected $agentService;

    public function __construct(
        PropertyService $propertyService,
        AgentService $agentService
    ) {
        $this->propertyService = $propertyService;
        $this->agentService = $agentService;
        parent::__construct();
    }

    /**
     * Contact page
     */
    public function index()
    {
        $property = $this->propertyService->findByCondition([['publish', '=', 2]]);
        $agents = $this->agentService->findByCondition(
            condition: [['publish', '=', 2]],
            flag: true
        );

        $system = $this->system;
        $seo = $this->buildSeo('Liên Hệ — HomePark');
        $schema = $this->schema($seo);
        $config = $this->config();

        return view('frontend.contact.index', compact(
            'config',
            'seo',
            'system',
            'schema',
            'property',
            'agents',
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
