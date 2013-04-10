<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace JQuery;


	/**
	 * handles text control element creation
	 * abstracts away the presentation logic and data access layer
	 * the server-side control for JQuery Widgets
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			JQuery
	 * @subpackage		Web
	 */
	abstract class JQueryWidgetBase extends \System\Web\WebControls\InputBase
	{
		/**
		 * jquery options
		 * @var array
		 */
		protected $options = array();


		/**
		 * Constructor
		 *
		 * The constructor sets attributes based on session data, triggering events, and is responcible for
		 * formatting the proper request value and garbage handling
		 *
		 * @param  string   $controlId	  Control Id
		 * @param  string   $default		Default value
		 * @param  string   $options		JQuery init options
		 * @return void
		 */
		public function __construct( $controlId, $default = null, array $options = array() )
		{
			$this->options = $options;
		}


		/**
		 * set JQuery init options
		 *
		 * @param  string   $options		JQuery init options
		 * @return void
		 */
		public function setOptions( array $options )
		{
			$this->options = $options;
		}


		/**
		 * get formatted JQuery init options
		 *
		 * @return array
		 */
		protected function getOptions()
		{
			$options = "";
			foreach($this->options as $key=>$val)
			{
				$options[] = "{$key}: {$val},";
			}
			return $options;
		}
	}
?>