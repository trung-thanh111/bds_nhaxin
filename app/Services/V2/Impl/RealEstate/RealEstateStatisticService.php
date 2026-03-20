<?php

namespace App\Services\V2\Impl\RealEstate;

use App\Repositories\RealEstate\AgentRepo;
use App\Repositories\RealEstate\PropertyRepo;
use App\Repositories\RealEstate\VisitRequestRepo;
use App\Repositories\RealEstate\FloorplanRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RealEstateStatisticService
{
    protected $agentRepo;
    protected $propertyRepo;
    protected $visitRequestRepo;
    protected $floorplanRepo;

    public function __construct(
        AgentRepo $agentRepo,
        PropertyRepo $propertyRepo,
        VisitRequestRepo $visitRequestRepo,
        FloorplanRepo $floorplanRepo
    ) {
        $this->agentRepo = $agentRepo;
        $this->propertyRepo = $propertyRepo;
        $this->visitRequestRepo = $visitRequestRepo;
        $this->floorplanRepo = $floorplanRepo;
    }

    public function getStats()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Counts
        $agentCount = $this->agentRepo->all()->count();
        $propertyCount = $this->propertyRepo->all()->count();
        $visitRequestCount = $this->visitRequestRepo->all()->count();
        $floorplanCount = $this->floorplanRepo->all()->count();

        // Growth for Visit Requests
        $currentMonthVR = $this->visitRequestRepo->findByCondition([
            ['created_at', '>=', $startOfMonth]
        ], true)->count();

        $lastMonthVR = $this->visitRequestRepo->findByCondition([
            ['created_at', '>=', $startOfLastMonth],
            ['created_at', '<=', $endOfLastMonth]
        ], true)->count();

        $growth = 0;
        if ($lastMonthVR > 0) {
            $growth = (($currentMonthVR - $lastMonthVR) / $lastMonthVR) * 100;
        } elseif ($currentMonthVR > 0) {
            $growth = 100;
        }

        return [
            'agentCount' => $agentCount,
            'propertyCount' => $propertyCount,
            'visitRequestCount' => $visitRequestCount,
            'floorplanCount' => $floorplanCount,
            'currentMonthVR' => $currentMonthVR,
            'lastMonthVR' => $lastMonthVR,
            'growth' => round($growth, 2),
            'vrChart' => $this->getVRChartData()
        ];
    }

    public function getVRChartData($type = 1)
    {
        $labels = [];
        $datasets = [];

        if ($type == 1) { // Annual (by month)
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = "Tháng $i";
                $datasets[] = $this->visitRequestRepo->findByCondition([
                    [DB::raw('MONTH(created_at)'), '=', $i],
                    [DB::raw('YEAR(created_at)'), '=', date('Y')]
                ], true)->count();
            }
        }

        return [
            'label' => $labels,
            'data' => $datasets
        ];
    }

    public function getRecentVisitRequests($limit = 10)
    {
        return $this->visitRequestRepo->findByCondition([], true, ['properties'], ['id', 'DESC'])->take($limit);
    }
}
