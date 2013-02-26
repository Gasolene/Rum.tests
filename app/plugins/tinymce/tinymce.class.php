<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace TinyMCE;
	use \System\Web\WebControls\InputBase;
	use \System\Web\WebApplicationBase;

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
	class TinyMCE extends InputBase
	{
		/**
		 * PATHTOEDITORFILES
		 * @var string
		 */
		const PATHTOEDITORFILES			= '/assets/tinymce';


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
            $this->getParentByType( '\System\Web\WebControls\Page' )->addScript(\System\Web\WebApplicationBase::getInstance()->config->uri . self::PATHTOEDITORFILES . '/tiny_mce.js' );
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
			$span = new \System\XML\DomObject( 'span' );
			$span->innerHtml = "
<script type=\"text/javascript\">
	tinyMCE.init({
		// General options
		mode : \"textareas\",
		theme : \"advanced\",
		plugins : \"autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave\",

		// Theme options
		theme_advanced_buttons1 : \"save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect\",
		theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor\",
		theme_advanced_buttons3 : \"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen\",
		theme_advanced_buttons4 : \"insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft\",
		theme_advanced_toolbar_location : \"top\",
		theme_advanced_toolbar_align : \"left\",
		theme_advanced_statusbar_location : \"bottom\",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : \"css/content.css\",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : \"lists/template_list.js\",
		external_link_list_url : \"lists/link_list.js\",
		external_image_list_url : \"lists/image_list.js\",
		media_external_list_url : \"lists/media_list.js\",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {}
	});
</script>
<textarea class=\"tinymce\" id=\"{$this->getHTMLControlIdString()}\" name=\"{$this->getHTMLControlIdString()}\" rows=\"15\" cols=\"80\" style=\"width:100%;\">
".\htmlentities($this->value)."
</textarea>";
			return $span;
		}
	}
?>