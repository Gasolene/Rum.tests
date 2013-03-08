<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace CKEditor;

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
	class CKEditor extends InputBase
	{
		/**
		 * PATHTOEDITORFILES
		 * @var string
		 */
		const PATHTOEDITORFILES			= '/assets/ckeditor';

		/**
		 * PATHTOEDITORFILES
		 * @var string
		 */
		const PATHTOUSERFILES			= '/resources';

		/**
		 * contains the list of configuration options
         * @var array
		 * @access public
		 */
		public $configuration			= array();

		/**
		 * contains the list of event handlers
         * @var array
		 * @access public
		 */
		public $events					= array();


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad()
		{
			parent::onLoad();

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
            $this->getParentByType( '\System\Web\WebControls\Page' )->addScript(WebApplicationBase::getInstance()->config->uri . self::PATHTOEDITORFILES . '/ckeditor.js' );
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
			require_once WebApplicationBase::getInstance()->config->htdocs . self::PATHTOEDITORFILES . '/ckeditor.php';

			// store path in session
			WebApplicationBase::getInstance()->session['CKEditor_UserFilesPath'] = serialize( WebApplicationBase::getInstance()->config->uri . self::PATHTOUSERFILES );
			WebApplicationBase::getInstance()->session['CKEditor_UserFilesAbsolutePath'] = serialize( WebApplicationBase::getInstance()->config->htdocs . self::PATHTOUSERFILES );

			$baseURI = WebApplicationBase::getInstance()->config->uri . self::PATHTOEDITORFILES;
			$baseURL = WebApplicationBase::getInstance()->config->url . self::PATHTOEDITORFILES;

			$CKEditor = new \CKEditor(self::PATHTOEDITORFILES);
			$this->configuration['filebrowserBrowseUrl'] = "{$baseURI}/filemanager/browser/default/browser.html?Connector={$baseURL}/filemanager/connectors/php/connector.php";
			$this->configuration['filebrowserImageBrowseUrl'] = "{$baseURI}/filemanager/browser/default/browser.html?Type=Image&Connector={$baseURL}/filemanager/connectors/php/connector.php";
			$this->configuration['filebrowserFlashBrowseUrl'] = "{$baseURI}/filemanager/browser/default/browser.html?Type=Flash&Connector={$baseURL}/filemanager/connectors/php/connector.php";

			ob_start();
			$CKEditor->editor($this->getHTMLControlId(), $this->value, $this->configuration, $this->events);
			$span = new DomObject( 'span' );
			$span->innerHtml = \ob_get_clean();
			return $span;
		}
	}
?>