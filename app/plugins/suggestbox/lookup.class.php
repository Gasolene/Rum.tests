<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace SuggestBox;


	/**
     * handles lookup control element creation
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 *
	 * @property string $textField text field
	 * @property string $valueField value field
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			2.0
	 * @package			PHPRum
	 * @subpackage		SuggestBox
	 * @since			3.1.1
	 */
	class Lookup extends SuggestBox
	{
		/**
		 * name of label field in datasource
         * @var string
		 * @access protected
		 */
		protected $textField			= '';

		/**
		 * name of value field in datasource
         * @var string
		 * @access protected
		 */
		protected $valueField			= '';


		/**
		 * Constructor
		 *
		 * @return void
		 * @access public
		 * @abstract
		 */
		public function __construct( $controlId, $default = null )
		{
			parent::__construct( $controlId, $default );

			$this->items = new \System\Web\WebControls\ListItemCollection();
		}


		/**
		 * returns an object property
		 *
		 * @param  string	$field		name of the field
		 *
		 * @return mixed
		 * @access public
		 * @ignore
		 */
		public function __get($field)
		{
			if(in_array($field,array_keys(get_object_vars($this))))
			{
				return $this->{$field};
			}
			else
			{
				return parent::__get($field);
			}
		}


		/**
		 * sets an object property
		 *
		 * @param  string	$field		name of the field
		 * @param  mixed	$value		value of the field
		 *
		 * @return void
		 * @access public
		 * @ignore
		 */
		public function __set($field,$value)
		{
			if($field=='textField')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='valueField')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='items')
			{
				throw new \System\BadMemberCallException("property $field is readonly in ".get_class($this));
			}
			else
			{
				return parent::__set($field, $value);
			}
		}


		/**
		 * returns widget object
		 *
		 * @param  none
		 * @return void
		 * @access public
		 */
		public function getDomObject()
		{
			$lookup = parent::getDomObject();

			if( $this->multiline )
			{
				$lookup->textarea->setAttribute( 'onkeyup', 'SuggestBox.handleKeyUp(event.keyCode,'.$this->maxNumToShow.','.($this->disableAutoComplete?'false':'true').',document.getElementById(\''.$this->getHTMLControlId().'\'),document.getElementById(\''.$this->getHTMLControlId().'__lookup\'),\''.$this->listName.'\',false,\''.str_replace('\'', '\\\'', $this->delimiter).'\');' );
			}
			else
			{
				$lookup->input->setAttribute( 'onkeyup', 'SuggestBox.handleKeyUp(event.keyCode,'.$this->maxNumToShow.','.($this->disableAutoComplete?'false':'true').',document.getElementById(\''.$this->getHTMLControlId().'\'),document.getElementById(\''.$this->getHTMLControlId().'__lookup\'),\''.$this->listName.'\',false,\''.str_replace('\'', '\\\'', $this->delimiter).'\');' );
			}

			return $lookup;
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$httpRequest )
		{
			if( isset( $httpRequest[$this->getHTMLControlId()] ))
			{
				if( $this->delimiter )
				{
					$values = explode( $this->delimiter, $httpRequest[$this->getHTMLControlId()] );
					$httpRequest[$this->getHTMLControlId()] = array();

					foreach($values as $value)
					{
						$index = $this->items->indexOf( (string) trim( $value ));

						if( $index > -1 )
						{
							$httpRequest[$this->getHTMLControlId()][] = $this->items->itemAt( $index );
						}
					}
				}
				else
				{
					$index = $this->items->indexOf( (string) $httpRequest[$this->getHTMLControlId()] );

					if( $index > -1 )
					{
						$httpRequest[$this->getHTMLControlId()] = $this->items->itemAt( $index );
					}
					else
					{
						$httpRequest[$this->getHTMLControlId()] = '';
					}
				}
			}

			parent::onRequest( $httpRequest );
		}


		/**
		 * bind control to data
		 *
		 * @param  $default			value
		 * @return void
		 * @access protected
		 */
		protected function onDataBind()
		{
			if( $this->dataSource )
			{
				if( $this->valueField && $this->textField )
				{
					while( !$this->dataSource->eof() )
					{
						$this->items->add( $this->dataSource[$this->textField], $this->dataSource->row[$this->valueField] );
						$this->dataSource->next();
					}
				}
				else
				{
					throw new \Exception( 'Lookup::dataBind() called with no valueField or textField set' );
				}
			}
			else
			{
				throw new \Exception( 'Lookup::dataBind() called with no dataSource' );
			}
		}


		/**
		 * get text value
		 *
		 * @return void
		 * @access protected
		 * @final
		 */
		final protected function getTextValue()
		{
			$index = $this->items->indexOfItem( $this->value );

			if( $index > -1 )
			{
				return $this->items->keyAt( $index );
			}

			return '';
		}


		/**
		 * get item array
		 *
		 * @return void
		 * @access protected
		 * @final
		 */
		final protected function getItemArray()
		{
			$items = new \System\Web\WebControls\ListItemCollection();

			foreach( $this->items as $key => $value )
			{
				$items->add(trim($key),trim($value));
			}

			$this->items = $items;
			$list = '';
			$values = $this->items->keys;

			foreach( $values as $value )
			{
				if( $list )
				{
					$list .= ',\'' . trim( addslashes( $value )) . '\'';
				}
				else
				{
					$list = '\'' . trim( addslashes( $value )) . '\'';
				}
			}

			return "Array($list)";
		}
	}
?>