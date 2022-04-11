<?php

namespace Payment\System\App\Exceptions;

class UserEmailAlreadyExistException extends BusinessLogicException
{
    /**
     * @return string
     */
    public function statusCode(): string
    {
        return 'user.emailAlreadyExist';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.userEmailAlreadyExist');
    }
}
