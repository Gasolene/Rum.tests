<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			JQuery
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace JScroll;


	/**
	 * Represents a JQuery Sortable Control
	 *
	 * @property string $pageURI Specifies the start page URI
	 * @property bool $debug When set to true, outputs useful information to the console display if the console object exists.
	 * @property bool $autoTrigger When set to true, triggers the loading of the next set of content automatically when the user scrolls to the bottom of the containing element. When set to false, the required next link will trigger the loading of the next set of content when clicked.
	 * @property string $loadingHtml The HTML to show at the bottom of the content while loading the next set.
	 * @property int $padding The distance from the bottom of the scrollable content at which to trigger the loading of the next set of content. This only applies when autoTrigger is set to true.
	 * @property string $nextSelector The selector to use for finding the link which contains the href pointing to the next set of content. If this selector is not found, or if it does not contain a href attribute, jScroll will self-destroy and unbind from the element upon which it was called.
	 * @property string $contentSelector A convenience selector for loading only part of the content in the response for the next set of content. This selector will be ignored if left blank and will apply the entire response to the DOM.
	 *
	 * @package			JQuery
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class JScroll extends \System\Web\WebControls\WebControlBase
	{
		/**
		 * Specifies the start page URI
		 * @var string
		 */
		protected $pageURI = '';

		/**
		 * When set to true, outputs useful information to the console display if the console object exists.
		 * @var bool
		 */
		protected $debug			= false;

		/**
		 * When set to true, triggers the loading of the next set of content automatically when the user scrolls to the bottom of the containing element. When set to false, the required next link will trigger the loading of the next set of content when clicked.
		 * @var bool
		 */
		protected $autoTrigger			= true;

		/**
		 * The HTML to show at the bottom of the content while loading the next set.
		 * @var bool
		 */
		protected $loadingHtml			= '<small>Loading...</small>';

		/**
		 * The distance from the bottom of the scrollable content at which to trigger the loading of the next set of content. This only applies when autoTrigger is set to true.
		 * @var bool
		 */
		protected $padding			= 0;

		/**
		 * The selector to use for finding the link which contains the href pointing to the next set of content. If this selector is not found, or if it does not contain a href attribute, jScroll will self-destroy and unbind from the element upon which it was called.
		 * @var bool
		 */
		protected $nextSelector			= 'a:last';

		/**
		 * A convenience selector for loading only part of the content in the response for the next set of content. This selector will be ignored if left blank and will apply the entire response to the DOM.
		 * @var bool
		 */
		protected $contentSelector		= '';


		/**
		 * sets the controlId and prepares the control attributes
		 *
		 * @param  string   $path  Specifies the path to the controller that contains the content
		 * @param  string   $pageParam  Specifies the page parameter, default is page
		 * @return void
		 */
		public function __construct( $controlId, $pageURI = '' )
		{
		    parent::__construct($controlId);
		    $this->pageURI = $pageURI;
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'pageURI' )
			{
				return $this->pageURI;
			}
			elseif( $field === 'debug' )
			{
				return $this->debug;
			}
			elseif( $field === 'autoTrigger' )
			{
				return $this->autoTrigger;
			}
			elseif( $field === 'loadingHtml' )
			{
				return $this->loadingHtml;
			}
			elseif( $field === 'padding' )
			{
				return $this->padding;
			}
			elseif( $field === 'nextSelector' )
			{
				return $this->nextSelector;
			}
			elseif( $field === 'contentSelector' )
			{
				return $this->contentSelector;
			}
			else
			{
				return parent::__get( $field );
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return mixed
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			if( $field === 'pageURI' )
			{
				$this->pageURI = (string)$value;
			}
			elseif( $field === 'debug' )
			{
				$this->debug = (bool)$value;
			}
			elseif( $field === 'autoTrigger' )
			{
				$this->autoTrigger = (bool)$value;
			}
			elseif( $field === 'loadingHtml' )
			{
				$this->loadingHtml = (string)$value;
			}
			elseif( $field === 'padding' )
			{
				$this->padding = (int)$value;
			}
			elseif( $field === 'nextSelector' )
			{
				$this->nextSelector = (string)$value;
			}
			elseif( $field === 'contentSelector' )
			{
				$this->contentSelector = (string)$value;
			}
			else
			{
				parent::__set($field,$value);
			}
		}


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$dom = new \System\XML\DomObject( 'div' );
			$dom->setAttribute( 'id', $this->getHTMLControlId() );
			$dom->appendAttribute( 'class', ' jscroll' );
			$dom->innerHtml .= "<a href=\"{$this->pageURI}\">more...</a>";

			return $dom;
		}


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->getParentByType('\System\Web\WebControls\Page')->onload .= $this->getJQueryInit();
		}


		/**
		 * Event called on ajax callback
		 *
		 * @return void
		 */
		protected function onUpdateAjax()
		{
			$page = $this->getParentByType('\System\Web\WebControls\Page');

			$page->loadAjaxJScriptBuffer('jscroll1 = document.getElementById(\''.$this->getHTMLControlId().'\');');
			$page->loadAjaxJScriptBuffer('jscroll2 = document.createElement(\'div\');');
			$page->loadAjaxJScriptBuffer('jscroll2.className = \' jscroll\';');
			$page->loadAjaxJScriptBuffer('jscroll2.setAttribute(\'id\', \''.$this->getHTMLControlId().'\');');
			$page->loadAjaxJScriptBuffer('jscroll2.innerHTML = \'<a href=\"'.$this->pageURI.'\">more...</a>\';');
			$page->loadAjaxJScriptBuffer('jscroll1.parentNode.insertBefore(jscroll2, jscroll1);');
			$page->loadAjaxJScriptBuffer('jscroll1.parentNode.removeChild(jscroll1);');
			$page->loadAjaxJScriptBuffer($this->getJQueryInit());
		}


		/**
		 * get jquery init
		 * @return string
		 */
		private function getJQueryInit()
		{
		    return "$('#{$this->getHTMLControlId()}').jscroll({".
    "debug: ".($this->debug?'true':'false').",".
    "autoTrigger: ".($this->autoTrigger?'true':'false').",".
    "loadingHtml: '".stripslashes($this->loadingHtml)."',".
    "padding: ".$this->padding.",".
    "nextSelector: '".stripslashes($this->nextSelector)."',".
    "contentSelector: '".stripslashes($this->contentSelector)."'".
"}).scroll();";
		}
	}
?>