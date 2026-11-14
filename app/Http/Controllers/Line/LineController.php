<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\Services\Line\LineBotService;
use Illuminate\Http\Request;

class LineController extends Controller
{

    public function __construct(LineBotService $lineBotService)
    {
        $this->lineBotService = $lineBotService;
    }

    public function webhook(Request $request)
    {
        try {
            $this->lineBotService->setLineBot();
            $this->lineBotService->webhook($request);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
