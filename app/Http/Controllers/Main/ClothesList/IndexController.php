<?php

namespace App\Http\Controllers\Main\ClothesList;

use App\Http\Controllers\Controller;
use App\Services\API\ApiClothesService;

class IndexController extends Controller
{
    public function __construct(private readonly ApiClothesService $apiClothesService)
    {

    }

    public function __invoke()
    {
        $data = $this->apiClothesService->getServices();
        session(['data' => $data]);
//        return $data;
    }
}
