<?php

namespace App\Services\V2\Impl\RealEstate;

use App\Services\V2\BaseService;
use App\Repositories\RealEstate\FloorplanRepo;
use Illuminate\Support\Facades\Auth;

class FloorplanService extends BaseService
{

    protected $repository;

    protected $fillable;

    protected $with = ['users', 'properties'];

    public function __construct(
        FloorplanRepo $repository,
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
        }
        return $this;
    }
}
