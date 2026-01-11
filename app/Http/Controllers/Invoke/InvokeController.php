<?php

namespace App\Http\Controllers\Invoke;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvokeController extends Controller
{
    public function __invoke()
    {
        print_r('I am invoke controller');
    }
}
