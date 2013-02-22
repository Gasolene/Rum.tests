<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace ScheduleView;
	use \System\Web\WebControls\WebControlBase;


	/**
	 * Weekly Schedule WebControl
	 *
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @package			PHPRum
	 * @subpackage		ScheduleView
	 */
	class ScheduleView extends WebControlBase {

		/**
		 * specifies the display mode
         * @var ScheduleViewMode
		 * @access public
		 */
		public $displayMode					= null;

		/**
		 * specifies whether to display all events instead of by date range
         * @var bool
		 * @access public
		 */
		public $displayAll					= false;

		/**
		 * specifies the start time for schedule display
         * @var string
		 * @access public
		 */
		public $startTime					= '07:00';

		/**
		 * specifies the start time for schedule display
         * @var string
		 * @access public
		 */
		public $endTime						= '18:00';

		/**
		 * specifies date range (defaults to today)
         * @var string
		 * @access public
		 */
		public $date						= '';

		/**
		 * specifies the table caption
         * @var string
		 * @access public
		 */
		public $caption						= '';

		/**
		 * Set to display column headers, Default is true
         * @var bool
		 * @access public
		 */
		public $showHeader					= true;

		/**
		 * Set to display table footer, Default is true
         * @var bool
		 * @access public
		 */
		public $showFooter					= true;

		/**
		 * specifies the javascript to execute url when a cell is clicked
		 *
		 * %date% is replaced by the selected time in format: Y-m-d
		 * %time% is replaced by the selected time in format: H:i:s
		 * %dayofmonth% is replaced by the selected day of the month
		 * %dayofweek% is replaced by the selected day of the week
		 * @access public
		 */
		public $onclick						= '';

		/**
		 * specifies the javascript to execute url when a cell is clicked
		 *
		 * %date% is replaced by the selected time in format: Y-m-d
		 * %time% is replaced by the selected time in format: H:i:s
		 * %dayofmonth% is replaced by the selected day of the month
		 * %dayofweek% is replaced by the selected day of the week
		 * @access public
		 */
		public $onmouseover					= '';

		/**
		 * specifies the javascript to execute url when a cell is clicked
		 *
		 * %date% is replaced by the selected time in format: Y-m-d
		 * %time% is replaced by the selected time in format: H:i:s
		 * %dayofmonth% is replaced by the selected day of the month
		 * %dayofweek% is replaced by the selected day of the week
		 * @access public
		 */
		public $onmouseout					= '';

		/* data fields */

		/**
		 * name of text field in datasource (event)
		 * @access public
		 */
		public $textField					= '';

		/**
		 * name of the starttime field in datasource (date)
		 * @access public
		 */
		public $starttimeField				= '';

		/**
		 * name of the endtime field in datasource (date)
		 * @access public
		 */
		public $endtimeField				= '';

		/**
		 * internal data pointer
		 * @access private
		 */
		private $_data						= null;


		/**
		 * Constructor
		 *
		 * @return void
		 * @access public
		 */
		public function __construct( $controlId ) {
			parent::__construct( $controlId );

			$this->displayMode = ScheduleViewMode::daily();
			$this->date = date('Y-m-d',time());
			$this->_data = \System\Data\DataAdapter::create('adapter=\Packages\ScheduleView\ScheduleViewDataAdapter;')->openDataSet('',\System\Data\DataSetType::OpenStatic());
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

			if( $this->enableViewState ) {
				$session['date']         = $this->date;
				$session['display_mode'] = $this->displayMode;
				$session['start_time']   = $this->startTime;
				$session['end_time']     = $this->endTime;
			}
		}


		/**
		 * read view state from session
		 *
		 * @param  array	$session	session data
		 * @return void
		 * @access protected
		 */
		protected function onLoadViewState( array &$session ) {
			parent::onLoadViewState( $session );

			if( $this->enableViewState ) {
				if( isset( $session['date'] ) &&
					isset( $session['display_mode'] ) &&
					isset( $session['start_time'] ) &&
					isset( $session['end_time'] )) {

					$this->date        = $session['date'];
					$this->displayMode = $session['display_mode'];
					$this->startTime   = $session['start_time'];
					$this->endTime     = $session['end_time'];
				}
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

			// Install assets
			if(!file_exists(__HTDOCS_PATH__ . '/assets/scheduleview'))
			{
				\System\Utils\FileSystem::copy(__DIR__ . '/assets', __HTDOCS_PATH__ . '/assets/scheduleview');
			}

			// include external resources
			$this->getParentByType( '\System\Web\WebControls\Page' )->addLink( \System\Base\ApplicationBase::getInstance()->config->assets . '/scheduleview/scheduleview.css' );
		}


		/**
		 * process the HTTP request array
		 *
		 * @return void
		 * @access public
		 */
		protected function onRequest( array &$httpRequest ) {

			// restore state if present
			if( isset( $httpRequest[$this->getHTMLControlIdString().'__date'] )) {
				$this->date = $httpRequest[$this->getHTMLControlIdString().'__date'];
				unset( $httpRequest[$this->getHTMLControlIdString().'__date'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__display_mode'] )) {
				$this->displayMode = (int) $httpRequest[$this->getHTMLControlIdString().'__display_mode'];
				unset( $httpRequest[$this->getHTMLControlIdString().'__display_mode'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__startTime'] )) {
				$this->startTime = $httpRequest[$this->getHTMLControlIdString().'__startTime'];
				unset( $httpRequest[$this->getHTMLControlIdString().'__startTime'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__endTime'] )) {
				$this->endTime = $httpRequest[$this->getHTMLControlIdString().'__endTime'];
				unset( $httpRequest[$this->getHTMLControlIdString().'__endTime'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__vday'] )) {
				$this->displayMode = ScheduleViewMode::daily();
				unset( $httpRequest[$this->getHTMLControlIdString().'__vday'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__vweek'] )) {
				$this->displayMode = ScheduleViewMode::weekly();
				unset( $httpRequest[$this->getHTMLControlIdString().'__vweek'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__vmonth'] )) {
				$this->displayMode = ScheduleViewMode::monthly();
				unset( $httpRequest[$this->getHTMLControlIdString().'__vmonth'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__vagenda'] )) {
				$this->displayMode = ScheduleViewMode::agenda();
				unset( $httpRequest[$this->getHTMLControlIdString().'__vagenda'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__prev'] )) {
				if( $this->displayMode == ScheduleViewMode::daily() ) {
					$this->date = date( 'Y-m-d', $this->_dateAdd( 'd', -1, strtotime( $this->date )));
				}
				elseif( $this->displayMode == ScheduleViewMode::weekly() ) {
					$this->date = date( 'Y-m-d', $this->_dateAdd( 'd', -7, strtotime( $this->date )));
				}
				elseif( $this->displayMode == ScheduleViewMode::monthly() ) {
					$this->date = date( 'Y-m-d', $this->_dateAdd( 'm', -1, strtotime( $this->date )));
				}
				elseif( $this->displayMode == ScheduleViewMode::agenda() ) {
					$this->date = date( 'Y-m-d', $this->_dateAdd( 'd', -7, strtotime( $this->date )));
				}
				unset( $httpRequest[$this->getHTMLControlIdString().'__prev'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__next'] )) {
				if( $this->displayMode == ScheduleViewMode::daily() ) {
					$this->date = date( 'Y-m-d', $this->_dateAdd( 'd', 1, strtotime( $this->date )));
				}
				elseif( $this->displayMode == ScheduleViewMode::weekly() ) {
					$this->date = date( 'Y-m-d', $this->_dateAdd( 'd', 7, strtotime( $this->date )));
				}
				elseif( $this->displayMode == ScheduleViewMode::monthly() ) {
					$this->date = date( 'Y-m-d', $this->_dateAdd( 'm', 1, strtotime( $this->date )));
				}
				elseif( $this->displayMode == ScheduleViewMode::agenda() ) {
					$this->date = date( 'Y-m-d', $this->_dateAdd( 'd', 7, strtotime( $this->date )));
				}
				unset( $httpRequest[$this->getHTMLControlIdString().'__next'] );
			}

			if( isset( $httpRequest[$this->getHTMLControlIdString().'__tday'] )) {
				$this->date = date( 'Y-m-d', time() );
				unset( $httpRequest[$this->getHTMLControlIdString().'__tday'] );
			}

			if( $this->endTime <= $this->startTime ) {
				$this->endTime = $this->startTime + 1;
			}
		}


		/**
		 * adds an event to the schedule
		 *
		 * @param  string		$starttime		datetime
		 * @param  string		$endtime		datetime
		 * @param  string		$display		text to display
		 *
		 * @return void
		 * @access public
		 */
		public function addEvent( $starttime, $endtime, $display = '' )
		{
			if( strtotime( $starttime ) &&
				strtotime( $endtime ) &&
				is_string( $display )) {

				$this->_data['starttime'] = $starttime;
				$this->_data['endtime']   = $endtime;
				$this->_data['display']   = $display;

				$this->_data->insert();
			}
			else
			{
				throw new \System\TypeMismatchException("type mis-match");
			}
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
			if( $this->dataSource )
			{
				if( isset($this->dataSource[$this->starttimeField]) && isset($this->dataSource[$this->endtimeField]) && isset($this->dataSource[$this->textField]) )
				{
					// populate item array with data from datasource
					while( !$this->dataSource->eof() )
					{
						$this->addEvent( $this->dataSource[$this->starttimeField], $this->dataSource->row[$this->endtimeField], ( $this->dataSource->row[$this->textField] ));
						$this->dataSource->next();
					}
					return true;
				}
				else
				{
					throw new \System\Base\InvalidOperationException( 'ScheduleView::dataBind() called with no starttimeField, endtimeField or textField set' );
				}
			}
			else
			{
				throw new \System\Base\InvalidOperationException( 'ScheduleView::dataBind() called with no dataSource' );
			}

			return false;
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
			$datestring = date( 'Y-m-01', strtotime( $this->date ));

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
			if((( (int) date( 'Y', strtotime( $this->date )) % 4 == 0 ) && ( (int) date( 'Y', strtotime( $this->date )) % 100 != 0 )) || ( (int) date( 'Y', strtotime( $this->date )) % 400 == 0 )) return 29;
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
			return $aMonthLenth[(((int) date( 'm', strtotime( $this->date ))) - 1)];
		}


		/**
		 * adds a shift to the schedule
		 *
		 * @param  string		$time			start time
		 * @param  string		$day			day of week
		 * @param  string		$duration		duration in hours
		 * @param  string		$display		text to display
		 * @param  string		$onclick		click URL
		 *
		 * @return void
		 * @access private
		 */
		private function _dateAdd( $_interval, $_number, $_date ) {
			$_date_time_array  = getdate($_date);

			$_hours =  $_date_time_array["hours"];
			$_minutes = $_date_time_array["minutes"];
			$_seconds = $_date_time_array["seconds"];
			$_month = $_date_time_array["mon"];
			$_day = $_date_time_array["mday"];
			$_year = $_date_time_array["year"];

			switch (strtolower($_interval))
			{
				case "y":
					$_year += $_number;
					break;
				case "q":
					$_year += ( $_number * 3 );
					break;
				case "m":
					$_month += $_number;
					break;
				case "d":
					 $_day += $_number;
					break;
				case "w":
					 $_day += ( $_number * 7 );
					break;
				case "h":
					 $_hours += $_number;
					break;
				case "n":
					 $_minutes += $_number;
					break;
				case "s":
					 $_seconds += $_number;
					break;
			}

			return mktime( $_hours, $_minutes, $_seconds, $_month ,$_day, $_year );
		}


		/**
		 * returns DomObject object
		 *
		 * @return bool		true if successful
		 * @access protected
		 */
		protected function getDailyView() {

			$start_time = $this->date . ' 00:00:00';
			$end_time   = $this->date . ' 23:59:59';
			$schedule = array();

			// build weekly schedule
			foreach( $this->_data->rows as $tb ) {
				$date      = date( 'Y-m-d', strtotime( $tb['starttime'] ));
				$starttime = date( 'Y-m-d H:i', strtotime( $tb['starttime'] ));
				$endtime   = date( 'Y-m-d H:i', strtotime( $tb['endtime'] ));

				while( date( 'i', strtotime( $starttime )) != '00' &&
					date( 'i', strtotime( $starttime )) != '15' &&
					date( 'i', strtotime( $starttime )) != '30' &&
					date( 'i', strtotime( $starttime )) != '45' ) {

					$starttime = date( 'Y-m-d H:i', strtotime( $starttime ) - 60 );
				}

				// filter invalid data
				if( !$this->displayAll ) {
					if( strtotime( $starttime ) < strtotime( $start_time )) $starttime = $start_time;
					if( strtotime( $endtime   ) > strtotime( $end_time   )) $endtime   = $end_time;
				}

				while( strtotime( $starttime ) < strtotime( $endtime )) {

					$schedule[date( 'H:i', strtotime( $starttime ))] = $tb;

					// increment 15 minute block
					$starttime = date( 'Y-m-d H:i', $this->_dateAdd( 'n', 15, strtotime( $starttime )));

					if( date( 'H:i', strtotime( $starttime )) == '00:00' ) {

						// increment day
						$date = date( 'Y-m-d', $this->_dateAdd( 'd', 1, strtotime( $date )));
					}
				}
			}

			// create table Dom
			$table   = $this->createDomObject( 'table' );
			$caption = new \System\XML\DomObject( 'caption' );
			$thead   = new \System\XML\DomObject( 'thead' );
			$tbody   = new \System\XML\DomObject( 'tbody' );
			$tfoot   = new \System\XML\DomObject( 'tfoot' );

			// set some basic properties
			$table->setAttribute( 'id',          $this->getHTMLControlIdString() );
			$table->appendAttribute( 'class',    ' scheduleview scheduleview_day' );

			$caption->nodeValue = $this->caption;

			// header
			$tr = new \System\XML\DomObject( 'tr' );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'colspan', '2' );

			$form = new \System\XML\DomObject( 'form' );
			$form->appendAttribute( 'action', $this->getQueryString() );
			$form->appendAttribute( 'method', 'post' );

			$div = new \System\XML\DomObject( 'div' );

			if( !$this->displayAll ) {
				$vagenda = new \System\XML\DomObject( 'input' );
				$vagenda->setAttribute( 'name', $this->getHTMLControlIdString() . '__vagenda' );
				$vagenda->setAttribute( 'type', 'submit' );
				$vagenda->setAttribute( 'value', \System\Base\ApplicationBase::getInstance()->translator->get('agenda', 'Agenda' ));
				$vagenda->setAttribute( 'style', 'float:right;' );
				if( $this->displayMode == ScheduleViewMode::agenda() ) {
					$vagenda->setAttribute( 'disabled', 'disabled' );
				}
				$div->addChild( $vagenda );
			}

			$vmonth = new \System\XML\DomObject( 'input' );
			$vmonth->setAttribute( 'name', $this->getHTMLControlIdString() . '__vmonth' );
			$vmonth->setAttribute( 'type', 'submit' );
			$vmonth->setAttribute( 'value', \System\Base\ApplicationBase::getInstance()->translator->get('month', 'Month' ));
			$vmonth->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::monthly() ) {
				$vmonth->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vmonth );

			$vweek = new \System\XML\DomObject( 'input' );
			$vweek->setAttribute( 'name', $this->getHTMLControlIdString() . '__vweek' );
			$vweek->setAttribute( 'type', 'submit' );
			$vweek->setAttribute( 'value', \System\Base\ApplicationBase::getInstance()->translator->get('week', 'Week' ));
			$vweek->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::weekly() ) {
				$vweek->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vweek );

			$vday = new \System\XML\DomObject( 'input' );
			$vday->setAttribute( 'name', $this->getHTMLControlIdString() . '__vday' );
			$vday->setAttribute( 'type', 'submit' );
			$vday->setAttribute( 'value', \System\Base\ApplicationBase::getInstance()->translator->get('day', 'Day' ));
			$vday->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::daily() ) {
				$vday->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vday );

			if( !$this->displayAll ) {

				$prev = new \System\XML\DomObject( 'input' );
				$prev->setAttribute( 'name', $this->getHTMLControlIdString() . '__prev' );
				$prev->setAttribute( 'type', 'submit' );
				$prev->setAttribute( 'value', '<' );
				$prev->setAttribute( 'style', 'float:left;' );
				$div->addChild( $prev );

				$next = new \System\XML\DomObject( 'input' );
				$next->setAttribute( 'name', $this->getHTMLControlIdString() . '__next' );
				$next->setAttribute( 'type', 'submit' );
				$next->setAttribute( 'value', '>' );
				$next->setAttribute( 'style', 'float:left;' );
				$div->addChild( $next );

				$tday = new \System\XML\DomObject( 'input' );
				$tday->setAttribute( 'name', $this->getHTMLControlIdString() . '__tday' );
				$tday->setAttribute( 'type', 'submit' );
				$tday->setAttribute( 'value', \System\Base\ApplicationBase::getInstance()->translator->get('today', 'Today' ));
				$tday->setAttribute( 'style', 'float:left;' );
				if( date( 'Y-m-d', strtotime( $this->date )) == date( 'Y-m-d' )) {
					$tday->setAttribute( 'disabled', 'disabled' );
				}
				$div->addChild( $tday );

				$caption->nodeValue = date( 'M j, Y', strtotime( $start_time ));
			}

			$form->addChild( $div );
			unset( $div );

			$th->addChild( $form );
			unset( $form );

			$tr->addChild( $th );
			unset( $th );

			$thead->addChild( $tr );
			unset( $tr );

			// body
			$rowspan = 1;

			if( isset( $start_time )) {
				$this_date = date( 'Y-m-d', strtotime( $start_time ));
			}

			// for( $time = date( 'H:i', strtotime( $this->startTime )), $x=0; $time != date( 'H:i', strtotime( $this->endTime )) && strtotime( $this->endTime ) > strtotime( $this->startTime ); $x++, $time = date( 'H:i', $this->_dateAdd( 'n', 15, strtotime( $time )))) {
			for( $time = date( 'H:i', strtotime( $this->startTime . ':00' )), $x = $this->startTime * 4; $x < $this->endTime * 4; $x++, $time = date( 'H:i', $this->_dateAdd( 'n', 15, strtotime( $time )))) {

				$tr = new \System\XML\DomObject( 'tr' );

				$td = new \System\XML\DomObject( 'td' );
				$td->nodeValue = $time;
				$td->appendAttribute( 'style', 'width: 16%;' );
				$td->setAttribute( 'class', 'scheduleview_time' );
				$tr->addChild( $td );
				unset( $td );

				if( $rowspan > 1 ) {
					$rowspan--;
				}
				elseif( isset( $schedule[$time] )?true:false ) {

					// calculate rowspan
					$starttime     = $this->date . ' ' . $time;
					$endtime       = $this->date . ' ' . date( 'H:i', strtotime( $schedule[$time]['endtime'] ));

					$rowspan = 1;

					$max=$this->endTime==24?date('Y-m-d H:i',$this->_dateAdd('d',1,strtotime($this->date.' 00:00'))):$this->date . ' ' . $this->endTime . ':00';
					$ignore=$starttime<$endtime?0:1;

					while(( strtotime( $starttime ) < strtotime( $endtime ) || $ignore ) && strtotime( $starttime ) < strtotime( $max )) {
						$rowspan++;
						$starttime = date( 'Y-m-d H:i', $this->_dateAdd( 'n', 15, strtotime( $starttime )));
					}
					if( $rowspan > 1 )$rowspan--;

					$td = new \System\XML\DomObject( 'td' );
					$td->setAttribute   ( 'class',       'scheduleview_event scheduleview_cell' );
					$td->appendAttribute( 'onclick',     str_replace( '%date%', date( 'Y-m-d', strtotime( $this->date )), str_replace( '%time%', $time, str_replace( '%dayofweek%', date( 'D', strtotime( $this->date )), str_replace( '%dayofmonth%', date( 'd', strtotime( $this->date )), $this->onclick )))));
					$td->appendAttribute( 'onmouseover', str_replace( '%date%', date( 'Y-m-d', strtotime( $this->date )), str_replace( '%time%', $time, str_replace( '%dayofweek%', date( 'D', strtotime( $this->date )), str_replace( '%dayofmonth%', date( 'd', strtotime( $this->date )), $this->onmouseover )))));
					$td->appendAttribute( 'onmouseout',  str_replace( '%date%', date( 'Y-m-d', strtotime( $this->date )), str_replace( '%time%', $time, str_replace( '%dayofweek%', date( 'D', strtotime( $this->date )), str_replace( '%dayofmonth%', date( 'd', strtotime( $this->date )), $this->onmouseout )))));
					$td->appendAttribute( 'rowspan', (string) $rowspan );

					$div = new \System\XML\DomObject( 'div' );

					if( isset( $this_date )?$this_date == date( 'Y-m-d' ):false) {
						$td->appendAttribute( 'class', ' scheduleview_today' );
					}

					$div->innerHtml = $schedule[$time]['display'];

					$td->addChild( $div );
					$tr->addChild( $td );
					unset( $div );
					unset( $td );
				}
				else {
					$td = new \System\XML\DomObject( 'td' );
					$td->appendAttribute( 'onclick',     str_replace( '%date%', date( 'Y-m-d', strtotime( $this->date )), str_replace( '%time%', $time, str_replace( '%dayofweek%', date( 'D', strtotime( $this->date )), str_replace( '%dayofmonth%', date( 'd', strtotime( $this->date )), $this->onclick )))));
					$td->appendAttribute( 'onmouseover', str_replace( '%date%', date( 'Y-m-d', strtotime( $this->date )), str_replace( '%time%', $time, str_replace( '%dayofweek%', date( 'D', strtotime( $this->date )), str_replace( '%dayofmonth%', date( 'd', strtotime( $this->date )), $this->onmouseover )))));
					$td->appendAttribute( 'onmouseout',  str_replace( '%date%', date( 'Y-m-d', strtotime( $this->date )), str_replace( '%time%', $time, str_replace( '%dayofweek%', date( 'D', strtotime( $this->date )), str_replace( '%dayofmonth%', date( 'd', strtotime( $this->date )), $this->onmouseout )))));
					$td->setAttribute   ( 'class', 'cell' );

					$div = new \System\XML\DomObject( 'div' );

					if( isset( $this_date )?$this_date == date( 'Y-m-d' ):false ) {
						$td->appendAttribute( 'class', ' scheduleview_today' );
					}

					$div->innerHtml = '&nbsp;';

					$td->addChild( $div );
					$tr->addChild( $td );
					unset( $div );
					unset( $td );
				}

				$tbody->addChild( $tr );
				unset( $tr );
			}

			// footer
			$tr = new \System\XML\DomObject( 'tr' );

			$td = new \System\XML\DomObject( 'td' );
			$td->appendAttribute( 'colspan', '2' );

			$form = new \System\XML\DomObject( 'form' );
			$form->appendAttribute( 'action', $this->getQueryString() );
			$form->appendAttribute( 'method', 'post' );

			$div = new \System\XML\DomObject( 'div' );

			$startTime = new \System\XML\DomObject( 'select' );
			$startTime->appendAttribute( 'name', $this->getHTMLControlIdString() . '__startTime' );

			for( $time = '00:00', $i=0; $i < 24; $i++, $time = date( 'H:i', $this->_dateAdd( 'H', 1, strtotime( $time )))) {
				$option = new \System\XML\DomObject( 'option' );
				$option->appendAttribute( 'value', (string) $i );
				$option->nodeValue = date( 'ga', strtotime( $time ));
				if( $i == $this->startTime ) {
					$option->appendAttribute( 'selected', 'selected' );
				}
				$startTime->addChild( $option );
				unset( $option );
			}

			$endTime = new \System\XML\DomObject( 'select' );
			$endTime->appendAttribute( 'name', $this->getHTMLControlIdString() . '__endTime' );

			for( $time = '01:00', $i=1; $i <= 24; $i++, $time = date( 'H:i', $this->_dateAdd( 'H', 1, strtotime( $time )))) {
				$option = new \System\XML\DomObject( 'option' );
				$option->appendAttribute( 'value', (string) $i );
				$option->nodeValue = date( 'ga', strtotime( $time ));
				if( $i == $this->endTime ) {
					$option->appendAttribute( 'selected', 'selected' );
				}
				$endTime->addChild( $option );
				unset( $option );
			}

			$to = new \System\XML\DomObject( 'span' );
			$to->nodeValue = ' to ';

			$change = new \System\XML\DomObject( 'input' );
			$change->appendAttribute( 'type', 'submit' );
			$change->appendAttribute( 'value', 'Change' );

			$div->addChild( $startTime );
			$div->addChild( $to );
			$div->addChild( $endTime );
			$div->addChild( $change );

			$form->addChild( $div );
			unset( $div );

			$td->addChild( $form );
			unset( $form );

			$tr->addChild( $td );
			unset( $td );

			$tfoot->addChild( $tr );
			unset( $tr );

			// finalize

			$table->addChild( $caption );

			if( $this->showHeader ) {
				$table->addChild( $thead );
			}
			if( $this->showFooter ) {
				$table->addChild( $tfoot );
			}
			$table->addChild( $tbody );

			return $table;
		}


		/**
		 * returns DomObject object
		 *
		 * @return bool		true if successful
		 * @access protected
		 */
		protected function getWeeklyView() {

			/* generate data for weekly view */
			$schedule = array( 'Mon' => array(), 'Tue' => array(), 'Wed' => array(), 'Thu' => array(), 'Fri' => array(), 'Sat' => array(), 'Sun' => array() );

			// filter by week
			$start_time = $this->date . ' 00:00:00';
			$end_time   = $this->date . ' 23:59:59';

			while( date( 'D', strtotime( $start_time )) != 'Mon' ) {
				$start_time = date( 'Y-m-d H:i:s', $this->_dateAdd( 'd', -1, strtotime( $start_time )));
			}

			while( date( 'D', strtotime( $end_time )) != 'Sun' ) {
				$end_time = date( 'Y-m-d H:i:s', $this->_dateAdd( 'd', 1, strtotime( $end_time )));
			}

			// build weekly schedule
			foreach( $this->_data->rows as $tb ) {

				$date      = date( 'Y-m-d', strtotime( $tb['starttime'] ));
				$starttime = date( 'Y-m-d H:i', strtotime( $tb['starttime'] ));
				$endtime   = date( 'Y-m-d H:i', strtotime( $tb['endtime'] ));

				while( date( 'i', strtotime( $starttime )) != '00' &&
					date( 'i', strtotime( $starttime )) != '15' &&
					date( 'i', strtotime( $starttime )) != '30' &&
					date( 'i', strtotime( $starttime )) != '45' ) {
					// todo: do better ^

					$starttime = date( 'Y-m-d H:i', strtotime( $starttime ) - 60 );
				}

				// filter invalid data
				if( !$this->displayAll ) {
					if( strtotime( $starttime ) < strtotime( $start_time )) $starttime = $start_time;
					if( strtotime( $endtime   ) > strtotime( $end_time   )) $endtime   = $end_time;
				}

				while( strtotime( $starttime ) < strtotime( $endtime )) {

					//$schedule[date( 'D', strtotime( $date ))][date( 'H:i', strtotime( $starttime ))] = $tb;
					$schedule[date( 'D', strtotime( $starttime ))][date( 'H:i', strtotime( $starttime ))] = $tb;

					// increment 15 minute block
					// $starttime = date( 'Y-m-d H:i', ( strtotime( $starttime ) + 900 ));
					$starttime = date( 'Y-m-d H:i', $this->_dateAdd( 'n', 15, strtotime( $starttime )));

					if( date( 'H:i', strtotime( $starttime )) == '00:00' ) {
						// increment day
						$date = date( 'Y-m-d', $this->_dateAdd( 'd', 1, strtotime( $date )));
						// $date = date( 'Y-m-d', strtotime( $date ) + 86400 );
					}
				}
			}

			// create table Dom
			$table   = $this->createDomObject( 'table' );
			$caption = new \System\XML\DomObject( 'caption' );
			$thead   = new \System\XML\DomObject( 'thead' );
			$tbody   = new \System\XML\DomObject( 'tbody' );
			$tfoot   = new \System\XML\DomObject( 'tfoot' );

			// set some basic properties
			$table->setAttribute( 'id',          $this->getHTMLControlIdString() );
			$table->appendAttribute( 'class',    ' scheduleview scheduleview_week' );

			$caption->nodeValue = $this->caption;

			// header
			$tr = new \System\XML\DomObject( 'tr' );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'colspan', '8' );

			$form = new \System\XML\DomObject( 'form' );
			$form->appendAttribute( 'action', $this->getQueryString() );
			$form->appendAttribute( 'method', 'post' );

			$div = new \System\XML\DomObject( 'div' );

			if( !$this->displayAll ) {
				$vagenda = new \System\XML\DomObject( 'input' );
				$vagenda->setAttribute( 'name', $this->getHTMLControlIdString() . '__vagenda' );
				$vagenda->setAttribute( 'type', 'submit' );
				$vagenda->setAttribute( 'value', 'Agenda' );
				$vagenda->setAttribute( 'style', 'float:right;' );
				if( $this->displayMode == ScheduleViewMode::agenda() ) {
					$vagenda->setAttribute( 'disabled', 'disabled' );
				}
				$div->addChild( $vagenda );
			}

			$vmonth = new \System\XML\DomObject( 'input' );
			$vmonth->setAttribute( 'name', $this->getHTMLControlIdString() . '__vmonth' );
			$vmonth->setAttribute( 'type', 'submit' );
			$vmonth->setAttribute( 'value', 'Month' );
			$vmonth->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::monthly() ) {
				$vmonth->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vmonth );

			$vweek = new \System\XML\DomObject( 'input' );
			$vweek->setAttribute( 'name', $this->getHTMLControlIdString() . '__vweek' );
			$vweek->setAttribute( 'type', 'submit' );
			$vweek->setAttribute( 'value', 'Week' );
			$vweek->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::weekly() ) {
				$vweek->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vweek );

			$vday = new \System\XML\DomObject( 'input' );
			$vday->setAttribute( 'name', $this->getHTMLControlIdString() . '__vday' );
			$vday->setAttribute( 'type', 'submit' );
			$vday->setAttribute( 'value', 'Day' );
			$vday->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::daily() ) {
				$vday->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vday );

			if( !$this->displayAll ) {

				$prev = new \System\XML\DomObject( 'input' );
				$prev->setAttribute( 'name', $this->getHTMLControlIdString() . '__prev' );
				$prev->setAttribute( 'type', 'submit' );
				$prev->setAttribute( 'value', '<' );
				$prev->setAttribute( 'style', 'float:left;' );
				$div->addChild( $prev );

				$next = new \System\XML\DomObject( 'input' );
				$next->setAttribute( 'name', $this->getHTMLControlIdString() . '__next' );
				$next->setAttribute( 'type', 'submit' );
				$next->setAttribute( 'value', '>' );
				$next->setAttribute( 'style', 'float:left;' );
				$div->addChild( $next );

				$tday = new \System\XML\DomObject( 'input' );
				$tday->setAttribute( 'name', $this->getHTMLControlIdString() . '__tday' );
				$tday->setAttribute( 'type', 'submit' );
				$tday->setAttribute( 'value', 'Today' );
				$tday->setAttribute( 'style', 'float:left;' );
				if( date( 'W', strtotime( $this->date )) == date( 'W' )) {
					$tday->setAttribute( 'disabled', 'disabled' );
				}
				$div->addChild( $tday );

				$caption->nodeValue = date( 'M j', strtotime( $start_time )) . ' - ' . date( 'M j, Y', strtotime( $end_time ));
			}

			$form->addChild( $div );
			unset( $div );

			$th->addChild( $form );
			unset( $form );

			$tr->addChild( $th );
			unset( $th );

			$thead->addChild( $tr );
			unset( $tr );

			$tr = new \System\XML\DomObject( 'tr' );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'style', 'width:16%;' );
			$th->nodeValue = '';
			$tr->addChild( $th );
			unset( $th );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'style', 'width:12%;' );
			$th->innerHtml = !$this->displayAll?'Mon<br />' . date( 'j', strtotime( $start_time )):'Mon';
			$tr->addChild( $th );
			unset( $th );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'style', 'width:12%;' );
			$th->innerHtml = !$this->displayAll?'Tue<br />' . date( 'j', $this->_dateAdd( 'd', 1, strtotime( $start_time ))):'Tue';
			$tr->addChild( $th );
			unset( $th );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'style', 'width:12%;' );
			$th->innerHtml = !$this->displayAll?'Wed<br />' . date( 'j', $this->_dateAdd( 'd', 2, strtotime( $start_time ))):'Wed';
			$tr->addChild( $th );
			unset( $th );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'style', 'width:12%;' );
			$th->innerHtml = !$this->displayAll?'Thu<br />' . date( 'j', $this->_dateAdd( 'd', 3, strtotime( $start_time ))):'Thu';
			$tr->addChild( $th );
			unset( $th );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'style', 'width:12%;' );
			$th->innerHtml = !$this->displayAll?'Fri<br />' . date( 'j', $this->_dateAdd( 'd', 4, strtotime( $start_time ))):'Fri';
			$tr->addChild( $th );
			unset( $th );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'style', 'width:12%;' );
			$th->innerHtml = !$this->displayAll?'Sat<br />' . date( 'j', $this->_dateAdd( 'd', 5, strtotime( $start_time ))):'Sat';
			$tr->addChild( $th );
			unset( $th );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'style', 'width:12%;' );
			$th->innerHtml = !$this->displayAll?'Sun<br />' . date( 'j', $this->_dateAdd( 'd', 6, strtotime( $start_time ))):'Sun';
			$tr->addChild( $th );
			unset( $th );

			$tbody->addChild( $tr );
			unset( $tr );

			// body
			$rowspan = array( 'Mon' => 1, 'Tue' => 1, 'Wed' => 1, 'Thu' => 1, 'Fri' => 1, 'Sat' => 1, 'Sun' => 1 );

//			for( $time = date( 'H:i', strtotime( $this->startTime )), $x=0; $time != date( 'H:i', strtotime( $this->endTime ) + 3600 ) && strtotime( $this->endTime ) > strtotime( $this->startTime ); $x++, $time = date( 'H:i', $this->_dateAdd( 'n', 15, strtotime( $time )))) {
			for( $time = date( 'H:i', strtotime( $this->startTime . ':00' )), $x = $this->startTime * 4; $x < $this->endTime * 4; $x++, $time = date( 'H:i', $this->_dateAdd( 'n', 15, strtotime( $time )))) {

				$tr = new \System\XML\DomObject( 'tr' );

				$td = new \System\XML\DomObject( 'td' );
				$td->nodeValue = $time;
				$td->setAttribute( 'class', 'scheduleview_time' );
				$tr->addChild( $td );
				unset( $td );

				for( $day='Mon', $y=0; $y < 7; $y++, $day = date( 'D', $this->_dateAdd( 'd', 1, strtotime( $day )))) {

					if( isset( $start_time )) {
						$this_date = date( 'Y-m-d', $this->_dateAdd( 'd', $y, strtotime( $start_time )));
					}

					if( $rowspan[$day] > 1 ) {
						$rowspan[$day]--;
					}
					elseif( isset( $schedule[$day][$time] )?true:false ) {

						// calculate rowspan
						//$starttime     = date( 'Y-m-d H:i', strtotime( 'this ' . $schedule[$day][$time]['starttime'] ));
						//$endtime       = date( 'Y-m-d H:i', strtotime( 'this ' . $schedule[$day][$time]['endtime'] ));
						$starttime     = $this_date . ' ' . $time;
						$endtime       = $this_date . ' ' . date( 'H:i', strtotime( $schedule[$day][$time]['endtime'] ));
						$rowspan[$day] = 1;

						$max=$this->endTime==24?date('Y-m-d H:i',$this->_dateAdd('d',1,strtotime($this_date.' 00:00'))):$this_date . ' ' . $this->endTime . ':00';
						$ignore=strtotime($starttime)<strtotime($endtime)?0:1;

						while(( strtotime( $starttime ) < strtotime( $endtime ) || $ignore ) && strtotime( $starttime ) < strtotime( $max )) {
							$rowspan[$day]++;
							$starttime = date( 'Y-m-d H:i', $this->_dateAdd( 'n', 15, strtotime( $starttime )));
						}
						if( $rowspan[$day] > 1 )$rowspan[$day]--;

						$td = new \System\XML\DomObject( 'td' );
						$td->setAttribute   ( 'class',       'scheduleview_event scheduleview_cell' );
						$td->appendAttribute( 'onclick',     str_replace( '%date%', isset( $this_date )?$this_date:'', str_replace( '%time%', $time, str_replace( '%dayofweek%', $day, str_replace( '%dayofmonth%', isset( $this_date )?date( 'd', strtotime( $this_date )):'', $this->onclick )))));
						$td->appendAttribute( 'onmouseover', str_replace( '%date%', isset( $this_date )?$this_date:'', str_replace( '%time%', $time, str_replace( '%dayofweek%', $day, str_replace( '%dayofmonth%', isset( $this_date )?date( 'd', strtotime( $this_date )):'', $this->onmouseover )))));
						$td->appendAttribute( 'onmouseout',  str_replace( '%date%', isset( $this_date )?$this_date:'', str_replace( '%time%', $time, str_replace( '%dayofweek%', $day, str_replace( '%dayofmonth%', isset( $this_date )?date( 'd', strtotime( $this_date )):'', $this->onmouseout )))));
						$td->appendAttribute( 'rowspan', (string) $rowspan[$day] );

						$div = new \System\XML\DomObject( 'div' );

						if( isset( $this_date )&&!$this->displayAll?$this_date == date( 'Y-m-d' ):false) {
							$td->appendAttribute( 'class', ' scheduleview_today' );
						}

						$div->innerHtml = $schedule[$day][$time]['display'];

						$td->addChild( $div );
						$tr->addChild( $td );
						unset( $div );
						unset( $td );
					}
					else {
						$td = new \System\XML\DomObject( 'td' );
						$td->appendAttribute( 'onclick',     str_replace( '%date%', isset( $this_date )?$this_date:'', str_replace( '%time%', $time, str_replace( '%dayofweek%', $day, str_replace( '%dayofmonth%', isset( $this_date )?date( 'd', strtotime( $this_date )):'', $this->onclick )))));
						$td->appendAttribute( 'onmouseover', str_replace( '%date%', isset( $this_date )?$this_date:'', str_replace( '%time%', $time, str_replace( '%dayofweek%', $day, str_replace( '%dayofmonth%', isset( $this_date )?date( 'd', strtotime( $this_date )):'', $this->onmouseover )))));
						$td->appendAttribute( 'onmouseout',  str_replace( '%date%', isset( $this_date )?$this_date:'', str_replace( '%time%', $time, str_replace( '%dayofweek%', $day, str_replace( '%dayofmonth%', isset( $this_date )?date( 'd', strtotime( $this_date )):'', $this->onmouseout )))));
						$td->setAttribute   ( 'class',       'cell' );

						$div = new \System\XML\DomObject( 'div' );

						if( isset( $this_date )&&!$this->displayAll?$this_date == date( 'Y-m-d' ):false ) {
							$td->appendAttribute( 'class', ' scheduleview_today' );
						}

						$div->innerHtml = '&nbsp;';

						$td->addChild( $div );
						$tr->addChild( $td );
						unset( $div );
						unset( $td );
					}
				}

				$tbody->addChild( $tr );
				unset( $tr );
			}

			// footer
			$tr = new \System\XML\DomObject( 'tr' );

			$td = new \System\XML\DomObject( 'td' );
			$td->appendAttribute( 'colspan', '8' );

			$form = new \System\XML\DomObject( 'form' );
			$form->appendAttribute( 'action', $this->getQueryString() );
			$form->appendAttribute( 'method', 'post' );

			$div = new \System\XML\DomObject( 'div' );

			$startTime = new \System\XML\DomObject( 'select' );
			$startTime->appendAttribute( 'name', $this->getHTMLControlIdString() . '__startTime' );

			for( $time = '00:00', $i=0; $i < 24; $i++, $time = date( 'H:i', $this->_dateAdd( 'H', 1, strtotime( $time )))) {
				$option = new \System\XML\DomObject( 'option' );
				$option->appendAttribute( 'value', (string) $i );
				$option->nodeValue = date( 'ga', strtotime( $time ));
				if( $i == $this->startTime ) {
					$option->appendAttribute( 'selected', 'selected' );
				}
				$startTime->addChild( $option );
				unset( $option );
			}

			$endTime = new \System\XML\DomObject( 'select' );
			$endTime->appendAttribute( 'name', $this->getHTMLControlIdString() . '__endTime' );

			for( $time = '01:00', $i=1; $i <= 24; $i++, $time = date( 'H:i', $this->_dateAdd( 'H', 1, strtotime( $time )))) {
				$option = new \System\XML\DomObject( 'option' );
				$option->appendAttribute( 'value', (string) $i );
				$option->nodeValue = date( 'ga', strtotime( $time ));
				if( $i == $this->endTime ) {
					$option->appendAttribute( 'selected', 'selected' );
				}
				$endTime->addChild( $option );
				unset( $option );
			}

			$to = new \System\XML\DomObject( 'span' );
			$to->nodeValue = ' to ';

			$change = new \System\XML\DomObject( 'input' );
			$change->appendAttribute( 'type', 'submit' );
			$change->appendAttribute( 'value', 'Change' );

			$div->addChild( $startTime );
			$div->addChild( $to );
			$div->addChild( $endTime );
			$div->addChild( $change );

			$form->addChild( $div );
			unset( $div );

			$td->addChild( $form );
			unset( $form );

			$tr->addChild( $td );
			unset( $td );

			$tfoot->addChild( $tr );
			unset( $tr );

			// finalize

			$table->addChild( $caption );

			if( $this->showHeader ) {
				$table->addChild( $thead );
			}
			if( $this->showFooter ) {
				$table->addChild( $tfoot );
			}
			$table->addChild( $tbody );

			return $table;
		}


		/**
		 * return widget object
		 *
		 * @return none
		 * @access protected
		 */
		protected function getMonthlyView()
		{
			/* variable for day count */
			$dayofmonth = 1;

			/* create array of weekdays */
			$weekday = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );

			// filter by month
			$start_time = $this->date . ' 00:00:00';
			$end_time   = $this->date . ' 23:59:59';

			while( (int) date( 'd', strtotime( $start_time )) != 1 ) {
				$start_time = date( 'Y-m-d', $this->_dateAdd( 'd', -1, strtotime( $start_time )));
			}

			while( (int) date( 'd', strtotime( $end_time )) != $this->_getMonthLen() ) {
				$end_time = date( 'Y-m-d', $this->_dateAdd( 'd', 1, strtotime( $end_time )));
			}

			/* create widget */
			$table    = $this->createDomObject( 'table' );
			$caption  = new \System\XML\DomObject( 'caption' );
			$thead    = new \System\XML\DomObject( 'thead' );
			$tbody    = new \System\XML\DomObject( 'tbody' );
			$tfoot    = new \System\XML\DomObject( 'tfoot' );

			/* attributes */
			$table->setAttribute( 'id',          $this->getHTMLControlIdString() );
			$table->appendAttribute( 'class',    ' scheduleview scheduleview_month' );

			$caption->nodeValue = $this->caption;

			// header
			$tr = new \System\XML\DomObject( 'tr' );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'colspan', '7' );

			$form = new \System\XML\DomObject( 'form' );
			$form->appendAttribute( 'action', $this->getQueryString() );
			$form->appendAttribute( 'method', 'post' );

			$div = new \System\XML\DomObject( 'div' );

			if( !$this->displayAll ) {
				$vagenda = new \System\XML\DomObject( 'input' );
				$vagenda->setAttribute( 'name', $this->getHTMLControlIdString() . '__vagenda' );
				$vagenda->setAttribute( 'type', 'submit' );
				$vagenda->setAttribute( 'value', 'Agenda' );
				$vagenda->setAttribute( 'style', 'float:right;' );
				if( $this->displayMode == ScheduleViewMode::agenda() ) {
					$vagenda->setAttribute( 'disabled', 'disabled' );
				}
				$div->addChild( $vagenda );
			}

			$vmonth = new \System\XML\DomObject( 'input' );
			$vmonth->setAttribute( 'name', $this->getHTMLControlIdString() . '__vmonth' );
			$vmonth->setAttribute( 'type', 'submit' );
			$vmonth->setAttribute( 'value', 'Month' );
			$vmonth->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::monthly() ) {
				$vmonth->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vmonth );

			$vweek = new \System\XML\DomObject( 'input' );
			$vweek->setAttribute( 'name', $this->getHTMLControlIdString() . '__vweek' );
			$vweek->setAttribute( 'type', 'submit' );
			$vweek->setAttribute( 'value', 'Week' );
			$vweek->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::weekly() ) {
				$vweek->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vweek );

			$vday = new \System\XML\DomObject( 'input' );
			$vday->setAttribute( 'name', $this->getHTMLControlIdString() . '__vday' );
			$vday->setAttribute( 'type', 'submit' );
			$vday->setAttribute( 'value', 'Day' );
			$vday->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::daily() ) {
				$vday->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vday );

			if( !$this->displayAll ) {

				$prev = new \System\XML\DomObject( 'input' );
				$prev->setAttribute( 'name', $this->getHTMLControlIdString() . '__prev' );
				$prev->setAttribute( 'type', 'submit' );
				$prev->setAttribute( 'value', '<' );
				$prev->setAttribute( 'style', 'float:left;' );
				$div->addChild( $prev );

				$next = new \System\XML\DomObject( 'input' );
				$next->setAttribute( 'name', $this->getHTMLControlIdString() . '__next' );
				$next->setAttribute( 'type', 'submit' );
				$next->setAttribute( 'value', '>' );
				$next->setAttribute( 'style', 'float:left;' );
				$div->addChild( $next );

				$tday = new \System\XML\DomObject( 'input' );
				$tday->setAttribute( 'name', $this->getHTMLControlIdString() . '__tday' );
				$tday->setAttribute( 'type', 'submit' );
				$tday->setAttribute( 'value', 'Today' );
				$tday->setAttribute( 'style', 'float:left;' );
				if( date( 'Y-m', strtotime( $this->date )) == date( 'Y-m' )) {
					$tday->setAttribute( 'disabled', 'disabled' );
				}
				$div->addChild( $tday );

				$caption->nodeValue = date( 'M, Y', strtotime( $end_time ));
			}

			$form->addChild( $div );
			unset( $div );

			$th->addChild( $form );
			unset( $form );

			$tr->addChild( $th );
			unset( $th );

			$thead->addChild( $tr );
			unset( $tr );

			// body
			$tr = new \System\XML\DomObject( 'tr' );

			for( $i = 0; $i < 7; $i++ ) {
				$th = new \System\XML\DomObject( 'th' );
				$th->nodeValue .= substr( $weekday[$i], 0, 3 );
				$th->appendAttribute( 'style', 'width:14%;' );
				$tr->addChild( $th );
				unset( $th );
			}

			// add node to calendar
			$tbody->addChild( $tr );
			unset( $tr );

			/* calendar output */
			$tr = new \System\XML\DomObject( 'tr' );

			/* add blank cells to offset start date */
			for( $i = 0; $i < $this->_getStartDate(); $i++ ) {
				$td = new \System\XML\DomObject( 'td' );
				$td->innerHtml = '&nbsp;';
				$tr->addChild( $td );
				unset( $td );
			}

			/* days */
			for( $i = $this->_getStartDate(); $i < $this->_getMonthLen() + $this->_getStartDate(); $i++ )
			{
				$td = new \System\XML\DomObject( 'td' );
				$td->appendAttribute( 'onclick',     str_replace( '%date%', date( 'Y-m-', strtotime( $this->date )) . $dayofmonth, str_replace( '%time%', date( 'H:i:s' ), str_replace( '%dayofweek%', date( 'D', strtotime( date( 'Y-m-', strtotime( $this->date )) . $dayofmonth )), str_replace( '%dayofmonth%', $dayofmonth, $this->onclick )))));
				$td->appendAttribute( 'onmouseover', str_replace( '%date%', date( 'Y-m-', strtotime( $this->date )) . $dayofmonth, str_replace( '%time%', date( 'H:i:s' ), str_replace( '%dayofweek%', date( 'D', strtotime( date( 'Y-m-', strtotime( $this->date )) . $dayofmonth )), str_replace( '%dayofmonth%', $dayofmonth, $this->onmouseover )))));
				$td->appendAttribute( 'onmouseout',  str_replace( '%date%', date( 'Y-m-', strtotime( $this->date )) . $dayofmonth, str_replace( '%time%', date( 'H:i:s' ), str_replace( '%dayofweek%', date( 'D', strtotime( date( 'Y-m-', strtotime( $this->date )) . $dayofmonth )), str_replace( '%dayofmonth%', $dayofmonth, $this->onmouseout )))));
				$td->setAttribute( 'class', 'cell' );

				$div = new \System\XML\DomObject( 'div' );
				$div->innerHtml = '<span class="day">' . $dayofmonth . '</span>';

				/* add new row after 7 columns */
				if(( $i % 7 ) == 0 ) {
					$tbody->addChild( $tr );
					unset( $tr );
					$tr = new \System\XML\DomObject( 'tr' );
				}

				$ds = $this->_data;
				$ds->first();

				while( !$ds->eof() ) {
					if( $this->displayAll ) {
						if( date( 'd', strtotime( $ds['starttime'] )) > date( 'd', strtotime( $ds->row['endtime'] ))) {
							if( date( 'd', strtotime( $ds['starttime'] )) <= $dayofmonth ||
								date( 'd', strtotime( $ds['endtime'] )) >= $dayofmonth ) {
								$div->innerHtml .= $ds['display'] . '<br />';
								$td->appendAttribute( 'class', ' scheduleview_event' );
							}
						}
						else {
							if( date( 'd', strtotime( $ds['starttime'] )) <= $dayofmonth &&
								date( 'd', strtotime( $ds['endtime'] )) >= $dayofmonth ) {
								$div->innerHtml .= $ds['display'] . '<br />';
								$td->appendAttribute( 'class', ' scheduleview_event' );
							}
						}
					}
					else {
						if( strtotime( date( 'Y-m-d', strtotime( $ds['starttime'] ))) <= strtotime( date( 'Y-m-', strtotime( $this->date )) . $dayofmonth ) &&
							strtotime( date( 'Y-m-d', strtotime( $ds['endtime'] ))) >= strtotime( date( 'Y-m-', strtotime( $this->date )) . $dayofmonth )) {
							$div->innerHtml .= $ds['display'] . '<br />';
							$td->appendAttribute( 'class', ' scheduleview_event' );
						}
					}
					$ds->next();
				}

				/* determine if current day is today */
				if( date( 'Y-m-' ) . $dayofmonth == date( 'Y-m-d', strtotime( $this->date )) && !$this->displayAll ) {
					$td->appendAttribute( 'class', ' scheduleview_today' );
				}

				$td->addChild( $div );
				$tr->addChild( $td );
				unset( $div );
				unset( $td );

				/* increment day number */
				$dayofmonth++;
			}

			/* add blank cells to offset end date */
			for(; $i % 7; $i++ ) {
				$td = new \System\XML\DomObject( 'td' );
				$td->innerHtml = '&nbsp;';
				$tr->addChild( $td );
				unset( $td );
			}

			$tbody->addChild( $tr );
			unset( $tr );

			// finalize
			$table->addChild( $caption );

			if( $this->showHeader ) {
				$table->addChild( $thead );
			}
			// no footer
			// $table->addChild( $tfoot );
			$table->addChild( $tbody );

			return $table;
		}


		/**
		 * return widget object
		 *
		 * @return none
		 * @access protected
		 */
		protected function getAgendaView()
		{
			// filter by month
			$start_time = $this->date . ' 00:00:00';
			$end_time   = $this->date . ' 23:59:59';

			while( (int) date( 'd', strtotime( $start_time )) != 1 ) {
				$start_time = date( 'Y-m-d', $this->_dateAdd( 'd', -1, strtotime( $start_time )));
			}

			while( (int) date( 'd', strtotime( $end_time )) != $this->_getMonthLen() ) {
				$end_time = date( 'Y-m-d', $this->_dateAdd( 'd', 1, strtotime( $end_time )));
			}

			/* create widget */
			$table    = $this->createDomObject( 'table' );
			$caption  = new \System\XML\DomObject( 'caption' );
			$thead    = new \System\XML\DomObject( 'thead' );
			$tbody    = new \System\XML\DomObject( 'tbody' );
			$tfoot    = new \System\XML\DomObject( 'tfoot' );

			/* attributes */
			$table->setAttribute( 'id',          $this->getHTMLControlIdString() );
			$table->appendAttribute( 'class',    ' scheduleview scheduleview_agenda' );

			$caption->nodeValue = $this->caption;

			// header
			$tr = new \System\XML\DomObject( 'tr' );

			$th = new \System\XML\DomObject( 'th' );
			$th->appendAttribute( 'colspan', '2' );

			$form = new \System\XML\DomObject( 'form' );
			$form->appendAttribute( 'action', $this->getQueryString() );
			$form->appendAttribute( 'method', 'post' );

			$div = new \System\XML\DomObject( 'div' );

			$vagenda = new \System\XML\DomObject( 'input' );
			$vagenda->setAttribute( 'name', $this->getHTMLControlIdString() . '__vagenda' );
			$vagenda->setAttribute( 'type', 'submit' );
			$vagenda->setAttribute( 'value', 'Agenda' );
			$vagenda->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::agenda() ) {
				$vagenda->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vagenda );

			$vmonth = new \System\XML\DomObject( 'input' );
			$vmonth->setAttribute( 'name', $this->getHTMLControlIdString() . '__vmonth' );
			$vmonth->setAttribute( 'type', 'submit' );
			$vmonth->setAttribute( 'value', 'Month' );
			$vmonth->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::monthly() ) {
				$vmonth->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vmonth );

			$vweek = new \System\XML\DomObject( 'input' );
			$vweek->setAttribute( 'name', $this->getHTMLControlIdString() . '__vweek' );
			$vweek->setAttribute( 'type', 'submit' );
			$vweek->setAttribute( 'value', 'Week' );
			$vweek->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::weekly() ) {
				$vweek->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vweek );

			$vday = new \System\XML\DomObject( 'input' );
			$vday->setAttribute( 'name', $this->getHTMLControlIdString() . '__vday' );
			$vday->setAttribute( 'type', 'submit' );
			$vday->setAttribute( 'value', 'Day' );
			$vday->setAttribute( 'style', 'float:right;' );
			if( $this->displayMode == ScheduleViewMode::daily() ) {
				$vday->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $vday );

			$prev = new \System\XML\DomObject( 'input' );
			$prev->setAttribute( 'name', $this->getHTMLControlIdString() . '__prev' );
			$prev->setAttribute( 'type', 'submit' );
			$prev->setAttribute( 'value', '<' );
			$prev->setAttribute( 'style', 'float:left;' );
			$div->addChild( $prev );

			$next = new \System\XML\DomObject( 'input' );
			$next->setAttribute( 'name', $this->getHTMLControlIdString() . '__next' );
			$next->setAttribute( 'type', 'submit' );
			$next->setAttribute( 'value', '>' );
			$next->setAttribute( 'style', 'float:left;' );
			$div->addChild( $next );

			$tday = new \System\XML\DomObject( 'input' );
			$tday->setAttribute( 'name', $this->getHTMLControlIdString() . '__tday' );
			$tday->setAttribute( 'type', 'submit' );
			$tday->setAttribute( 'value', 'Today' );
			$tday->setAttribute( 'style', 'float:left;' );
			if( date( 'Y-m-d', strtotime( $this->date )) == date( 'Y-m-d' )) {
				$tday->setAttribute( 'disabled', 'disabled' );
			}
			$div->addChild( $tday );

			if( !$this->displayAll ) {
				$caption->nodeValue = date( 'M j', strtotime( $this->date )) . ' - ' . date( 'M j, Y', $this->_dateAdd( 'd', 6, strtotime( $this->date )));
			}

			$form->addChild( $div );
			unset( $div );

			$th->addChild( $form );
			unset( $form );

			$tr->addChild( $th );
			unset( $th );

			$thead->addChild( $tr );
			unset( $tr );

			// body
			$rowspan = 1;

			$this->_data->filter( 'starttime', '<=', date( 'Y-m-d', $this->_dateAdd( 'd', 7, strtotime( $this->date ))));
			$this->_data->filter( 'starttime', '>=', date( 'Y-m-d', strtotime( $this->date )));
			$this->_data->sort( 'starttime' );

			foreach( $this->_data->rows as $record ) {

				$tr = new \System\XML\DomObject( 'tr' );

				$td = new \System\XML\DomObject( 'td' );
				$td->appendAttribute( 'style', 'width:16%;' );
				$td->setAttribute( 'class', 'scheduleview_time' );
				$td->nodeValue = date( 'M j, Y', strtotime( $record['starttime'] ));
				$tr->addChild( $td );
				unset( $td );

				$td = new \System\XML\DomObject( 'td' );
				$td->setAttribute   ( 'class', 'cell event' );
				$td->appendAttribute( 'onclick',     str_replace( '%date%', date( 'Y-m-d', strtotime( $record['starttime'] )), str_replace( '%time%', date( 'H:i:s', strtotime( $record['starttime'] )), str_replace( '%dayofweek%', date( 'D', strtotime( $record['starttime'] )), str_replace( '%dayofmonth%', date( 'd', strtotime( $record['starttime'] )), $this->onclick )))));
				$td->appendAttribute( 'onmouseover', str_replace( '%date%', date( 'Y-m-d', strtotime( $record['starttime'] )), str_replace( '%time%', date( 'H:i:s', strtotime( $record['starttime'] )), str_replace( '%dayofweek%', date( 'D', strtotime( $record['starttime'] )), str_replace( '%dayofmonth%', date( 'd', strtotime( $record['starttime'] )), $this->onmouseover )))));
				$td->appendAttribute( 'onmouseout',  str_replace( '%date%', date( 'Y-m-d', strtotime( $record['starttime'] )), str_replace( '%time%', date( 'H:i:s', strtotime( $record['starttime'] )), str_replace( '%dayofweek%', date( 'D', strtotime( $record['starttime'] )), str_replace( '%dayofmonth%', date( 'd', strtotime( $record['starttime'] )), $this->onmouseout )))));

				$div = new \System\XML\DomObject( 'div' );

				if( isset( $this_date )?$this_date == date( 'Y-m-d' ):false) {
					$td->appendAttribute( 'class', ' scheduleview_today' );
				}

				$div->innerHtml = date( 'M-j g:ia', strtotime( $record['starttime'] )) . ' - ' . date( 'M-j g:ia', strtotime( $record['endtime'] ));
				$div->innerHtml .= ' <span>' . $record['display'] . '</span>';

				$td->addChild( $div );
				$tr->addChild( $td );
				$tbody->addChild( $tr );
				unset( $div );
				unset( $td );
				unset( $tr );
			}

			// footer

			// finalize
			$table->addChild( $caption );

			if( $this->showHeader ) {
				$table->addChild( $thead );
			}
			// no footer
			// $table->addChild( $tfoot );
			$table->addChild( $tbody );

			return $table;
		}


		/**
		 * returns DomObject object
		 *
		 * @return bool		true if successful
		 * @access protected
		 */
		public function getDomObject() {
			if( $this->displayMode == ScheduleViewMode::daily() ) {
				return $this->getDailyView();
			}
			elseif( $this->displayMode == ScheduleViewMode::weekly() ) {
				return $this->getWeeklyView();
			}
			elseif( $this->displayMode == ScheduleViewMode::monthly() ) {
				return $this->getMonthlyView();
			}
			elseif( $this->displayMode == ScheduleViewMode::agenda() ) {
				return $this->getAgendaView();
			}
		}
	}
?>