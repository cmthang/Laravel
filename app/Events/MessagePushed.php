<?php

namespace App\Events;

use App\Models\UserActivity;
use App\Utils\JobHelper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessagePushed implements ShouldBroadcast
{
    use SerializesModels;

    public $html;
    public $message;

    public function __construct($data)
    {
        $userActivity = UserActivity::getByCondition(['id' => $data['id'], 'limit' => 1]);
        $this->html = '';
        $this->message = '';

        if (count($userActivity) > 0) {
            $item = $userActivity[0];
            $image_server = false;
            $this->html = view('ajax._activity_item', compact('item', 'image_server'))->render();
            $this->message = '<span class="bootstrap-notify-message">' . strip_tags($this->html, '<span>') . '</span>';

            JobHelper::buildDiscordContent($item);
        }
    }

    public function broadcastOn()
    {
        return ['channel-name'];
    }
}
