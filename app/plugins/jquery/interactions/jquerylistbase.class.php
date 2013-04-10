<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			JQuery
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace JQuery;


	/**
	 * Represents a JQuery ListBase
	 *
	 * @property bool $multiple Specifies whether multiple selections are allowed
	 * @property string $textField Specifies name of text field in datasource
	 * @property string $valueField Specifies name of value field in datasource
	 *
	 * @package			JQuery
	 * @subpackage		Web
	 * @author			Darnell Shinbine
	 */
	abstract class JQueryListBase extends JQueryInteractionsBase
	{
		/**
		 * collection of items
		 * @var ListItemCollection
		 */
		protected $items;

		/**
		 * Specifies whether multiple selections are allowed
		 * @var bool
		 */
		protected $multiple				= false;

		/**
		 * Specifies name of text field in datasource
		 * @var string
		 */
		protected $textField			= '';

		/**
		 * Specifies name of value field in datasource
		 * @var string
		 */
		protected $valueField			= '';


		/**
		 * Constructor
		 *
		 * @param  string   $controlId  Control Id
		 * @return void
		 */
		public function __construct( $controlId )
		{
			parent::__construct( $controlId );

			$this->items = new \System\Web\WebControls\ListItemCollection();
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
			if( $field === 'items' )
			{
				return $this->items;
			}
			elseif( $field === 'multiple' )
			{
				return $this->multiple;
			}
			elseif( $field === 'textField' )
			{
				return $this->textField;
			}
			elseif( $field === 'valueField' )
			{
				return $this->valueField;
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
			if( $field === 'multiple' )
			{
				$this->multiple = (bool)$value;
			}
			elseif( $field === 'textField' )
			{
				$this->textField = (string)$value;
			}
			elseif( $field === 'valueField' )
			{
				$this->valueField = (string)$value;
			}
			else
			{
				parent::__set($field,$value);
			}
		}


		/**
		 * read view state from session
		 *
		 * @param  object	$viewState	session array
		 * @return void
		 */
		protected function onLoadViewState( array &$viewState )
		{
			parent::onLoadViewState( $viewState );

			if( !$this->value && $this->multiple )
			{
				$this->value = array();
			}
		}


		/**
		 * bind control to data
		 *
		 * @param  $default			value
		 * @return void
		 */
		protected function onDataBind()
		{
			$this->items->removeAll();

			if( $this->valueField && $this->textField )
			{
				foreach( $this->dataSource->rows as $row )
				{
					$this->items->add( $row[$this->textField], $row[$this->valueField] );
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException( 'JQueryList::onDataBind event called with no valueField or textField set' );
			}
		}
	}
?>