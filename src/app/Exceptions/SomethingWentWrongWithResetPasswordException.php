<?php


namespace Payment\System\App\Exceptions;


class SomethingWentWrongWithResetPasswordException extends BusinessLogicException
{
    public function statusCode(): string
    {
        return 'password.canNotReset';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.passwordCanNotReset');
    }
}
