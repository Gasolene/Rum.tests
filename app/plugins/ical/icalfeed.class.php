<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace ICal;


	/**
     * Represents an iCal feed (NOT timezone safe!!)
     *
	 * @property array $items items
	 * @property string $title title
     *
	 * @package			PHPRum
	 * @subpackage		RSS
	 */
	class ICalFeed
	{
		/**
		 * specifies the name of the iCal calendar
         * @var string
		 * @access protected
		 */
		protected $title				= '';

		/**
		 * contains the rss items
         * @var array
		 * @access private
		 */
		private $_items					= array();


		/**
		 * gets object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @access protected
		 * @ignore
		 */
		public function __get( $field )
		{
			if( $field === 'items' )
			{
				return $this->_items;
			}
			elseif( $field === 'title' )
			{
				return $this->title;
			}
			else
			{
				throw new \System\Base\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * add an ical event to the ical feed
		 *
		 * @param  object	$item		RSSItem object
		 *
		 * @return void
		 * @access public
		 */
		public function parse( $url )
		{
			$icalXML = $this->iCalToXML($url);

			$XMLParser = new \System\XML\XMLParser();
			$ical = $XMLParser->parse( $icalXML );

			if($ical->findChildByName('x-wr-calname')) {
				$this->title =  $ical->getChildByName('x-wr-calname')->value;
			}

			foreach($ical->getChildrenByName('VEVENT') as $item)
			{
				$ICalEvent = new ICalEvent();

				if($item->findChildByName('DTSTART') != "") $ICalEvent->DTSTART = date("Y-m-d H:i:s",strtotime($item->DTSTART->value));
				if($item->findChildByName('DTEND') != "") $ICalEvent->DTEND = date("Y-m-d H:i:s",strtotime($item->DTEND->value));
				if($item->findChildByName('DTSTAMP') != "") $ICalEvent->DTSTAMP = date("Y-m-d H:i:s",strtotime($item->DTSTAMP->value));
				if($item->findChildByName('UID') != "") $ICalEvent->UID = $item->UID->value;
				if($item->findChildByName('LOCATION') != "") $ICalEvent->LOCATION = $item->LOCATION->value;
				if($item->findChildByName('STATUS') != "") $ICalEvent->STATUS = $item->STATUS->value;
				if($item->findChildByName('ORGANIZER') != "") $ICalEvent->ORGANIZER = $item->ORGANIZER->value;
				if($item->findChildByName('SUMMARY') != "") $ICalEvent->SUMMARY = $item->SUMMARY->value;
				if($item->findChildByName('DESCRIPTION') != "") $ICalEvent->DESCRIPTION = $item->DESCRIPTION->value;
				if($item->findChildByName('URL') != "") $ICalEvent->URL = $item->URL->value;

				$this->addItem($ICalEvent);
			}
		}


		/**
		 * add an event item to the ical feed
		 *
		 * @param  object	$item		ICALEvent object
		 *
		 * @return void
		 * @access public
		 */
		public function addItem(ICalEvent &$item)
		{
			$this->_items[] = $item;
		}


		/**
		 * @param  object	$item		RSSItem object
		 *
		 * @return xml
		 * @access public
		 */
		function iCalToXML($url)
		{
		    $myfile = @file_get_contents($url);

			if($myfile!==false)
			{
				$myfile = str_replace("\n ", "", $myfile);
				$myfile = str_replace("&", " and ", $myfile);

				$mylines = explode("\n", $myfile);

				$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
				foreach($mylines as $line) {
					if($line == "") continue;

					list($key,$val) = explode(":", $line);
					$key = trim($key, "\r\n");
					$val = trim($val, "\r\n");
					$val = str_replace("\n", "", $val);
					$val = str_replace("\r", "", $val);

					$attribs = explode(";", $key);
					$key = $attribs[0];
					$attribs = array_slice($attribs, 1);
					$myattribs = " ";
					foreach($attribs as $attrib) {
						list($att, $attval) = explode("=", $attrib);
						$myattribs .= "$att=\"$attval\" ";
					}
					$myattribs = rtrim($myattribs);

					// BEGIN and END keywords
					if($key) {
						if($key == 'BEGIN') {
						  $xml .= "<$val$myattribs>\n";
						} else if($key == 'END') {
						  $xml .= "</$val>\n";
						} else {
						  $xml .= "<$key$myattribs>$val</$key>\n";
						}
					}
				}

				return $xml;
			}
			else
			{
				throw new \Exception("failed to open stream: ".$url);
			}
		}
	}
?>