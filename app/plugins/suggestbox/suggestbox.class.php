<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace SuggestBox;
	use \System\Web\WebControls\InputBase;


	/**
     * handles SuggestBox control element creation
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 *
	 * @property bool $multiline multiline
	 * @property int $size size of textbox
	 * @property int $rows no of rows
	 * @property int $cols no of cols
	 * @property bool $disableAutoComplete disable auto complete
	 * @property int $maxNumToShow max no to show
	 * @property int $listSize list size
	 * @property string $textField text field
	 * @property bool $showImage show image
	 * @property string $listName list name
	 * @property string $delimiter delimiter
	 * @property SuggestItemCollection $items	collection of items
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			2.0
	 * @package			PHPRum
	 * @subpackage		SuggestBox
	 * @since			3.1.2
	 */
	class SuggestBox extends \System\Web\WebControls\TextBox
	{
		/**
		 * Specifies whether textbox will accept multiple lines of text
         * @var bool
		 * @access protected
		 */
		protected $multiline			= false;

		/**
		 * Specifies the size of a textbox
         * @var int
		 * @access protected
		 */
		protected $size					= 30;

		/**
		 * Specifies that number of rows in multiline textbox
         * @var int
		 * @access protected
		 */
		protected $rows					= 5;

		/**
		 * Specifies that number of columns in multiline textbox
         * @var int
		 * @access protected
		 */
		protected $cols					= 60;

		/**
		 * Specifies whether to enabled the browsers auto complete feature
         * @var bool
		 * @access protected
		 */
		protected $disableAutoComplete	= false;

		/**
		 * max number of items to show on filter
         * @var int
		 * @access protected
		 */
		protected $maxNumToShow			= 20;

		/**
		 * size of lookup list
         * @var int
		 * @access protected
		 */
		protected $listSize				= 5;

		/**
		 * name of label field in datasource
         * @var string
		 * @access protected
		 */
		protected $textField			= '';

		/**
		 * Specifies whether to show image(s) or not (optional)
         * @var bool
		 * @access protected
		 */
		protected $showImage			= true;

		/**
		 * determines the list name so that lists can be shared, Defaults to controlId
         * @var string
		 * @access protected
		 */
		protected $listName				= '';

		/**
		 * spcifies the delimiter
         * @var string
		 * @access protected
		 */
		protected $delimiter			= '';

		/**
		 * collection of dictionary objects
         * @var SuggestItemCollection
		 * @access protected
		 */
		protected $items;


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

			$this->items            = new SuggestItemCollection();
			$this->listName         = $controlId;
			$this->ajaxEventHandler = 'SuggestBox.onResponse';
			$this->ajaxHTTPRequest  = 'SuggestBox.HTTPRequest';
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
			if($field=='multiline')
			{
				$this->{$field} = (bool)$value;
			}
			elseif($field=='size')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='rows')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='cols')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='disableAutoComplete')
			{
				$this->{$field} = (bool)$value;
			}
			elseif($field=='maxNumToShow')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='listSize')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='textField')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='showImage')
			{
				$this->{$field} = (bool)$value;
			}
			elseif($field=='listName')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='delimiter')
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
			$input = null;

			// create widget
			if( $this->multiline )
			{
				$input = $this->createDomObject('textarea');
				$input->appendAttribute( 'class', ' lookup' );
				$input->setAttribute( 'cols', $this->cols );
				$input->setAttribute( 'rows', $this->rows );
				$input->nodeValue = $this->getTextValue();
			}
			else
			{
				$input = $this->getInputDomObject();
				$input->setAttribute( 'type', 'text' );
				$input->setAttribute( 'size', $this->size );
				$input->setAttribute( 'autocomplete', 'off' );
				$input->setAttribute( 'value', $this->getTextValue() );
			}

			$input->setAttribute( 'id',           $this->getHTMLControlIdString() );
			$input->setAttribute( 'name',         $this->getHTMLControlIdString() );
			$input->setAttribute( 'tabIndex',     $this->tabIndex );
			$input->appendAttribute( 'class',     ' textbox suggestbox' );

			$input->appendAttribute( 'onkeydown',    'if(document.getElementById(\''.$this->getHTMLControlIdString().'__lookup\').style.display==\'block\')if(event.keyCode==13){return false;}' );
			$input->appendAttribute( 'onkeyup',      'SuggestBox.handleKeyUp(event.keyCode,'.$this->maxNumToShow.','.($this->disableAutoComplete?'false':'true').',document.getElementById(\''.$this->getHTMLControlIdString().'\'),document.getElementById(\''.$this->getHTMLControlIdString().'__lookup\'),\''.$this->listName.'\',false,\''.str_replace('\'', '\\\'', $this->delimiter).'\');' );

			$select = new \System\XML\DomObject( 'select' );
			$select->setAttribute( 'id',          $this->getHTMLControlIdString() . '__lookup' );
			$select->setAttribute( 'size',        $this->listSize );
			$select->setAttribute( 'style',       'display:none;' );
			$select->setAttribute( 'class',       'listbox lookup_list' );
			$select->setAttribute( 'onkeyup',     'SuggestBox.selectHandleKeyUp(event.keyCode,document.getElementById(\''.$this->getHTMLControlIdString().'\'),document.getElementById(\''.$this->getHTMLControlIdString().'__lookup\'),\''.str_replace('\'', '\\\'', $this->delimiter).'\');' );
			$select->setAttribute( 'onclick',     'SuggestBox.update(document.getElementById(\''.$this->getHTMLControlIdString().'\'),document.getElementById(\''.$this->getHTMLControlIdString().'__lookup\'),\''.str_replace('\'', '\\\'', $this->delimiter).'\');' );

			// handle for XHTML
			/**
			$option = new \System\XML\DomObject( 'option' );
			$option->setAttribute( 'value', '' );
			$option->nodeValue = '';
			$select->addChild( $option );
			unset( $option );
			 *
			 */

			$img = new \System\XML\DomObject( 'img' );
			$img->setAttribute( 'src',            \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'suggestbox', 'type'=>'image/gif')) . '&asset=icon.gif' );
			$img->setAttribute( 'alt',            'lookup' );
			$img->setAttribute( 'class',          'lookup_img' );
			$img->setAttribute( 'onclick',        'SuggestBox.handleKeyUp(event.keyCode,'.$this->maxNumToShow.','.($this->disableAutoComplete?'false':'true').',document.getElementById(\''.$this->getHTMLControlIdString().'\'),document.getElementById(\''.$this->getHTMLControlIdString().'__lookup\'),\''.$this->listName.'\',false,\''.str_replace('\'', '\\\'', $this->delimiter).'\');' );

			$lookup = new \System\XML\DomObject( 'span' );
			$lookup->setAttribute( 'style',       'position: relative;' );
			$lookup->addChild( $input );
			$lookup->addChild( $select );
			if( $this->showImage && $this->visible ) $lookup->addChild( $img );

			return $lookup;
		}


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'suggestbox', 'type'=>'text/css')) . '&asset=suggestbox.css' );
			$this->getParentByType( '\System\Web\WebControls\Page' )->addScript( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'suggestbox', 'type'=>'text/javascript')) . '&asset=suggestbox.js' );
			$this->getParentByType( '\System\Web\WebControls\Page' )->addScript( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'suggestbox', 'type'=>'text/javascript')) . '&asset=array.js' );

			if( !defined( '__LISTNAME' . $this->listName ))
			{
				define( '__LISTNAME' . $this->listName, true );

				$this->getParentByType( '\System\Web\WebControls\Page' )->onload .= $this->ajaxHTTPRequest . '[\''.$this->listName.'\'] = PHPRum.sendHttpRequest( \'' . $this->ajaxCallback . '\', \'' . $this->getRequestData() . '&' . $this->getHTMLControlIdString() . '__async=true\', \'POST\', function() { ' . $this->ajaxEventHandler . '( \'' . $this->listName . '\', \'' . $this->textField . '\', \'' . $this->getHTMLControlIdString() . '\' ); } );SuggestBox.textValues[\''.$this->listName.'\']=document.getElementById(\''.$this->getHTMLControlIdString().'\').value;document.getElementById(\''.$this->getHTMLControlIdString().'\').value=\'Loading...\';document.getElementById(\''.$this->getHTMLControlIdString().'\').disabled=true;';
			}
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$httpRequest )
		{
			if( isset( $httpRequest[$this->getHTMLControlIdString() . '__async'] ))
			{
				// send DataSet as xml message
				\System\Web\HTTPResponse::addHeader('content-type', 'text/xml');
				\System\Web\HTTPResponse::write($this->getItemArray());
				\System\Web\HTTPResponse::end();
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
			if( $this->textField )
			{
				while( !$this->dataSource->eof() )
				{
					if( !$this->items->contains( $this->dataSource[$this->textField] ))
					{
						$this->items->add( $this->dataSource[$this->textField] );
					}
					$this->dataSource->next();
				}
			}
			else
			{
				throw new \Exception( 'SuggestBox::dataBind() called with no textField set' );
			}
		}


		/**
		 * get text value
		 *
		 * @return void
		 * @access protected
		 */
		protected function getTextValue()
		{
			return $this->value;
		}


		/**
		 * get item array
		 *
		 * @return void
		 * @access protected
		 */
		protected function getItemArray()
		{
			$this->items->trim();
			$list = '';
			foreach( $this->items as $value )
			{
				if( $list )
				{
					$list .= ',\'' . addslashes( $value ). '\'';
				}
				else
				{
					$list = '\'' . addslashes( $value ). '\'';
				}
			}

			return "Array($list)";
		}
	}
?>