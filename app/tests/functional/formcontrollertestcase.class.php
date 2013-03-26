<?php
    namespace MyApp\Controllers;
    use MyApp\App;
    use System\Data\DataAdapter;

	class FormControllerTestCase extends \System\Testcase\ControllerTestCaseBase {

		function prepare() {
			// copy blank CSV file
			copy( \Rum::config()->fixtures . '/Address Book.csv', __ROOT__ . '/app/data/Address Book.csv' );
		}

		function cleanup() {
		}

		function testOutput() {
			$this->get();

			$html = $this->responseAsXMLEntity();

			$this->assertTrue( $html->body->div->getChildByAttribute('id', 'body')->div->form->getChildrenByName( 'input' )->count >= 1 );
			$this->assertTrue( $html->body->div->getChildByAttribute('id', 'body')->div->form->getChildrenByName( 'input' )->count <= 2 );
			$this->assertEqual( $html->body->div->getChildByAttribute('id', 'body')->div->form->getChildByAttribute( 'name', \Rum::config()->requestParameter )->getAttribute( 'type' ), 'hidden' );
			//$this->assertResponse( \Rum::config()->themes );
			$this->assertResponse( '<legend><span>Sample Fieldset</span></legend>' );
			$this->assertResponse( ' my_class1' );
			$this->assertResponse( ' my_class2' );
			$this->assertResponse( 'Enter your name here tooltip' );
			$this->assertResponse( 'textbox datepicker' );
			$this->assertResponse( 'textbox colorpicker' );
			$this->assertResponse( 'dateselector_month' );
			$this->assertResponse( 'timeselector_hour' );
			$this->assertResponse( 'dateselector_month' );
			$this->assertResponse( '<label class=" required" for="page_form_fieldset1_City">' );
		}

		function testFocus() {
			$this->get();

			// default focus to first control
			$this->assertResponse( 'Rum.id(\'page_form_fieldset1_Address\').focus();' );

			$this->post( array( 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_Name'=>'foo', 'page_form_fieldset1_Address'=>'12345', 'page_form_fieldset1_City' => 'Boston', 'page_form_fieldset1_Province__post' => '1', 'page_form_fieldset1_Province' => 'CA', 'page_form_fieldset2_title' => 'Mr', 'page_form_fieldset1_E-Mail_Address' => 'a@b.c', 'page_form_fieldset2_Birthday' => '2005-09-32' ));

			// default focus to first invalid control
			$this->assertResponse( 'Rum.id(\'page_form_fieldset1_E-Mail_Address\').focus();' );
		}

		function testDefaults() {
			$this->get();

            // test for radio default
			$this->assertResponse( 'value="f" type="radio" checked="checked" />' );
		}

		function testCheckBoxList() {
			$this->post( array( 'page_form__gotcha'=>'', 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_fieldset2_favoritecolors__post' => '1', 'page_form_fieldset2_title' => 'Mr', 'page_form_fieldset2_favoritecolors' => array( '#00FF00', '#0000FF' )));

			$this->assertResponse( 'value="#FF0000" title="" type="checkbox" name="page_form_fieldset2_favoritecolors[]"' );
			$this->assertResponse( 'value="#00FF00" title="" type="checkbox" name="page_form_fieldset2_favoritecolors[]" checked="checked"' );
			$this->assertResponse( 'value="#0000FF" title="" type="checkbox" name="page_form_fieldset2_favoritecolors[]"' );
		}

		function testState() {
			$this->post( array( 'page_form'.\System\Web\WebControls\GOTCHAFIELD=>'', 'page_form__submit' => '1', 'page_form_fieldset1_Address' => '555' ));

			$this->get();

			$this->assertEqual( $this->controller->Address->value, '555' );
		}

		function testValidation() {
			$this->post( array( 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_fieldset1_E-Mail_Address' => 'a@b.c', 'page_form_fieldset2_Birthday' => '2005-09-32', 'page_form_fieldset1_Province__post' => '1', 'page_form_fieldset1_Province' => '' ));

			// test validate()
			$err = '';
			$this->assertFalse( $this->controller->form->fieldset1->getControl( 'E-Mail_Address' )->validate($err) );
			$this->assertEqual(trim($err), 'E-Mail Address must be a valid email address');

			// test messages
			$this->assertResponse( 'You must enter a name!' );
			$this->assertResponse( 'E-Mail Address must be a valid email address' );
			$this->assertResponse( 'valid date' );
			$this->assertResponse( 'blast off' );
		}

		function testEvents() {
			$this->get();

			$this->assertFalse( $this->controller->Name->submitted );
			$this->assertFalse( $this->controller->Address->submitted );
			$this->assertFalse( $this->controller->City->submitted );
			$this->assertFalse( $this->controller->save->submitted );
			$this->assertFalse( $this->controller->Name->changed );
			$this->assertFalse( $this->controller->Address->changed );
			$this->assertFalse( $this->controller->City->changed );
			$this->assertFalse( $this->controller->save->changed );

			$this->post( array( 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_Name' => 'foo-bar', 'page_form_fieldset1_Address' => '1234', 'page_form_save' => 'Save' ));

			$this->assertMessage( 'Name was changed' );

			$this->assertTrue ( $this->controller->Name->submitted );
			$this->assertTrue ( $this->controller->Address->submitted );
			$this->assertFalse( $this->controller->City->submitted );
			$this->assertTrue ( $this->controller->save->submitted );
			$this->assertTrue ( $this->controller->Name->changed );
			$this->assertTrue ( $this->controller->Address->changed );
			$this->assertFalse( $this->controller->City->changed );
			$this->assertTrue ( $this->controller->save->changed );

			$this->post( array( 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_Name' => 'xfoo-bar', 'page_form_fieldset1_Address' => '1234', 'page_form_save' => 'Save' ));

			$this->assertTrue ( $this->controller->Name->submitted );
			$this->assertTrue ( $this->controller->Address->submitted );
			$this->assertFalse( $this->controller->City->submitted );
			$this->assertTrue ( $this->controller->save->submitted );
			$this->assertTrue ( $this->controller->Name->changed );
			$this->assertFalse( $this->controller->Address->changed );
			$this->assertFalse( $this->controller->City->changed );
			// $this->assertFalse( $this->controller->save->changed );
		}

		function testInsert() {
			$this->post( array( 'page_form__submit' => '1'
                                , 'page_form__gotcha' => ''
								, 'page_form_fieldset1_Province__post' => '1'
								, 'page_form_fieldset1_Country__post' => '1'
								, 'page_form_Name' => 'George'
								, 'page_form_fieldset1_Address' => '7 45ST SW'
								, 'page_form_fieldset1_City' => 'Springfield'
								, 'page_form_fieldset1_Province' => 'AB'
								, 'page_form_fieldset1_Country' => 'CA'
								, 'page_form_fieldset1_Postal_Zip_Code' => 'T4t-4t4'
								, 'page_form_fieldset1_Sex' => 'm'
								, 'page_form_fieldset1_Phone_No' => '4035551234'
								, 'page_form_fieldset1_E-Mail_Address' => 'a@b.ca'
								, 'page_form_fieldset2_Birthday' => '1980-12-30'
								, 'page_form_fieldset2_Favorite_Color' => '#ff0000'
								, 'page_form_fieldset2_Active' => '1'
								, 'page_form_fieldset2_Date__month' => '01'
								, 'page_form_fieldset2_Date__day' => '01'
								, 'page_form_fieldset2_Date__year' => '2007'
								, 'page_form_fieldset2_Date__null' => '1'
								, 'page_form_fieldset2_Time__hour' => '3'
								, 'page_form_fieldset2_Time__minute' => '15'
								, 'page_form_fieldset2_Time__meridiem' => 'pm'
								, 'page_form_fieldset2_Time__null' => '1'
								, 'page_form_fieldset2_title' => 'Mr'
								, 'page_form_fieldset2_title__post' => '1'
								));

			// test messages
			$this->assertMessage( 'record saved', \System\Base\AppMessageType::Success() );

			// test forward
			$this->assertRedirectedTo( 'form' );

			// test CSV
			$db = DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Address Book.csv' );
			$rs = $db->openDataSet( 'Address Book.csv' );

			$this->assertEqual( $rs->count, 1 );
			$this->assertEqual( $rs->row['Name'], 'George' );
			$this->assertEqual( $rs->row['Address'], '7 45ST SW' );
			$this->assertEqual( $rs->row['City'], 'Springfield' );
			$this->assertEqual( $rs->row['Province'], 'AB' );
			$this->assertEqual( $rs->row['Country'], 'CA' );
			$this->assertEqual( $rs->row['Postal/Zip Code'], 'T4T 4T4' );
			$this->assertEqual( $rs->row['Sex'], 'm' );
			$this->assertEqual( $rs->row['Phone No'], '(403) 555-1234' );
			$this->assertEqual( $rs->row['E-Mail Address'], 'a@b.ca' );
			$this->assertEqual( $rs->row['Birthday'], '1980-12-30' );
			$this->assertEqual( $rs->row['Favorite Color'], '#ff0000' );
			$this->assertEqual( $rs->row['Active'], '1' );
			$this->assertEqual( $rs->row['Date'], '2007-01-01' );
			$this->assertEqual( $rs->row['Time'], '15:15:00' );
		}

		function testInsertAgainWithTestCaseSubmit() {
			$this->submit( 'form', array( 
								  'Name' => 'George'
								, 'fieldset1_Address' => '7 45ST SW'
								, 'fieldset1_City' => 'Springfield'
								, 'fieldset1_Province' => 'AB'
								, 'fieldset1_Country' => 'CA'
								, 'fieldset1_Postal_Zip_Code' => 'T4t-4t4'
								, 'fieldset1_Sex' => 'm'
								, 'fieldset1_Phone_No' => '4035551234'
								, 'fieldset1_E-Mail_Address' => 'a@b.ca'
								, 'fieldset2_Birthday' => '1980-12-30'
								, 'fieldset2_Favorite_Color' => '#ff0000'
								, 'fieldset2_Active' => '1'
								, 'fieldset2_title' => 'Mr'
								, 'page_form_fieldset2_Date__month' => '01'
								, 'page_form_fieldset2_Date__day' => '01'
								, 'page_form_fieldset2_Date__year' => '2007'
								, 'page_form_fieldset2_Date__null' => '1'
								, 'page_form_fieldset2_Time__hour' => '3'
								, 'page_form_fieldset2_Time__minute' => '15'
								, 'page_form_fieldset2_Time__meridiem' => 'pm'
								, 'page_form_fieldset2_Time__null' => '1'
								));

			// test messages
			$this->assertMessage( 'record saved', \System\Base\AppMessageType::Success() );

			// test forward
			$this->assertRedirectedTo( 'form' );

			// test CSV
			$db = DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Address Book.csv' );
			$rs = $db->openDataSet( 'Address Book.csv' );

			$this->assertEqual( $rs->count, 1 );
			$this->assertEqual( $rs->row['Name'], 'George' );
			$this->assertEqual( $rs->row['Address'], '7 45ST SW' );
			$this->assertEqual( $rs->row['City'], 'Springfield' );
			$this->assertEqual( $rs->row['Province'], 'AB' );
			$this->assertEqual( $rs->row['Country'], 'CA' );
			$this->assertEqual( $rs->row['Postal/Zip Code'], 'T4T 4T4' );
			$this->assertEqual( $rs->row['Sex'], 'm' );
			$this->assertEqual( $rs->row['Phone No'], '(403) 555-1234' );
			$this->assertEqual( $rs->row['E-Mail Address'], 'a@b.ca' );
			$this->assertEqual( $rs->row['Birthday'], '1980-12-30' );
			$this->assertEqual( $rs->row['Favorite Color'], '#ff0000' );
			$this->assertEqual( $rs->row['Active'], '1' );
			$this->assertEqual( $rs->row['Date'], '2007-01-01' );
			$this->assertEqual( $rs->row['Time'], '15:15:00' );
		}

		function testBinding() {
			$db = DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Address Book.csv' );
			$rs = $db->openDataSet();

			$rs['Name'] = 'Bob';
			$rs['City'] = 'Edmonton';
			$rs['Sex']  = 'm';
			$rs->insert();
			$rs['Name'] = 'Jane';
			$rs['City'] = 'Calgary';
			$rs['Sex']  = 'f';
			$rs->insert();
			$rs['Name'] = 'Sally';
			$rs['City'] = 'Vancouver';
			$rs['Sex']  = 'f';
			$rs->insert();

			$this->get( array( 'id' => 'Jane' ));

			$this->assertEqual( $this->controller->form->Name->value, 'Jane' );
			$this->assertEqual( $this->controller->form->City->value, 'Calgary' );
			$this->assertEqual( $this->controller->form->Sex->value, 'f' );

			$this->post( array( 'page_form__submit' => '1'
                                , 'page_form__gotcha' => ''
								, 'page_form_fieldset1_Province__post' => '1'
								, 'page_form_fieldset1_Country__post' => '1'
								, 'page_form_Name' => 'Jane'
								, 'page_form_fieldset1_Address' => '5th AVE'
								, 'page_form_fieldset1_City' => 'New York'
								, 'page_form_fieldset1_Province' => 'NY'
								, 'page_form_fieldset1_Country' => 'US'
								, 'page_form_fieldset1_Postal_Zip_Code' => '90210'
								, 'page_form_fieldset1_Sex' => 'f'
								, 'page_form_fieldset1_Phone_No' => '5555551234'
								, 'page_form_fieldset1_E-Mail_Address' => 'a@b.us'
								, 'page_form_fieldset2_Birthday' => '1980-12-30'
								, 'page_form_fieldset2_Favorite_Color' => '#ff0000'
								, 'page_form_fieldset2_Active' => '0'
                                , 'page_form_fieldset2_Active__post' => '1'
								, 'page_form_fieldset2_Date__month' => '01'
								, 'page_form_fieldset2_Date__day' => '01'
								, 'page_form_fieldset2_Date__year' => '2007'
								, 'page_form_fieldset2_Date__null' => '1'
								, 'page_form_fieldset2_Time__hour' => '3'
								, 'page_form_fieldset2_Time__minute' => '15'
								, 'page_form_fieldset2_Time__meridiem' => 'pm'
								, 'page_form_fieldset2_Time__null' => '1'
								, 'page_form_fieldset2_title__post' => '1'
								, 'page_form_fieldset2_title' => 'Mr'
								, 'id' => 'Jane'
								));

			$rs = $db->openDataSet();

			$this->assertEqual( $rs->count, 3 );
			$this->assertEqual( $rs->rows[0]['Name'], 'Bob' );
			$this->assertEqual( $rs->rows[1]['Name'], 'Jane' );
			$this->assertEqual( $rs->rows[2]['Name'], 'Sally' );
			$this->assertEqual( $rs->rows[0]['City'], 'Edmonton' );
			$this->assertEqual( $rs->rows[1]['City'], 'New York' );
			$this->assertEqual( $rs->rows[2]['City'], 'Vancouver' );
			$this->assertEqual( $rs->rows[0]['Sex'], 'm' );
			$this->assertEqual( $rs->rows[1]['Sex'], 'f' );
			$this->assertEqual( $rs->rows[2]['Sex'], 'f' );
		}

		function testEscaping() {
			$this->post( array( 'page_form__submit' => '1'
                                , 'page_form__gotcha' => ''
								, 'page_form_Name' => '\'\'\'\'////""""\\\\\\\\' // ''''////""""\\\\
								, 'page_form_fieldset1_Address' => '&&&&&amp;'
								, 'page_form_fieldset1_City' => "''''"
								, 'page_form_fieldset1_Province' => 'AB'
								, 'page_form_fieldset1_Country' => 'CA'
								, 'page_form_fieldset1_Postal_Zip_Code' => 'T4t-4t4'
								, 'page_form_fieldset1_Sex' => 'm'
								, 'page_form_fieldset1_Phone_No' => '4035551234'
								, 'page_form_fieldset1_E-Mail_Address' => 'a@b.ca'
								, 'page_form_fieldset2_Birthday' => '1980-12-30'
								, 'page_form_fieldset2_Favorite_Color' => '#ff0000'
								, 'page_form_fieldset2_Active__post' => '1'
								, 'page_form_fieldset1_Province__post' => '1'
								, 'page_form_fieldset1_Country__post' => '1'
								, 'page_form_fieldset2_Active' => '1'
								, 'page_form_fieldset2_Date__month' => '01'
								, 'page_form_fieldset2_Date__day' => '01'
								, 'page_form_fieldset2_Date__year' => '2007'
								, 'page_form_fieldset2_Date__null' => '1'
								, 'page_form_fieldset2_Time__hour' => '3'
								, 'page_form_fieldset2_Time__minute' => '15'
								, 'page_form_fieldset2_Time__meridiem' => 'pm'
								, 'page_form_fieldset2_Time__null' => '1'
								, 'page_form_fieldset2_title__post' => '1'
								, 'page_form_fieldset2_title' => 'Mr'
								));

			// test CSV
			$db = DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Address Book.csv' );
			$rs = $db->openDataSet();

			$this->assertEqual( $rs->count, 1 );
			$this->assertEqual( $rs->row['Name'], "''''////" . '""""\\\\\\\\' );
			$this->assertEqual( $rs->row['Address'], '&&&&&amp;' );
			$this->assertEqual( $rs->row['City'], '\'\'\'\'' );

			// $this->assertResponse( 'tabindex="1" value="\'\'\'\'////&quot;&quot;&quot;&quot;\\\\\\\\"' );
			// $this->assertResponse( 'tabindex="2" value="&amp;&amp;&amp;&amp;&amp;amp;' );
			// $this->assertResponse( 'tabindex="3" value="\'\'\'\'' );

			$this->assertResponse( 'value="\'\'\'\'////&quot;&quot;&quot;&quot;\\\\\\\\"' );
			$this->assertResponse( 'value="&amp;&amp;&amp;&amp;&amp;amp;' );
			$this->assertResponse( 'value="\'\'\'\'' );
		}
	}
?>