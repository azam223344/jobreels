<?php

namespace App\Jobs;

use App\Mail\SendEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
// use App\Mail\SendEmailNotification;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mail_configs;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail_configs)
    {
        $this->mail_configs = $mail_configs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // dd($this->mail_configs->mail_configs);die();
        $email = new SendEmailNotification($this->mail_configs->mail_configs);
        Mail::to($this->mail_configs->mail_configs['to'])->send($email);
    }
}
