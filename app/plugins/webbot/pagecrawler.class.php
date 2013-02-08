<?php
	/**
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	namespace Webbot;

	/**
	 * crawls individual pages and parses the response
	 *
     * @property-read string $url
     * @property-read string $httpStatus
     * @property-read string $response
     * @property-read int $elapsed
     * 
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 * @version			1.0.0
	 * @since			4.6.0
	 * @package			PHPRum
	 * @subpackage		Webbot
	 */
	class PageCrawler {

		/**
		 * specifies url
		 * @access protected
		 */
		protected $url					= '';

		/**
		 * contains http status
		 * @access protected
		 */
		protected $httpStatus			= '';

		/**
		 * contains http response
		 * @access protected
		 */
		protected $response				= '';

		/**
		 * contains elapsed time
		 * @access protected
		 */
		protected $elapsed				= 0;


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
			elseif( $field === 'httpStatus' ) {
				return $this->httpStatus;
			}
			elseif( $field === 'response' ) {
				return $this->response;
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
		function crawl( $url ) {

			$timer = new \System\Utils\Timer(true);
			$this->url = $url;

			$httpWebRequest = new \System\Comm\HTTPWebRequest();
			$httpWebRequest->method = 'GET';
			$httpWebRequest->referer = $this->url;
			$httpWebRequest->url = $this->url;
			$httpWebResponse = $httpWebRequest->getResponse();

			// handle errors

			$this->httpStatus  = $httpWebResponse->httpStatus;
			$this->contentType = $httpWebResponse->contentType;
			$this->response    = $httpWebResponse->content;
			$this->elapsed     = $timer->elapsed();
		}


		/**
		 * search result for a string pattern
		 *
		 * @param  string		$needle		string to find
		 *
		 * @return int						number of matches found
		 * @access public
		 */
		function search( $needle ) {
			return strpos( str_replace( "\n", '', str_replace( "\r", '', $this->response )), $needle );
		}


		/**
		 * search result for a hyperlink
		 *
		 * @param  string		$url		url to find
		 *
		 * @return bool						TRUE if text found
		 * @access public
		 */
		function searchLink( $url ) {

			if( $this->response ) {

				$url  = str_replace( '://www.', '://', $url ); // remove www
				$host = preg_replace( "`(http|ftp)+(s)?:(//)((\w|\.|\-|_)+)(/)?(\S+)?`i", "\\4", $url ); // extract host
				$path = str_replace( 'http://', '', str_replace( $host, '', $url )); // extract path

				// find pattern - support for https implemented but not tested
				$pattern = "^<a(.*?)href( *)=( *)[\\\"'](http|https)://(www.)?$host$path(.*?)[\\\"'](.*?)>(.+?)</a>^i";
				return preg_match( $pattern, str_replace( "\n", '', str_replace( "\r", '', $this->response )));
			}
			return false;
		}


		/**
		 * get all anchor tags on page
		 *
		 * @return StringDictionary		Dictionary of links
		 * @access public
		 */
		function getLinks()
		{
			if( $this->response )
			{
				preg_match_all( "/<a.*?href *= *[\\\"'](.*?)[\\\"'].*?>(.*?)<\\/a>/i", str_replace( "\r", ' ', str_replace( "\n", ' ', $this->response )), $urls );
				return $urls;
			}
			else
			{
				return array();
			}
		}
	}
?>