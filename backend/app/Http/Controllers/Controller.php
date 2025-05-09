<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base controller that other controllers extend.
 *
 * This provides shared functionality like authorization.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests;
}
