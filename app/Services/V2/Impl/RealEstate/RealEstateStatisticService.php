<?php

namespace App\Services\V2\Impl\RealEstate;

use App\Repositories\RealEstate\AgentRepo;
use App\Repositories\RealEstate\PropertyRepo;
use App\Repositories\RealEstate\ContactRequestRepo;
use App\Repositories\RealEstate\FloorplanRepo;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\RealEstate\RealEstateRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RealEstateStatisticService
{
    protected $agentRepo;
    protected $contactRequestRepo;
    protected $realEstateRepository;
    protected $projectRepository;

    public function __construct(
        AgentRepo $agentRepo,
        ContactRequestRepo $contactRequestRepo,
        RealEstateRepository $realEstateRepository,
        ProjectRepository $projectRepository
    ) {
        $this->agentRepo = $agentRepo;
        $this->contactRequestRepo = $contactRequestRepo;
        $this->realEstateRepository = $realEstateRepository;
        $this->projectRepository = $projectRepository;
    }

    public function getStats()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Counts
        $agentCount = $this->agentRepo->all()->count();
        $realEstateCount = $this->realEstateRepository->all()->count();
        $contactRequestCount = $this->contactRequestRepo->all()->count();
        $projectCount = $this->projectRepository->all()->count();

        // Growth for Contact Requests
        $currentMonthCR = $this->contactRequestRepo->findByCondition([
            ['created_at', '>=', $startOfMonth]
        ], true)->count();

        $lastMonthCR = $this->contactRequestRepo->findByCondition([
            ['created_at', '>=', $startOfLastMonth],
            ['created_at', '<=', $endOfLastMonth]
        ], true)->count();

        $growth = 0;
        if ($lastMonthCR > 0) {
            $growth = (($currentMonthCR - $lastMonthCR) / $lastMonthCR) * 100;
        } elseif ($currentMonthCR > 0) {
            $growth = 100;
        }

        return [
            'agentCount' => $agentCount,
            'realEstateCount' => $realEstateCount,
            'contactRequestCount' => $contactRequestCount,
            'projectCount' => $projectCount,
            'currentMonthCR' => $currentMonthCR,
            'lastMonthCR' => $lastMonthCR,
            'growth' => round($growth, 2),
            'crChart' => $this->getCRChartData()
        ];
    }

    public function getCRChartData($type = 1)
    {
        $labels = [];
        $datasets = [];

        if ($type == 1) { // Annual (by month)
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = "Tháng $i";
                $datasets[] = $this->contactRequestRepo->findByCondition([
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

    public function getRecentContactRequests($limit = 10)
    {
        return $this->contactRequestRepo->findByCondition([], true, ['projects'], ['id', 'DESC'])->take($limit);
    }
}
