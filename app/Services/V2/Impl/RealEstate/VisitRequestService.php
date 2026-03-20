<?php

namespace App\Services\V2\Impl\RealEstate;

use App\Services\V2\BaseService;
use App\Repositories\RealEstate\VisitRequestRepo;
use Illuminate\Support\Facades\Auth;

class VisitRequestService extends BaseService
{

    protected $repository;

    protected $fillable;

    protected $with = ['users', 'properties', 'agents'];

    public function __construct(
        VisitRequestRepo $repository,
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
