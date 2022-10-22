<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeInMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data;
    protected $warehouse;
    public function __construct($warehouse, $data)
    {
        $this->data = $data;
        $this->warehouse = $warehouse;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('product_trade_in.mail_trade_in')
            ->from('noreply@profectaperdana.com', 'PROFECTA PERDANA')
            ->subject('INVOICE TRADE-IN ' . $this->data->trade_in_number)
            ->with(['data' => $this->data, 'warehouse' => $this->warehouse])
            ->attach(public_path('pdf_trade_in/' . $this->data->trade_in_number . '.pdf'));
    }
}
