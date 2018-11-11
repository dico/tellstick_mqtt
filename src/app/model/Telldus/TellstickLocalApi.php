<?php
namespace TellstickMQTT\Telldus;

/**
* Class for connecting to Telldus ZNet locally
* Please see official documentation here: http://api.telldus.net/localapi/api/authentication.html
*
* LICENSE: Free to use with no warranty or support
*
* @author Robert Andresen <ra@fosen-utvikling.no
*/
 
class TellstickLocalApi
{
	
	function __construct($app_name, $ip)
	{
		$this->Dir = '/var/www/html/config/'; // Path to store .json files
		$this->AppName = $app_name;
		$this->BaseURL = "http://".$ip."/api/";
	}
 
 
 
 
	/**
	* Step 1 - Request a request token
	* 
	* Request a request token by performing a PUT call to the endpoint /api/token. 
	* You need to supply the application name as a parameter “app”
	*
	* @return  	stdClass	$result		stdClass Object ( [authUrl], [token] )
	*/
	function get_request_token()
	{
		$curl = curl_init($this->BaseURL.'token');
		$data = array(
			'app' => $this->AppName,
		);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
 
		// Make the REST call, returning the result
		$response = curl_exec($curl);
		curl_close($curl);
 
 
		$result = json_decode($response);
 
		
 
		return $result;
	}
 
 
 
 
	/**
	* Step 3 - Exchange the request token for an access token
	* 
	* When the user has authenticated the request token in step 2 the application needs to exchange this for an access token. 
	* The access token can be used in the API requests. 
	* To exchange the request token for an access token perform a GET call to the same endpoint in step 1. 
	* Supply the request token as the parameter “token”.
	*
	* @param  	String		$token		Request token from step 1
	* @return  	stdClass	$result		stdClass Object ( [allowRenew], [expires], [token] )
	*/
	function get_access_token($token)
	{
		// Get cURL resource
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->BaseURL.'token?token='.$token);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
 
		$response = curl_exec($curl);
		curl_close($curl);
 
		$result = json_decode($response);
 
		// Write access-token to textfile
		if (isset($result->token) && !empty($result->token)) {
			$this->write_access_token($result);
		}
 
		return $result;
	}
 
 
 
 
	/**
	* Refreshing a token
	* 
	* If the user allowed the application to renew the token in steg 2 it can be renewed by the calling application. 
	* The token must be refreshed before it expires. 
	* If the token has expired the authentication must be restarted from step 1 again.
	*
	* @uses $this->get_stored_access_token()
	*
	* @return  	stdClass	$result		stdClass Object ( [expires], [token] )
	*/
 
	function renew_token()
	{
		// Get access token from textfile
		$authorization = "Authorization: Bearer " . $this->get_stored_access_token();
 
		// Get cURL resource
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->BaseURL.'refreshToken');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
 
		$response = curl_exec($curl);
		curl_close($curl);
 
		$result = json_decode($response);
 
		
		// Write access-token to textfile
		$this->write_access_token($result->token);
 
		return $result;
	}
 
 
 
 
	/**
	* Fetch device/sensor data (Remote procedure call)
	* 
	* To make a requst to a TellStick ZNet API endpoint the token in step 3 must be supplied as a bearer token in the header. 
	* This is an example requesting a list of devices.
	*
	* Call commands:
	*	device:
	*		device/bell
	*		device/command
	*		device/dim
	*		device/down
	*		device/info
	*		device/learn
	*		device/setName
	*		device/stop
	*		device/turnOff
	*		device/turnOn
	*		device/up
	*
	*	devices:
	*		devices/list
	*
	*	sensor:
	*		sensor/info
	*		sensor/setName
	*
	*	sensors:
	*		sensors/list
	*
	*
	* @uses $this->get_stored_access_token()
	*
	* @return  	stdClass	$result		stdClass Object
	*/
 
 
	function rpc($call)
	{
		$access_token = $this->get_stored_access_token();
 
		// Get access token from textfile
		$authorization = "Authorization: Bearer " . $access_token['token'];
 
 
		// Get cURL resource
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->BaseURL.$call);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
 
		$response = curl_exec($curl);
		curl_close($curl);
 
		$result = json_decode($response);
 
		return $result;
	}
 
 
 
 
	/**
	* Store the access-token in a textfile
	* 
	* The generated access token after approving the application is required to future calls.
	* This stores it in a textfile. 
	*
	* @return  	Boolean		$result		True/False
	*/
 
	function write_access_token($data)
	{
		$myfile = fopen($this->Dir . "token.json", "w") or die("Unable to open file!");
		$result = fwrite($myfile, json_encode($data));
		fclose($myfile);
 
		return $result;
	}
 
 
 
 
	/**
	* Retrieve access-token from textfile
	*
	* @return  	String		$token		Access token
	*/
 
	function get_stored_access_token()
	{
		$string = file_get_contents($this->Dir.'token.json');
		$result = json_decode($string, true);

		return $result;
	}
 
}