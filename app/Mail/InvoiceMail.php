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
        $so_number = str_replace('IVPP', 'DOPP', $this->data->order_number);

        if ($this->data->isPaid == 0) {
            return $this->view('invoice.mail_invoice')
                ->from(env('MAIL_USERNAME'), 'PROFECTA PERDANA')
                ->subject('INVOICE ' . $this->data->order_number . ' / ' . $so_number)
                ->with(['data' => $this->data, 'warehouse' => $this->warehouse, 'ppn' => $this->ppn])
                ->attach(base_path('pdf/' . $this->data->order_number . '.pdf'));
        } else {
            return $this->view('invoice.mail_invoice')
                ->from(env('MAIL_USERNAME'), 'PROFECTA PERDANA')
                ->subject('INVOICE ' . $this->data->order_number . ' / ' . $so_number .  ' (PAID)')
                ->with(['data' => $this->data, 'warehouse' => $this->warehouse, 'ppn' => $this->ppn])
                ->attach(base_path('pdf/' . $this->data->order_number . '.pdf'));
        }
    }
}
