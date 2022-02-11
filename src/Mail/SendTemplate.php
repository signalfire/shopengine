<?php

namespace Signalfire\Shopengine\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTemplate extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $order;
    public $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $template)
    {
        $this->order = $order;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $html = str_replace('#type#', 'html', $this->template);
        $text = str_replace('#type#', 'text', $this->template);

        return $this
            ->view('shopengine::'.$html)
            ->text('shopengine::'.$text);
    }
}
