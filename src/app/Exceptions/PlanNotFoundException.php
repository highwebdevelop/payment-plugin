<?php


namespace Payment\System\App\Exceptions;


class PlanNotFoundException extends BusinessLogicException
{
    /**
     * @return string
     */
    public function statusCode(): string
    {
        return 'plan.notFound';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.planTokenNotFound');
    }
}
