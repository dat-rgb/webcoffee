<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact_name;
    public $contact_email;
    public $reply_message;

    public $subjectLine;

    public function __construct($contact_name, $contact_email, $reply_message, $subjectLine)
    {
        $this->contact_name = $contact_name;
        $this->contact_email = $contact_email;
        $this->reply_message = $reply_message;
        $this->subjectLine = $subjectLine;
    }


    /**
     * Tiêu đề email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Phản hồi từ CDMT Coffee & Tea'
        );
    }

    /**
     * Trả về nội dung email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'clients.emails.contactReplyMail',
            with: [
                'contact_name' => $this->contact_name,
                'reply_message' => $this->reply_message,
            ]
        );
    }

    /**
     * Đính kèm nếu có (hiện tại không).
     */
    public function attachments(): array
    {
        return [];
    }
}
