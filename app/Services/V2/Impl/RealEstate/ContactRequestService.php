<?php

namespace App\Services\V2\Impl\RealEstate;

use App\Services\V2\BaseService;
use App\Repositories\RealEstate\ContactRequestRepo;
use Illuminate\Support\Facades\Auth;

class ContactRequestService extends BaseService
{

    protected $repository;

    protected $fillable;

    protected $with = ['users', 'projects', 'agents'];

    public function __construct(
        ContactRequestRepo $repository,
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
