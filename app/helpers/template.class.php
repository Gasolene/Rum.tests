<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace MyApp;

	/**
	 * Template Helper
	 *
	 * It provides helper methods for use inside templates.  This
	 * helper should only be used inside templates.
	 *
	 * @version			1.0
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	final class Template
	{
		/**
		 * creates a link tag for any controller
		 *
		 * @param string $title	title of link
		 * @param string $page name of page
		 * @param array $args array of args
		 * @return void
		 */
		static public function linkTo($title, $page, array $args = array())
		{
			$uri = \htmlentities(\System\HTTPAppServlet::getInstance()->getPageURI($page, $args));
			$title = \htmlentities($title);
			\System\IO\HTTPResponse::write("<a href=\"{$uri}\" title=\"{$title}\">{$title}</a>");
		}


		/**
		 * outputs the application base uri
		 *
		 * @return void
		 */
		static public function baseURI()
		{
			$uri = \htmlentities(\System\HTTPAppServlet::getInstance()->config->uri);
			\System\IO\HTTPResponse::write($uri);
		}


		/**
		 * outputs the application base uri
		 *
		 * @param string $page name of page
		 * @param array $args array of parameters
		 * @return void
		 */
		static public function pageURI($page, array $args = array())
		{
			$uri = \System\HTTPAppServlet::getInstance()->getPageURI($page, $args);
			\System\IO\HTTPResponse::write($uri);
		}


		/**
		 * outputs the application messages
		 *
		 * @return void
		 */
		static public function messages()
		{
			$output = '<ul id="messages" class="messages">';

			if( \System\AppServlet::getInstance()->messages->count > 0 )
			{
				foreach(\System\AppServlet::getInstance()->messages as $message)
				{
					if($message->type == \System\AppMessageType::Fail())
					{
						$output .= '<li class="fail">';
					}
					elseif($message->type == \System\AppMessageType::Notice())
					{
						$output .= '<li class="warning">';
					}
					elseif($message->type == \System\AppMessageType::Success())
					{
						$output .= '<li class="success">';
					}
					else
					{
						$output .= '<li>';
					}

					$output .= nl2br(htmlentities($message->message)) . '</li>';
				}
			}

			$output .= '</ul>';

			\System\IO\HTTPResponse::write($output);
		}


		/**
		 * outputs the application messages
		 *
		 * @return void
		 */
		static public function breadcrumb()
		{
			$pages = array();
			$breadcrumb = '';
			$parent = '';
			foreach(explode('/', \System\HTTPAppServlet::getInstance()->thisPage) as $page)
			{
				$title = $page;
				if($parent) {
					$page = $parent . '/' . $page;
				}
				else {
					$page = $page;
				}
				$pages[$title] = $page;
				$parent = $page;
			}
			$i=0;
			foreach($pages as $title=>$page)
			{
				if($breadcrumb)
				{
					$breadcrumb .= '&nbsp;&raquo;&nbsp;';
				}
				if(count($pages)-1<>$i++)
				{
					$breadcrumb .= "<a href=\"".\System\HTTPAppServlet::getInstance()->getPageURI($page)."\">".ucwords($title)."</a>";
				}
				else
				{
					$breadcrumb .= ucwords($title);
				}
			}

			\System\IO\HTTPResponse::write($breadcrumb);
		}
	}
?>