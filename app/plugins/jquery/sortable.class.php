<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			JQuery
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace JQuery;


	/**
	 * Represents a JQuery Sortable Control
	 *
	 * @property string $onclick Specifies the action to take on click events
	 * @property string $ondblclick Specifies the action to take on double click events
	 *
	 * @package			JQuery
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	class Sortable extends JQueryListBase
	{
		/**
		 * Specifies name of value field in datasource
		 * @var string
		 */
		protected $sortField			= '';

		/**
		 * Specifies value of the sortable list
		 * @var array
		 */
		protected $value				= array();


		/**
		 * Constructor
		 *
		 * @param  string   $controlId  Control Id
		 * @return void
		 */
		public function __construct( $controlId )
		{
			parent::__construct( $controlId );

			$this->events->add(new SortableSortEvent());

			$onSortMethod = 'on'.ucwords($this->controlId).'Sort';
			if(\method_exists(\System\Web\WebApplicationBase::getInstance()->requestHandler, $onSortMethod))
			{
				$this->events->registerEventHandler(new SortableSortEventHandler('\System\Web\WebApplicationBase::getInstance()->requestHandler->' . $onSortMethod));
			}
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
			if( $field === 'sortField' )
			{
				return $this->sortField;
			}
			elseif( $field === 'value' )
			{
				return $this->value;
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
			if( $field === 'sortField' )
			{
				$this->sortField = (string)$value;
			}
			elseif( $field === 'value' )
			{
				$this->value = (array)$value;
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
			$dom = new \System\XML\DomObject( 'ul' );
			$dom->setAttribute( 'id', $this->getHTMLControlIdString() );
			$dom->appendAttribute( 'class', ' sortable' );

			for( $i = 0, $count = $this->items->count; $i < $count; $i++ )
			{
				$dom->innerHtml .= '<li id="'.$this->getHTMLControlIdString().'__'.$this->items->itemAt($i).'" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.($this->items->keyAt($i)).'</li>';
			}

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

			$page = $this->getParentByType('\System\Web\WebControls\Page');
			$page->addLink(\System\Base\ApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'commoncontrols', 'type'=>'text/css')).'&asset=sortable/sortable.css');
			$page->onload .= "$( \"#{$this->getHTMLControlIdString()}\" ).sortable();$( \"#{$this->getHTMLControlIdString()}\" ).disableSelection();$( \"#{$this->getHTMLControlIdString()}\" ).sortable({update:function(event,ui){PHPRum.sendHttpRequest('".$this->getQueryString()."',($('#{$this->getHTMLControlIdString()}').sortable('serialize')));}});";
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			if( isset( $request[$this->getHTMLControlIdString().'_'] ))
			{
				$this->value = $request[$this->getHTMLControlIdString().'_'];

				$this->events->raise(new SortableSortEvent(), $this);

				if( $this->sortField && $this->dataSource )
				{
					for($i=count($this->value)-1; $i>-1; $i--)
					{
						$this->dataSource->seek($this->valueField, $this->value[$i]);
						$this->dataSource[$this->sortField] = $i;
						$this->dataSource->update();
					}
				}
			}
		}


		/**
		 * Event called on ajax callback
		 *
		 * @return void
		 */
		protected function onUpdateAjax()
		{
			$page = $this->getParentByType('\System\Web\WebControls\Page');

			$page->loadAjaxJScriptBuffer('list1 = document.getElementById(\''.$this->getHTMLControlIdString().'\');');
			$page->loadAjaxJScriptBuffer('list2 = document.createElement(\'div\');');
			$page->loadAjaxJScriptBuffer('list2.innerHTML = \''.\addslashes(str_replace("\n", '', str_replace("\r", '', $this->fetch()))).'\';');
			$page->loadAjaxJScriptBuffer('list1.parentNode.insertBefore(list2, list1);');
			$page->loadAjaxJScriptBuffer('list1.parentNode.removeChild(list1);');

			$page->loadAjaxJScriptBuffer("$( \"#{$this->getHTMLControlIdString()}\" ).sortable();");
			$page->loadAjaxJScriptBuffer("$( \"#{$this->getHTMLControlIdString()}\" ).disableSelection();");
			$page->loadAjaxJScriptBuffer("$( \"#{$this->getHTMLControlIdString()}\" ).sortable({update: function(event, ui) {PHPRum.sendHttpRequest('".$this->getQueryString()."', ($('#{$this->getHTMLControlIdString()}').sortable('serialize')));}})");
		}
	}
?>