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
            ->from(env('MAIL_USERNAME'), 'PROFECTA PERDANA')
            ->subject('PRIOR CLAIM INFORMATION ' . $this->data->claim_number)
            ->with(['data' => $this->data, 'warehouse' => $this->warehouse])
            ->attach(url('pdf_claim/' . $this->data->claim_number . '.pdf'));
    }
}
