<?php

    namespace MyApp;

	class MyForm extends \System\Web\FormModelBase
	{
		protected $fields = array(
			'name'=>'string',
			'phone'=>'string',
			'active'=>'boolean'
		);

		protected $rules = array(
			'name'=>array('required','length(1,10)'),
			'phone'=>'required'
		);

		public function save()
		{
			dmp($this["name"]);
		}

		static public function create()
		{
			return new MyForm();
		}
	}
?>