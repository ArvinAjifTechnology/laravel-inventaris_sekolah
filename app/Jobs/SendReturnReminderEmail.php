<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReturnReminderMail;

class SendReturnReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $borrow;
    /**
     * Create a new job instance.
     */
    public function __construct($borrow)
    {
        $this->borrow = $borrow;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->borrow->user->email)->send(new ReturnReminderMail($this->borrow));
    }
}
