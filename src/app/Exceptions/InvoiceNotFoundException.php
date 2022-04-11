<?php

namespace Payment\System\App\Exceptions;

use Exception;

class InvoiceNotFoundException extends Exception
{
    public function statusCode(): string
    {
        return 'invoice.notFound';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.invoiceNotFound');
    }
}
