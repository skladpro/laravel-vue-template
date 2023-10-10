<?php

namespace App\Console\Commands;

use App\Services\API\ApiClothesService;
use Illuminate\Console\Command;

class SynkServicesCommand extends Command
{
    public function __construct( protected readonly ApiClothesService $apiClothesService)
    {
        parent::__construct();
    }

    protected $signature = 'app:sync';

    protected $description = 'Command description';

    public function handle()
    {
        $result = $this->apiClothesService->services();
    }
}
