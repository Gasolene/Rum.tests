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
	class ProvinceStateSelector extends DropDownList
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
		public static $provincesstates = array (
			'--Select a State/Province--' => '',
			'Outside U.S./Canada' => '--',
			'Alabama' => 'AL',
			'Alaska' => 'AK',
			'Alberta' => 'AB',
			'Arizona' => 'AZ',
			'Arkansas' => 'AR',
			'British Columbia' => 'BC',
			'California' => 'CA',
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
			'Manitoba' => 'MB',
			'Maryland' => 'MD',
			'Massachusetts' => 'MA',
			'Michigan' => 'MI',
			'Minnesota' => 'MN',
			'Mississippi' => 'MS',
			'Missouri' => 'MO',
			'Montana' => 'MT',
			'Nebraska' => 'NE',
			'Nevada' => 'NV',
			'New Brunswick' => 'NB',
			'New Hampshire' => 'NH',
			'New Jersey' => 'NJ',
			'New Mexico' => 'NM',
			'New York' => 'NY',
			'Newfoundland and Labrador' => 'NL',
			'North Carolina' => 'NC',
			'North Dakota' => 'ND',
			'Northwest Territories' => 'NT',
			'Nova Scotia' => 'NS',
			'Nunavut' => 'NU',
			'Ohio' => 'OH',
			'Oklahoma' => 'OK',
			'Ontario' => 'ON',
			'Oregon' => 'OR',
			'Pennsylvania' => 'PA',
			'Prince Edward Island' => 'PE',
			'Quebec' => 'QC',
			'Rhode Island' => 'RI',
			'Saskatchewan' => 'SK',
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
			'Wyoming' => 'WY',
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

			$this->items = new \System\Web\WebControls\ListItemCollection(ProvinceStateSelector::$provincesstates);

			if( $this->countrySelector ) {
				$htmlControlIdString = $this->countrySelector->getHTMLControlId();
				$this->attributes->add( 'onchange', 'if(this.value==\'AB\'||this.value==\'BC\'||this.value==\'MB\'||this.value==\'NB\'||this.value==\'NL\'||this.value==\'NT\'||this.value==\'NS\'||this.value==\'NU\'||this.value==\'ON\'||this.value==\'PE\'||this.value==\'QC\'||this.value==\'SK\'||this.value==\'YT\'){document.getElementById(\'' . $htmlControlIdString . '\').value = \'CA\';}else if(this.value!=\'--\' && this.value!=\'\'){document.getElementById(\'' . $htmlControlIdString . '\').value = \'US\';}' );
				$this->countrySelector->attributes->add( 'onchange', 'if(this.value!=\'CA\' && this.value!=\'US\'){document.getElementById(\'' . $this->getHTMLControlId() . '\').value = \'--\';}' );
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