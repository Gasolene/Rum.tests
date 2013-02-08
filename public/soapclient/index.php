<?php
// turn off the WSDL cache for debugging only
ini_set('soap.wsdl_cache_enabled', '0');

//$client = new SoapClient("http://localhost/NuvistaInventoryManagement/public/webservice/?wsdl");
//$client = new SoapClient("http://stage01.commerx.com/darnell/nuvista/current/public/webservice/?wsdl");
$client = new SoapClient("http://rms.nuvistahomes.com/webservice/?wsdl");

echo "<pre>";

//if($client->login('root', 'admin77'))
if($client->login('remote', '12345678'))
{
	// I am now logged in...
	// Send a hello message to the server and output the response
	
	print_r(json_decode($client->getImages(2)));
}
else
{
	echo "Auth failed";
}
?>