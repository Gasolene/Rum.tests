<?php
    namespace MyApp\Controllers;

	class XmlMessageControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testOutput() {
			$this->get();

			$response = $this->responseAsXmlEntity();
			if( $response ) {

				if( $response->message ) {
					$this->assertEqual( $response->message->value, 'success' );
				}
				else {
					$this->assertTrue( false, '$response->message is null' );
				}

				if( $response->data ) {
					if( $response->data->note ) {
						$this->assertEqual( $response->data->note->getAttribute( 'charset' ), 'utf-8' );
						if( $response->data->note->to ) {
							$this->assertEqual( $response->data->note->to->value, 'Tove' );
						}
						else {
							$this->assertTrue( false, '$response->data->note->to is null' );
						}
						if( $response->data->note->from ) {
							$this->assertEqual( $response->data->note->from->value, 'Jani' );
						}
						else {
							$this->assertTrue( false, '$response->data->note->from is null' );
						}
						if( $response->data->note->heading ) {
							$this->assertEqual( $response->data->note->heading->value, 'Reminder' );
						}
						else {
							$this->assertTrue( false, '$response->data->note->heading is null' );
						}
						if( $response->data->note->body ) {
							$this->assertEqual( $response->data->note->body->value, 'Don\'t forget me this weekend & have fun!' );
						}
						else {
							$this->assertTrue( false, '$response->data->note->body is null' );
						}
					}
					else {
						$this->assertTrue( false, '$response->data->note is null' );
					}
				}
				else {
					$this->assertTrue( false, '$response->data is null' );
				}
			}
			else {
				$this->assertTrue( false, '$this->responseAsXmlEntity() failed' );
			}
		}
	}
?>