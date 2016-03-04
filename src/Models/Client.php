<?php
namespace Nebo15\LumenOauth2\Models;

class Client extends Base
{
    protected $collection = 'oauth_clients';

    public static function findByClientId($client_id)
    {
        return self::where(['client_id' => $client_id])->first();
    }
}
