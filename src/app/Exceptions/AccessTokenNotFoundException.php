<?php


namespace Payment\System\App\Exceptions;


class AccessTokenNotFoundException extends BusinessLogicException
{
    /**
     * @return string
     */
    public function statusCode(): string
    {
        return 'accessToken.notFound';
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return __('business.errors.accessTokenNotFound');
    }
}
