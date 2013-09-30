<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace CommonControls;

	// register event handler
	\System\Base\ApplicationBase::getInstance()->events->registerEventHandler(new \System\Base\Events\ApplicationRunEventHandler('\\CommonControls\\registerFieldAndRuleTypes'));


	/**
	 * register field types and rules
	 * 
	 * @return void
	 */
	function registerFieldAndRuleTypes()
	{
		\System\Web\FormModelBase::registerFieldType('address', '\CommonControls\AddressInput');
		\System\Web\FormModelBase::registerFieldType('unitnumber', '\CommonControls\UnitNumberInput');
		\System\Web\FormModelBase::registerFieldType('buildingnumber', '\CommonControls\BuildingNumberInput');
		\System\Web\FormModelBase::registerFieldType('streetname', '\CommonControls\StreetNameInput');
		\System\Web\FormModelBase::registerFieldType('quadrant', '\CommonControls\QuadrantSelector');
		\System\Web\FormModelBase::registerFieldType('province', '\CommonControls\ProvinceSelector');
		\System\Web\FormModelBase::registerFieldType('provincestate', '\CommonControls\ProvinceStateSelector');
		\System\Web\FormModelBase::registerFieldType('state', '\CommonControls\StateSelector');
		\System\Web\FormModelBase::registerFieldType('country', '\CommonControls\CountrySelector');
		\System\Web\FormModelBase::registerFieldType('postalcode', '\CommonControls\PostalCodeInput');
		\System\Web\FormModelBase::registerFieldType('postalzipcode', '\CommonControls\PostalZipCodeInput');
		\System\Web\FormModelBase::registerFieldType('zipcode', '\CommonControls\ZipCodeInput');

		\System\Web\FormModelBase::registerFieldType('cardexpiration', '\CommonControls\CardExpirationDateSelector');
		\System\Web\FormModelBase::registerFieldType('cardnumber', '\CommonControls\CardNumberInput');
		\System\Web\FormModelBase::registerFieldType('cardsecuritycode', '\CommonControls\CardSecurityCodeInput');
		\System\Web\FormModelBase::registerFieldType('cardtype', '\CommonControls\CardTypeSelector');
		
		\System\Web\FormModelBase::registerFieldType('csv', '\CommonControls\CSVView');
		\System\Web\FormModelBase::registerFieldType('email', '\CommonControls\EmailAddressInput');
		\System\Web\FormModelBase::registerFieldType('gender', '\CommonControls\GenderSelector');
		\System\Web\FormModelBase::registerFieldType('image', '\CommonControls\ImageUpload');
		\System\Web\FormModelBase::registerFieldType('numeric', '\CommonControls\NumericInput');
		\System\Web\FormModelBase::registerFieldType('password', '\CommonControls\PasswordInput');
		\System\Web\FormModelBase::registerFieldType('pdf', '\CommonControls\PDFView');
		\System\Web\FormModelBase::registerFieldType('percent', '\CommonControls\PercentInput');
		\System\Web\FormModelBase::registerFieldType('phonenumber', '\CommonControls\PhoneNumberInput');
		\System\Web\FormModelBase::registerFieldType('price', '\CommonControls\MoneyInput');
		\System\Web\FormModelBase::registerFieldType('money', '\CommonControls\MoneyInput');
		\System\Web\FormModelBase::registerFieldType('title', '\CommonControls\TitleSelector');
		\System\Web\FormModelBase::registerFieldType('url', '\CommonControls\URLInput');
		\System\Web\FormModelBase::registerFieldType('year', '\CommonControls\YearInput');

		\System\Web\FormModelBase::registerRuleType('cardexpiration', '\CommonControls\CardExpirationDateValidator');
		\System\Web\FormModelBase::registerRuleType('cardnumber', '\CommonControls\CardNumberValidator');
		\System\Web\FormModelBase::registerRuleType('cardsecuritycode', '\CommonControls\CardSecurityCodeValidator');
		\System\Web\FormModelBase::registerRuleType('phonenumber', '\CommonControls\PhoneNumberValidator');
		\System\Web\FormModelBase::registerRuleType('postalzipcode', '\CommonControls\PostalZipCodeValidator');
	}
?>