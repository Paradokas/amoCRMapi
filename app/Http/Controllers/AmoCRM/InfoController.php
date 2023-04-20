<?php

namespace App\Http\Controllers\AmoCRM;

use App\Http\Controllers\Controller;
use App\Services\AmoCRMService;

class InfoController extends Controller
{
    private AmoCRMService $amoCRMService;

    public function __construct(AmoCRMService $amoCRMService)
    {
        $this->amoCRMService = $amoCRMService;
    }

    public function index(): void
    {
        $amo = $this->amoCRMService->init();
        print_r($amo->account->toArray());
    }
}
