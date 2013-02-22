<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace ColorPicker;
	use \System\Web\WebControls\TextBox;


	/**
     * handles color picker control element creation
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 * 
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		ColorPicker
	 */
	class ColorPicker extends TextBox
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
			if(!file_exists(__HTDOCS_PATH__ . '/assets/colorpicker'))
			{
				\System\Utils\FileSystem::copy(__DIR__ . '/assets', __HTDOCS_PATH__ . '/assets/colorpicker');
			}

			// include external resources
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Base\ApplicationBase::getInstance()->config->assets . '/colorpicker/colorpicker.css' );
			$this->getParentByType( '\System\Web\WebControls\Page' )->addScript( \System\Base\ApplicationBase::getInstance()->config->assets . '/colorpicker/colorpicker.js' );

			$this->addValidator(new ColorValidator());
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
			$text = parent::getDomObject();
			$text->appendAttribute( 'onclick', 'document.getElementById(\'' . 'c_' . $this->getHTMLControlIdString() . '\').style.display=\'block\'');
			$text->appendAttribute( 'class', ' colorpicker' );

			$colorpicker = new \System\XML\DomObject( 'div' );
			$colorpicker->setAttribute( 'id', 'c_' . $this->getHTMLControlIdString() );
			$colorpicker->setAttribute( 'class', 'colorpicker_colorarea' );

			$callback     = 'ColorPicker.getColor';
			$enableCustom = false;
			$elementId    = $this->getHTMLControlIdString();
			$defaultColor = $this->value?$this->value:'#FFFFFF';
			$style        = 'display: block; margin: 3px; width: 12px; height: 12px; text-align: center; font-size: 1px; cursor: pointer;';

			$table = '
<table>
<tbody>
<tr>
<td colspan="8" style="font-size: 10px;" onmousedown="' . $callback . '()"><div style="'.$style.'float: left; background-color: ' . $defaultColor . '" id="' . $elementId . '_selected" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"></div>Automatic</td>
</tr>
<tr>
<td><div style="'.$style.'background-color:  #000000" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #993300" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #333300" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #003300" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #003366" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #000080" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #333399" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #333333" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
</tr>
<tr> 
<td><div style="'.$style.'background-color:  #800000" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #ff6600" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #808000" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #808080" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #008080" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #0000FF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #666699" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #808080" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
</tr>
<tr> 
<td><div style="'.$style.'background-color:  #ff0000" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #ff9900" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #99CC00" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #339966" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #33CCCC" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #3366FF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #808000" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #999999" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
</tr>
<tr> 
<td><div style="'.$style.'background-color:  #FF00FF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #FFCC00" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #FFFF00" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #00FF00" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #00FFFF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #00CCFF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #993366" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #C0C0C0" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
</tr>
<tr> 
<td><div style="'.$style.'background-color:  #FF99CC" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #FFCC99" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #FFFF99" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #CCFFCC" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #CCFFFF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #99CCFF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #CC99FF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
<td><div style="'.$style.'background-color:  #FFFFFF" onmouseover="document.getElementById(\'' . $elementId . '_selected\').style.backgroundColor=this.style.backgroundColor;" onmousedown="' . $callback . '(this.style.backgroundColor,\'' . $elementId . '\')"  ></div></td>
</tr>
</tbody>
</table>';

			// if custom
			/*
			$table .= '
<tr> 
<td colspan="8" style="text-align: center;"><input type="button" value="More Colors..." onmousedown="' . $callback . '(prompt(\'Enter a hexidecimal color value\', \'#000000\', \'Text Color\')); selDown(this)" onmouseover="selOn(this)" onmouseout="selOff(this)" onmouseup="selUp(this)" /></td>
</tr>';
			*/

			$colorpicker->innerHtml .= str_replace( "\n", '', str_replace( "\r", '', $table ));

			$dom = new \System\XML\DomObject( 'div' );
			$dom->setAttribute( 'style', 'position:relative;display:inline;' );
			$dom->addChild( $colorpicker );
			$dom->addChild( $text );

			return $dom;
		}
	}
?>