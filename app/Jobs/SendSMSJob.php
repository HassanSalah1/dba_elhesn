<?php

namespace App\Jobs;

use App\Repositories\General\UtilsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mobile;
    private $message;
    private $locale;

    public function __construct(string $mobile, string $message , string $locale)
    {
        $this->mobile = $mobile;
        $this->message = $message;
        $this->locale = $locale;
    }

    public function handle()
    {
        UtilsRepository::sendSMS($this->mobile, $this->message , $this->locale);
    }
}
