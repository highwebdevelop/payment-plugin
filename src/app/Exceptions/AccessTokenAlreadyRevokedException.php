<?php


namespace Payment\System\App\Exceptions;


class AccessTokenAlreadyRevokedException extends BusinessLogicException
{
    /**
     * @return string
     */
    public function statusCode(): string
    {
        return 'accessToken.alreadyRevoked';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.accessTokenAlreadyRevoked');
    }
}
