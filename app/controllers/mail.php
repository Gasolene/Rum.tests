<?php
    namespace MyApp\Controllers;
    use \MyApp\App;

	class Mail extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args )
		{
			$this->page->add(\MyApp\UI::Form('form'));
			$this->page->form->add(\MyApp\UI::Button('PHPMailClient'));
			$this->page->form->add(\MyApp\UI::Button('SendMailClient'));
			$this->page->form->add(\MyApp\UI::Button('SmtpClient'));
		}

		function onPHPMailClientClick( &$page, $args )
		{
			$message = $this->getMailMessage('PHPMailer test');

			$PHPMailClient = new \System\Comm\Mail\PHPMailClient();
			$PHPMailClient->send($message);

			\Rum::flash("s:PHP mail() function");
			\Rum::forward();
		}

		function onSendMailClientClick( &$page, $args )
		{
			$message = $this->getMailMessage('Sendmail test');

			$sendMailClient = new \System\Comm\Mail\SendMailClient('/usr/sbin/sendmail');
			$sendMailClient->send($message);

			\Rum::flash("s:\$sendmail program");
			\Rum::forward();
		}

		function onSmtpClientClick( &$page, $args )
		{
			$message = $this->getMailMessage('SMTP test');

			$smtpClient = new \System\Comm\Mail\SMTPClient();
			$smtpClient->host = 'mail.commerx.com';
			$smtpClient->helo = 'commerx.com';
			$smtpClient->port = 366;
			$smtpClient->timeout = 15;
			$smtpClient->authUsername = 'darnell.shinbine@commerx.com';
			$smtpClient->authPassword = 'Efogefog1';
			$smtpClient->send($message);

			\Rum::flash("s:smtp client");
			\Rum::forward();
		}

		private function getMailMessage($subject)
		{
			$message = new \System\Comm\Mail\MailMessage();
			$message->to = 'darnell@commerx.com';
			$message->from = 'no-reply@commerx.com';
			$message->subject = $subject;
			$message->body = 'This is a <strong>Test Message</strong>';
			$message->addBcc('gasolene@gmail.com');
			$message->attach(__ROOT__ . '/app/data/Cities.csv', 'text/plain');
			$message->attach("hello\nworld", 'text/plain', 'hello.txt', true, true);

			return $message;
		}
	}
?>