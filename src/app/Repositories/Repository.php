<?php


namespace Payment\System\App\Repositories;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Repository
{
    private $model;
    private $with = [];

    /**
     * Repository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $relations
     * @return $this
     */
    public function with(array $relations)
    {
        $this->with = $relations;
        return $this;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function prepareQuery(Model $query)
    {
        return $query->with($this->with);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @return Model
     */
    public function getModel()
    {

//        $this->prepareQuery($this->model);

        return $this->model;
    }
}
