<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ContactMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContactMessage $message)
    {
        $this->contactMessage = $message;
        $this->contactMessage->content = (object) json_decode($message->content);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.contact', [
            "content" => $this->contactMessage->content
        ])
            ->from(env("MAIL_FROM_ADDRESS"), env("APP_NAME") . " - CONTATO VIA FORMULÁRIO")
            ->replyTo($this->contactMessage->email, $this->contactMessage->content->name)
            ->subject($this->contactMessage->content->subject);
    }
}
