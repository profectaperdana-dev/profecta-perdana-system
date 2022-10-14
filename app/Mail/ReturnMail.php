<?php

namespace App\Mail;

use App\Models\ValueAddedTaxModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReturnMail extends Mailable
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
        return $this->view('returns.mail_return')
            ->from('noreply@profectaperdana.com', 'PROFECTA PERDANA')
            ->subject('Return ' . $this->data->return_number)
            ->with(['data' => $this->data, 'warehouse' => $this->warehouse, 'ppn' => ValueAddedTaxModel::first()->ppn / 100])
            ->attach(public_path('pdf/' . $this->data->return_number . '.pdf'));
    }
}
