<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace MyApp;

	/**
	 * UI Helper
	 *
	 * It provides a way to create controls outside of the current namespace
	 * without importing the classes.
	 *
	 * @version			1.0
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	final class UI
	{
		/**
		 * creates a new DateSelector control
		 *
		 * @param string $controlId
		 * @return DateSelector
		 */
		static public function DateSelector($controlId)
		{
			return new \System\UI\WebControls\DateSelector($controlId);
		}

		/**
		 * creates a new DateSelector control
		 *
		 * @param string $controlId
		 * @return DateSelector
		 */
		static public function DateTimeSelector($controlId)
		{
			return new \System\UI\WebControls\DateTimeSelector($controlId);
		}

		/**
		 * creates a new FormView control
		 *
		 * @param string $controlId
		 * @return FormView
		 */
		static public function FormView($controlId)
		{
			return new \System\UI\WebControls\FormView($controlId);
		}

		/**
		 * creates a new GridView control
		 *
		 * @param string $controlId
		 * @return GridView
		 */
		static public function GridView($controlId)
		{
			return new \System\UI\WebControls\GridView($controlId);
		}

		/**
		 * creates a new GridViewColumn control
		 *
		 * @param  string		$dataField			name of data field to bind column to
		 * @param  string		$headerText			column header text
		 * @param  string		$itemText			column item text (templating allowed)
		 * @param  string		$footerText			column footer text
		 * @param  string		$className			column css class name
		 * @return GridViewColumn
		 */
		static public function GridViewColumn( $dataField, $headerText = '', $itemText = '', $footerText = '', $className = '' )
		{
			return new \System\UI\WebControls\GridViewColumn($dataField, $headerText, $itemText, $footerText, $className);
		}

		/**
		 * creates a new GridViewButton control
		 *
		 * @param  string		$dataField			field name
		 * @param  string		$buttonName			name of button
		 * @param  string		$parameter			parameter
		 * @param  string		$headerText			header text
		 * @param  string		$footerText			footer text
		 * @param  string		$className			css class name
		 * @return GridViewButton
		 */
		static public function GridViewButton( $dataField, $buttonName, $parameter='', $headerText='', $footerText='', $className='' )
		{
			return new \System\UI\WebControls\GridViewButton($dataField, $buttonName, $parameter, $headerText, $footerText, $className);
		}

		/**
		 * creates a new GridViewLink control
		 *
		 * @param  string		$dataField			field name
		 * @param  string		$headerText			header text
		 * @param  string		$url				url (templating allowed)
		 * @param  string		$title				link title (templating allowed)
		 * @param  string		$target				link target
		 * @param  string		$alt				alt text
		 * @param  string		$footerText			footer text
		 * @param  string		$className			css class name
		 * @return GridViewLink
		 */
		static public function GridViewLink( $dataField, $headerText='', $url='', $title='', $target='', $alt='', $footerText='', $className='' )
		{
			return new \System\UI\WebControls\GridViewLink($dataField, $headerText, $url, $title, $target, $alt, $footerText, $className);
		}

		/**
		 * creates a new GridViewImage control
		 *
		 * @param  string		$dataField			field name
		 * @param  string		$headerText			header text
		 * @param  string		$src				src (templating allowed)
		 * @param  string		$alt				alt text
		 * @param  string		$footerText			footer text
		 * @param  string		$className			css class name
		 * @return GridViewImage
		 */
		static public function GridViewImage( $dataField, $headerText='', $src='', $alt='', $footerText='', $className='' )
		{
			return new \System\UI\WebControls\GridViewImage($dataField, $headerText, $src, $alt, $footerText, $className);
		}

		/**
		 * creates a new Page control
		 *
		 * @param string $controlId
		 * @return Page
		 */
		static public function Page($controlId)
		{
			return new \System\UI\WebControls\Page($controlId);
		}

		/**
		 * creates a new LoginForm control
		 *
		 * @param string $controlId
		 * @return LoginForm
		 */
		static public function LoginForm($controlId)
		{
			return new \System\UI\WebControls\LoginForm($controlId);
		}

		/**
		 * creates a new MasterView control
		 *
		 * @param string $controlId
		 * @return MasterView
		 */
		static public function MasterView($controlId)
		{
			return new \System\UI\WebControls\MasterView($controlId);
		}

		/**
		 * creates a new ReportView control
		 *
		 * @param string $controlId
		 * @return ReportView
		 */
		static public function ReportView($controlId)
		{
			return new \System\UI\WebControls\ReportView($controlId);
		}

		/**
		 * creates a new TimeSelector control
		 *
		 * @param string $controlId
		 * @return TimeSelector
		 */
		static public function TimeSelector($controlId)
		{
			return new \System\UI\WebControls\TimeSelector($controlId);
		}

		/**
		 * creates a new TreeNode control
		 *
		 * @param string $id
		 * @param bool $textOrHtml
		 * @param bool $expanded
		 * @param string $imgSrc
		 * @return TreeNode
		 */
		static public function TreeNode($id, $textOrHtml = '', $expanded = false, $imgSrc = null)
		{
			return new \System\UI\WebControls\TreeNode($id, $textOrHtml, $expanded, $imgSrc);
		}

		/**
		 * creates a new TreeView control
		 *
		 * @param string $controlId
		 * @return TreeView
		 */
		static public function TreeView($controlId)
		{
			return new \System\UI\WebControls\TreeView($controlId);
		}

		/**
		 * creates a new Form control
		 *
		 * @param string $controlId
		 * @return Form
		 */
		static public function Form($controlId)
		{
			return new \System\UI\WebControls\Form($controlId);
		}

		/**
		 * creates a new Button control
		 *
		 * @param string $controlId
		 * @param string $buttonText
		 * @return Button
		 */
		static public function Button($controlId, $buttonText = '')
		{
			return new \System\UI\WebControls\Button($controlId, $buttonText);
		}

		/**
		 * creates a new CheckBox control
		 *
		 * @param string $controlId
		 * @param bool $default
		 * @return CheckBox
		 */
		static public function CheckBox($controlId, $default = '')
		{
			return new \System\UI\WebControls\CheckBox($controlId, $default);
		}

		/**
		 * creates a new FileBrowser control
		 *
		 * @param string $controlId
		 * @return FileBrowser
		 */
		static public function FileBrowser($controlId)
		{
			return new \System\UI\WebControls\FileBrowser($controlId);
		}

		/**
		 * creates a new RadioButton control
		 *
		 * @param string $controlId
		 * @param string $default
		 * @return RadioButton
		 */
		static public function RadioButton($controlId, $default = '')
		{
			return new \System\UI\WebControls\RadioButton($controlId, $default);
		}

		/**
		 * creates a new RadioGroup control
		 *
		 * @param string $controlId
		 * @param string $default
		 * @return RadioGroup
		 */
		static public function RadioGroup($controlId, $default = '')
		{
			return new \System\UI\WebControls\RadioGroup($controlId, $default);
		}

		/**
		 * creates a new TextBox control
		 *
		 * @param string $controlId
		 * @param string $default
		 * @return TextBox
		 */
		static public function TextBox($controlId, $default = '')
		{
			return new \System\UI\WebControls\TextBox($controlId, $default);
		}

		/**
		 * creates a new CheckBoxList control
		 *
		 * @param string $controlId
		 * @param string $default
		 * @return CheckBoxList
		 */
		static public function CheckBoxList($controlId, $default = '')
		{
			return new \System\UI\WebControls\CheckBoxList($controlId, $default);
		}

		/**
		 * creates a new DropDownList control
		 *
		 * @param string $controlId
		 * @param string $default
		 * @return DropDownList
		 */
		static public function DropDownList($controlId, $default = '')
		{
			return new \System\UI\WebControls\DropDownList($controlId, $default);
		}

		/**
		 * creates a new ListBox control
		 *
		 * @param string $controlId
		 * @param string $default
		 * @return ListBox
		 */
		static public function ListBox($controlId, $default = '')
		{
			return new \System\UI\WebControls\ListBox($controlId, $default);
		}

		/**
		 * creates a new RadioButtonList control
		 *
		 * @param string $controlId
		 * @param string $default
		 * @return RadioButtonList
		 */
		static public function RadioButtonList($controlId, $default = '')
		{
			return new \System\UI\WebControls\RadioButtonList($controlId, $default);
		}

		/**
		 * creates a new DateValidator
		 *
		 * @param string $errorMessage
		 * @return DateValidator
		 */
		static public function DateValidator($errorMessage = '')
		{
			return new \System\UI\Validators\DateValidator($errorMessage);
		}

		/**
		 * creates a new FileSizeValidator
		 *
		 * @param string $errorMessage
		 * @param double $maxSize
		 * @return FileSizeValidator
		 */
		static public function FileSizeValidator($maxSize, $errorMessage = '')
		{
			return new \System\UI\Validators\FileSizeValidator($maxSize, $errorMessage);
		}

		/**
		 * creates a new FileTypeValidator
		 *
		 * @param string $errorMessage
		 * @param  array	$types array of types
		 * @return FileTypeValidator
		 */
		static public function FileTypeValidator(array $types = array(), $errorMessage = '')
		{
			return new \System\UI\Validators\FileTypeValidator($types, $errorMessage);
		}

		/**
		 * creates a new IntegerValidator
		 *
		 * @param string $errorMessage
		 * @return IntegerValidator
		 */
		static public function IntegerValidator($errorMessage = '')
		{
			return new \System\UI\Validators\IntegerValidator($errorMessage);
		}

		/**
		 * creates a new LengthValidator
		 *
		 * @param  double   $min		min value
		 * @param  double   $max		max value
		 * @param string $errorMessage
		 * @return LengthValidator
		 */
		static public function LengthValidator($min, $max, $errorMessage = '')
		{
			return new \System\UI\Validators\LengthValidator($min, $max, $errorMessage);
		}

		/**
		 * creates a new MatchValidator
		 *
		 * @param  InputBase $controlToMatch control to match
		 * @param string $errorMessage
		 * @return MatchValidator
		 */
		static public function MatchValidator(\System\UI\WebControls\InputBase &$controlToMatch, $errorMessage = '')
		{
			return new \System\UI\Validators\MatchValidator($controlToMatch, $errorMessage);
		}

		/**
		 * creates a new NumericValidator
		 *
		 * @param string $errorMessage
		 * @return NumericValidator
		 */
		static public function NumericValidator($errorMessage = '')
		{
			return new \System\UI\Validators\NumericValidator($errorMessage);
		}

		/**
		 * creates a new PatternValidator
		 *
		 * @param string $errorMessage
		 * @return PatternValidator
		 */
		static public function PatternValidator($errorMessage = '')
		{
			return new \System\UI\Validators\PatternValidator($errorMessage);
		}

		/**
		 * creates a new RangeValidator
		 *
		 * @param  double   $min		min value
		 * @param  double   $max		max value
		 * @param string $errorMessage
		 * @return RangeValidator
		 */
		static public function RangeValidator($min, $max, $errorMessage = '')
		{
			return new \System\UI\Validators\RangeValidator($min, $max, $errorMessage);
		}

		/**
		 * creates a new RequiredValidator
		 *
		 * @param string $errorMessage
		 * @return RequiredValidator
		 */
		static public function RequiredValidator($errorMessage = '')
		{
			return new \System\UI\Validators\RequiredValidator($errorMessage);
		}
	}
?>