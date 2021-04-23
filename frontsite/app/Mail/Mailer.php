<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Repositories\ConfigRepository;

class Mailer extends Mailable
{
    use Queueable, SerializesModels;

    public $params;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        //
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(ConfigRepository $config)
    {
        $data = $this->params;
        $data['config'] = $config->get();
        return $this->subject($data['subject'])->view('emails.'.$data['template'], $data);
    }

}
