<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace Packages\Commerx\SBM;

	use System\BadMemberCallException;
	use System\InvalidOperationException;
	use System\Collections\StringCollection;
	use System\Comm\WebRequest;
	use System\XML\XMLParser;


	/**
	 * Represents a service record
	 *
	 * @property string $description
	 * @property string $startDate
	 * @property string $endDate
	 * @property BillingInterval $interval
	 * @property int $period
	 * @property PaymentMethod $paymentMethod
	 * @property int $days
	 * @property string $company
	 * @property string $firstName
	 * @property string $lastName
	 * @property string $address
	 * @property string $city
	 * @property string $province
	 * @property string $country
	 * @property string $postal
	 * @property string $phone
	 * @property string $email
	 * @property string $nameOnCard
	 * @property string $creditCardNumber
	 * @property string $creditCardExpirationMonth
	 * @property string $creditCardExpirationYear
	 * @property string $creditCardCVV
	 * @property ServiceItemCollection $items
	 * @property-read string $ref
	 * @property-read string $status
	 * @property-read StringCollection $errors
	 * @property-read string $url
	 * @property-read string $username
	 * @property-read string $password
	 * 
	 * @author			Darnell Shinbine
	 * @package			PHPRum
	 * @subpackage		Commerx.SBM
	 */
	final class Service
	{
		// connection parameters

		/**
		 * url of sbm web service
		 * @var string
		 */
		private $url = "";

		/**
		 * username
		 * @var string
		 */
		private $username = "";

		/**
		 * password
		 * @var string
		 */
		private $password = "";

		// billing structure

		/**
		 * ref no (optional)
		 * @var string
		 */
		private $ref = "";

		/**
		 * description
		 * @var string
		 */
		private $description = "Generic Service";

		/**
		 * start date
		 * @var string
		 */
		private $startDate = "";

		/**
		 * end date
		 * @var string
		 */
		private $endDate = "";

		/**
		 * interval
		 * @var BillingInterval
		 */
		private $interval;

		/**
		 * peroid
		 * @var int
		 */
		private $period = 1;

		/**
		 * payment method
		 * @var PaymentMethod
		 */
		private $paymentMethod;

		/**
		 * payment terms
		 * @var int
		 */
		private $days = 15;

		// client

		/**
		 * company
		 * @var string
		 */
		private $company = "";

		/**
		 * first name
		 * @var string
		 */
		private $firstName = "";

		/**
		 * last name
		 * @var string
		 */
		private $lastName = "";

		/**
		 * address
		 * @var string
		 */
		private $address = "";

		/**
		 * city
		 * @var string
		 */
		private $city = "";

		/**
		 * province/state
		 * @var string
		 */
		private $province = "";

		/**
		 * country
		 * @var string
		 */
		private $country = "";

		/**
		 * postal/zip
		 * @var string
		 */
		private $postal = "";

		/**
		 * phone no
		 * @var string
		 */
		private $phone = "";

		/**
		 * email address
		 * @var string
		 */
		private $email = "";

		// cc information

		/**
		 * name on card
		 * @var string
		 */
		private $nameOnCard = "";

		/**
		 * card number
		 * @var string
		 */
		private $creditCardNumber = "";

		/**
		 * expiration month
		 * @var string
		 */
		private $creditCardExpirationMonth = "";

		/**
		 * expiration year
		 * @var string
		 */
		private $creditCardExpirationYear = "";

		/**
		 * card cvv
		 * @var string
		 */
		private $creditCardCVV = "";

		/**
		 * service items
		 * @var ServiceItemCollection
		 */
		private $items;

		// return values

		/**
		 * status
		 * @var string
		 */
		private $status = "";

		/**
		 * errors
		 * @var array
		 */
		private $errors;


		/**
		 * Service
		 *
		 * returns an object property
		 *
		 * @param  string	$url			url to sbm web service
		 * @param  string	$username		username
		 * @param  string	$password		password
		 *
		 * @return void
		 * @access public
		 */
		public function Service($url, $username, $password)
		{
			$this->items = new ServiceItemCollection();
			$this->interval = BillingInterval::month();
			$this->paymentMethod = PaymentMethod::invoice();
			$this->startDate = date("Y-m-d");

			$this->url = $url;
			$this->username = $username;
			$this->password = $password;
		}


		/**
		 * __get
		 *
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
				throw new BadMemberCallException("property $field does not exist in ".get_class($this));
			}
		}


		/**
		 * __set
		 *
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
			if($field=='ref'||$field=='status'||$field=='username'||$field=='password'||$field=='url')
			{
				throw new BadMemberCallException("property $field is readonly");
			}
			elseif($field=='description')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='startDate')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='endDate')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='interval')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='period')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='paymentMethod')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='days')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='company')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='firstName')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='lastName')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='address')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='city')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='province')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='country')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='postal')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='phone')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='email')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='nameOnCard')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='creditCardNumber')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='creditCardExpirationMonth')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='creditCardExpirationYear')
			{
				$this->{$field} = (string)$value;
			}
			elseif($field=='creditCardCVV')
			{
				$this->{$field} = (string)$value;
			}
			else
			{
				throw new BadMemberCallException("property $field does not exist in ".get_class($this));
			}
		}


		/**
		 * addItem
		 *
		 * adds a service item
		 *
		 * @param string $code
		 * @param string $description
		 * @param int $qty
		 * @param float $price
		 * @param bool $onetime
		 *
		 * @return void
		 */
		public function addItem($code, $description, $qty, $price, $onetime = false)
		{
			$this->items->add(new ServiceItem($code, $description, $qty, $price, $onetime));
		}


		/**
		 * create
		 *
		 * create a service
		 *
		 * @param string $ref
		 * @return string
		 */
		public function create($ref="")
		{
			return $this->send($this->_getXML("put",$ref));
		}


		/**
		 * update
		 *
		 * update a service
		 *
		 * @param string $ref
		 * @return string
		 */
		public function update($ref)
		{
			return $this->send($this->_getXML("post",$ref));
		}


		/**
		 * delete
		 *
		 * delete a service
		 *
		 * @param string $ref
		 * @return string
		 */
		public function delete($ref)
		{
			return $this->send($this->_getXML("delete",$ref));
		}


		/**
		 * get
		 *
		 * get a service
		 * 
		 * @param string $ref
		 * @return string
		 */
		public function get($ref)
		{
			return $this->send($this->_getXML("get",$ref));
			// TODO: populate object?
		}


		/**
		 * getAll
		 *
		 * get all services
		 *
		 * @return string
		 */
		public function getAll()
		{
			return $this->send($this->_getXML("get"));
		}


		/**
		 * send
		 *
		 * @param string $xml
		 * @return string
		 */
		private function send($xml)
		{
			$result = "";
			$this->ref = "";
			$this->status = "";
			$this->errors = new StringCollection();

			try
			{
				$request = new HTTPWebRequest();
				$request->contentType = "text/xml";
				$request->setData($xml);
				$request->url = $this->url;
				$response = $request->getResponse();

				// check for errors
				$parser = new XMLParser();
				$result = $parser->parse($response->content);
				$errors = $result->getChildrenByName('error');
				if($errors->count > 0)
				{
					foreach($errors as $error)
					{
						$this->errors->add( $error->value );
					}
				}
			}
			catch( Exception $e )
			{
				throw new InvalidOperationException("an error occurred while attempting to communicate with {$this->url}");
			}

			return $result;
		}

		private function _getXML($method="put",$ref="")
		{
			return '<'.'?xml version="1.0"?'.">
<request username=\"{$this->username}\" password=\"{$this->password}\">
  <$method entity=\"service\"".(strlen($ref)>0?" ref=\"{$ref}\"":"").(($method=="put"||$method=="post")?">".$this->_getServiceXML()."</$method>":"/>")."
</request>";
		}

		private function _getServiceXML()
		{
			return "
    <service>
      <description>{$this->description}</description>
      <billing>
        <startdate>{$this->startDate}</startdate>".(strlen($this->endDate)>0?"
        <enddate>{$this->endDate}</enddate>":"")."
        <interval>{$this->interval}</interval>
        <period>{$this->period}</period>
        <paymentmethod>{$this->paymentMethod}</paymentmethod>
        <days>{$this->days}</days>".(strlen($this->nameOnCard)>0?$this->_getCreditCardXML():"")."
      </billing>
      <client>
        <company>{$this->company}</company>
        <firstname>{$this->firstName}</firstname>
        <lastname>{$this->lastName}</lastname>
        <address>{$this->address}</address>
        <city>{$this->city}</city>
        <province>{$this->province}</province>
        <country>{$this->country}</country>
        <postal>{$this->postal}</postal>
        <phone>{$this->phone}</phone>
        <email>{$this->email}</email>
      </client>
      <items>".$this->_getItemsXML()."
      </items>
    </service>";
		}

		private function _getCreditCardXML()
		{
			return "
        <creditcard>
          <nameoncard>{$this->nameOnCard}</nameoncard>
          <number>{$this->creditCardNumber}</number>
          <expmonth>{$this->creditCardExpirationMonth}</expmonth>
          <expyear>{$this->creditCardExpirationYear}</expyear>
          <cvv>{$this->creditCardCVV}</cvv>
        </creditcard>";
		}

		private function _getItemsXML()
		{
			$xml = "";
			foreach($this->items as $item) {
				$xml .= "
        <item".($item->oneTime?" onetime=\"true\"":"").">
          <code>{$item->code}</code>
          <description>{$item->description}</description>
          <qty>{$item->qty}</qty>
          <price>{$item->price}</price>
        </item>";
			}
			return $xml;
		}
	}
?>