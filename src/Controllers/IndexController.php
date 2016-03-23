<?php
namespace Nebo15\LumenOauth2\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class IndexController extends BaseController
{
    public function index(Request $request)
    {
        return response(json_encode($request->user()));
    }
}
