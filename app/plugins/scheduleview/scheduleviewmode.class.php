<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace ScheduleView;


	/**
	 * Represents a Schedule View Mode
	 *
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		ScheduleView
	 */
	final class ScheduleViewMode
	{
		private $flags;

		private function  __construct($flags)
		{
			$this->flags = (int)$flags;
		}

		/**
		 * specifies the view mode daily
		 * @var ScheduleViewMode
		 */
		public static function daily() {return new ScheduleViewMode(1);}

		/**
		 * specifies the view mode weekly
		 * @var ScheduleViewMode
		 */
		public static function weekly() {return new ScheduleViewMode(2);}

		/**
		 * specifies the view mode monthly
		 * @var ScheduleViewMode
		 */
		public static function monthly() {return new ScheduleViewMode(4);}

		/**
		 * specifies the view mode agenda
		 * @var ScheduleViewMode
		 */
		public static function agenda() {return new ScheduleViewMode(8);}
	}
?>