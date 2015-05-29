<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace Ical;


	/**
     * Represents an iCal Event item
     *
     * @property string $dtstart
     * @property string $dtend
     * @property string $dtstamp
     * @property string $uid
     * @property string $location
     * @property string $organizer
     * @property string $summary
     * @property string $description
     * @property string $url
     *
	 * @package			PHPRum
	 * @subpackage		RSS
	 */
	class ICalEvent
	{
	    /**
	     * 
	     * @var string
	     */
	    protected $DTSTART = '';

	    /**
	     * 
	     * @var string
	     */
	    protected $DTEND = '';

	    /**
	     * 
	     * @var string
	     */
	    protected $DTSTAMP = '';

	    /**
	     * 
	     * @var string
	     */
	    protected $UID = '';

	    /**
	     * 
	     * @var string
	     */
	    protected $LOCATION = '';

	    /**
	     * 
	     * @var string
	     */
	    protected $ORGANIZER = '';

	    /**
	     * 
	     * @var string
	     */
	    protected $SUMMARY = '';

	    /**
	     * specifies the content of the item
	     * @var string
	     */
	    protected $DESCRIPTION = '';

	    /**
	     * specifies the url of the item
	     * @var string
	     */
	    protected $URL = '';


		/**
		 * sets object property
		 *
		 * @param  string	$field		name of field
		 * @param  mixed	$value		value of field
		 * @return string				string of variables
		 * @access protected
		 * @ignore
		 */
		public function __set( $field, $value )
		{
			$field = strtoupper($field);
			if( $field === 'DTSTART' )
			{			    
				$this->DTSTART = (string)$value;
			}
			elseif( $field === 'DTEND' )
			{
				$this->DTEND = (string)$value;
			}
			elseif( $field === 'DTSTAMP' )
			{
				$this->DTSTAMP = (string)$value;
			}
			elseif( $field === 'UID' )
			{
				$this->UID = (string)$value;
			}
			elseif( $field === 'LOCATION' )
			{
				$this->LOCATION = (string)$value;
			}
			elseif( $field === 'STATUS' )
			{
				$this->STATUS = (string)$value;
			}
			elseif( $field === 'ORGANIZER' )
			{
				$this->ORGANIZER = (string)$value;
			}
			elseif( $field === 'SUMMARY' )
			{
				$this->SUMMARY = (string)$value;
			}
			elseif( $field === 'DESCRIPTION' )
			{
				$this->DESCRIPTION = (string)$value;
			}
			elseif( $field === 'URL' )
			{
				$this->URL = (string)$value;
			}
			else
			{
				throw new \System\BadMemberCallException("call to undefined property $field in ".get_class($this));
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
		public function __get( $field )
		{
			$field = strtoupper($field);
		    switch($field) {
			case 'DTSTART': 
			case 'DTEND':
			case 'DTSTAMP':
			case 'UID':
			case 'LOCATION':
			case 'STATUS':
			case 'ORGANIZER':
			case 'SUMMARY':
			case 'DESCRIPTION':
			case 'URL':
				return $this->{$field};
			break;			
		    }
		}
	}
?>