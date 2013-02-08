<?php
    namespace MyApp\Models;
    use \System\XML\XMLEntity;
	use \System\Comm\Mail\MailMessage;
	include_once __ROOT__ . '/app/includes/string.inc';

	class MessageTestCase extends \System\Testcase\UnitTestCaseBase {

		function prepare() {
			// create data file
			$fp = fopen( __TMP_PATH__ . '/attachment.txt', 'w+' );
			fwrite( $fp, "line1\nline2\n" );
			fclose( $fp );
		}

		function cleanup() {
			// cleanup data file
			unlink( __TMP_PATH__ . '/attachment.txt' );
		}

		function testSend() {
			if( strtoupper( substr( PHP_OS, 0, 3 ) == 'WIN' )) {
				$eol = "\r\n";
			} elseif( strtoupper( substr( PHP_OS, 0, 3 ) == 'MAC' )) {
				$eol = "\r";
			} else {
				$eol = "\n";
			}

			$message = new MailMessage();

			$message->to = 'user@domain.tld';
			$message->from = 'user@domain.tld';
			$message->subject = 'user@domain.tld';
			$message->body = "line1\nline2";
			$message->addCc( 'cc1@domain.tld' );
			$message->addCc( 'cc2@domain.tld' );
			$message->addBcc( 'bcc1@domain.tld' );
			$message->addBcc( 'bcc2@domain.tld' );
			$message->addBcc( 'bcc3@domain.tld' );
			$message->addBcc( 'bcc4@domain.tld' );
			$message->addBcc( 'bcc5@domain.tld' );

			$headers = $message->getHeaders();
			$message = $message->getContent();
			$boundry = extract_segment_from_chunk( $headers, "boundary=\"", "\"{$eol}" );

			$this->assertTrue( strpos( $headers, "MIME-Version: 1.0{$eol}" ) === 0 );
			$this->assertTrue( strpos( $headers, "From: user@domain.tld{$eol}" ));
			$this->assertTrue( strpos( $headers, "Return-Path: user@domain.tld{$eol}" ));
			$this->assertTrue( strpos( $headers, "X-Sender: user@domain.tld{$eol}" ));
			$this->assertTrue( strpos( $headers, "Content-Type: multipart/mixed; boundary=\"$boundry\"" ));
			$this->assertTrue( strpos( $headers, "Content-Transfer-Encoding: 7bit{$eol}" ));
			$this->assertTrue( strpos( $headers, "Bcc: bcc1@domain.tld,bcc2@domain.tld,bcc3@domain.tld,bcc4@domain.tld,bcc5@domain.tld{$eol}" ));

			// $this->assertTrue( strpos( $message, "-$boundry{$eol}Content-Type: text/html; charset=iso-8859-1{$eol}Content-Transfer-Encoding: 7bit{$eol}{$eol}line1{$eol}line2{$eol}{$eol}--$boundry--" ));
		}

		function testInvalid() {
			if( strtoupper( substr( PHP_OS, 0, 3 ) == 'WIN' )) {
				$eol = "\r\n";
			} elseif( strtoupper( substr( PHP_OS, 0, 3 ) == 'MAC' )) {
				$eol = "\r";
			} else {
				$eol = "\n";
			}

			$message = new MailMessage();

			$this->expectException();

			$message->to = "user@domain.ca{$eol}exit;";
			$message->from = "invalid";
			$message->subject = "subject{$eol}line2";
			$this->assertTrue( $message->from == '' );
			$this->assertFalse( $message->addCc( "invalid" ));
		}

		function testAttachments() {
			if( strtoupper( substr( PHP_OS, 0, 3 ) == 'WIN' )) {
				$eol = "\r\n";
			} elseif( strtoupper( substr( PHP_OS, 0, 3 ) == 'MAC' )) {
				$eol = "\r";
			} else {
				$eol = "\n";
			}

			$message = new MailMessage();

			$message->to          = 'user@domain.tld';
			$message->from        = 'user@domain.tld';
			$message->addCc(        'user1@domain.tld' );
			$message->addCc(        'user2@domain.tld' );
			$message->addCc(        'user3@domain.tld' );
			$message->addBcc(       'privateuser1@domain.tld' );
			$message->addBcc(       'privateuser2@domain.tld' );
			$message->subject     = 'subject line...';
			$message->body        = '<h1>heading</h1><p>paragraph</p>';
			$message->charset     = 'iso-8859-1';
			$message->encoding    = '7bit';
			$message->contentType = 'text/plain';
			$message->attach( __TMP_PATH__ . '/attachment.txt' );
			$message->attach( __TMP_PATH__ . '/attachment.txt', 'text/plain', $filename = 'MyFile.txt' );

			$headers = $message->getHeaders();
			$message = $message->getContent();
			$boundry = extract_segment_from_chunk( $headers, "boundary=\"", "\"{$eol}" );

			$this->assertTrue( strpos( $headers, "IME-Version: 1.0{$eol}" ));
			$this->assertTrue( strpos( $headers, "From: user@domain.tld{$eol}" ));
			$this->assertTrue( strpos( $headers, "Return-Path: user@domain.tld{$eol}" ));
			$this->assertTrue( strpos( $headers, "X-Sender: user@domain.tld{$eol}" ));
			$this->assertTrue( strpos( $headers, "Content-Type: multipart/mixed; boundary=\"$boundry\"" ));
			$this->assertTrue( strpos( $headers, "Content-Transfer-Encoding: 7bit{$eol}" ));
			$this->assertTrue( strpos( $headers, "Cc: user1@domain.tld,user2@domain.tld,user3@domain.tld{$eol}" ));
			$this->assertTrue( strpos( $headers, "Bcc: privateuser1@domain.tld,privateuser2@domain.tld{$eol}" ));

			$this->assertTrue( strpos( $message, "-$boundry{$eol}Content-Type: text/plain; name=\"attachment.txt\"{$eol}Content-Transfer-Encoding: base64{$eol}Content-Disposition: attachment{$eol}{$eol}bGluZTEKbGluZTIK" ));
			$this->assertTrue( strpos( $message, "--$boundry{$eol}Content-Type: text/plain; name=\"MyFile.txt\"{$eol}Content-Transfer-Encoding: base64{$eol}Content-Disposition: attachment{$eol}{$eol}bGluZTEKbGluZTIK" ));
			$this->assertTrue( strpos( $message, "--$boundry{$eol}Content-Type: text/html; charset=iso-8859-1{$eol}Content-Transfer-Encoding: 7bit{$eol}{$eol}<h1>heading</h1><p>paragraph</p>{$eol}{$eol}--$boundry--" ));
		}
	}
?>