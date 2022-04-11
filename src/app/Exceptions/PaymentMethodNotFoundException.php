<?php


namespace Payment\System\App\Exceptions;


class PaymentMethodNotFoundException extends BusinessLogicException
{
    /**
     * @return string
     */
    public function statusCode(): string
    {
        return 'paymentMethod.notFound';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.paymentMethodNotFound');
    }
}
