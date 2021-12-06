<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserDeleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $emailList;
    private $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $mailList)
    {
        $this->emailList = $mailList;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (is_array($this->emailList)) {
            foreach ($this->emailList as $singleEmail) {
                sendEmail('mail/deleted', ['user' => $this->user], $singleEmail, __('custom.user_deleted') . '!');
            }
        }
    }
}
