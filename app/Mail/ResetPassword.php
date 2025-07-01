<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $token;

    public function __construct(string $name, string $token)
    {
        $this->name  = $name;
        $this->token = $token;   // token thuần để nhúng vào nút “Đặt lại mật khẩu”
    }

    public function build()
    {
        return $this->subject('Đặt lại mật khẩu | CDMT Coffee & Tea')
                    ->view('clients.emails.resetPasswordMail');
    }
}
