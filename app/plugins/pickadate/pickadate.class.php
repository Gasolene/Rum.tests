<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace PickADate;
	use \System\Web\WebControls\TextBox;


	/**
     * handles text control element creation
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		DatePicker
	 */
	class PickADate extends TextBox
	{
		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad() {
			parent::onLoad();

			// Install assets
			if(!file_exists(__HTDOCS_PATH__ . '/assets/pickadate'))
			{
				\System\Utils\FileSystem::copy(__DIR__ . '/assets', __HTDOCS_PATH__ . '/assets/datepicker');
			}

			// include external resources
			$this->addValidator(new \System\Validators\DateTimeValidator());
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Base\ApplicationBase::getInstance()->config->assets . '/pickadate/pickadate.css' );
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Base\ApplicationBase::getInstance()->config->assets . '/pickadate/custom.css' );
			$this->getParentByType( '\System\Web\WebControls\Page' )->addScript( \System\Base\ApplicationBase::getInstance()->config->assets . '/pickadate/pickadate.js' );
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
			$span = new \System\XML\DomObject('span');
			$input = parent::getDomObject();
			$input->appendAttribute( 'class', ' datepicker' );

			$script = new \System\XML\DomObject('span');
			
			//Ensure value is formatting such that PickADate.js can use the value
			$formattedDate = date('Y-n-j', strtotime($this->value));
			$arrayFormatValue = "['".str_replace('-', "', '", $formattedDate)."']";
			
			$script->innerHtml = "
			<script type=\"text/javascript\">
				$( '#".$this->getHTMLControlIdString()."' ).pickadate({
					date_min:		false,
					date_max:		false,
					date_selected:	".$arrayFormatValue.",
					format:			'yyyy-mm-dd',
					format_submit:	'yyyy-mm-dd'
				});
			</script>";

			$span->addChild($input);
			$span->addChild($script);
			return $span;
		}
	}
	?>