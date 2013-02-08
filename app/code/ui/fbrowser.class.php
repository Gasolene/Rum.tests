<?php	/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
	// +----------------------------------------------------------------------+
	// | PHP version 4.3.8, 4.3.9 (tested)                                    |
	// +----------------------------------------------------------------------+
	// | Copyright (c) 2005                                                   |
	// +----------------------------------------------------------------------+
	// | License:                                                             |
	// |                                                                      |
	// |                                                                      |
	// |                                                                      |
	// |                                                                      |
	// +----------------------------------------------------------------------+
	// | Version: 1.0.0                                                       |
	// | Modified: Apr 06, 2005                                               |
	// +----------------------------------------------------------------------+
	// | Author(s): Darnell Shinbine                                          |
	// |                                                                      |
	// +----------------------------------------------------------------------+
	//
	// $id$

    namespace MyApp\UI;

	/**
	 * TFbrowser
	 *
	 * Handles file browser control
	 * Preserves the application state
	 * 
     * @version  $id$
	 * @author	 Darnell Shinbine
	 * @date	 Feb 15, 2005
	 * @package  WebWidgets
	 */
	class FBrowser extends TreeView
	{
		/**
		 * Current path
		 * @access public
		 */
		var $path						= '';

		/**
		 * collection of file types to include
		 */
		var $filter;

		/**
		 * specifies whether on the file type filter is on
		 */
		var $filterOn					= false;


		/**
		 * __construct
		 *
		 * @return void
		 * @access public
		 */
		function __construct( $controlId, $path = __ROOT__ ) {
			parent::__construct( $controlId );

			$this->path = $path;
			$this->filter = new Collection;
		}


		/**
		 * onLoad
		 *
		 * called when control is loaded and processed
		 *
		 * @return bool			true if successfull
		 * @access public
		 */
		function onLoad() {
			parent::onLoad();

			$this->rootNode = $this->_getNode( $this->path );
		}


		/**
		 * _getNode
		 *
		 * returns tree node
		 *
		 * @param  string	$path				path root
		 * @return object	TreeNode
		 * @access public
		 */
		function & _getNode( $path, $file='root' ) {

			if( file_exists( $path )) {
				$dir = opendir( $path );
				if($dir) {

					// create TreeNode
					$TreeNode = new TreeNode( $file );
					$TreeNode->textOrHtml = $file;

					// loop through all files in directory level and add to tree
					while( $file = readdir( $dir )) {

						$newpath = "$path/$file"; // get path of current node

						if( $file != '.' && $file != '..' ) { // ignore parent folders

							// child is a folder so create new branch
							if( is_dir( $newpath )) {
								$childNode = $this->_getNode( $newpath, $file );
								if($childNode) { // recursive
									$TreeNode->addChild( $childNode );
									unset( $childNode );
								}
							}
							else
							{
								// child is a file so create new node
								if( $this->filter->find( substr( strrchr( $file, '.' ), 1, strlen( strrchr( $file, '.' )))) || !$this->filterOn ) { // check against accepted file types

									// get path to image source
									if( strrchr( $file, '.' ) == '.txt' ) {
										$imgSrc = $this->assets . '/treeview/text.gif';
									}
									elseif( strrchr( $file, '.' ) == '.jpg' ||
											strrchr( $file, '.' ) == '.jpeg' || 
											strrchr( $file, '.' ) == '.bmp' || 
											strrchr( $file, '.' ) == '.gif' || 
											strrchr( $file, '.' ) == '.tif' || 
											strrchr( $file, '.' ) == '.png' ) {
										$imgSrc = $this->assets . '/treeview/image.gif';
									}
									elseif( strrchr( $file, '.' ) == '.pdf' ) {
										$imgSrc = $this->assets . '/treeview/pdf.gif';
									}
									elseif( strrchr( $file, '.' ) == '.html' ||
											strrchr( $file, '.' ) == '.htm' || 
											strrchr( $file, '.' ) == '.xml' || 
											strrchr( $file, '.' ) == '.xsl' || 
											strrchr( $file, '.' ) == '.asp' || 
											strrchr( $file, '.' ) == '.php' ) {
										$imgSrc = $this->assets . '/treeview/web.gif';
									}
									elseif( strrchr( $file, '.' ) == '.doc' ||
											strrchr( $file, '.' ) == '.rtf' ) {
										$imgSrc = $this->assets . '/treeview/word.gif';
									}
									elseif( strrchr( $file, '.' ) == '.zip' ||
											strrchr( $file, '.' ) == '.rar' ) {
										$imgSrc = $this->assets . '/treeview/zip.gif';
									}
									else {
										$imgSrc = $this->assets . '/treeview/unknown.gif';
									}

									$childNode = new TreeNode( $file );
									$childNode->textOrHtml = $file;
									$childNode->imgSrc = $imgSrc;
									$TreeNode->addChild( $childNode );
									unset( $childNode );
								}
							}
						}
					}
					closedir( $dir );
					return $TreeNode;
				}
			}
			return FALSE;
		}
	}
?>