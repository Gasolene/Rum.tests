<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
    namespace RSS;


	/**
     * Represents an RSS item
     *
     * @property string $title title
     * @property string $link link
     * @property string $description description
     * @property string $author author
     * @property string $category category
     * @property string $comments comments
     * @property string $enclosureURL enclosure URL
     * @property string $enclosureLength enclosure length
     * @property string $enclosureType enclosure type
     * @property string $guid guid
     * @property string $pubDate publish date
     *
	 * @package			PHPRum
	 * @subpackage		RSS
	 */
	class RSSItem
	{
		/**
		 * specifies the name of the channel item
         * @var string
		 * @access protected
		 */
		protected $title				= '';

		/**
		 * specifies the URL of the item
         * @var string
		 * @access protected
		 */
		protected $link					= '';

		/**
		 * specifies the content of the item
         * @var string
		 * @access protected
		 */
		protected $description			= '';

		/**
		 * specifies the name of the writer
         * @var string
		 * @access protected
		 */
		protected $author				= '';

		/**
		 * specifies the category of the content in the item
         * @var string
		 * @access protected
		 */
		protected $category				= '';

		/**
		 * specifies the URL to a comments page for the item
         * @var string
		 * @access protected
		 */
		protected $comments				= '';

		/**
		 * describes a media object url that is attached to the item
         * @var string
		 * @access protected
		 */
		protected $enclosureURL			= '';

		/**
		 * describes a media object size that is attached to the item
         * @var string
		 * @access protected
		 */
		protected $enclosureLength		= 0;

		/**
		 * describes a media object type that is attached to the item
         * @var string
		 * @access protected
		 */
		protected $enclosureType		= '';

		/**
		 * specifies the unique identifer for the item
         * @var string
		 * @access protected
		 */
		protected $guid					= '';

		/**
		 * specifies the date the item was published
         * @var string
		 * @access protected
		 */
		protected $pubDate				= '';


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
			elseif( $field === 'author' )
			{
				$this->author = (string)$value;
			}
			elseif( $field === 'category' )
			{
				$this->category = (string)$value;
			}
			elseif( $field === 'comments' )
			{
				$this->comments = (string)$value;
			}
			elseif( $field === 'enclosureURL' )
			{
				$this->enclosureURL = (string)$value;
			}
			elseif( $field === 'enclosureLength' )
			{
				$this->enclosureLength = (string)$value;
			}
			elseif( $field === 'enclosureType' )
			{
				$this->enclosureType = (string)$value;
			}
			elseif( $field === 'guid' )
			{
				$this->guid = (string)$value;
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
			elseif( $field === 'author' )
			{
				return $this->author;
			}
			elseif( $field === 'category' )
			{
				return $this->category;
			}
			elseif( $field === 'comments' )
			{
				return $this->comments;
			}
			elseif( $field === 'enclosureURL' )
			{
				return $this->enclosureURL;
			}
			elseif( $field === 'enclosureLength' )
			{
				return $this->enclosureLength;
			}
			elseif( $field === 'enclosureType' )
			{
				return $this->enclosureType;
			}
			elseif( $field === 'guid' )
			{
				return $this->guid;
			}
			elseif( $field === 'pubDate' )
			{
				return $this->pubDate;
			}
			else
			{
				throw new \System\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * return DomObject for the rss item
		 *
		 * @return DomObject
		 * @access public
		 */
		public function getRSSItem()
		{
			if( $this->title || $this->description )
			{
				$item = new \System\XML\DomObject( 'item' );

				if( $this->title )
				{
					$title = new \System\XML\DomObject( 'title' );
					$title->nodeValue = htmlentities( $this->title );
					$item->addChild( $title );
				}

				if( $this->link )
				{
					$link = new \System\XML\DomObject( 'link' );
					$link->nodeValue = htmlentities( $this->link );
					$item->addChild( $link );
				}

				if( $this->description )
				{
					$description = new \System\XML\DomObject( 'description' );
					// $description->nodeValue = '<![CDATA[' . (string) $this->description . ']]>';
					$description->nodeValue = htmlentities( $this->description );
					$item->addChild( $description );
				}

				if( $this->author )
				{
					$author = new \System\XML\DomObject( 'author' );
					$author->nodeValue = htmlentities( $this->author );
					$item->addChild( $author );
				}

				if( $this->category )
				{
					$category = new \System\XML\DomObject( 'category' );
					$category->nodeValue = htmlentities( $this->category );
					$item->addChild( $category );
				}

				if( $this->comments )
				{
					$comments = new \System\XML\DomObject( 'comments' );
					$comments->nodeValue = htmlentities( $this->comments );
					$item->addChild( $comments );
				}

				if( $this->guid )
				{
					$guid = new \System\XML\DomObject( 'guid' );
					$guid->nodeValue = htmlentities( $this->guid );
					$item->addChild( $guid );
				}

				if( $this->pubDate )
				{
					$pubDate = new \System\XML\DomObject( 'pubDate' );
					$pubDate->nodeValue = gmdate( 'D, d M Y G:i:s', strtotime( $this->pubDate )) . ' GMT';
					$item->addChild( $pubDate );
				}

				if( $this->enclosureURL && $this->enclosureLength && $this->enclosureType )
				{
					$enclosure = new \System\XML\DomObject( 'enclosure' );
					$enclosure->setAttribute( 'url', $this->enclosureURL );
					$enclosure->setAttribute( 'length', $this->enclosureLength );
					$enclosure->setAttribute( 'type', $this->enclosureType );

					$item->addChild( $enclosure );
				}

				return $item;
			}
			else
			{
				throw new \System\Base\InvalidOperationException("RSSItem::getRSSItem() called with null title or description member");
			}
		}
	}
?>