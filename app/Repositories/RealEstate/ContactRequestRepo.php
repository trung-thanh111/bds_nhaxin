<?php  
namespace App\Repositories\RealEstate;
use App\Models\ContactRequest;
use App\Repositories\BaseRepository;

class ContactRequestRepo extends BaseRepository{

    protected $model;

    public function __construct(
        ContactRequest $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}