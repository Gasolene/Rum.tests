<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace FCKEditor;

	use \System\Web\WebControls\InputBase;
	use \System\Web\WebApplicationBase;
	use \System\XML\DomObject;

	/**
     * handles text control element creation
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		FCKEditor
	 */
	class FCKEditor extends InputBase
	{
		/**
		 * PATHTOEDITORFILES
		 * @var string
		 */
		const PATHTOEDITORFILES			= '/assets/fckeditor';

		/**
		 * PATHTOEDITORFILES
		 * @var string
		 */
		const PATHTOUSERFILES			= '/resources';

		/**
		 * specifies the width in pixels or percent
         * @var string
		 * @access public
		 */
		public $width					= '100%';

		/**
		 * specifies the height in pixels or percent
         * @var string
		 * @access public
		 */
		public $height					= '300';

		/**
		 * specifies the toolbar to use
         * @var string
		 * @access public
		 */
		public $toolbar					= 'Default';

		/**
		 * contains the list of configuration options
         * @var array
		 * @access public
		 */
		public $configuration			= array();


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad()
		{
			parent::onLoad();

			\trigger_error("FCKEditor plugin is deprecated, use CKEditor plugin instead", E_USER_NOTICE);

			// Install assets
			if(!file_exists(__HTDOCS_PATH__ . self::PATHTOEDITORFILES))
			{
				try
				{
					\System\Utils\FileSystem::copy(__DIR__ . '/assets', __HTDOCS_PATH__ . self::PATHTOEDITORFILES);
				}
				catch(\Exception $e)
				{
					throw new \System\Utils\DirectoryNotWritableException("Could not install assets into " . __HTDOCS_PATH__ . self::PATHTOEDITORFILES);
				}
			}

            // include external resources
            $this->getParentByType( '\System\Web\WebControls\Page' )->addScript(WebApplicationBase::getInstance()->config->uri . self::PATHTOEDITORFILES . '/fckeditor.js' );
        }


		/**
		 * returns a DomObject object
		 *
		 * @param  none
		 * @return DomObject
		 * @access public
		 */
		public function getDomObject()
		{
			require_once WebApplicationBase::getInstance()->config->htdocs . self::PATHTOEDITORFILES . '/fckeditor.php';

			// store path in session
			WebApplicationBase::getInstance()->session['FCKEditor_UserFilesPath'] = serialize( WebApplicationBase::getInstance()->config->uri . self::PATHTOUSERFILES );
			WebApplicationBase::getInstance()->session['FCKEditor_UserFilesAbsolutePath'] = serialize( WebApplicationBase::getInstance()->config->htdocs . self::PATHTOUSERFILES );

			$FCKEditor = new \FCKeditor($this->getHTMLControlId());
			$FCKEditor->BasePath = WebApplicationBase::getInstance()->config->uri . self::PATHTOEDITORFILES . '/';
			$FCKEditor->Width = $this->width;
			$FCKEditor->Height = $this->height;
			$FCKEditor->ToolbarSet = $this->toolbar;
			$FCKEditor->Value = $this->value;
			$FCKEditor->Config = $this->configuration;

			$span = new DomObject( 'span' );
			$span->innerHtml = $FCKEditor->CreateHtml();
			return $span;
/**
			\System\Base\ApplicationBase::getInstance()->session['FCKEditor_UserFilesPath'] = serialize( WebApplicationBase::getInstance()->config->htdocs . self::PATHTOUSERFILES );

			$value = $this->value;
			$value = str_replace( "\\", "\\\\", $value );		// escape backslash
			$value = str_replace( "\"", "\\\"", $value );		// escape dbl quotes
			$value = str_replace( "\n", '', $value);			// remove linebreaks
			$value = str_replace( "\r", '', $value);
            $value = str_replace( "</script>", '</scr"+"ipt>', $value);

			$span = new \System\XML\DomObject( 'span' );

			$fckeditor = new \System\XML\DomObject( 'script' );
			$fckeditor->setAttribute( 'type', 'text/javascript' );
			$fckeditor->innerHtml = "<!--
var oFCKeditor_{$this->controlId}        = new FCKeditor( \"{$this->getHTMLControlId()}\" ) ;
oFCKeditor_{$this->controlId}.BasePath	 = \"" . WebApplicationBase::getInstance()->config->uri . self::PATHTOEDITORFILES . "/\" ;
oFCKeditor_{$this->controlId}.Height	 = 300;
oFCKeditor_{$this->controlId}.Value	     = \"{$value}\";
oFCKeditor_{$this->controlId}.Create() ;
//-->";

			$span->addChild( $fckeditor );
			return $span;
 */
		}
	}
?>