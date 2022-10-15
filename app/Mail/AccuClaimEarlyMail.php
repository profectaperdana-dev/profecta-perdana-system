<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccuClaimEarlyMail extends Mailable
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
        return $this->view('claim.pdf_accu_claims_mail')
            ->from('noreply@profectaperdana.com', 'PROFECTA PERDANA')
            ->subject('CLAIM CONFIRMED ' . $this->data->claim_number)
            ->with(['data' => $this->data, 'warehouse' => $this->warehouse])
            ->attach(public_path('pdf_claim/' . $this->data->claim_number . '.pdf'));
    }
}
