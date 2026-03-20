<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use App\Repositories\Core\DistrictRepository;
use App\Repositories\User\ProvinceRepository;

class LocationController extends Controller
{
    protected $districtRepository;
    protected $provinceRepository;

    // tgian cache json
    const JSON_CACHE_TTL = 3600;

    public function __construct(
        DistrictRepository $districtRepository,
        ProvinceRepository $provinceRepository
    ) {
        $this->districtRepository = $districtRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function getLocation(Request $request)
    {
        $target     = $request->input('target');
        $locationId = $request->input('data.location_id');
        $source     = $request->input('source', 'db');

        if ($source === 'db') {
            if (str_starts_with($target, 'old_')) {
                $source = 'before';
            } elseif (in_array($target, ['wards', 'districts'])) {
                $source = 'after';
            }
        }

        if ($source !== 'db') {
            return $this->getLocationFromJson($target, $locationId, $source);
        }

        $html = '<option value="0">[Không có dữ liệu]</option>';
        if ($target === 'districts') {
            $province = $this->provinceRepository->findById($locationId, ['code', 'name'], ['districts']);
            $html = $this->renderHtml($province->districts ?? []);
        } elseif ($target === 'wards') {
            $district = $this->districtRepository->findById($locationId, ['code', 'name'], ['wards']);
            $html = $this->renderHtml($district->wards ?? [], '[Chọn Phường/Xã]');
        }

        return response()->json(['html' => $html]);
    }

    private function getLocationFromJson(string $target, $locationId, string $source)
    {
        $data = $this->getJsonData($source);

        if (empty($data)) {
            return response()->json(['html' => '<option value="0">Không có dữ liệu</option>']);
        }

        $html = '<option value="0">[Không tìm thấy dữ liệu]</option>';

        switch ($target) {
            case 'wards':
                $province = collect($data)->firstWhere('codename', $locationId);
                if ($province && isset($province['wards'])) {
                    $html = $this->renderHtmlFromJson($province['wards'], '[Chọn Phường/Xã]');
                }
                break;

            case 'old_districts':
                $province = collect($data)->firstWhere('codename', $locationId);
                if ($province) {
                    if (isset($province['districts'])) {
                        $html = $this->renderHtmlFromJson($province['districts'], '[Chọn Quận/Huyện]');
                    } elseif (isset($province['wards'])) {
                        $html = $this->renderHtmlFromJson($province['wards'], '[Chọn Phường/Xã]');
                    }
                }
                break;

            case 'old_wards':
                $district = $this->findDistrictInData($data, $locationId);
                if ($district && isset($district['wards'])) {
                    $html = $this->renderHtmlFromJson($district['wards'], '[Chọn Phường/Xã]');
                }
                break;
        }

        return response()->json(['html' => $html]);
    }

    private function findDistrictInData(array $data, $districtCodename): ?array
    {
        foreach ($data as $province) {
            if (!isset($province['districts'])) continue;
            $found = collect($province['districts'])->firstWhere('codename', $districtCodename);
            if ($found) return $found;
        }
        return null;
    }

    private function getJsonData(string $source): array
    {
        $cacheKey = 'location_json_' . $source;
        return Cache::remember($cacheKey, 3600, function () use ($source) {
            $filePath = resource_path('json/vie_address_' . $source . '_1_7.json');
            if (!File::exists($filePath)) return [];
            return json_decode(File::get($filePath), true) ?? [];
        });
    }

    private function renderHtmlFromJson(array $items, string $placeholder): string
    {
        $html = '<option value="0">' . $placeholder . '</option>';
        foreach ($items as $item) {
            $html .= '<option value="' . $item['codename'] . '">' . $item['name'] . '</option>';
        }
        return $html;
    }

    public function renderHtml($items, string $placeholder = '[Chọn Quận/Huyện]'): string
    {
        $html = '<option value="0">' . $placeholder . '</option>';
        foreach ($items as $item) {
            $html .= '<option value="' . $item->code . '">' . $item->name . '</option>';
        }
        return $html;
    }
}
