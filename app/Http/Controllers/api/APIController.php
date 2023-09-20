<?php

namespace App\Http\Controllers\api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class APIController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
