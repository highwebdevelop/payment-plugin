<?php


namespace Payment\System\App\Repositories;


class TransactionRepository extends Repository
{
    public function __construct(Tram $model)
    {
        parent::__construct($model);
    }
}
