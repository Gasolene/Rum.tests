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
	class StateSelector extends DropDownList
	{
		/**
		 * linked CountrySelector
		 * @var CountrySelector
		 */
		protected $countrySelector;

		/**
		 * array of province/state codes
		 * @var array
		 */
		public static $states = array(
				'--Select a State--' => '',
				'Outside U.S' => '--',
				'Alabama' => 'AL',
				'Alaska' => 'AK',
				'Arizona' => 'AZ',
				'Arkansas' => 'AR',
				'Colorado' => 'CO',
				'Connecticut' => 'CT',
				'Delaware' => 'DE',
				'District of Columbia' => 'DC',
				'Florida' => 'FL',
				'Georgia' => 'GA',
				'Hawaii' => 'HI',
				'Idaho' => 'ID',
				'Illinois' => 'IL',
				'Indiana' => 'IN',
				'Iowa' => 'IA',
				'Kansas' => 'KS',
				'Kentucky' => 'KY',
				'Louisiana' => 'LA',
				'Maine' => 'ME',
				'Maryland' => 'MD',
				'Massachusetts' => 'MA',
				'Michigan' => 'MI',
				'Minnesota' => 'MN',
				'Mississippi' => 'MS',
				'Missouri' => 'MO',
				'Montana' => 'MT',
				'Nebraska' => 'NE',
				'Nevada' => 'NV',
				'New Hampshire' => 'NH',
				'New Jersey' => 'NJ',
				'New Mexico' => 'NM',
				'New York' => 'NY',
				'North Carolina' => 'NC',
				'North Dakota' => 'ND',
				'Ohio' => 'OH',
				'Oklahoma' => 'OK',
				'Oregon' => 'OR',
				'Pennsylvania' => 'PA',
				'Puerto Rico' => 'PR',
				'Rhode Island' => 'RI',
				'South Carolina' => 'SC',
				'South Dakota' => 'SD',
				'Tennessee' => 'TN',
				'Texas' => 'TX',
				'Utah' => 'UT',
				'Vermont' => 'VT',
				'Virginia' => 'VA',
				'Washington' => 'WA',
				'West Virginia' => 'WV',
				'Wisconsin' => 'WI',
				'Wyoming' => 'WY' );


		/**
		 * called when control is loaded
		 *
		 * @return bool			true if successfull
		 * @access public
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$this->items = new \System\Web\WebControls\ListItemCollection(StateSelector::$states);

			if( $this->countrySelector ) {
				$htmlControlIdString = $this->countrySelector->getHTMLControlIdString();
				$this->attributes->add( 'onchange', 'document.getElementById(\'' . $htmlControlIdString . '\').value = \'US\';' );
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