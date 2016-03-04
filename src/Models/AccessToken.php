<?php
namespace Nebo15\LumenOauth2\Models;

/**
 * Class Token
 * @package Nebo15\LumenOauth2\Models
 */
class AccessToken extends Base
{
    protected $fillable = [ 'access_token', 'client_id', 'expires', 'scope'];
}
