<?php


namespace Payment\System\App\Services;


use Payment\System\App\Exceptions\AccessTokenAlreadyRevokedException;
use App\Models\User;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Token;

class AccessTokenService extends Service
{
    /**
     * @param User $user
     * @param array $withRelations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAccessTokensByUser(User $user, array $withRelations)
    {
        return $user->tokens()->with($withRelations)->get();
    }


    /**
     * @param Token $accessToken
     * @param array $withRelations
     * @throws AccessTokenAlreadyRevokedException
     */
    public function revoke(Token $accessToken, array $withRelations)
    {
        if ($accessToken->revoked) {
            throw new AccessTokenAlreadyRevokedException();
        }
        $accessToken->fill(
            [
                'revoked' => true
            ]
        )->load($withRelations)->save();

        return $accessToken;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getById(string $id)
    {
        return AccessToken::where('id', $id)->first();
    }
}
