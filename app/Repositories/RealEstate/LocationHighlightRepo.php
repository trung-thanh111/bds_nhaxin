<?php  
namespace App\Repositories\RealEstate;
use App\Models\LocationHighlight;
use App\Repositories\BaseRepository;

class LocationHighlightRepo  extends BaseRepository{

    protected $model;

    public function __construct(
        LocationHighlight $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}