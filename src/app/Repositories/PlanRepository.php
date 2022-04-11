<?php


namespace Payment\System\App\Repositories;


use Payment\System\App\Models\Plan;
use Illuminate\Database\Eloquent\Model;

class PlanRepository extends Repository
{
    /**
     * PlanRepository constructor.
     * @param Plan $model
     */
    public function __construct(Plan $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all()
    {
        return $this->getModel()->all();
    }

    /**
     * @param $planId
     * @return mixed
     */
    public function findById($planId)
    {
        return $this->getModel()->where('planId', $planId)->first();
    }
}
