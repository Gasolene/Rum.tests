<?php
    namespace MyApp\Controllers;
    use MyApp\App;

	class Report extends \MyApp\ApplicationController
	{
		function onPageInit( &$page, $args ) {
			

			$this->page->add( new \System\UI\WebControls\ReportView( 'report' ));
			$this->page->report->addGrouping( 'Name' );
			$this->page->report->addGrouping( 'Test', false );
			$this->page->report->addFilter( 'Score', '>', 0 );
			$this->page->report->addSorting( 'Score' );
		}

		function onPageLoad( &$page, $args ) {
			

			$da = \System\Data\DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Report.csv' );
			$ds = $da->openDataSet();

			// sync controls
			$this->report->dataSource = $ds;
		}


		/**
		 * generic method for handling report header
		 *
		 * @param  DataSet	$ds			DataSet object with current resultset
		 * @return void
		 */
		public function onReportHeader( \System\UI\WebControls\ReportView  &$report )
		{
			echo '<table class="datareport">';
			echo '<caption>' . $report->title . '</caption>';

			echo '<thead>';

			echo '<tr>';
			foreach( $report->dataSource->fields as $field ) {
				echo '<th>' . $field . '</th>';
			}
			echo '</tr>';

			echo '</thead>';
			echo '<tfoot>';
			echo '</tfoot>';

			echo '<tbody>';
		}


		/**
		 * generic method for handling report footer
		 *
		 * @param  DataSet	$ds			DataSet object with current resultset
		 * @return void
		 */
		public function onReportFooter( \System\UI\WebControls\ReportView  &$report )
		{
			echo '</tbody>';
			echo '</table>';
		}
	}
?>