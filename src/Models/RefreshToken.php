<?php
namespace Nebo15\LumenOauth2\Models;

/**
 * Class Token
 * @package Nebo15\LumenOauth2\Models
 */
class RefreshToken extends Base
{
    protected $fillable = [ 'refresh_token', 'client_id', 'expires', 'scope'];
}
