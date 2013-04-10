<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace JQuery;
	use System\Base\EventBase;


	/**
	 * Provides event handling
	 *
	 * @package			PHPRum
	 * @subpackage		Base
	 * @author			Darnell Shinbine
	 */
	final class SortableSortEvent extends EventBase
	{
		/**
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct("onSortableSort");
		}
	}
?>