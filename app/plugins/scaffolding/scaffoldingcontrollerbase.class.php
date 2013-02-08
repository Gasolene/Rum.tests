<?php
	/**
	 * @license			see /docs/license.txt
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 * @copyright		Copyright (c) 2011
	 */
	namespace Scaffolding;


	/**
	 * action parameter
	 * @var string
	 */
	const ACTIONPARAM = 'id';

	/**
	 * list action
	 * @var string
	 */
	const LISTACTION = 'list';

	/**
	 * add action
	 * @var string
	 */
	const ADDACTION = 'add';

	/**
	 * edit action
	 * @var string
	 */
	const EDITACTION = 'edit';

	/**
	 * delete action
	 * @var string
	 */
	const DELETEACTION = 'delete';


	/**
	 * Provides the interface for ActiveRecord Scaffolding
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	interface IScaffolding {}

	/**
	 * Provides base functionality for ActiveRecord Scaffolding actions: list, add, edit, delete
	 *
	 * @package			PHPRum
	 * @author			Darnell Shinbine
	 */
	class ScaffoldingControllerBase extends \System\Controllers\PageControllerBase
	{
		/**
		 * specifies a model name to provide scaffold functionality
		 * @var string
		 */
		protected $scaffold				= '';

		/**
		 * specifies whether to allow associative mapping
		 * @var bool
		 */
		protected $assocMapping			= false;

		/**
		 * specifies whether to show primary key
		 * @var bool
		 */
		protected $showPrimaryKey		= false;

		/**
		 * specifies action for active scaffolding
		 * @var string
		 */
		protected $action				= LISTACTION;

		/**
		 * specifies record id for active scaffolding
		 * @var mixed
		 */
		protected $pid					= null;

		/**
		 * instance of a GridView
		 * @var GridView
		 */
		protected $list					= null;

		/**
		 * instance of a Form
		 * @var Form
		 */
		protected $form					= null;

		/**
		 * contains an instance of the ActiveRecordBase object
		 * @var ActiveRecordBase
		 */
		private $activeRecord			= null;

		/**
		 * contains an instance of the DataSet object
		 * @var DataSet
		 */
		private $dataSet				= null;

		/**
		 * contains an array of associations
		 * @var array
		 */
		private $associations			= array();

		/**
		 * specifies the name of the Form control
		 * @var string
		 */
		private $_form					= '';

		/**
		 * specifies the name of the GridView control
		 * @var string
		 */
		private $_gridview				= '';

		/**
		 * specifies the name of the Active Record class
		 * @var string
		 */
		private $_activeRecordClass		= '';


		/**
		 * This method will process the request and impliment the page life cycles
		 *
		 * @param   HTTPRequest		&$request	HTTPRequest object
		 * @return  void
		 */
		final public function requestProcessor( \System\Web\HTTPRequest &$request )
		{
			if( $this instanceof IScaffolding )
			{
				if( strrchr( $this->scaffold, '\\' ) !== false )
				{
					$this->_activeRecordClass = substr( strrchr( $this->scaffold, '\\'), 1 );
				}
				else
				{
					$this->_activeRecordClass = $this->scaffold;
				}

				$this->_gridview =  $this->_activeRecordClass . '_list';
				$this->_form = $this->_activeRecordClass . '_details';

				if( isset( $request[ACTIONPARAM] ))
				{
					$this->action = $request[ACTIONPARAM];
				}
				if( isset( $request[$this->_activeRecordClass] ))
				{
					$this->pid = $request[$this->_activeRecordClass];
				}

				// set controllerId for view state handling
				$this->controllerId .= '.' . $this->action;

				if( class_exists( $this->scaffold ))
				{
					eval( '$this->dataSet = ' . "{$this->scaffold}::all();" );

					if( $this->action === EDITACTION || $this->action === DELETEACTION )
					{
						if( in_array( 'findById', get_class_methods( $this->scaffold )))
						{
							eval( '$this->activeRecord = ' . $this->scaffold . '::findById( ' . $this->pid . ');' );

							if( is_null( $this->activeRecord ))
							{
								\System\Web\WebApplicationBase::getInstance()->sendHTTPError( 400 ); // bad request
							}
						}
						else
						{
							throw new \System\Base\BadMethodCallException("call to protected method {$this->scaffold}::findById()");
						}
					}
					else
					{
						eval( '$this->activeRecord = ' . $this->scaffold . '::create();' );
					}

					foreach( $this->activeRecord->associations as $mapping )
					{
						if( $mapping['relationship'] == \System\ActiveRecord\RelationshipType::BelongsTo()->__toString() )
						{
							$this->associations[] = $mapping;
						}
						elseif( $mapping['relationship'] == \System\ActiveRecord\RelationshipType::HasManyAndBelongsTo()->__toString() )
						{
							$this->associations[] = $mapping;
						}
					}
				}
				else
				{
					throw new \System\Base\InvalidOperationException("class {$this->scaffold} does not exist");
				}
			}

			parent::requestProcessor( $request );
		}


		/**
		 * return view component for rendering
		 *
		 * @return  Page
		 */
		final public function getView()
		{
			if( $this instanceof IScaffolding )
			{
				if( !file_exists( $this->page->template ))
				{
					if( $this->page->hasControl( $this->_form ))
					{
						$this->page->setData( $this->form->fetch() );
					}
					elseif( $this->page->hasControl( $this->_gridview ))
					{
						$this->page->setData( $this->list->fetch() );
					}
				}
			}

			return $this->page;
		}


		/**
		 * event called on page initialization
		 *
		 * Called before Viewstate is loaded and Request is loaded and Post events are handled.
		 * Use this method to create the page components and set their relationships and default values.
		 *
		 * This method should not contain dynamic content as it may be cached for performance.
		 * This method should be idempotent as it invoked every page request.
		 *
		 * @param   HTTPRequest		&$request		HTTPRequest Object
		 * @return  void
		 */
		public function onPageInit( &$sender, $args )
		{
			if( $this instanceof IScaffolding )
			{
				/**
				 * Action: List
				 *
				 * List all records using static method records()
				 */
				if( $this->action === LISTACTION )
				{
					$this->list = new \System\Web\WebControls\GridView( $this->_gridview );
					$this->page->add( $this->list );
					$this->list->caption = 'List ' . $this->_activeRecordClass . ' Records';

					for( $i = 0, $count = count($this->dataSet->fieldMeta); $i < $count; $i++ )
					{
						if( strpos( $this->dataSet->fieldMeta[$i]->name, '.' ) === false )
						{
							foreach( $this->associations as $mapping )
							{
								if( $this->dataSet->fieldMeta[$i]->name === $mapping['columnKey'] )
								{
									continue 2;
								}
							}

							if( $this->dataSet->fieldMeta[$i]->primaryKey )
							{
								if( !$this->showPrimaryKey )
								{
									continue;
								}
							}

							if( $this->dataSet->fieldMeta[$i]->boolean )
							{
								$this->list->addColumn( new \System\Web\WebControls\GridViewColumn( $this->dataSet->fieldMeta[$i]->name, ucwords( str_replace( '_', ' ', str_replace( $this->activeRecord->prefix, '', $this->dataSet->fieldMeta[$i]->name ))), '%' . $this->dataSet->fieldMeta[$i]->name . '%?\'Yes\':\'No\'' ));
							}
							elseif( $this->dataSet->fieldMeta[$i]->date )
							{
								$this->list->addColumn( new \System\Web\WebControls\GridViewColumn( $this->dataSet->fieldMeta[$i]->name, ucwords( str_replace( '_', ' ', str_replace( $this->activeRecord->prefix, '', $this->dataSet->fieldMeta[$i]->name ))), 'date(\'F j, Y\',strtotime(%' . $this->dataSet->fieldMeta[$i]->name . '%))' ));
							}
							elseif( $this->dataSet->fieldMeta[$i]->time )
							{
								$this->list->addColumn( new \System\Web\WebControls\GridViewColumn( $this->dataSet->fieldMeta[$i]->name, ucwords( str_replace( '_', ' ', str_replace( $this->activeRecord->prefix, '', $this->dataSet->fieldMeta[$i]->name ))), 'date(\'g:ia\',strtotime(%' . $this->dataSet->fieldMeta[$i]->name . '%))' ));
							}
							elseif( $this->dataSet->fieldMeta[$i]->datetime )
							{
								$this->list->addColumn( new \System\Web\WebControls\GridViewColumn( $this->dataSet->fieldMeta[$i]->name, ucwords( str_replace( '_', ' ', str_replace( $this->activeRecord->prefix, '', $this->dataSet->fieldMeta[$i]->name ))), 'date(\'F j, Y g:ia\',strtotime(%' . $this->dataSet->fieldMeta[$i]->name . '%))' ));
							}
							elseif( $this->dataSet->fieldMeta[$i]->blob )
							{
								continue;
							}
							else
							{
								$this->list->addColumn( new \System\Web\WebControls\GridViewColumn( $this->dataSet->fieldMeta[$i]->name, ucwords( str_replace( '_', ' ', str_replace( $this->activeRecord->prefix, '', $this->dataSet->fieldMeta[$i]->name )))));
							}
						}
					}
				}

				/**
				 * Action: Add|Edit
				 *
				 * Create form bound to ActiveRecord
				 */
				elseif( $this->action === ADDACTION || $this->action === EDITACTION )
				{
					$this->form = new \System\Web\WebControls\Form( $this->_form );
					$this->page->add( $this->form );
					$this->form->add( new \System\Web\WebControls\Fieldset( 'fieldset' ));

					foreach( $this->dataSet->fieldMeta as $field )
					{
						// create lookup controls
						foreach( $this->associations as $mapping )
						{
							if( $field->name === $mapping['columnKey'] )
							{
								$this->form->fieldset->add( new \System\Web\WebControls\DropDownList( $field->name ));
								$this->form->fieldset->getControl( $field->name )->label = ucwords( $this->_getTypeClass( $mapping['type'] ));

								if( !$field->notNull )
								{
									$this->form->fieldset->getControl( $field->name )->items->add( '', '' );
								}

								continue 2;
							}
						}

						// create Form controls
						if( $field->autoIncrement )
						{
							continue;
						}
						elseif( $field->datetime )
						{
							$this->form->fieldset->add( new \System\Web\WebControls\TextBox( $field->name ));
						}
						elseif( $field->boolean )
						{
							$this->form->fieldset->add( new \System\Web\WebControls\CheckBox( $field->name ));
						}
						elseif( $field->numeric )
						{
							$this->form->fieldset->add( new \System\Web\WebControls\TextBox( $field->name ));
							$this->form->fieldset->getControl( $field->name )->errorMessage = ucwords( $this->form->fieldset->getControl( $field->name )->label ) . ' must be numeric';
						}
						elseif( $field->blob )
						{
							if( $field->binary )
							{
								$this->form->fieldset->add( new \System\Web\WebControls\FileBrowser( $field->name ));
							}
							else
							{
								$this->form->fieldset->add( new \System\Web\WebControls\TextBox( $field->name ));
								$this->form->fieldset->getControl( $field->name )->multiline = true;
							}
						}
						else
						{
							$this->form->fieldset->add( new \System\Web\WebControls\TextBox( $field->name ));
						}

						$this->form->fieldset->getControl( $field->name )->label = ucwords( str_replace( '_', ' ', str_replace( $this->activeRecord->prefix, '', $field->name )));

						if( $field->notNull )
						{
							// $this->form->fieldset->getControl( $field->name )->required = true;
						}
					}

					// create associative controls
					if( $this->assocMapping )
					{
						foreach( $this->associations as $mapping )
						{
							if($mapping['relationship'] == \System\ActiveRecord\RelationshipType::HasManyAndBelongsTo()->__toString())
							{
								$this->form->fieldset->add( new \System\Web\WebControls\ListBox( $mapping['columnRef'] ));

								$this->form->fieldset->getControl( $mapping['columnRef'] )->dataField = $mapping['columnRef'];
								$this->form->fieldset->getControl( $mapping['columnRef'] )->multiple  = true;
								$this->form->fieldset->getControl( $mapping['columnRef'] )->listSize  = 5;
								$this->form->fieldset->getControl( $mapping['columnRef'] )->label	 = ucwords( str_replace( '_', ' ', $this->_getTypeClass( $mapping['type'] )));
							}
						}
					}

					// create button controls
					$this->form->add( new \System\Web\WebControls\Button( 'save' ));
					$this->form->add( new \System\Web\WebControls\Button( 'cancel', 'Cancel' ));

					if( $this->action === ADDACTION )
					{
						$this->form->fieldset->legend = 'Add ' . $this->_activeRecordClass . ' Record';
						$this->form->getControl( 'save' )->text = 'Add';
					}
					elseif( $this->action === EDITACTION )
					{
						$this->form->fieldset->legend = 'Update ' . $this->_activeRecordClass . ' Record';
						$this->form->getControl( 'save' )->text = 'Update';
					}
				}
			}
		}


		/**
		 * event called on page load
		 *
		 * Called after Viewstate is loaded but before Request is loaded and Post events are handled.
		 * Use this method to bind components and set component values.
		 *
		 * This method should be idempotent as it invoked every page request.
		 *
		 * @param   HTTPRequest		&$request		HTTPRequest Object
		 * @return  void
		 */
		public function onPageLoad( &$sender, $args )
		{
			if( $this instanceof IScaffolding )
			{
				/**
				 * Action: List
				 *
				 * List all records using static method records()
				 */
				if( $this->action === LISTACTION )
				{
					// bind datagrid
					$this->list->dataSource = $this->dataSet;

					// add, edit, delete columns
					$this->list->addColumn( new \System\Web\WebControls\GridViewColumn( '', '', "'<a href=\"' . htmlentities( \System\Web\WebApplicationBase::getInstance()->getPageURI( '', array( '" . ACTIONPARAM . "' => '" . EDITACTION . "', '" . $this->_activeRecordClass . "' => %{$this->activeRecord->pkey}% ))) . '\">Edit</a>'", '<a href="' . htmlentities( \System\Web\WebApplicationBase::getInstance()->getPageURI( '', array( ACTIONPARAM => ADDACTION ))) . '">Add</a>', 'width:100px;', 'width:100px;', 'width:100px;' ));
					$this->list->addColumn( new \System\Web\WebControls\GridViewColumn( '', '', "'<a onclick=\"if(!confirm(\'Are you sure you want to delete this " . $this->_activeRecordClass . "?\'))return false;\" href=\"' . htmlentities( \System\Web\WebApplicationBase::getInstance()->getPageURI( '', array( '" . ACTIONPARAM . "' => '" . DELETEACTION . "', '" . $this->_activeRecordClass . "' => %{$this->activeRecord->pkey}% ))) . '\">Delete</a>'", '', 'width:100px;', 'width:100px;' ));
					$this->list->ondblclick = "document.location='".\System\Web\WebApplicationBase::getInstance()->getPageURI('')."?".ACTIONPARAM."=".EDITACTION."&".$this->_activeRecordClass."=%{$this->activeRecord->pkey}%'";
					$this->list->showFooter = true;
				}

				/**
				 * Action: Add|Edit
				 *
				 * Bind form
				 */
				elseif( $this->action === ADDACTION || ( $this->action === EDITACTION ))
				{
					foreach( $this->dataSet->fieldMeta as $field )
					{
						// bind lookup controls
						foreach( $this->associations as $mapping )
						{
							if( $field->name === $mapping['columnKey'] )
							{
								$dataSet = null;

								eval( '$dataSet = ' . "{$mapping['type']}::all();" );

								if( $this->form->fieldset->getControl( $field->name ))
								{
									foreach( $dataSet->rows as $row )
									{
										// get item text
										$text = '';
										$count=0;
										foreach( $dataSet->fieldMeta as $dataField )
										{
											if( !$dataField->numeric &&
												!$dataField->boolean &&
												!$dataField->blob &&
												!$dataField->primaryKey )
											{
												if( $text )
												{
													$text .= ', ' . $row[$dataField->name];
												}
												else
												{
													$text .= $row[$dataField->name];
												}

												// limit 3
												if(($count++)==3) break;
											}
										}

										if( !$this->form->fieldset->getControl( $field->name )->items->contains( $text ))
										{
											$this->form->fieldset->getControl( $field->name )->items->add( $text, $row[$mapping['columnRef']] );
										}
									}
								}

								continue 2;
							}
						}
					}

					// bind associative controls
					if( $this->assocMapping )
					{
						$dataSet = null;

						foreach( $this->associations as $mapping )
						{
							if( $mapping['relationship'] == \System\ActiveRecord\RelationshipType::HasManyAndBelongsTo()->__toString())
							{
								eval( '$dataSet = ' . "{$mapping['type']}::all();" );

								foreach( $dataSet->rows as $row )
								{
									// get item text
									$text = '';
									foreach( $dataSet->fieldMeta as $dataField )
									{
										if( !$dataField->numeric &&
											!$dataField->boolean &&
											!$dataField->blob &&
											!$dataField->primaryKey )
										{
											if( $text )
											{
												$text .= ', ' . $row[$dataField->name];
											}
											else
											{
												$text .= $row[$dataField->name];
											}
										}
									}

									if( !$this->form->fieldset->getControl( $mapping['columnRef'] )->items->contains( $text ))
									{
										if( isset( $row[$mapping['columnRef']] ))
										{
											$this->form->fieldset->getControl( $mapping['columnRef'] )->items->add( $text, $row[$mapping['columnRef']] );
										}
										elseif( isset( $row[$mapping['columnKey']] ))
										{
											$this->form->fieldset->getControl( $mapping['columnRef'] )->items->add( $text, $row[$mapping['columnKey']] );
										}
									}
								}

								// eval( '$assocDataSet = $this->activeRecord->get' . $mapping['type'] . 'DataSet();' );
								$assocDataSet = $this->dataSet->dataAdapter->queryBuilder()
									->select()
									->from($mapping['table'])
									->where($mapping['table'], $mapping['columnKey'], '=', (int) $this->activeRecord[$mapping['columnKey']])->openDataSet();

								$values = array();
								foreach( $assocDataSet->rows as $row )
								{
									$values[] = $row[$mapping['columnRef']];
								}

								$this->form->fieldset->getControl( $mapping['columnRef'] )->value = $values;
							}
						}
					}

					// bind Form
					$this->form->dataSource = $this->activeRecord;
				}
			}
		}


		/**
		 * event called on page request
		 *
		 * Called after Viewstate is loaded and Request is loaded but before Post events are handled.
		 * Use this method to perform operations using the request data.
		 *
		 * This method should be idempotent as it invoked every page request.
		 *
		 * @param   HTTPRequest		&$request		HTTPRequest Object
		 * @return  void
		 */
		public function onPageRequest( &$sender, $args )
		{
			if( $this instanceof IScaffolding )
			{
				if( $this->action === DELETEACTION )
				{
					$fail = false;
					foreach( $this->activeRecord->associations as $mapping )
					{
						if( $mapping['relationship'] == \System\ActiveRecord\RelationshipType::HasMany()->__toString() )
						{
							$count = 0;
							eval( '$count = $this->activeRecord->getAll'.$this->_getTypeClass( $mapping['type'] ).'Records()->count;' );

							if( $count > 0 )
							{
								$fail = true;
								\System\Base\ApplicationBase::getInstance()->messages->add( new \System\Base\AppMessage( str_replace('%x', ucwords( str_replace( '_', ' ', $this->_activeRecordClass )), str_replace('%y', $this->_getTypeClass( $mapping['type'] ), \System\Base\ApplicationBase::getInstance()->translator->get('cannot_delete_x_there_are_y_records_associated_with_this_record', 'Cannot delete %x, there are %y records associated with this record'))), \System\Base\AppMessageType::Fail() ));
							}
						}
						elseif( $mapping['relationship'] == \System\ActiveRecord\RelationshipType::HasManyAndBelongsTo()->__toString() )
						{
							eval( '$this->activeRecord->removeAll'.$this->_getTypeClass( $mapping['type'] ).'Records();' );
						}
					}

					\System\Web\WebApplicationBase::getInstance()->setForwardPage( '', array( ACTIONPARAM => LISTACTION ));

					if( !$fail )
					{
						try
						{
							$this->activeRecord->delete();
						}
						catch( \System\Data\SQLException $e )
						{
							throw new \System\Data\SQLException( $e->getMessage() );
						}

						\System\Base\ApplicationBase::getInstance()->messages->add( new \System\Base\AppMessage( ucwords( str_replace( '_', ' ', $this->_activeRecordClass )) . ' ' . \System\Base\ApplicationBase::getInstance()->translator->get('deleted', 'Deleted'), \System\Base\AppMessageType::Success() ));
					}
				}
			}
		}


		/**
		 * event called on a post event
		 *
		 * Called after view state is loaded and request is loaded but before GUI events are handled
		 * and only if Post data is present
		 *
		 * @param   HTTPRequest		&$request		HTTPRequest Object
		 * @return  void
		 */
		public function onPagePost( &$sender, $args )
		{
			if( $this instanceof IScaffolding )
			{
				if( $this->action === ADDACTION || ( $this->action === EDITACTION ))
				{
					if( $this->form->getControl( 'cancel' )->submitted )
					{
						\System\Web\WebApplicationBase::getInstance()->setForwardPage( '', array( ACTIONPARAM => LISTACTION ));
					}
					elseif( $this->form->submitted &&
							$this->form->getControl( 'save' )->submitted )
					{
						if( $this->form->validate() )
						{
							foreach( $this->dataSet->fieldMeta as $field )
							{
								if( $this->form->fieldset->hasControl( $field->name ))
								{
									$input = $this->form->fieldset->getControl( $field->name );

									if( $input instanceof \System\Web\WebControls\FileBrowser )
									{
										$info = $input->getFileInfo();

										if( $info['size'] > 0 )
										{
											$this->activeRecord[$field->name] = $input->getFileRawData();
										}
									}
									else
									{
										$this->activeRecord[$field->name] = $input->value;
									}
								}
							}

							try
							{
								$this->activeRecord->save();
							}
							catch( \System\Data\SQLException $e )
							{
								throw new \System\Data\SQLException( $e->getMessage() );
							}

							\System\Web\WebApplicationBase::getInstance()->setForwardPage( '', array( ACTIONPARAM => LISTACTION ));

							if( $this->action === EDITACTION )
							{
								\System\Base\ApplicationBase::getInstance()->messages->add( new \System\Base\AppMessage( ucwords( str_replace( '_', ' ', $this->scaffold )) . ' ' . \System\Base\ApplicationBase::getInstance()->translator->get('updated', 'Updated'), \System\Base\AppMessageType::Success() ));
							}
							else
							{
								\System\Base\ApplicationBase::getInstance()->messages->add( new \System\Base\AppMessage( ucwords( str_replace( '_', ' ', $this->scaffold )) . ' ' . \System\Base\ApplicationBase::getInstance()->translator->get('added', 'Added'), \System\Base\AppMessageType::Success() ));
							}

							// update associative records
							if( $this->assocMapping )
							{
								foreach( $this->associations as $mapping )
								{
									if( $mapping['relationship'] == \System\ActiveRecord\RelationshipType::HasManyAndBelongsTo()->__toString() )
									{
										$activeRecord = null;

										if( $this->form->fieldset->getControl( $mapping['columnRef'] ))
										{
											$values = $this->form->fieldset->getControl( $mapping['columnRef'] )->value;

											// remove existing associations
											$query = $this->dataSet->dataAdapter->queryBuilder();
											$query->delete();
											$query->from($mapping['table']);
											$query->where($mapping['table'], $mapping['columnKey'], '=', (int) $this->activeRecord[$mapping['columnKey']]);
											$this->dataSet->dataAdapter->execute($query->getQuery());

											foreach( $values as $value )
											{
												$query = $this->dataSet->dataAdapter->queryBuilder();
												$query->insertInto($mapping['table'], array($mapping['columnKey'], $mapping['columnRef']));
												$query->values(array((int) $this->activeRecord[$mapping['columnKey']], (int) $value));

												// $this->dataSet->dataAdapter->execute("insert into `{$mapping['table']}` (`{$mapping['columnKey']}`, `{$mapping['columnRef']}`) values(" . (int) $this->activeRecord[$mapping['columnKey']] . ", " . (int)$value . ")" );
												$this->dataSet->dataAdapter->execute($query->getQuery());
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}


		/**
		 * checks if action is allowed
		 *
		 * @return  bool
		 */
		private function _getTypeClass( $type )
		{
			if( \strrchr( $type, '\\' ))
			{
				return \substr( strrchr( $type, '\\'), 1 );
			}
			else
			{
				return $type;
			}
		}
	}
?>