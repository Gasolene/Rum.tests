<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace JQuery;
	use System\Base\EventHandlerBase;


	/**
	 * Provides event handling
	 *
	 * @package			PHPRum
	 * @subpackage		Base
	 * @author			Darnell Shinbine
	 */
	final class SortableSortEventHandler extends EventHandlerBase
	{
		/**
		 * Constructor
		 *
		 * @param  string $callback call back
		 * @return void
		 */
		public function __construct($callback)
		{
			parent::__construct("onSortableSort", $callback);
		}
	}
?>