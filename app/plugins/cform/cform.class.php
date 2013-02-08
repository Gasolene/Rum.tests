<?php
	/**
	 * @package PublicAnnouncements\Models
	 */
	namespace CForm;

	/**
	 * This class represents a form.
	 *
	 * The FormModelBase exposes 2 protected properties
	 * @property array $fields Contains an associative array of field names mapped to field types
	 * @property array $rules Contains an associative array of field names mapped to rules
	 * 
	 * @property string $successMsg Specifies the success message
	 * 
	 * @package			PublicAnnouncements\Models
	 */
	class CForm extends \System\Web\FormModelBase
	{
		/**
		 * success message
		 * @var string
		 */
		protected $successMsg = "Your message has been sent";

		protected $fields = array(
			'name'=>'string',
			'email'=>'email',
			'subject'=>'string',
			'body'=>'blob'
		);

		protected $rules = array(
			'name'=>array('required', 'length(0, 255)'),
			'email'=>array('required', 'length(0, 255)'),
			'subject'=>array('required', 'length(0, 255)'),
			'body'=>array('required')
		);

		/**
		 * recipient
		 * @var string
		 */
		protected $recipient;


		/**
		 * Constructor
		 *
		 * @param	string $recipient recipient
		 *
		 * @return  void
		 */
		protected function __construct($recipient = '')
		{
			parent::__construct();

			$this->recipient = $recipient;
		}


		/**
		 * returns an object property
		 *
		 * @param  string	$field		name of the field
		 * @return mixed
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field == 'successMsg' )
			{
				return $this->successMsg;
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
		 * @return bool					true on success
		 * @ignore
		 */
		function __set( $field, $value )
		{
			if( $field == 'successMsg' )
			{
				$this->successMsg = (string)$value;
			}
			else
			{
				parent::__set($field, $value);
			}
		}


		/**
		 * create new instance
		 * 
		 * @param	string $recipient recipient
		 *
		 * @return void
		 */
		static public function create($recipient = '')
		{
			return new CForm($recipient);
		}


		/**
		 * static method to return a Form object
		 *
		 * @param  string		$controlId		form id
		 * @return Form
		 */
		static public function form( $controlId )
		{
			$form = parent::form( $controlId );
			$form->fieldset->legend = 'Contact us';
			$form->submit->text = 'Send';

			return $form;
		}


		/**
		 * save model state
		 *
		 * @return void
		 */
		public function save()
		{
			$message = new \System\Comm\Mail\MailMessage();
			$message->to = $this->recipient;
			$message->from = $this["email"];
			$message->subject = $this["subject"];
			$message->body = $this["body"];

			\System\Base\ApplicationBase::getInstance()->mailClient->send($message);
			\Rum::flash("i:{$this->successMsg}");
		}
	}
?>