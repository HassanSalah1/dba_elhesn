<?php

namespace App\Jobs;

use App\Repositories\General\UtilsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        UtilsRepository::sendEmail($this->data);
    }
}
