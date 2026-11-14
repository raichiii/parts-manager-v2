<?php

namespace App\Listeners;

use App\Events\JobCreated;
use App\Services\Line\LineBotService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LineEventSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    protected $lineBotService;

    public function __construct(LineBotService $lineBotService)
    {
        $this->lineBotService = $lineBotService;
    }

    public function handleJobCreated($event, $roomId=null)
    {
        $message = 'Jobの作成('.auth()->user()->name."): ".$event->number;
        $this->lineBotService->setLineBot();
        $this->lineBotService->sendMessage($roomId, $message);
    }

    public function handleOrderCreated($event, $roomId=null)
    {
        $model_no = $event->thing? $event->thing->model_no : ($event->unregisteredModelNo()? $event->unregisteredModelNo()->name : '品名なし');
        $message = '依頼の作成('.auth()->user()->name."): ".$model_no;
        $this->lineBotService->setLineBot();
        $this->lineBotService->sendMessage($roomId, $message);
    }

    public function subscribe($events)
    {
        $events->listen(
            JobCreated::class,
            [LineEventSubscriber::class, 'handleJobCreated']
        );
    }
}
