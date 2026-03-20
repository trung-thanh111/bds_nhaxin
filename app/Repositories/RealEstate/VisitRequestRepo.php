<?php  
namespace App\Repositories\RealEstate;
use App\Models\VisitRequest;
use App\Repositories\BaseRepository;

class VisitRequestRepo  extends BaseRepository{

    protected $model;

    public function __construct(
        VisitRequest $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}