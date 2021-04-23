<?php

namespace App\Repositories;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\CacheRepository;
use App\Models\EmailTemplate;
use App\Models\Email;

class EmailRepository  extends Mailable
{
    use Queueable, SerializesModels;

	private $tplname = '';
	private $subject = '';
	private $message = '';
	private $to = '';
	private $cc = '';
	private $bcc = '';
	private $_params = [];

	public function __construct()
	{
		$this->from('admin@visitdairi.com');
	}

	public function build()
    {
        return $this->from('admin@visitdairi.com')
        	->host('smtp.visitdairi.com'),
            ->port('465'),
            ->encryption('ssl'),
            ->username('admin@visitdairi.com'),
            ->password('1Sampai6'),
            ->view('mails.demo')
            ->text('mails.demo_plain');
    }

	public function setTemplate($tplname, $type = 'defaults')
	{
		$template = EmailTemplate::where('type', $type)
			->where('name', $tplname)
			->first();
		if($template)
		{
			$this->tplname = $tplname;
			$this->subject = $template->subject;
		}
		return $this;
	}

	public function setParam($params)
	{
		$this->_params = $params;
	}

	public function send()
	{
		$success = 'pending';
		// send message
		$param = $this->_params;
		$message = view('emails.'.$this->tplname, $param);

		// save to outbox
		try {
			Email::insert([
				'subject' => $this->subject,
				'message' => $message,
				'date' => date('Y-m-d H:i:s'),
				'to' => $this->to,
				'cc' => $this->cc,
				'bcc' => $this->bcc,
				'status' => $success
			]);	
		} catch(Exception $e) {

		}
		return true;
	}
}