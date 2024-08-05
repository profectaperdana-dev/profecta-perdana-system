<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccuClaimFinishMail extends Mailable
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
        return $this->view('claim.pdf_accu_claims_mail_finish')
            ->from('information@profectaperdana.com', 'PROFECTA PERDANA')
            ->subject('FINISH CLAIM INFORMATION ' . $this->data->claim_number)
            ->with(['data' => $this->data, 'warehouse' => $this->warehouse])
            ->attach(url('pdf_claim_finish/' . $this->data->claim_number . '.pdf'));
    }
}
