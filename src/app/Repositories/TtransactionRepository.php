<?php

namespace Payment\System\App\Repositories;

use Payment\System\App\Models\Transaction;

/**
 * Class TtransactionRepository.
 */
class TtransactionRepository extends Repository
{
    /**
     * TtransactionRepository constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        parent::__construct($transaction);
    }

    /**
     * @param Transaction $transaction
     * @param string $uuid
     * @param string $status
     * @return mixed
     */
    public function update(Transaction $transaction, $uuid, $status)
    {
        $transaction->update([
            'uuid' => $uuid,
            'status' => $status,
        ]);

        return $this->firstByUuid($transaction->uuid);
    }

    /**
     * @param string $uuid
     * @return mixed
     */
    public function firstByUuid(string $uuid)
    {
        return $this->getModel()
            ->where('uuid', $uuid)
            ->first();
    }
}
