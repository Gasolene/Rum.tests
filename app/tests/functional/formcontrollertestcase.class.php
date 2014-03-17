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
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->get();

			$html = $this->responseAsXMLEntity();

			$this->assertTrue( $html->body->div->getChildByAttribute('id', 'body')->div->form->getChildrenByName( 'input' )->count >= 1 );
			$this->assertTrue( $html->body->div->getChildByAttribute('id', 'body')->div->form->getChildrenByName( 'input' )->count <= 3 );
			$this->assertEqual( $html->body->div->getChildByAttribute('id', 'body')->div->form->getChildByAttribute( 'name', \Rum::config()->requestParameter )->getAttribute( 'type' ), 'hidden' );
			//$this->assertResponse( \Rum::config()->themes );
			$this->assertResponse( '<legend><span>Sample Form</span></legend>' );
			$this->assertResponse( 'Enter your name here tooltip' );
		}

		function testDefaults() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->get();

            // test for radio default
			$this->assertResponse( 'value="f" type="radio" checked="checked" />' );
		}

		function testCheckBoxList() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->post( array( 'page_form__gotcha'=>'', 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_favoritecolors__post' => '1', 'page_form_title' => 'Mr', 'page_form_favoritecolors' => array( '#00FF00', '#0000FF' )));

			$this->assertResponse( 'value="#FF0000" title="" type="checkbox" name="page_form_favoritecolors[]"' );
			$this->assertResponse( 'value="#00FF00" title="" type="checkbox" name="page_form_favoritecolors[]" checked="checked"' );
			$this->assertResponse( 'value="#0000FF" title="" type="checkbox" name="page_form_favoritecolors[]"' );
		}

		function testState() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->post( array( 'page_form'.\System\Web\WebControls\GOTCHAFIELD=>'', 'page_form__submit' => '1', 'page_form_Address' => '555' ));

			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->get();

			$this->assertEqual( $this->controller->Address->value, '555' );
		}

		function testValidation() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->post( array( 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_E-Mail_Address' => 'a@b.c', 'page_form_Birthday' => '2005-09-32', 'page_form_Province__post' => '1', 'page_form_Province' => '' ));

			// test validate()
			$err = '';
			$this->assertFalse( $this->controller->form->getControl( 'E-Mail_Address' )->validate($err) );
			$this->assertEqual(trim($err), 'must be a valid email address');

			// test messages
			$this->assertResponse( 'You must enter a name!' );
			$this->assertResponse( 'be a valid email address' );
//			$this->assertResponse( 'valid date' );
			$this->assertResponse( 'blast off' );
		}

		function testEvents() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->get();

			$this->assertFalse( $this->controller->Name->submitted );
			$this->assertFalse( $this->controller->Address->submitted );
			$this->assertFalse( $this->controller->City->submitted );
			$this->assertFalse( $this->controller->save->submitted );
			$this->assertFalse( $this->controller->Name->changed );
			$this->assertFalse( $this->controller->Address->changed );
			$this->assertFalse( $this->controller->City->changed );
			$this->assertFalse( $this->controller->save->changed );

			$this->post( array( 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_Name' => 'foo-bar', 'page_form_Address' => '1234', 'page_form_save' => 'Save' ));

			$this->assertMessage( 'Name was changed' );

			$this->assertTrue ( $this->controller->Name->submitted );
			$this->assertTrue ( $this->controller->Address->submitted );
			$this->assertFalse( $this->controller->City->submitted );
			$this->assertTrue ( $this->controller->save->submitted );
			$this->assertTrue ( $this->controller->Name->changed );
			$this->assertTrue ( $this->controller->Address->changed );
			$this->assertFalse( $this->controller->City->changed );
			$this->assertTrue ( $this->controller->save->changed );

			$this->post( array( 'page_form__gotcha'=>'', 'page_form__submit' => '1', 'page_form_Name' => 'xfoo-bar', 'page_form_Address' => '1234', 'page_form_save' => 'Save' ));

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
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->post( array( 'page_form__submit' => '1'
                                , 'page_form__gotcha' => ''
								, 'page_form_Province__post' => '1'
								, 'page_form_Country__post' => '1'
								, 'page_form_Name' => 'George'
								, 'page_form_Address' => '7 45ST SW'
								, 'page_form_City' => 'Springfield'
								, 'page_form_Province' => 'AB'
								, 'page_form_Country' => 'CA'
								, 'page_form_Postal_Zip_Code' => 'T4t-4t4'
								, 'page_form_Sex' => 'm'
								, 'page_form_Phone_No' => '4035551234'
								, 'page_form_E-Mail_Address' => 'a@b.ca'
								, 'page_form_Birthday' => '1980-12-30'
								, 'page_form_Favorite_Color' => '#ff0000'
								, 'page_form_Active' => '1'
								, 'page_form_Date' => '2007-01-01'
								, 'page_form_Time' => '15:15:00'
								, 'page_form_title' => 'Mr'
								, 'page_form_title__post' => '1'
								));

			// test messages
			$this->assertMessage( 'record saved', \System\Base\AppMessageType::Success() );

			// test forward
			$this->assertRedirectedTo( 'form' );

			// test CSV
			$db = DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Address Book.csv' );
			$rs = $db->openDataSet( 'Address Book.csv' );


$rs = $db->prepare( 'select * from users where user=@user', array('user'=>'Bob'))->openDataSet();

$statement = $db->prepare( 'select * from users where user=@user' );
$statement->bind('user', 'Tom');
$statement->openDataSet();


			$this->assertEqual( $rs->count, 1 );
			$this->assertEqual( $rs->row['Name'], 'George' );
			$this->assertEqual( $rs->row['Address'], '7 45ST SW' );
			$this->assertEqual( $rs->row['City'], 'Springfield' );
			$this->assertEqual( $rs->row['Province'], 'AB' );
			$this->assertEqual( $rs->row['Country'], 'CA' );
			$this->assertEqual( $rs->row['Postal/Zip Code'], 'T4T 4T4' );
			$this->assertEqual( $rs->row['Sex'], 'm' );
			$this->assertEqual( $rs->row['Phone No'], '4035551234' );
			$this->assertEqual( $rs->row['E-Mail Address'], 'a@b.ca' );
			$this->assertEqual( $rs->row['Birthday'], '1980-12-30' );
			$this->assertEqual( $rs->row['Favorite Color'], '#ff0000' );
			$this->assertEqual( $rs->row['Active'], '1' );
			$this->assertEqual( $rs->row['Date'], '2007-01-01' );
			$this->assertEqual( $rs->row['Time'], '15:15:00' );
		}

		function testInsertAgainWithTestCaseSubmit() {
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->submit( 'form', array( 
								  'Name' => 'George'
								, 'Address' => '7 45ST SW'
								, 'City' => 'Springfield'
								, 'Province' => 'AB'
								, 'Country' => 'CA'
								, 'Postal_Zip_Code' => 'T4t-4t4'
								, 'Sex' => 'm'
								, 'Phone_No' => '4035551234'
								, 'E-Mail_Address' => 'a@b.ca'
								, 'Birthday' => '1980-12-30'
								, 'Favorite_Color' => '#ff0000'
								, 'Active' => '1'
								, 'title' => 'Mr'
								, 'page_form_Date' => '2007-01-01'
								, 'page_form_Time' => '15:15:00'
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
			$this->assertEqual( $rs->row['Phone No'], '4035551234' );
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

			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->get( array( 'id' => 'Jane' ));

			$this->assertEqual( $this->controller->form->Name->value, 'Jane' );
			$this->assertEqual( $this->controller->form->City->value, 'Calgary' );
			$this->assertEqual( $this->controller->form->Sex->value, 'f' );

			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->post( array( 'page_form__submit' => '1'
                                , 'page_form__gotcha' => ''
								, 'page_form_Province__post' => '1'
								, 'page_form_Country__post' => '1'
								, 'page_form_Name' => 'Jane'
								, 'page_form_Address' => '5th AVE'
								, 'page_form_City' => 'New York'
								, 'page_form_Province' => 'NY'
								, 'page_form_Country' => 'US'
								, 'page_form_Postal_Zip_Code' => '90210'
								, 'page_form_Sex' => 'f'
								, 'page_form_Phone_No' => '5555551234'
								, 'page_form_E-Mail_Address' => 'a@b.us'
								, 'page_form_Birthday' => '1980-12-30'
								, 'page_form_Favorite_Color' => '#ff0000'
								, 'page_form_Active' => '0'
                                , 'page_form_Active__post' => '1'
								, 'page_form_Date__month' => '01'
								, 'page_form_Date__day' => '01'
								, 'page_form_Date__year' => '2007'
								, 'page_form_Date__null' => '1'
								, 'page_form_Time__hour' => '3'
								, 'page_form_Time__minute' => '15'
								, 'page_form_Time__meridiem' => 'pm'
								, 'page_form_Time__null' => '1'
								, 'page_form_title__post' => '1'
								, 'page_form_title' => 'Mr'
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
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->expectError();
			$this->post( array( 'page_form__submit' => '1'
                                , 'page_form__gotcha' => ''
								, 'page_form_Name' => '\'\'\'\'////""""\\\\\\\\' // ''''////""""\\\\
								, 'page_form_Address' => '&&&&&amp;'
								, 'page_form_City' => "''''"
								, 'page_form_Province' => 'AB'
								, 'page_form_Country' => 'CA'
								, 'page_form_Postal_Zip_Code' => 'T4t-4t4'
								, 'page_form_Sex' => 'm'
								, 'page_form_Phone_No' => '4035551234'
								, 'page_form_E-Mail_Address' => 'a@b.ca'
								, 'page_form_Birthday' => '1980-12-30'
								, 'page_form_Favorite_Color' => '#ff0000'
								, 'page_form_Active__post' => '1'
								, 'page_form_Province__post' => '1'
								, 'page_form_Country__post' => '1'
								, 'page_form_Active' => '1'
								, 'page_form_Date__month' => '01'
								, 'page_form_Date__day' => '01'
								, 'page_form_Date__year' => '2007'
								, 'page_form_Date__null' => '1'
								, 'page_form_Time__hour' => '3'
								, 'page_form_Time__minute' => '15'
								, 'page_form_Time__meridiem' => 'pm'
								, 'page_form_Time__null' => '1'
								, 'page_form_title__post' => '1'
								, 'page_form_title' => 'Mr'
								));

			// test CSV
			$db = DataAdapter::create( 'driver=text;format=TabDelimited;source=' . \Rum::config()->root . '/app/data/Address Book.csv' );
			$rs = $db->openDataSet();

			$this->assertEqual( $rs->count, 1 );
			$this->assertEqual( $rs->row['Name'], "''''////" . '""""\\\\\\\\' );
			$this->assertEqual( $rs->row['Address'], '&&&&&amp;' );
			$this->assertEqual( $rs->row['City'], '\'\'\'\'' );

			$this->assertResponse( 'value="\'\'\'\'////&quot;&quot;&quot;&quot;\\\\\\\\"' );
			$this->assertResponse( 'value="&amp;&amp;&amp;&amp;&amp;amp;' );
			$this->assertResponse( 'value="\'\'\'\'' );
		}
	}
?>