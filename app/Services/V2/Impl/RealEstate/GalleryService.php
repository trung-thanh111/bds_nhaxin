<?php

namespace App\Services\V2\Impl\RealEstate;

use App\Services\V2\BaseService;
use App\Repositories\RealEstate\GalleryRepo;
use Illuminate\Support\Facades\Auth;

class GalleryService extends BaseService
{

    protected $repository;

    protected $fillable;

    protected $with = ['users', 'properties'];

    public function __construct(
        GalleryRepo $repository,
    ) {
        $this->repository = $repository;
    }

    public function prepareModelData(): static
    {
        $request = $this->context['request'] ?? null;
        if (!is_null($request)) {
            $this->fillable = $this->repository->getFillable();
            $this->modelData = $request->only($this->fillable);
            $this->modelData['user_id'] = Auth::id();

            // Tự động lấy ảnh đầu tiên làm đại diện
            if (isset($this->modelData['album']) && !empty($this->modelData['album'])) {
                $album = (is_string($this->modelData['album'])) ? json_decode($this->modelData['album'], true) : $this->modelData['album'];
                if (is_array($album) && count($album) > 0) {
                    $this->modelData['image'] = $album[0];
                }
            }
        }
        return $this;
    }
}
