<?php
    namespace MyApp\Models;
    use System\Base\AppConfiguration;
    use System\Base\AppSettingsCollection;

	class AppConfigTestCase extends \System\Testcase\UnitTestCaseBase {

		function prepare() {
			// create config file
			$fp = fopen( __TMP_PATH__ . '/test-config.xml', 'w+' );
			fwrite( $fp, '<?xml version="1.0" encoding="iso-8859-1"?>

<application                   state = "on">
  <authorization deny="Guest">
    <page path = "form" allow="Admin"/>
  </authorization>
  <app-settings>
    <add                         key = "test-key"
                               value = "test-value" />
    <add                         key = "test-key2"
                               value = "test-value2" />
  </app-settings>
  <request                   default = "test-default"
                               param = "test-param"
                       friendly-uris = "true" />
  <session                cookieless = "true"
                             timeout = "99" />
  <cache                     enabled = "true"
                             expires = "36" />
  <authentication             method = "basic"
                                deny = "test-all"
                               allow = "test-none">
    <basic                     realm = "test-realm" />
    <forms                 loginpage = "test-page"
                          cookiename = "__TEST" secret="mysecret" />
    <credentials>
      <user                 username = "foo"
                            password = "bar"
                              active = "true" />
      <user                 username = "foo2"
                            password = "bar2"
                              active = "false"
                     password-format = "md5" salt="test-salt"/>
      <table                  source = "test-table"
                      username-field = "test-username"
                      password-field = "test-password" />
      <table                     dsn = "test-dsn"
                              source = "test-table2"
                      username-field = "test-username2"
                      password-field = "test-password2"
                          salt-field = "part-1"
                     password-format = "sha1" salt="part-2" />
    </credentials>
    <memberships>
      <membership           username = "foo"
                                role = "Admin" />
      <membership           username = "foo"
                                role = "User" />
      <table                  source = "test-table"
                      username-field = "test-username"
                          role-field = "test-password" />
    </memberships>
  </authentication>
  <data-source                   dsn = "driver={driver};
                                        uid={uid};
                                        pwd={pwd};
                                        server={host};
                                        database={db};" />
  <errors>
    <when                      error = "401"
                                page = "Errors/test-401" />
    <when                      error = "404"
                                page = "Errors/test-404" />
  </errors>
</application>
' );
			fclose( $fp );

			// create incpmplete file
			$fp = fopen( __TMP_PATH__ . '/incomplete-config.xml', 'w+' );
			fwrite( $fp, '<?xml version="1.0" encoding="iso-8859-1"?>

<application                   state = "on">
  <session></session>
  <authentication             method = "basic">
    <credentials>
      <user                 username = "foo"
                            password = "bar"
                              active = "true" />
      <user                 username = "foo2"
                            password = "bar2"
                              active = "false" />
      <table                  source = "test-table"
                      username-field = "test-username"
                      password-field = "test-password" />
      <table                     dsn = "test-dsn"
                              source = "test-table2"
                      username-field = "test-username2"
                      password-field = "test-password2" />
    </credentials>
  </authentication>

  <data-source                   dsn = "driver={driver};
                                        uid={uid};
                                        pwd={pwd};
                                        server={host};
                                        database={db};" />
  <errors>
  </errors>
</application>
' );
			fclose( $fp );

			$fp = fopen( __TMP_PATH__ . '/bad-config.xml', 'w+' );
			fwrite( $fp, '<?xml version="1.0" encoding="iso-8859-1"?>
<application state = "true" />
' );
			fclose( $fp );
		}

		function cleanup() {
			// cleanup data file
			//unlink( __TMP_PATH__ . '/test-config.xml' );
			//unlink( __TMP_PATH__ . '/incomplete-config.xml' );
			//unlink( __TMP_PATH__ . '/bad-config.xml' );
		}

		function testDefaults() {
			$config = new AppConfiguration();

			$this->assertTrue( $config->state == \System\Base\AppState::On() );
			$this->assertTrue( $config->appsettings instanceof \System\Base\AppSettingsCollection );
			$this->assertTrue( $config->root === __ROOT__ );
			$this->assertTrue( $config->htdocs === __HTDOCS_PATH__ );
			$this->assertTrue( $config->protocol === __PROTOCOL__ );
			$this->assertTrue( $config->host === __HOST__ );
			$this->assertTrue( $config->uri === __APP_URI__ );
			$this->assertTrue( $config->assets === __ASSETS_URI__ );
			$this->assertTrue( $config->controllers === __CONTROLLERS_PATH__ );
			$this->assertTrue( $config->views === __VIEWS_PATH__ );
			$this->assertTrue( $config->functionaltests === __FUNCTIONAL_TESTS_PATH__ );
			$this->assertTrue( $config->unittests === __UNIT_TESTS_PATH__ );
			$this->assertTrue( $config->fixtures === __FIXTURES_PATH__ );
			$this->assertTrue( $config->defaultController === 'Index' );
			$this->assertTrue( $config->requestParameter === __PATH_REQUEST_PARAMETER__ );
			$this->assertTrue( $config->rewriteURIS === true );
			$this->assertTrue( $config->cookielessSession === false );
			$this->assertTrue( $config->sessionTimeout === 0 );

			$this->assertTrue( $config->authenticationMethod === 'none' );
			$this->assertTrue( $config->authenticationFormsCookieName === '__AUTHCOOKIE' );
			$this->assertTrue( $config->authenticationFormsSecret === 'secret' );
			$this->assertTrue( $config->authenticationDeny === array('all') );
			$this->assertTrue( $config->authenticationAllow === array('none') );
			$this->assertTrue( $config->authenticationBasicRealm === 'My Realm' );
			$this->assertTrue( $config->authenticationFormsLoginPage === 'login' );
			$this->assertTrue( $config->authenticationCredentialsUsers === array() );
			$this->assertTrue( $config->authenticationMemberships === array() );

			$this->assertTrue( $config->cacheEnabled === true );
			$this->assertTrue( $config->cacheExpires === 0 );
			$this->assertTrue( $config->dsn === '' );
			$this->assertTrue( $config->errors === array() );
		}

		function testValid() {
			$config = new AppConfiguration();

			$this->assertNull( $config->loadAppConfig( __TMP_PATH__ . '/test-config.xml' ));

			$this->assertTrue( $config->appsettings == new AppSettingsCollection( array( "test-key"=>'test-value',"test-key2"=>'test-value2' )));

			$this->assertTrue( $config->defaultController === 'test-default' );
			$this->assertTrue( $config->requestParameter === 'test-param' );
			$this->assertTrue( $config->rewriteURIS === TRUE );
			$this->assertTrue( $config->cookielessSession === TRUE );
			$this->assertTrue( $config->sessionTimeout === 99 );

			$this->assertTrue( $config->authenticationMethod === 'basic' );
			$this->assertTrue( $config->authenticationFormsCookieName === '__TEST' );
			$this->assertTrue( $config->authenticationDeny === array('test-all') );
			$this->assertTrue( $config->authenticationAllow=== array('test-none') );
			$this->assertTrue( $config->authenticationBasicRealm === 'test-realm' );
			$this->assertTrue( $config->authenticationFormsLoginPage === 'test-page' );
			$this->assertTrue( $config->authenticationCredentialsUsers === array(array('username'=>'foo','password'=>'bar','active'=>true),array('username'=>'foo2','password'=>'bar2','active'=>false,'salt'=>'test-salt','password-format'=>'md5' )));
			$this->assertTrue( $config->authenticationCredentialsTables === array(array('source'=>'test-table','username-field'=>'test-username','password-field'=>'test-password'),array('dsn'=>'test-dsn','source'=>'test-table2','username-field'=>'test-username2','password-field'=>'test-password2','salt-field'=>'part-1','salt'=>'part-2','password-format'=>'sha1')) );
			$this->assertTrue( $config->authenticationMemberships === array(array('username'=>'foo','role'=>'Admin'), array('username'=>'foo','role'=>'User')) );
			$this->assertTrue( $config->authenticationMembershipsTables === array(array('source'=>'test-table','username-field'=>'test-username','role-field'=>'test-password')) );
			$this->assertTrue( $config->authorizationPages['form']['allow'][0] === 'Admin' );
			$this->assertTrue( $config->authorizationDeny === array( 'Guest' ));

			$this->assertTrue( $config->cacheEnabled === TRUE );
			$this->assertTrue( $config->cacheExpires === 36 );
			$this->assertTrue( $config->dsn === 'driver={driver};                                         uid={uid};                                         pwd={pwd};                                         server={host};                                         database={db};' );
			$this->assertTrue( $config->errors === array(401=>'Errors/test-401',404=>'Errors/test-404' ));
		}

		function testInvalid() {
			$config = new AppConfiguration();

			$this->expectException();
			$this->assertFalse( $config->loadAppConfig( __TMP_PATH__ . '/bad-config.xml' ));
		}

		function testIncomplete() {
			$config = new AppConfiguration();

			$this->assertNull( $config->loadAppConfig( __TMP_PATH__ . '/incomplete-config.xml' ));

			$this->assertTrue( $config->appsettings instanceof AppSettingsCollection );
			$this->assertTrue( $config->root === __ROOT__ );
			$this->assertTrue( $config->htdocs === __HTDOCS_PATH__ );
			$this->assertTrue( $config->protocol === __PROTOCOL__ );
			$this->assertTrue( $config->host === __HOST__ );
			$this->assertTrue( $config->uri === __APP_URI__ );
			$this->assertTrue( $config->controllers === __CONTROLLERS_PATH__ );
			$this->assertTrue( $config->views === __VIEWS_PATH__ );
			$this->assertTrue( $config->functionaltests === __FUNCTIONAL_TESTS_PATH__ );
			$this->assertTrue( $config->unittests === __UNIT_TESTS_PATH__ );
			$this->assertTrue( $config->fixtures === __FIXTURES_PATH__ );
			$this->assertTrue( $config->defaultController === 'Index' );
			$this->assertTrue( $config->requestParameter === __PATH_REQUEST_PARAMETER__ );
			$this->assertTrue( $config->rewriteURIS === true );
			$this->assertTrue( $config->cookielessSession === false );
			$this->assertTrue( $config->sessionTimeout === 0 );

			$this->assertTrue( $config->authenticationMethod === 'basic' );
			$this->assertTrue( $config->authenticationCredentialsUsers === array(array('username'=>'foo','password'=>'bar','active'=>true),array('username'=>'foo2','password'=>'bar2','active'=>false)));
			$this->assertTrue( $config->authenticationCredentialsTables === array(array('source'=>'test-table','username-field'=>'test-username','password-field'=>'test-password'),array('dsn'=>'test-dsn','source'=>'test-table2','username-field'=>'test-username2','password-field'=>'test-password2')) );

			$this->assertTrue( $config->cacheEnabled === true );
			$this->assertTrue( $config->cacheExpires === 0 );
			$this->assertTrue( $config->dsn === 'driver={driver};                                         uid={uid};                                         pwd={pwd};                                         server={host};                                         database={db};' );
			$this->assertTrue( $config->errors === array() );
		}
	}
?>