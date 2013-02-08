<?php

	class ApplicationController extends PageControllerBase {

		function onInit( HTTPRequest &$request ) {
			parent::onInit( $request );

            $this->page->add(new SystemMessage('messages'));
		}
	}
?>