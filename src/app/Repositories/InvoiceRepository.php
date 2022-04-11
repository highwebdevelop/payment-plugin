<?php


namespace Payment\System\App\Repositories;

use Payment\System\App\Models\Invoice;

class InvoiceRepository extends Repository
{
    /**
     * InvoiceRepository constructor.
     * @param Invoice $model
     */
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $atributes
     * @return Invoice
     */
    public function create(array $atributes)
    {
        return Invoice::create($atributes);
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function firstByUuid($uuid) {
        return $this->getModel()
            ->where('uuid', $uuid)
            ->first();
    }
}
