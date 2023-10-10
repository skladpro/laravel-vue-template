<?php

namespace App\Http\Controllers\Auth\Logout;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class IndexController extends Controller
{
    public function __invoke()
    {
        Session::forget('login');
        return redirect()->route('auth.index');
    }
}
