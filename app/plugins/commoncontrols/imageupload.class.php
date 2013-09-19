<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\File;

	/**
     * handles element creation and event handling
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 * 
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class ImageUpload extends File
	{
		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad() {
			parent::onLoad();

			$this->addValidator(new \System\Validators\FileTypeValidator(array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')));
		}
	}
?>