<?php


namespace Payment\System\App\Exceptions;


class SomethingwentToWrongWithPaymentException extends BusinessLogicException
{
    public function statusCode(): string
    {
        return 'payment.error';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.paymentError');
    }
}
