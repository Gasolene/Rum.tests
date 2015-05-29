<?php
	/**
	 * Backwards compatability
	 *
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @ignore
	 */
	namespace System;
	abstract class AppServlet extends Base\ApplicationBase {}
	abstract class HTTPAppServlet extends Web\WebApplicationBase {}

	namespace System\ActiveRecords;
	abstract class ActiveRecordBase extends \System\ActiveRecord\ActiveRecordBase {}

	namespace System\Controllers;
	abstract class ControllerBase extends \System\Web\ControllerBase {}
	abstract class PageControllerBase extends \System\Web\PageControllerBase {}

	namespace System\Data;
	abstract class DataAdapter extends \System\DB\DataAdapter {}

	namespace System\Testcase;
	abstract class UnitTestCaseBase extends \System\Test\UnitTestCaseBase {}
	abstract class ControllerTestCaseBase extends \System\Test\ControllerTestCaseBase {}

	namespace System\IO;
	class Cache extends \System\Caching\FileCache {}
	class HTTPRequest extends \System\Web\HTTPRequest {}
	class HTTPResponse extends \System\Web\HTTPResponse {}
	class File extends \System\Utils\File {}
	class Folder extends \System\Utils\Folder {}
	class FileSystem extends \System\Utils\FileSystem {}

	namespace System\IO\Stream;
	class SocketStream extends \System\Utils\SocketStream {}
	class FileStream extends \System\Utils\FileStream {}

	namespace System\UI\Validators;
	class DateTimeValidator extends \System\Validators\DateTimeValidator {}
	class FileSizeValidator extends \System\Validators\FileSizeValidator {}
	class FileTypeValidator extends \System\Validators\FileTypeValidator {}
	class IntegerValidator extends \System\Validators\IntegerValidator {}
	class LengthValidator extends \System\Validators\LengthValidator {}
	class MatchValidator extends \System\Validators\PatternValidator {}
	class NumericValidator extends \System\Validators\NumericValidator {}
	class PatternValidator extends \System\Validators\PatternValidator {}
	class RangeValidator extends \System\Validators\RangeValidator {}
	class RequiredValidator extends \System\Validators\RequiredValidator {}

	namespace System\UI\WebControls;
	class Button extends \System\Web\WebControls\Button {}
	class CheckBox extends \System\Web\WebControls\CheckBox {}
	class CheckBoxList extends \System\Web\WebControls\CheckBoxList {}
	class ComboBox extends \System\Web\WebControls\DropDownList {}
	class DateSelector extends \System\Web\WebControls\Date {}
	class DateTimeSelector extends \System\Web\WebControls\DateTime {}
	class DropDownList extends \System\Web\WebControls\DropDownList {}
	class FileBrowser extends \System\Web\WebControls\File {}
	class Form extends \System\Web\WebControls\Form {}
	class GridView extends \System\Web\WebControls\GridView {}
	class GridViewButton extends \System\Web\WebControls\GridViewButton {}
	class GridViewCheckBox extends \System\Web\WebControls\GridViewCheckBox {}
	class GridViewColumn extends \System\Web\WebControls\GridViewColumn {}
	class GridViewDropDownMenu extends \System\Web\WebControls\GridViewDropDownList {}
	class GridViewImage extends \System\Web\WebControls\GridViewImage {}
	class GridViewLink extends \System\Web\WebControls\GridViewLink {}
	class GridViewTextBox extends \System\Web\WebControls\GridViewText {}
	class ListBox extends \System\Web\WebControls\ListBox {}
	class LoginForm extends \System\Web\WebControls\LoginForm {}
	class MasterView extends \System\Web\WebControls\MasterView {}
	class Page extends \System\Web\WebControls\Page {}
	class RadioButton extends \System\Web\WebControls\RadioButton {}
	class RadioButtonList extends \System\Web\WebControls\RadioButtonList {}
	class ReportView extends \System\Web\WebControls\ReportView {}
	class TextBox extends \System\Web\WebControls\Text {}
	class TextArea extends \System\Web\WebControls\TextArea {}
	class TimeSelector extends \System\Web\WebControls\Time {}
	class TreeNode extends \System\Web\WebControls\TreeNode {}
	class TreeView extends \System\Web\WebControls\TreeView {}
	class View extends \System\Web\WebControls\View {}
	class WebForm extends \System\Web\WebControls\Form {}


	/**
	 * Deprecated classes
	 * @ignore
	 */
	class FormView extends Form
	{
		protected function onInit()
		{
			$this->add( new Button( 'save', 'Save' ));
		}
		protected function onDataBind()
		{
			foreach( $this->dataSource->fields as $field )
			{
				if( $field->autoIncrement )
				{
					continue;
				}
				elseif( $field->boolean )
				{
					$this->add( new CheckBox( $field->name ));
				}
				elseif( $field->blob )
				{
					$this->add( new TextArea( $field->name ));
				}
				else
				{
					$this->add( new TextBox( $field->name ));
				}

				$this->getControl( $field->name )->label = ucwords( str_replace( '_', ' ', $field->name ));
			}

			parent::onDataBind();
		}
		protected function onRequest( array &$request )
		{
			if( $this->getControl('save')->submitted )
			{
				$this->save();
			}
		}
		public function getDomObject()
		{
			$dom = parent::getDomObject();
			$dom->appendAttribute( 'class', ' formview' );
			return $dom;
		}
	}
	final class RenderMode
	{
		private $flags;
		private function __construct($flags)
		{
			$this->flags = (int)$flags;
		}
		public static function HTML() {return new RenderMode(1);}
		public static function DHTML() {return new RenderMode(2);}
		public static function AJAX() {return new RenderMode(4);}
	}
?>