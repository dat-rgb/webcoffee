<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order_id;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $shippingMethod;
    public $paymentMethod;
    public $status;
    public $statusPayment;
    public $order_time;
    public $cart;
    public $total;

    /**
     * Create a new message instance.
     */
    public function __construct($order_id, $name, $email, $phone, $shippingMethod, $paymentMethod, $status, $statusPayment, $address, $order_time, $cart, $total)
    {
        $this->order_id = $order_id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->shippingMethod = $shippingMethod;
        $this->paymentMethod = $paymentMethod;
        $this->status = $status;
        $this->statusPayment = $statusPayment;
        $this->address = $address;
        $this->order_time = $order_time;
        $this->cart = $cart;
        $this->total = $total;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đơn hàng từ CDMT Coffee & Tea',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'clients.emails.orderMail',
            with: [
                'order_id' => $this->order_id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'shippingMethod' => $this->shippingMethod,
                'paymentMethod' => $this->paymentMethod,
                'status' => $this->status,
                'statusPayment' => $this->statusPayment,
                'address' => $this->address,
                'order_time' => $this->order_time,
                'cart' => $this->cart,
                'total' => $this->total,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
