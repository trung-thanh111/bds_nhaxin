<?php  
namespace App\Repositories\RealEstate;
use App\Models\Floorplan;
use App\Repositories\BaseRepository;

class FloorplanRepo  extends BaseRepository{

    protected $model;

    public function __construct(
        Floorplan $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}