<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\ClothesList\IndexController as ClothesListController;
use App\Http\Controllers\Main\Groups\IndexController as GroupsController;

class IndexController extends Controller
{
    public function __invoke(ClothesListController $clothesListController, GroupsController $groupsController)
    {
        /** @var TYPE_NAME $clothesListController */
        $data = $clothesListController->__invoke();
        $jsonData = $groupsController->__invoke();

        return view('main.index', compact('data', 'jsonData'));
    }
}
