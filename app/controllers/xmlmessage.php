<?php
    namespace MyApp\Controllers;
    use MyApp\App;
    use System\UI\WebControls\View;
    use System\XML\XMLEntity;
    use System\XML\XmlParser;
    use System\Comm\HTTPWebRequest;
    use System\Comm\HTTPWebResponse;

	class XmlMessage extends \MyApp\ApplicationController
	{
		function getView( \System\Web\HTTPRequest &$request) {

			$note = new XmlEntity( 'note' );
			$note->setAttribute( 'charset', 'utf-8' );
			$note->children->add( new XMLEntity( 'to' ));
			$note->to->value = 'Tove';
			$note->addChild( new XMLEntity( 'from' ));
			$note->from->value = 'Jani';
			$note->addChild( new XmlEntity( 'heading' ));
			$note->heading->value = 'Reminder';
			$note->addChild( new XmlEntity( 'body' ));
			$note->body->value = 'Don\'t forget me this weekend & have fun!';

			$message = new HttpWebRequest();
			$message->setData( array( 'data' => $note->getXMLString() ));
            $message->url = \Rum::config()->protocol . '://' . \Rum::config()->host . str_replace( ' ', '%20', \Rum::config()->uri ) . '/assets/php/listener.php';

			$view = new View( 'xml' );
			$view->contentType = 'text/xml';

			$response = $message->getResponse()->content;

			$xmlParser = new XmlParser();
			if( $xmlParser->parse( $response )) {
				$view->setData( $xmlParser->parse( $response )->getXMLString() );
			}

			return $view;
		}
	}
?>