<?php

namespace Payment\System\App\Exceptions;

use Exception;

class SubscriptionNotFoundException extends BusinessLogicException
{
    public function statusCode(): string
    {
        return 'subscription.notFound';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.subscriptionNotFound');
    }
}
