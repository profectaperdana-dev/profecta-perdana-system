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
    protected $ppn;
    public function __construct($warehouse, $data, $ppn)
    {
        $this->data = $data;
        $this->warehouse = $warehouse;
        $this->ppn = $ppn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('invoice.mail_invoice')
            ->from('noreply@profectaperdana.com', 'PROFECTA PERDANA')
            ->subject('INVOICE ' . $this->data->order_number)
            ->with(['data' => $this->data, 'warehouse' => $this->warehouse, 'ppn' => $this->ppn])
            ->attach(public_path('pdf/' . $this->data->order_number . '.pdf'));
    }
}
