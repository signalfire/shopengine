<?php

namespace Signalfire\Shopengine\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $order;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $message)
    {
        $this->order = $order;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('shopengine::emails.html.message')
            ->text('shopengine::emails.text.message');
    }
}
