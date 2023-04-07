<?php

namespace App\Http\Controllers\AmoCRM;

use App\Http\Controllers\Controller;
use App\Services\AmoCRMService;

class InfoController extends Controller
{
    public function index(): void
    {
        $amo = (new AmoCRMService())->init();
        print_r($amo->account->toArray());
    }
}
