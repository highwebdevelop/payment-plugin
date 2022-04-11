<?php

namespace Payment\System\App\Exceptions;

use Exception;

class TransactionNotFoundException extends Exception
{
    public function statusCode(): string
    {
        return 'transaction.notFound';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.transactionNotFound');
    }
}
