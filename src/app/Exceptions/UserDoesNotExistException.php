<?php

namespace Payment\System\App\Exceptions;

class UserDoesNotExistException extends BusinessLogicException
{
    /**
     * @return string
     */
    public function statusCode(): string
    {
        return 'user.notExist';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.userNotExist');
    }
}
