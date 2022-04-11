<?php


namespace Payment\System\App\Repositories;

use Payment\System\App\Models\Item;

class ItemRepository extends Repository
{
    /**
     * ItemRepository constructor.
     * @param Item $model
     */
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $atributes
     * @return Item
     */
    public function create(array $atributes)
    {
        return Item::create($atributes);
    }
}
