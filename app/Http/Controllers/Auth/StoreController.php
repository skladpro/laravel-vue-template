<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRequest;
use App\Services\API\ApiAuthService;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    protected readonly ApiAuthService $apiAuthService;

    public function __construct (ApiAuthService $apiAuthService)
    {
        $this->apiAuthService = $apiAuthService;
    }

    public function __invoke(StoreRequest $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        $result = $this->apiAuthService->authenticate($login, $password);

        if (is_string($result)) {
            return redirect()->back()->withErrors(['errorMessage' => $result]);
        }

        Session::put('login', $login);
        return redirect()->route('main.index');
    }
}
