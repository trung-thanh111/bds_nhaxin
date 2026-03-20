<?php  
namespace App\Repositories\RealEstate;
use App\Models\Agent;
use App\Repositories\BaseRepository;

class AgentRepo  extends BaseRepository{

    protected $model;

    public function __construct(
        Agent $model
    )
    {
        $this->model = $model;
        parent::__construct($model);
    }
}