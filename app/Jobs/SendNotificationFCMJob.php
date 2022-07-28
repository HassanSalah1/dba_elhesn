<?php

namespace App\Jobs;

use App\Models\User;
use App\Repositories\General\UtilsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationFCMJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $notification_obj;
    private $extraData;

    public function __construct(User $user, array $notification_obj, array $extraData = [])
    {
        $this->user = $user;
        $this->notification_obj = $notification_obj;
        $this->extraData = $extraData;
    }

    public function handle()
    {
        UtilsRepository::sendFCMNotification($this->user, $this->notification_obj, $this->extraData);
    }
}
