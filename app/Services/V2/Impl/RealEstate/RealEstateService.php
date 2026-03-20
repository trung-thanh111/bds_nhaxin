<?php  
namespace App\Services\V2\Impl\RealEstate;

use App\Services\V2\BaseService;
use App\Repositories\RealEstate\RealEstateRepository;
use Illuminate\Support\Facades\Auth;

class RealEstateService extends BaseService 
{
    protected $repository;
    protected $fillable;
    protected $with = ['users', 'languages'];

    public function __construct(
        RealEstateRepository $repository,
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
