<?php

namespace App\Services\Line;

use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\AccountLinkEvent;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use Log;


class LineBotService
{
    private $bot;

    private $roomId;

    public function __construct()
    {
        $this->roomId = env('LINE_ID_PARTS_MANAGER');
    }

    public function setLineBot()
    {
        $this->bot = app('line-bot');
    }

    public function isValidSignature($request)
    {
        $this->signature = $request->headers->get(HTTPHeader::LINE_SIGNATURE);
        return SignatureValidator::validateSignature(
            $request->getContent(),
            env('LINE_CHANNEL_SECRET'),
            $this->signature
        );
    }

    public function webhook($request)
    {
        if (!$this->isValidSignature($request)) {
            throw new \Exception('リクエストが不正です。');
        }

        $events = $this->bot->parseEventRequest($request->getContent(), $this->signature);

        foreach ($events as $event) {
            switch (true) {
                case $event instanceof TextMessage: //メッセージを受け取った場合
                    $this->textMessageEvent($event);
                    break;
                case $event instanceof FollowEvent: //フォロー？した場合
                    $this->followEvent($event);
                    break;
                default:
                    break;
            }
        }
    }

    public function sendMessage($id, $message)
    {
        if (isset($id)) {
            return $this->bot->pushMessage($id, new TextMessageBuilder($message));
        }

        return $this->bot->pushMessage($this->roomId, new TextMessageBuilder($message));
        // return $this->bot->broadcast(new TextMessageBuilder($message));
    }

    public function textMessageEvent($event)
    {
        $userId = $event->getUserId();
        $text = $event->getText();
        switch ($text) {
            case 'user-id':
                $this->bot->pushMessage($userId, new TextMessageBuilder($userId));
                break;
            case 'room-id':
                $roomId = $event->getRoomId();
                $this->bot->pushMessage($roomId, new TextMessageBuilder($roomId));
            case 'group-id':
                $groupId = $event->getGroupId();
                $this->bot->pushMessage($groupId, new TextMessageBuilder($groupId));
            default:
                // オウム返し
                $this->bot->pushMessage($userId, new TextMessageBuilder($text));
        }
    }

    public function followEvent($event)
    {
        $userId = $event->getUserId();
        $this->bot->pushMessage($userId, new TextMessageBuilder($userId . '友達登録ありがとね^^'));
    }
}
