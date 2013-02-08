<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace Webbot;

	/**
	 * crawls entire websites and parses each page
     *
     * @property-read string $url
     * @property-read array $pagecrawlers
     * @property-read int $elapsed
	 * 
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @since			4.6.0
	 * @package			PHPRum
	 * @subpackage		Webbot
	 */
	class SiteCrawler {

		/**
		 * specifies url
		 * @access protected
		 */
		protected $url					= '';

		/**
		 * contains array of crawled pages
		 * @access protected
		 */
		protected $pagecrawlers			= array();

		/**
		 * contains elapsed time
		 * @access protected
		 */
		protected $elapsed				= 0;

		/**
		 * specifies max crawl count
		 * @access protected
		 */
		protected $max					= __SITECRAWLER_MAX_CRAWL__;

		/**
		 * contains crawl count
		 * @access protected
		 */
		private $_crawlCount			= 0;


		/**
		 * get object property
		 *
		 * @param  string	$field		name of field
		 * @return string				string of variables
		 * @access protected
		 * @ignore
		 */
		public function __get( $field ) {
			if( $field === 'url' ) {
				return $this->url;
			}
			elseif( $field === 'pagecrawlers' ) {
				return $this->pagecrawlers;
			}
			elseif( $field === 'elapsed' ) {
				return $this->elapsed;
			}
			else {
				throw new \System\BadMemberCallException("call to undefined property $field in ".get_class($this));
			}
		}


		/**
		 * read HTTP response from request and store it in a variable
		 *
		 * @param  string		$url			url
		 *
		 * @return void
		 * @access public
		 */
		public function crawl( $url ) {
			$timer = new \System\Utils\Timer(true);
			$this->icrawl( $this->url = $url );
			$this->elapsed = $timer->elapsed();
		}


		/**
		 * read HTTP response from request and store it in a variable
		 *
		 * @param  string		$url			url
		 *
		 * @return void
		 * @access public
		 */
		protected function icrawl( $url ) {

			// only crawl each page once
			if( !defined( '__URL_' . $url )) {
				define( '__URL_' . $url, TRUE );

				if( ++$this->_crawlCount > $this->max ) return;

				// crawl page
				$pagecrawler = new PageCrawler();
				$pagecrawler->crawl( $url );

				// check content type
				if( strpos( strtolower( $pagecrawler->contentType ), 'html' ) !== false ) {

					// save webpage info
					$this->pagecrawlers[] = $pagecrawler;

					// check http status
					if( strpos( strtolower( $pagecrawler->httpStatus ), '200 ok' ) !== false ) {

						// get links
						$links = $pagecrawler->getLinks();

						// crawl all subpages
						foreach( $links[1] as $link ) {
							if( $this->isValidURL( $link )) {
								$this->icrawl( $this->parseURL( $link ));
							}
						}
					}
					elseif( strpos( strtolower( $pagecrawler->httpStatus ), '301 moved permanently' ) !== false ) {
						// moved
					}
					else {
						// bad status
					}
				}
				else {
					// ignore content type
				}
			}
		}


		/**
		 * check if URL is valid
		 *
		 * @return bool
		 * @access public
		 */
		protected function isValidURL( $url ) {
			if(( strpos( $url, 'http://' ) !== false || strpos( $url, 'https://' ) !== false ) && strpos( $this->url, $url ) === false ) {
				// link is external
				return false;
			}
			if( strpos( $url, 'mailto:' ) !== false ) {
				// link contains mailto:
				return false;
			}
			if( strpos( $url, 'javascript:' ) !== false ) {
				// link contains mailto:
				return false;
			}
			if( strpos( $url, '.pdf' ) !== false ) {
				// link is a PDF
				return false;
			}
			if( strpos( $url, '.exe' ) !== false ) {
				// link is an executable
				return false;
			}
			if( strpos( $url, '.txt' ) !== false ) {
				// link is a text doc
				return false;
			}
			if( strpos( $url, '.swf' ) !== false ) {
				// link is a flash movie
				return false;
			}
			if( strpos( $url, '.doc' ) !== false ) {
				// link is a doc
				return false;
			}
			if( strpos( $url, '.xls' ) !== false ) {
				// link is a xls file
				return false;
			}
			if( strpos( $url, '.xml' ) !== false ) {
				// link is an xml file
				return false;
			}
			return true;
		}


		/**
		 * format URL
		 *
		 * @return string
		 * @access public
		 */
		protected function parseURL( $uri ) {
			if( strpos( $uri, 'http://' ) === false && strpos( $uri, 'https://' ) === false ) {
				// link is uri
				if( strpos( $uri, '/' ) === 0 ) {
					$url = $this->url . $uri;
				}
				else {
					$url = $this->url . '/' . $uri;
				}
			}
			else {
				// link is url
				$url = $uri;
			}

			return substr( $url, 0, 8 ) . str_replace( '//', '/', substr( $url, 8 ));
		}
	}
?>