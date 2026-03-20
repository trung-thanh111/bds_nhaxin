<?php  
namespace App\Repositories\RealEstate;
use App\Models\Gallery;
use App\Repositories\BaseRepository;

class GalleryRepo  extends BaseRepository{

    protected $model;

    public function __construct(
        Gallery $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}