<?php

namespace App\Http\Controllers\Main\Groups;

use App\Http\Controllers\Controller;
use App\Services\API\ApiGroupsService;

class IndexController extends Controller
{
    public function __construct(private readonly ApiGroupsService $apiGroupsService)
    {

    }

    public function __invoke()
    {
        $jsonData = $this->apiGroupsService->getServices();
        session(['jsonData' => $jsonData]);
        return $jsonData;
    }
}
