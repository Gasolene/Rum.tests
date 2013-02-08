<?php
    namespace MyApp;

	class TestSmtpClient implements \System\Comm\Mail\IMailClient
	{
		/**
		 * sends a single email to all addresses in message
		 *
		 * @param MailMessage $message message to send
		 * @return void
		 */
		public function send(\System\Comm\Mail\MailMessage $message)
		{
			$array['cc']  = $this->cc;
			$array['bcc'] = $this->bcc;
			$array['to']  = $this->to;

			$this->state[] = $array;
		}
	}
?>