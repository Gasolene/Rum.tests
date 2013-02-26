<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
    namespace imagecropper;
	use \System\Web\WebControls\FileBrowser;

	/**
     * handles element creation and event handling
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 *
	 * @property int $width width of cropped image
	 * @property int $height height of cropped image
	 * @property int $previewWidth width of preview image
	 * @property int $previewHeight height of preview image
	 * @property string $tmpPath path to temp folder
	 * @property string $tmpURI public URI to temp folder
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		CommonControls
	 */
	class ImageCropper extends FileBrowser
	{
		/**
		 * width of cropped image
		 * @var int
		 */
		protected $width			= 400;

		/**
		 * height of cropped image
		 * @var int
		 */
		protected $height			= 200;

		/**
		 * width of preview image
		 * @var int
		 */
		protected $previewWidth		= 200;

		/**
		 * height of preview image
		 * @var int
		 */
		protected $previewHeight	= 100;

		/**
		 * path to temp folder
		 * @var string
		 */
		protected $tmpPath			= '';

		/**
		 * public uri to temp folder
		 * @var string
		 */
		protected $tmpURI			= '';

		/**
		 * x offset
		 * @var int
		 */
		protected $xoffset			= 0;

		/**
		 * y offset
		 * @var int
		 */
		protected $yoffset			= 0;


		/**
		 * Constructor
		 *
		 * @return void
		 * @access public
		 * @abstract
		 */
		public function __construct( $controlId, $default = null )
		{
			parent::__construct( $controlId, $default );

			$this->tmpPath = $this->tmpPath?$this->tmpPath:__HTDOCS_PATH__.'/resources/~tmp';
			$this->tmpURI = $this->tmpURI?$this->tmpURI:__APP_URI__.'/resources/~tmp';
		}


		/**
		 * returns an object property
		 *
		 * @param  string	$field		name of the field
		 *
		 * @return mixed
		 * @access public
		 * @ignore
		 */
		public function __get($field)
		{
			if(in_array($field,array_keys(get_object_vars($this))))
			{
				return $this->{$field};
			}
			else
			{
				return parent::__get($field);
			}
		}


		/**
		 * sets an object property
		 *
		 * @param  string	$field		name of the field
		 * @param  mixed	$value		value of the field
		 *
		 * @return void
		 * @access public
		 * @ignore
		 */
		public function __set($field,$value)
		{
			if($field=='width')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='height')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='previewWidth')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='previewHeight')
			{
				$this->{$field} = (int)$value;
			}
			elseif($field=='tmpPath')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='tmpURI')
			{
				$this->{$field} = (string)$value;
			}
			else
			{
				return parent::__set($field, $value);
			}
		}


		/**
		 * called when control is loaded
		 *
		 * @return void
		 */
		protected function onLoad()
		{
			parent::onLoad();

			$page = $this->getParentByType('\System\Web\WebControls\Page');
			$page->addScript( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'imagecropper', 'type'=>'text/css')) . '&asset=facebookcropper.css' );
			$page->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'imagecropper', 'type'=>'text/javascript')) . '&asset=imagecropper-1.2-core-yc.js' );
			$page->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'imagecropper', 'type'=>'text/javascript')) . '&asset=imagecropper-1.2-more-drag.js' );
			$page->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'imagecropper', 'type'=>'text/javascript')) . '&asset=imagecropper.js' );
			$page->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'imagecropper', 'type'=>'text/javascript')) . '&asset=facebookcropper.js' );

			$this->addValidator(new \System\Validators\FileTypeValidator(array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')));
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 */
		protected function onRequest( array &$request )
		{
			parent::onRequest( $request );

			if( !$this->disabled )
			{
				if(\class_exists('\GD\GDImage'))
				{
					$tmp_path = $this->tmpPath . '/'.\session_id().'_'.$this->controlId.'_orig';
					$tmp_uri = $this->tmpURI . '/'.\session_id().'_'.$this->controlId.'_orig';
					$save_path = $this->getSavePath();

					if(isset($request[$this->getHTMLControlIdString()."__uploaded"]) && $request[$this->getHTMLControlIdString()."__uploaded"] == 1)
					{
						$info = $this->getFileInfo();
						$this->xoffset = (-$request[$this->getHTMLControlIdString()."__xoffset"]);
						$this->yoffset = (-$request[$this->getHTMLControlIdString()."__yoffset"]);
						if($this->xoffset<0)$this->xoffset=0;
						if($this->yoffset<0)$this->yoffset=0;

						// crop image
						$img = new \GD\GDImage(\file_get_contents($tmp_path));
						$img->resize($this->width, $this->height, \GD\GDImageResizeMode::scaleToCrop());
						$img->crop($this->xoffset, $this->yoffset, $this->previewWidth, $this->previewHeight);
						$img->output($save_path, $info["type"]);
						\unlink($tmp_path);

						$this->submitted = true;
						$this->value = $save_path;

						unset($request[$this->getHTMLControlIdString()."__xoffset"]);
						unset($request[$this->getHTMLControlIdString()."__yoffset"]);
						unset($request[$this->getHTMLControlIdString()."__uploaded"]);
						unset($request[$this->getHTMLControlIdString()."__post"]);
					}
					else
					{
						if( isset( $_FILES[$this->getHTMLControlIdString()] ) && isset($request[$this->getHTMLControlIdString()."__post"]) && $request[$this->getHTMLControlIdString()."__post"] == 1)
						{
							$info = $this->getFileInfo();
							$img = new \GD\GDImage(\file_get_contents($info["tmp_name"]));
							$img->resize($this->width, $this->height, \GD\GDImageResizeMode::scaleToCrop());

							// store image
							\copy($info["tmp_name"], $tmp_path);

							\System\Web\HttpResponse::write("document.getElementById('{$this->getHTMLControlIdString()}__loading').style.display='none';");
							\System\Web\HttpResponse::write("document.getElementById('{$this->getHTMLControlIdString()}__facebook-cropper-container').style.display='block';");
							\System\Web\HttpResponse::write("document.getElementById('{$this->getHTMLControlIdString()}__preview').src='{$tmp_uri}?t=".time()."';");
							\System\Web\HttpResponse::write("document.getElementById('{$this->getHTMLControlIdString()}__post').value='0';");
							\System\Web\HttpResponse::write("document.getElementById('{$this->getHTMLControlIdString()}__uploaded').value='1';");

							// auto size
							\System\Web\HttpResponse::write("document.getElementById('{$this->getHTMLControlIdString()}__preview').style.width='".$img->getWidth()."px';");
							\System\Web\HttpResponse::write("document.getElementById('{$this->getHTMLControlIdString()}__preview').style.height='".$img->getHeight()."px';");

							\System\Web\HttpResponse::end();
						}
					}
				}
				else
				{
					throw new \System\Base\InvalidOperationException("The FacebookCropper control requires the GD plugin");
				}
			}
		}


		/**
		 * Event called when control is ready for rendering
		 *
		 * @return void
		 */
		protected function onPreRender()
		{
			parent::onPreRender();

			$path = $this->getSavePath();

			if(\file_exists($path))
			{
				\unlink($path);
			}
		}


		/**
		 * returns a DomObject representing control
		 *
		 * @return DomObject
		 */
		public function getDomObject()
		{
			$span = new \System\XML\DomObject('span');
			$cropHolder = new \System\XML\DomObject('div');
			$cropHolder->innerHtml = '
<div id="'.$this->getHTMLControlIdString().'__facebook-cropper" class="facebook-cropper" style="width:'.$this->previewWidth.'px;height:'.$this->previewHeight.'px;display:none;">
	<img id="'.$this->getHTMLControlIdString().'__loading" src="'.\System\Base\ApplicationBase::getInstance()->config->host . \System\Base\ApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'commoncontrols', 'type'=>'image/gif')).'&asset=imagecropper/spinner.gif" style="display:none;margin:0px auto;margin-top:'.($this->previewHeight/2-8).'px;" />
	<div id="'.$this->getHTMLControlIdString().'__facebook-cropper-container" class="facebook-cropper-container" style="width:'.$this->previewWidth.'px;height:'.$this->previewHeight.'px;">
		<img id="'.$this->getHTMLControlIdString().'__preview" src="" />
	</div>
</div>
<input name="'.$this->getHTMLControlIdString().'__yoffset" id="'.$this->getHTMLControlIdString().'__yoffset" type="hidden" value="1" />
<input name="'.$this->getHTMLControlIdString().'__xoffset" id="'.$this->getHTMLControlIdString().'__xoffset" type="hidden" value="1" />
<input name="'.$this->getHTMLControlIdString().'__uploaded" id="'.$this->getHTMLControlIdString().'__uploaded" type="hidden" value="0" />
<input name="'.$this->getHTMLControlIdString().'__post" id="'.$this->getHTMLControlIdString().'__post" type="hidden" value="0" />

<script type="text/javascript">
//<![CDATA[
window.addEvent(\'load\', function(){ // important do not use domReady since we want the image to be loaded
	document.ondragstart = function () { return false; }; //IE drag hack
	imagecropper.FacebookCropper.cropImage(\''.$this->getHTMLControlIdString().'__preview\', \''.$this->getHTMLControlIdString().'__facebook-cropper-container\', \''.$this->getHTMLControlIdString().'__facebook-cropper\', \''.$this->getHTMLControlIdString().'__yoffset\', \''.$this->getHTMLControlIdString().'__xoffset\', '.$this->height/$this->width.', \''.$this->width.'px\', \''.$this->height.'px\');
	});
//]]>
</script>';

			$input = parent::getDomObject();
			$input->appendAttribute( 'onchange', "
				document.getElementById('{$this->getHTMLControlIdString()}__facebook-cropper').style.display='block';
				document.getElementById('{$this->getHTMLControlIdString()}__loading').style.display='block';
				document.getElementById('{$this->getHTMLControlIdString()}__facebook-cropper-container').style.display='none';
				document.getElementById('{$this->getHTMLControlIdString()}__uploaded').value='0';
				document.getElementById('{$this->getHTMLControlIdString()}__post').value='1';
				imagecropper.FacebookCropper.uploadImage(document.getElementById('{$this->getParentByType('\System\Web\WebControls\Form')->getHTMLControlIdString()}'), imagecropper.FacebookCropper.handleResponse);");

			$span->addChild($cropHolder);
			$span->addChild($input);
			return $span;
		}


		/**
		 * return cropped file data
		 *
		 * @return string				raw file data
		 */
		public function getCroppedRawData()
		{
			$path = $this->getSavePath();
			$size = \filesize( $path );

			if( file_exists( $path ) && $size > 0 )
			{
				$fp = fopen( $path, 'rb' );
				if( $fp )
				{
					$data = fread( $fp, filesize( $path ));
					fclose( $fp );
					return $data;
				}
				else
				{
					throw new \System\Base\InvalidOperationException("could not open file for reading");
				}
			}
			else
			{
				return '';
			}
		}


		/**
		 * get save path
		 * @return string save path
		 */
		private function getSavePath()
		{
			return $this->tmpPath . '/'.\session_id().'_'.$this->controlId.'_cropped';
		}
	}
?>