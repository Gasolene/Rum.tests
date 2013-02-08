<?php
	/**
	 * @package			PHPRum
	 *
	 */
    namespace PickList;
	use \System\Web\WebControls\ListControlBase;


	/**
	 * Represents an PickList Control
	 *
	 * @property int $listSize Size of listbox
	 *
	 * @package			PHPRum
	 *
	 */
	class PickList extends ListControlBase
	{
		/**
		 * Size of listbox, default is 6
		 * @var int
		 * @access protected
		 */
		protected $listSize				= 6;

		/**
		 * Specifies whether multiple selections are allowed
         * @var bool
		 * @access protected
		 */
		protected $multiple				= true;


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @access protected
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'listSize' )
			{
				return $this->listSize;
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
		 *
		 * @return mixed
		 * @access protected
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			if( $field === 'listSize' )
			{
				$this->listSize = (int)$value;
			}
			elseif( $field === 'autoPostBack' || $field === 'multiple' )
			{
				throw new \System\BadMemberCallException("call to readonly property $field in ".get_class($this));
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
		 * @access public
		 */
		public function getDomObject()
		{
			$select = new \System\XML\DomObject( 'select' );
			$select->setAttribute( 'id', $this->getHTMLControlIdString() . '__unselected');
			$select->setAttribute( 'name', $this->getHTMLControlIdString() . '__unselected[]' );
			$select->setAttribute( 'class', 'picklist_listbox picklist_listbox_left' );
			$select->setAttribute( 'size', $this->listSize );
			$select->setAttribute( 'multiple', 'multiple' );

			$select2 = new \System\XML\DomObject( 'select' );
			$select2->setAttribute( 'id', $this->getHTMLControlIdString() . '__dummy');
			$select2->setAttribute( 'name', $this->getHTMLControlIdString() . '__dummy[]');
			$select2->setAttribute( 'class', 'picklist_listbox picklist_listbox_right' );
			$select2->setAttribute( 'size', $this->listSize );
			$select2->setAttribute( 'multiple', 'multiple' );

			$select3 = new \System\XML\DomObject( 'select' );
			$select3->setAttribute( 'id', $this->getHTMLControlIdString() . '__selected');
			$select3->setAttribute( 'name', $this->getHTMLControlIdString() . '[]');
			$select3->setAttribute( 'multiple', 'multiple' );
			$select3->setAttribute( 'style', 'display:none;' );

			if( $this->submitted && !$this->validate() )
			{
				$select->appendAttribute( 'class', ' invalid' );
			}

			if( $this->readonly )
			{
				$select->setAttribute( 'disabled', 'disabled' );
				$select2->setAttribute( 'disabled', 'disabled' );
			}

			if( $this->disabled )
			{
				$select->setAttribute( 'disabled', 'disabled' );
				$select2->setAttribute( 'disabled', 'disabled' );
			}

			// create options
			$keys = $this->items->keys;
			$values = $this->items->values;

			for( $i = 0, $count = $this->items->count; $i < $count; $i++ )
			{
				if( array_search( $values[$i], $this->value ) === false )
				{
					$option = new \System\XML\DomObject( 'option' );
					$option->setAttribute( 'value', $this->items->itemAt( $i ));
					$option->nodeValue .= $keys[$i];

					$select->addChild( $option );
				}
			}

			for( $i = 0, $count = count($this->value); $i < $count; $i++ )
			{
				$key = array_search( $this->value[$i], $values );
				if( $key !== false )
				{
					$option = new \System\XML\DomObject( 'option' );
					$option->setAttribute( 'value', $values[$key] );
					$option->nodeValue .= $keys[$key];

					$option2 = new \System\XML\DomObject( 'option' );
					$option2->setAttribute( 'value', $values[$key] );
					$option2->nodeValue .= $keys[$key];
					$option2->setAttribute('selected', 'selected');

					$select2->addChild( $option );
					$select3->addChild( $option2 );
				}
			}

			// if no options (required for XHTML)
			if( $this->items->count === 0 )
			{
				$option = new \System\XML\DomObject( 'option' );
				$option->setAttribute( 'value', '' );
				$option->nodeValue = '';

				$select->addChild( $option );
			}

			// create buttons
			$add = new \System\XML\DomObject( 'input' );
			$add->setAttribute( 'value', '>>' );
			$add->setAttribute( 'type', 'button' );
			$add->setAttribute( 'class', 'picklist_button picklist_button_add' );
			$add->setAttribute( 'id', $this->getHTMLControlIdString() . '__add' );
			$add->setAttribute( 'onclick', 'PickList.move(\''.$this->getHTMLControlIdString().'__unselected\', \''.$this->getHTMLControlIdString().'__dummy\');PickList.updateSelected(\''.$this->getHTMLControlIdString().'__dummy\', \''.$this->getHTMLControlIdString().'__selected\');' );

			$remove = new \System\XML\DomObject( 'input' );
			$remove->setAttribute( 'value', '<<' );
			$remove->setAttribute( 'type', 'button' );
			$remove->setAttribute( 'class', 'picklist_button picklist_button_rem' );
			$remove->setAttribute( 'id', $this->getHTMLControlIdString() . '__rem' );
			$remove->setAttribute( 'onclick', 'PickList.move(\''.$this->getHTMLControlIdString().'__dummy\', \''.$this->getHTMLControlIdString().'__unselected\');PickList.updateSelected(\''.$this->getHTMLControlIdString().'__dummy\', \''.$this->getHTMLControlIdString().'__selected\');' );

			$span = $this->createDomObject( 'span' );
			$span->setAttribute( 'id', $this->getHTMLControlIdString() . '__node' );
			$span->appendAttribute( 'class', 'picklist' );
			$span->addChild( $select );
			$span->addChild( $add );
			$span->addChild( $remove );
			$span->addChild( $select2 );
			$span->addChild( $select3 );

			if( !$this->visible )
			{
				$span->setAttribute( 'style', 'display: none;' );
			}

			return $span;
		}


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access public
		 */
		protected function onLoad()
		{
			parent::onLoad();

			// Install assets
			if(!file_exists(__HTDOCS_PATH__ . '/assets/picklist'))
			{
				\System\Utils\FileSystem::copy(__PLUGINS_PATH__ . str_replace(__PLUGINS_PATH__, '', str_replace('\\', '/', __DIR__)) . '/assets', __HTDOCS_PATH__ . '/assets/picklist');
			}

			$page = $this->getParentByType( '\System\Web\WebControls\Page' );

			if( $page )
			{
				$page->addScript( \System\Base\ApplicationBase::getInstance()->config->assets . '/picklist/picklist.js' );
				$page->addLink( \System\Base\ApplicationBase::getInstance()->config->assets . '/picklist/picklist.css' );
			}
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$request )
		{
			if( !$this->disabled )
			{
				if( $this->readonly )
				{
					$this->submitted = true;
				}
				elseif( isset( $request[$this->getHTMLControlIdString() . '__post'] ))
				{
					$this->submitted = true;

					if( isset( $request[$this->getHTMLControlIdString()] ))
					{
						if( $this->value != $request[$this->getHTMLControlIdString()] )
						{
							$this->changed = true;
						}

						if(is_array($request[$this->getHTMLControlIdString()]))
						{
							$this->value = $request[$this->getHTMLControlIdString()];
						}
						else
						{
							$this->value = array();
						}
						unset( $request[$this->getHTMLControlIdString()] );
					}
					else
					{
						$this->value = array();
					}

					unset( $request[$this->getHTMLControlIdString() . '__post'] );
				}

				if( !$this->value )
				{
					$this->value = array();
				}
			}
		}
	}
?>