<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;
	use \System\Web\WebControls\DropDownList;

	/**
     * handles button element creation and event handling
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class ProvinceSelector extends DropDownList
	{
		/**
		 * linked CountrySelector
		 * @var CountrySelector
		 */
		protected $countrySelector;


		/**
		 * array of province codes
		 * @var array
		 */
		public static $provinces = array (
			'--Select a Province--' => '',
			'Outside Canada' => '--',
			'Alberta' => 'AB',
			'British Columbia' => 'BC',
			'Manitoba' => 'MB',
			'New Brunswick' => 'NB',
			'Newfoundland and Labrador' => 'NL',
			'Northwest Territories' => 'NT',
			'Nova Scotia' => 'NS',
			'Nunavut' => 'NU',
			'Ontario' => 'ON',
			'Prince Edward Island' => 'PE',
			'Quebec' => 'QC',
			'Saskatchewan' => 'SK',
			'Yukon' => 'YT' );


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access public
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->items = new \System\Web\WebControls\ListItemCollection(ProvinceSelector::$provinces);

			if( $this->countrySelector ) {
				$htmlControlIdString = $this->countrySelector->getHTMLControlId();
				$this->attributes->add( 'onchange', 'document.getElementById(\'' . $htmlControlIdString . '\').value = \'CA\';' );
			}
		}


		/**
		 * attach a CountrySelector control
		 *
		 * @param CountrySelector $countrySelector
		 * @return void
		 */
		public function attachCountrySelector(CountrySelector $countrySelector)
		{
			$this->countrySelector = $countrySelector;
		}
	}
?>