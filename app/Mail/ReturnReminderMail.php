<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturnReminderMail extends Mailable
{
    use Queueable, SerializesModels;


    protected $borrow;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->borrow =$borrow;
    }

    // /**
    //  * Get the message envelope.
    //  */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Return Reminder Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: 'emails.return_reminder',
    //     );
    // }rrpw

    public function build()
    {
        return $this->markdown('emails.return_reminder')
            ->subject('Reminder: Return',$this->borrow->item->Item_code)
            ->with('borrow', $this->borrow);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
