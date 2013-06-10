<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace RSS;


	/**
     * Represents an RSS feed
     *
     * @property string $title title
     * @property string $link link
     * @property string $description description
     * @property string $language language
     * @property string $copyright copyright
     * @property string $managingEditor managingEditor
     * @property string $webMaster webMaster
     * @property string $pubDate pubDate
     * @property string $lastBuildDate lastBuildDate
     * @property string $category category
     * @property string $generator generator
     * @property string $docs docs
     * @property string $ttl ttl
     * @property string $image image
	 * @property array $items items
     *
	 * @package			PHPRum
	 * @subpackage		RSS
	 */
	class RSSFeed
	{
		/**
		 * specifies the name of the RSS channel
         * @var string
		 * @access protected
		 */
		protected $title				= '';

		/**
		 * specifies the URL for the website that RSS is describing
         * @var string
		 * @access protected
		 */
		protected $link					= '';

		/**
		 * specifies a brief explanation of the information the feed contains
         * @var string
		 * @access protected
		 */
		protected $description			= '';

		/**
		 * specifies the language code the feed uses
         * @var string
		 * @access protected
		 */
		protected $language 			= 'en';

		/**
		 * specifies the copyright notice for content in the channel
         * @var string
		 * @access protected
		 */
		protected $copyright 			= '';

		/**
		 * specifies the email address for person responsible for editorial content
         * @var string
		 * @access protected
		 */
		protected $managingEditor		= '';

		/**
		 * specifies the email address for person responsible for technical issues relating to channel
         * @var string
		 * @access protected
		 */
		protected $webMaster			= '';

		/**
		 * specifies The protectedation date for the content in the channel
         * @var string
		 * @access protected
		 */
		protected $pubDate				= '';

		/**
		 * specifies the last time the feed was manipulated
         * @var string
		 * @access protected
		 */
		protected $lastBuildDate		= '';

		/**
		 * specifies the category of the content in the feed
         * @var string
		 * @access protected
		 */
		protected $category				= '';

		/**
		 * specifies the string indicating the program used to generate the channel
         * @var string
		 * @access protected
		 */
		protected $generator			= 'Simple RSS Feed Generator 1.0';

		/**
		 * specifies a URL that points to the documentation for the format used in the RSS file
         * @var string
		 * @access protected
		 */
		protected $docs					= '';

		/**
		 * specifies the number of minutes that indicates how long a channel can be cached before refreshing from the source
         * @var int
		 * @access protected
		 */
		protected $ttl					= 60;

		/**
		 * specifies a GIF, JPEG or PNG image that can be displayed with the channel
         * @var string
		 * @access protected
		 */
		protected $image				= '';

		/**
		 * contains the rss items
         * @var array
		 * @access private
		 */
		private $_items					= array();


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
			if( $field === 'title' )
			{
				$this->title = (string)$value;
			}
			elseif( $field === 'link' )
			{
				$this->link = (string)$value;
			}
			elseif( $field === 'description' )
			{
				$this->description = (string)$value;
			}
			elseif( $field === 'language' )
			{
				$this->language = (string)$value;
			}
			elseif( $field === 'copyright' )
			{
				$this->copyright = (string)$value;
			}
			elseif( $field === 'managingEditor' )
			{
				$this->managingEditor = (string)$value;
			}
			elseif( $field === 'webMaster' )
			{
				$this->webMaster = (string)$value;
			}
			elseif( $field === 'pubDate' )
			{
				if( strtotime( (string)$value ))
				{
					$this->pubDate = (string)$value;
				}
				else
				{
					throw new \System\BadMemberCallException("pubDate {$value} must be a valid datetime string in ".get_class($this));
				}
			}
			elseif( $field === 'lastBuildDate' )
			{
				if( strtotime( (string)$value ))
				{
					$this->lastBuildDate = (string)$value;
				}
				else
				{
					throw new \System\BadMemberCallException("lastBuildDate {$value} must be a valid datetime string in ".get_class($this));
				}
			}
			elseif( $field === 'category' )
			{
				$this->category = (string)$value;
			}
			elseif( $field === 'generator' )
			{
				$this->generator = (string)$value;
			}
			elseif( $field === 'docs' )
			{
				$this->docs = (string)$value;
			}
			elseif( $field === 'ttl' )
			{
				$this->ttl = (int)$value;
			}
			elseif( $field === 'image' )
			{
				$this->image = (string)$value;
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
			if( $field === 'title' )
			{
				return $this->title;
			}
			elseif( $field === 'link' )
			{
				return $this->link;
			}
			elseif( $field === 'description' )
			{
				return $this->description;
			}
			elseif( $field === 'language' )
			{
				return $this->language;
			}
			elseif( $field === 'copyright' )
			{
				return $this->copyright;
			}
			elseif( $field === 'managingEditor' )
			{
				return $this->managingEditor;
			}
			elseif( $field === 'webMaster' )
			{
				return $this->webMaster;
			}
			elseif( $field === 'pubDate' )
			{
				return $this->pubDate;
			}
			elseif( $field === 'lastBuildDate' )
			{
				return $this->lastBuildDate;
			}
			elseif( $field === 'category' )
			{
				return $this->category;
			}
			elseif( $field === 'generator' )
			{
				return $this->generator;
			}
			elseif( $field === 'docs' )
			{
				return $this->docs;
			}
			elseif( $field === 'ttl' )
			{
				return $this->ttl;
			}
			elseif( $field === 'image' )
			{
				return $this->image;
			}
			elseif( $field === 'items' )
			{
				return $this->_items;
			}
			else
			{
				throw new \System\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * add an rss item to the rss feed
		 *
		 * @param  object	$item		RSSItem object
		 *
		 * @return void
		 * @access public
		 */
		public function parse( $url )
		{
			$webRequest = new \System\Comm\HTTPWebRequest();
			$webRequest->url = $url;
			$webRequest->addHeader("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.20) Gecko/20110803 Firefox/3.6.20 ( .NET CLR 3.5.30729)");
			$webRequest->addHeader("Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
			$webRequest->addHeader("Accept-Language: en-us,en;q=0.5");
			$webRequest->method = 'GET';
			$xml = $webRequest->getResponse()->content;

			$XMLParser = new \System\XML\XMLParser();
			$rss = $XMLParser->parse( $xml );

			$this->title = $rss->channel->getChildByName('title')->value;
			$this->description = $rss->channel->getChildByName('description')->value;
			$this->link = $rss->channel->getChildByName('link')->value;
			if($rss->channel->findChildByName('language')) $this->language = $rss->channel->getChildByName('language')->value;
			if($rss->channel->findChildByName('lastBuildDate')) $this->lastBuildDate = $rss->channel->getChildByName('lastbuilddate')->value;
			$this->_items = array();

			foreach($rss->channel->getChildrenByName('item') as $item)
			{
				$RSSItem = new RSSItem();
				$RSSItem->title = $item->title->value;
				$RSSItem->description = $item->description->value;
				if($item->findChildByName('pubDate')) $RSSItem->pubDate = $item->pubDate->value;
				if($item->findChildByName('link')) $RSSItem->link = $item->link->value;
				if($item->findChildByName('guid')) $RSSItem->guid = $item->guid->value;
				if($item->findChildByName('author')) $RSSItem->author = $item->author->value;

				$this->addItem($RSSItem);
			}
		}


		/**
		 * add an rss item to the rss feed
		 *
		 * @param  object	$item		RSSItem object
		 *
		 * @return void
		 * @access public
		 */
		public function addItem( RSSItem &$item )
		{
			$this->_items[] = $item;
		}


		/**
		 * getRSSFeed
		 *
		 * return DomObject for the rss feed
		 *
		 * @return DomObject
		 * @access public
		 */
		public function getRSSFeed()
		{
			$feed = new \System\XML\DomObject( 'rss' );
			$feed->setAttribute( 'version', '2.0' );

			if( $this->title && $this->link && $this->description )
			{
				$channel = new \System\XML\DomObject( 'channel' );

				$title = new \System\XML\DomObject( 'title' );
				$title->nodeValue = $this->title;
				$channel->addChild( $title );

				$link = new \System\XML\DomObject( 'link' );
				$link->nodeValue = $this->link;
				$channel->addChild( $link );

				$description = new \System\XML\DomObject( 'description' );
				$description->nodeValue = htmlentities( $this->description );
				$channel->addChild( $description );

				if( $this->language )
				{
					$language = new \System\XML\DomObject( 'language' );
					$language->nodeValue = $this->language;
					$channel->addChild( $language );
				}

				if( $this->copyright )
				{
					$copyright = new \System\XML\DomObject( 'copyright' );
					$copyright->nodeValue = $this->copyright;
					$channel->addChild( $copyright );
				}

				if( $this->managingEditor )
				{
					$managingEditor = new \System\XML\DomObject( 'managingEditor' );
					$managingEditor->nodeValue = $this->managingEditor;
					$channel->addChild( $managingEditor );
				}

				if( $this->webMaster )
				{
					$webMaster = new \System\XML\DomObject( 'webMaster' );
					$webMaster->nodeValue = $this->webMaster;
					$channel->addChild( $webMaster );
				}

				if( $this->pubDate )
				{
					$pubDate = new \System\XML\DomObject( 'pubDate' );
					$pubDate->nodeValue = gmdate( 'D, d M Y G:i:s', strtotime( $this->pubDate )) . ' GMT';
					$channel->addChild( $pubDate );
				}

				if( $this->lastBuildDate )
				{
					$lastBuildDate = new \System\XML\DomObject( 'lastBuildDate' );
					$lastBuildDate->nodeValue = gmdate( 'D, d M Y G:i:s', strtotime( $this->lastBuildDate )) . ' GMT';
					$channel->addChild( $lastBuildDate );
				}

				if( $this->category )
				{
					$category = new \System\XML\DomObject( 'category' );
					$category->nodeValue = $this->category;
					$channel->addChild( $category );
				}

				if( $this->generator )
				{
					$generator = new \System\XML\DomObject( 'generator' );
					$generator->nodeValue = $this->generator;
					$channel->addChild( $generator );
				}

				if( $this->docs )
				{
					$docs = new \System\XML\DomObject( 'docs' );
					$docs->nodeValue = $this->docs;
					$channel->addChild( $docs );
				}

				if( $this->ttl > 0 )
				{
					$ttl = new \System\XML\DomObject( 'ttl' );
					$ttl->nodeValue = (string)$this->ttl;
					$channel->addChild( $ttl );
				}

				if( $this->image )
				{
					$image = new \System\XML\DomObject( 'image' );
					$image_url = new \System\XML\DomObject( 'url' );
					$image_url->nodeValue = $this->image;
					$image->addChild( $image_url );
					$image_title = new \System\XML\DomObject( 'title' );
					$image_title->nodeValue = $this->title;
					$image->addChild( $image_title );
					$image_link = new \System\XML\DomObject( 'link' );
					$image_link->nodeValue = $this->link;
					$image->addChild( $image_link );

					$channel->addChild( $image );
				}

				foreach( $this->_items as $item )
				{
					$channel->addChild( $item->getRSSItem() );
				}

				$feed->addChild( $channel );

				return $feed->getXMLString();
			}
			else
			{
				throw new \System\Base\InvalidOperationException("RSSFeed::getRSSFeed() called with null title link or description member");
			}
		}
	}
?>