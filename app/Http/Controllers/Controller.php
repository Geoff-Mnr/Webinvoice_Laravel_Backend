<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;


abstract class Controller
{
    /**
     * The "booting" method of the controller.
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
