<?php
    namespace MyApp\Controllers;

	class LookupControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
		}

		function cleanup() {
		}

		function testOnLoad() {
			$this->get();

			$this->assertResponse( "City_Suggest__async=true', 'POST', function() { SuggestBox.onResponse( 'MyList', 'Name of city', 'page_form_City_Suggest' ); } );SuggestBox.textValues['MyList']=document.getElementById('page_form_City_Suggest').value;document.getElementById('page_form_City_Suggest').value='Loading...';document.getElementById('page_form_City_Suggest').disabled=true;" );
			$this->assertResponse( "City_lookup__async=true', 'POST', function() { SuggestBox.onResponse( 'City_lookup', 'Name of city', 'page_form_City_lookup' ); } );SuggestBox.textValues['City_lookup']=document.getElementById('page_form_City_lookup').value;document.getElementById('page_form_City_lookup').value='Loading...';document.getElementById('page_form_City_lookup').disabled=true;" );

			$this->assertResponse( "onkeydown=\"if(document.getElementById('page_form_City_Suggest__lookup').style.display=='block')if(event.keyCode==13){return false;}\"" );
			$this->assertResponse( "onkeyup=\"SuggestBox.handleKeyUp(event.keyCode,20,true,document.getElementById('page_form_City_Suggest'),document.getElementById('page_form_City_Suggest__lookup'),'MyList',false,'');\"" );
		}

		function testPost() {
			$this->post( array( 'page_form_City_Suggest' => 'Calgary'
							, 'page_form_City_lookup' => 'Edmonton'
							, 'page_form_save' => 'Save' ));

			$this->assertResponse( "City_Suggest: Calgary" );
			$this->assertResponse( "City_lookup: EDM" );
		}

		function testInvalidPost() {
			$this->post( array( 'page_form_City_Suggest' => 'Calgary'
							, 'page_form_City_lookup' => 'Edmonton'
							, 'page_form_save' => 'Save' ));

			$this->assertResponse( "City_Suggest: Calgary" );
			$this->assertResponse( "City_lookup: EDM" );
		}
/**
		function testGetXML() {
			$this->get( array( 'page' => 'lookup', 'page_form_City_Suggest__async' => 'true' ));

			$this->assertEqual( $this->responseAsXmlEntity()->fields->children->count, 3 );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children->count, 21 );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[1]->children->count, 3 );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[2]->children[0]->getAttribute( 'name' ), 'code' );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[3]->children[1]->getAttribute( 'name' ), 'Name of city' );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[1]->children[0]->value, 'CLG' );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[1]->children[1]->value, ' Calgary ' );
		}

		function testPostXML() {
			$this->post( array( 'page' => 'lookup', 'page_form_City_Suggest__async' => 'true' ));

			$this->assertEqual( $this->responseAsXmlEntity()->fields->children->count, 3 );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children->count, 21 );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[1]->children->count, 3 );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[2]->children[0]->getAttribute( 'name' ), 'code' );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[3]->children[1]->getAttribute( 'name' ), 'Name of city' );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[1]->children[0]->value, 'CLG' );
			$this->assertEqual( $this->responseAsXmlEntity()->records->children[1]->children[1]->value, ' Calgary ' );
		}
 */
	}
?>