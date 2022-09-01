<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
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
        return $this->view('invoice.mail_invoice')
            ->from('notification@profectaperdana.com', 'PROFECTA PERDANA')
            ->subject('INVOICE ' . $this->data->order_number)
            ->with(['data' => $this->data, 'warehouse' => $this->warehouse])
            ->attach(public_path('pdf_invoice/' . $this->data->order_number . '.pdf'));
    }
}
