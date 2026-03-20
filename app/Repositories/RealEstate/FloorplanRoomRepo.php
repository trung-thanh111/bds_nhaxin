<?php  
namespace App\Repositories\RealEstate;
use App\Models\FloorplanRoom;
use App\Repositories\BaseRepository;

class FloorplanRoomRepo  extends BaseRepository{

    protected $model;

    public function __construct(
        FloorplanRoom $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}