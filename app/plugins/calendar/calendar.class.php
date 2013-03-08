<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace Calendar;
	use \System\Web\WebControls\WebControlBase;


	/**
     * handles calendar control element creation
	 * abstracts away the presentation logic and data access layer
     * the server-side control for WebWidgets
	 * 
     * @property int $month specifies the month
     * @property int $year specifies the year
     * @property bool $showWeekdays specifies whether to show weekdays
     * @property bool $showMonths specifies whether to show month
     * @property bool $shortWeekdays specifies whether to use short weekday names
     * @property bool $shortMonths specifies whether to use short month names
     * @property bool $showEventName specifies whether to show event name
	 * @property string $onDayClick specifies URL to redirect to when on a day click event, %date% will be replaced with the date
     * @property string $textField text field
     * @property string $dateField date field
     * @property EventCollection $items collection of calendar events
     *
	 * @author			Darnell Shinbine
	 * @copyright		copyright (c) 2010
	 * @version			2.0.0
	 * @package			PHPRum
	 * @subpackage		Calendar
	 */
	class Calendar extends WebControlBase
	{
		/**
		 * numerical value representing month
		 * @access protected
		 */
		protected $month				= null;

		/**
		 * numerical value representing year
		 * @access protected
		 */
		protected $year					= null;

		/**
		 * show weekdays in calendar, Default is true
		 * @access protected
		 */
		protected $showWeekdays			= true;

		/**
		 * show month in calendar, Default is true
		 * @access protected
		 */
		protected $showMonth			= true;

		/**
		 * display weekdays by letter, Default is true
		 * @access protected
		 */
		protected $shortWeekdays		= true;

		/**
		 * display short form of month, Default is true
		 * @access protected
		 */
		protected $shortMonths			= true;

		/**
		 * show event names in calendar, Default is true
		 * @access protected
		 */
		protected $showEventName		= true;

		/**
		 * onDayClick event
		 * @var string
		 */
		protected $onDayClick           = '';

		/**
		 * name of display field in datasource (event)
		 * @access protected
		 */
		protected $textField			= '';

		/**
		 * name of value field in datasource (date)
		 * @access protected
		 */
		protected $dateField			= '';

		/**
		 * collection of calendar events
		 * @access protected
		 */
		protected $items				= null;


		/**
		 * Constructor
		 *
		 * @return void
		 * @access public
		 */
		public function __construct( $controlId, $month = null, $year = null )
		{
			parent::__construct( $controlId );

			$this->items = new EventCollection();
			$this->month  = date("m");
			$this->year   = date("Y");

			if( isset( $month )) {
				if( is_numeric( $this->month ) && (int) $this->month > 0 && (int) $this->month < 13 ) {
					$this->month = (int) $month;
				}
			}
			if( isset( $year )) {
				if( is_numeric( $this->year )) {
					$this->year = (int) $year;
				}
			}
		}


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @access protected
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field == 'month' ) {
				return $this->month;
			}
			elseif( $field == 'year' ) {
				return $this->year;
			}
			elseif( $field == 'showWeekdays' ) {
				return $this->showWeekdays;
			}
			elseif( $field == 'showMonths' ) {
				return $this->showMonths;
			}
			elseif( $field == 'shortWeekdays' ) {
				return $this->shortWeekdays;
			}
			elseif( $field == 'shortMonths' ) {
				return $this->shortMonths;
			}
			elseif( $field == 'showEventName' ) {
				return $this->showEventName;
			}
			elseif( $field == 'onDayClick' ) {
				return $this->onDayClick;
			}
			elseif( $field == 'textField' ) {
				return $this->textField;
			}
			elseif( $field == 'dateField' ) {
				return $this->dateField;
			}
			elseif( $field == 'items' ) {
				return $this->items;
			}
			else {
				return parent::__get( $field );
			}
		}


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return mixed
		 * @access protected
		 * @ignore
		 */
		public function __set( $field, $value ) {
			if( $field == 'month' ) {
				if( (int)$value > 0 && (int)$value < 13 ) {
					$this->month = (int)$value;
				}
				else {
					throw new \Exception("Calendar::month must be and integer value in range 1 to 12");
				}
			}
			elseif( $field == 'year' ) {
				$this->year = (int)$value;
			}
			elseif( $field == 'showWeekdays' ) {
				$this->showWeekdays = (bool)$value;
			}
			elseif( $field == 'showMonths' ) {
				$this->showMonths = (bool)$value;
			}
			elseif( $field == 'shortWeekdays' ) {
				$this->shortWeekdays = (bool)$value;
			}
			elseif( $field == 'shortMonths' ) {
				$this->shortMonths = (bool)$value;
			}
			elseif( $field == 'showEventName' ) {
				$this->showEventName = (bool)$value;
			}
			elseif( $field == 'onDayClick' ) {
				$this->onDayClick = (string)$value;
			}
			elseif( $field == 'textField' ) {
				$this->textField = (string)$value;
			}
			elseif( $field == 'dateField' ) {
				$this->dateField = (string)$value;
			}
			else {
				parent::__set( $field, $value );
			}
		}


		/**
		 * write view state to session
		 *
		 * @param  object	$session	session array
		 * @return void
		 * @access protected
		 */
		protected function onSaveViewState( array &$session ) {
			parent::onSaveViewState( $session );

			if( $this->enableViewState  ) {
				$session['month'] = $this->month;
				$session['year']  = $this->year;
			}
		}


		/**
		 * read view state from session
		 *
		 * @param  object	$session	session array
		 * @return void
		 * @access protected
		 */
		protected function onLoadViewState( array &$session ) {
			parent::onLoadViewState( $session );

			if( $this->enableViewState ) {
				if( array_key_exists( 'month', $session ) &&
					array_key_exists( 'year', $session )) {

					$this->month = $session['month'];
					$this->year  = $session['year'];
				}
			}
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$httpRequest ) {

			if( isset( $httpRequest[$this->getHTMLControlId().'__month'] )) {
				$this->month = $httpRequest[$this->getHTMLControlId().'__month'];
				unset( $httpRequest[$this->getHTMLControlId().'__month'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlId().'__year'] )) {
				$this->year = $httpRequest[$this->getHTMLControlId().'__year'];
				unset( $httpRequest[$this->getHTMLControlId().'__year'] );
			}
		}


		/**
		 * called when control is loaded
		 * 
		 * @return bool			true if successfull
		 * @access protected
		 */
		protected function onLoad() {
			parent::onLoad();

			// include external resources
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Web\WebApplicationBase::getInstance()->getPageURI(__MODULE_REQUEST_PARAMETER__, array('id'=>'calendar', 'type'=>'text/css')) . '&asset=calendar.css' );
		}


		/**
		 * bind control to data
		 *
		 * @param  $default			value
		 * @return void
		 * @access protected
		 */
		protected function onDataBind()
		{
			if( $this->dateField && $this->textField )
			{
				while( !$this->dataSource->eof() )
				{
					$this->addEvent( $this->dataSource[$this->dateField], $this->dataSource->row[$this->textField] );
					$this->dataSource->next();
				}
			}
			else
			{
				throw new \Exception( 'Calendar::dataBind() called with no dateField or textField set' );
			}
		}


		/**
		 * returns formatted string with selected date
		 *
		 * @param  string		$event		name/title of event (html allowed)
		 * @param  string		$date		date string (mm/dd/yy)
		 * @return bool						true if successfull
		 * @access public
		 */
		public function addEvent( $date, $event )
		{
			$timestamp = strtotime( (string)$date );
			if( $timestamp !== false )
			{
				$newevent = '';
				if( $this->items->contains( date( 'Y-m-d', $timestamp )))
				{
					$newevent .= $this->items[date( 'Y-m-d', $timestamp )];
				}
				$newevent .= (string)$event . '<br />';
				$this->items[date('Y-m-d', $timestamp)] = (string)$newevent;
			}
			else
			{
				throw new \Exception("Argument 1 passed to ".get_class($this)."::addEvent() must be a valid datetime string");
			}
		}


		/**
		 * return widget object
		 *
		 * @return none
		 * @access public
		 */
		public function getDomObject()
		{
			/* variable for day count */
			$dayofmonth = 1;

			/* create getDate object and return textual month */
			$thismonth = getdate( strtotime( $this->month."/1/".$this->year ));

			/* create array of weekdays */
			$weekday = array( "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" );

			/* create widget */
			$calendar = $this->createDomObject( 'table' );
			$thead    = new \System\XML\DomObject( 'thead' );
			$tbody    = new \System\XML\DomObject( 'tbody' );
			$tfoot    = new \System\XML\DomObject( 'tfoot' );

			/* attributes */
			$calendar->setAttribute( 'id',          $this->getHTMLControlId() );
			$calendar->appendAttribute( 'class',    ' calendar' );

			/* month */
			if( $this->showMonth )
			{
				$month = new \System\XML\DomObject( 'tr' );
				$td    = new \System\XML\DomObject( 'th' );
				$prev  = new \System\XML\DomObject( 'a' );
				$next  = new \System\XML\DomObject( 'a' );
				$title = new \System\XML\DomObject( 'span' );

				$month->setAttribute( 'class', 'month' );
				$td->setAttribute( 'colspan',  '7' );

				$prev->setAttribute( 'href', $this->_getPrevMonth() );
				$prev->setAttribute( 'class', 'prev' );
				$prev->nodeValue .= '<< prev';

				$next->setAttribute( 'href', $this->_getNextMonth() );
				$next->setAttribute( 'class', 'next' );
				$next->nodeValue .= 'next >>';

				if( $this->shortMonths ) {
					$title->nodeValue .= substr( $thismonth['month'], 0, 3 ) . ', ' . $this->year;
				}
				else {
					$title->nodeValue .= $thismonth['month'] . ", " . $this->year;
				}

				$td->addChild( $prev );
				$td->addChild( $title );
				$td->addChild( $next );

				$month->addChild( $td );

				// add node to calendar
				$thead->addChild( $month );
				$calendar->addChild( $thead );
			}

			/* weekdays */
			if( $this->showWeekdays )
			{
				$weekdays = new \System\XML\DomObject( 'tr' );
				$weekdays->setAttribute( 'class', 'weekdays' );

				for( $iWeek = 0; $iWeek < 7; $iWeek++ )
				{
					$td = new \System\XML\DomObject( 'th' );

					if( $this->shortWeekdays ) {
						$td->nodeValue .= substr( $weekday[$iWeek], 0, 1 );
					}
					else {
						$td->nodeValue .= substr( $weekday[$iWeek], 0, 3 );
					}

					$weekdays->addChild( $td );
					unset( $td );
				}

				// add node to calendar
				$tbody->addChild( $weekdays );
			}

			$tr = new \System\XML\DomObject( 'tr' );

			/* add blank cells to offset start date */
			$strt_date = $this->_getStartDate();
			$month_len = $this->_getMonthLen();

			for( $iCount = 0; $iCount < $strt_date; $iCount++ )
			{
				unset( $td );
				$td = new \System\XML\DomObject( 'td' );
				$td->innerHtml = '&nbsp;';
				$tr->addChild( $td );
			}

			/* days */
			for( $iCell = $strt_date; $iCell < $month_len + $strt_date; $iCell++ )
			{
				unset( $td );
				$td = new \System\XML\DomObject( 'td' );

				/* add new row after 7 columns */
				if(( $iCell % 7 ) === 0 ) {
					$tbody->addChild( $tr );
					unset( $tr );
					$tr = new \System\XML\DomObject( 'tr' );
				}

				/* output day cell */
				$td = $this->_getDay( $dayofmonth );
				$tr->addChild( $td );

				/* increment day number */
				$dayofmonth++;
			}

			/* add blank cells to offset end date */
			for(; $iCell % 7; $iCell++ )
			{
				$td = new \System\XML\DomObject( 'td' );
				$td->innerHtml = '&nbsp;';
				$tr->addChild( $td );
			}

			$tbody->addChild( $tr );

			// add node to calendar
			$calendar->addChild( $tbody );

			/* return node */
			return $calendar;
		}


		/**
		 * returns first weekday of current 
		 *
		 * @return string		first weekday of current month
		 * @access private
		 */
		private function _getStartDate()
		{
			/* create date string that is the 1st of this month */
			$datestring = $this->month."/1/".$this->year;

			/* create geDateSelector object using date string */
			$thismonth = getdate( strtotime( $datestring ));

			/* return weekday of date string */
			return $thismonth['wday'];
		}


		/**
		 * returns number of days in febuary in current year
		 *
		 * @return int		number of days in febuary
		 * @access private
		 */
		private function _getFebuary()
		{
			if((( $this->year % 4 == 0 ) && ( $this->year % 100 != 0 )) || ( $this->year % 400 == 0 )) return 29;
			return 28;
		}


		/**
		 * returns number of days in current month
		 *
		 * @return int		no. of days in month
		 * @access private
		 */
		private function _getMonthLen()
		{
			$aMonthLenth = array( 31, $this->_getFebuary(), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 );
			return $aMonthLenth[(($this->month) - 1)];
		}


		/**
		 * returns formatted url string with the next month
		 *
		 * @return string				url
		 * @access private
		 */
		private function _getPrevMonth()
		{
			$mo = $this->month;
			$year = $this->year;

			if( $mo > 1 )
			{
				$mo--;
			}
			else
			{
				$mo = 12;

				if( $year == 2000 )
				{
					$year = 1999;
				}
				else if( $year <= 1999 )
				{
					$syear = substr( $year, 2, strlen( $year ));
					$syear--;
					$year = (int)('19' . $syear);
				}
				else
				{
					$year--;
				}
			}

			return $this->getQueryString( '?' . $this->getHTMLControlId().'__month=' . $mo . '&' . $this->getHTMLControlId().'__year=' . $year );
		}


		/**
		 * returns formatted url string with the previous month
		 *
		 * @return string				url
		 * @access private
		 */
		private function _getNextMonth()
		{
			$mo = $this->month;
			$year = $this->year;

			if( $mo < 12 )
			{
				$mo++;
			}
			else
			{
				$mo = 1;
				$year++;
			}

			return $this->getQueryString( '?' . $this->getHTMLControlId().'__month=' . $mo . '&' . $this->getHTMLControlId().'__year=' . $year );
		}


		/**
		 * returns formatted widget for current day
		 *
		 * @param  int		$dayofmonth		selected day of month
		 * @return string					html string
		 * @access private
		 */
		private function _getDay( $dayofmonth )
		{
			$td = new \System\XML\DomObject( 'td' );
			$td->setAttribute('class', 'day');

			if( $this->onDayClick )
			{
				$day = new \System\XML\DomObject('a');
				$day->setAttribute('href', str_replace( '%date%', (int)$this->year . '-' . (int)$this->month . '-' . (int)$dayofmonth, $this->onDayClick ));
				$day->nodeValue = $dayofmonth;
			}
			else
			{
				$day = new \System\XML\DomObject('span');
				$day->nodeValue = $dayofmonth;
			}

			// determine if current day is today
			if( $dayofmonth == date( "d" ) && $this->month == date( "m" ) && $this->year == date( "Y" ))
			{
				$td->appendAttribute('class', ' today');
			}

			if( $this->items->contains( date( 'Y-m-d', strtotime( $this->month . '/' . $dayofmonth . '/' . $this->year ))))
			{
				$td->appendAttribute('class', ' event');

				if( $this->showEventName )
				{
					$event = $this->items->item( date( 'Y-m-d', strtotime( $this->month . '/' . $dayofmonth . '/' . $this->year )));
					$day->innerHtml = $dayofmonth . ' <div class="details">' . $event . '</div>';
				}
			}

			$td->addChild($day);

			return $td;
		}
	}
?>