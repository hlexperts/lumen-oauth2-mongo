<?php
namespace Nebo15\LumenOauth2\Traits;

use Illuminate\Hashing\BcryptHasher;
use Nebo15\LumenOauth2\Models\AccessToken;
use Nebo15\LumenOauth2\Models\RefreshToken;

trait Oauthable {

    public function getUserByUsername($username)
    {
        return $this->where(['username' => $username])->first();
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPasswordHasher()
    {
        return (new BcryptHasher());
    }

    public function findByAccessToken($access_token)
    {
        return self::where('accessTokens.access_token', '=', $access_token)->first();
    }

    public function findByRefreshToken($refresh_token)
    {
        return self::where('refreshTokens.refresh_token', '=', $refresh_token)->first();
    }

    public function accessTokens()
    {
        return $this->embedsMany('Nebo15\LumenOauth2\Models\AccessToken');
    }

    public function getAccessToken($access_token)
    {
        return $this->accessTokens()->where('access_token', $access_token)->first();
    }

    public function setAccessToken($data)
    {
        $token = ($data instanceof AccessToken) ? $data : new AccessToken($data);
        $this->accessTokens()->associate($token);
        return $this;
    }

    public function deleteAccessToken($access_token)
    {
        $this->accessTokens()->dissociate($this->getAccessToken($access_token));
        return $this;
    }

    public function refreshTokens()
    {
        return $this->embedsMany('Nebo15\LumenOauth2\Models\RefreshToken');
    }

    public function getRefreshToken($refresh_token)
    {
        return $this->refreshTokens()->where('refresh_token', $refresh_token)->first();
    }

    public function setRefreshToken($data)
    {
        $token = ($data instanceof RefreshToken) ? $data : new RefreshToken($data);
        $this->refreshTokens()->associate($token);
        return $this;
    }

    public function deleteRefreshToken($refresh_token)
    {
        $this->refreshTokens()->dissociate($this->getRefreshToken($refresh_token));
        return $this;
    }
}