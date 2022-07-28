<?php

namespace App\Jobs;

use App\Repositories\General\UtilsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tokens;
    private $title;
    private $body;
    private $notificationData;

    public function __construct(array $tokens, string $title, string $body,
                                array $notificationData)
    {
        $this->tokens = $tokens;
        $this->title = $title;
        $this->body = $body;
        $this->notificationData = $notificationData;
    }

    public function handle()
    {
        UtilsRepository::sendNotification($this->tokens, $this->title, $this->body
            , $this->notificationData);
    }
}
