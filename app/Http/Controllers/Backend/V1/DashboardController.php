<?php

namespace App\Http\Controllers\Backend\V1;

use App\Http\Controllers\Controller;

use App\Services\V2\Impl\RealEstate\RealEstateStatisticService;

class DashboardController extends Controller
{
    protected $statisticService;

    public function __construct(
        RealEstateStatisticService $statisticService
    ) {
        $this->statisticService = $statisticService;
    }

    public function index()
    {
        $stats = $this->statisticService->getStats();
        $recentVisitRequests = $this->statisticService->getRecentVisitRequests();

        $config = $this->config();
        $template = 'backend.dashboard.home.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'stats',
            'recentVisitRequests'
        ));
    }

    private function config()
    {
        return [
            'js' => [
                'backend/js/plugins/chartJs/Chart.min.js',
                'backend/library/dashboard.js',
            ]
        ];
    }
}
