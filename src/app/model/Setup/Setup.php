<?php
namespace TellstickMQTT\Setup;
/**
* Setup
*
* LICENSE: Free to use with no warranty or support
*
* @author Robert Andresen <ra@fosen-utvikling.no
*/
 
class Setup
{
	
	function __construct()
	{
		//$this->Dir = $_SERVER['DOCUMENT_ROOT'].'/config/'; // Path to store .json files
		$this->Dir = '/var/www/html/config/'; // Path to store .json files
	}



	/**
	* Create tellstick config
	* 
	* Take variables and write them as a json file
	*
	* @param  	String		$app_name		Tellstick app name
	* @param  	String		$ip				Tellstick IP address
	* @return  	String		$write			Outputs the resource ID for file write
	*/
	public function create_tellstick_conf($app_name, $ip)
	{
		$data = array('app_name'=> $app_name, 'ip'=> $ip);

		$fp = fopen($this->Dir.'tellstick_conf.json', 'w') or die("Unable to open file!");
		$write = fwrite($fp, json_encode($data));
		fclose($fp);

		return $write;
	}


	/**
	* Fetch Tellstick data from json config file
	* 
	* It also stores the config data in SESSION, to make it remember if user removes the config for edit.
	*
	* @return  	String		$result		Outputs content in json as array
	*/
	public function get_tellstick_conf()
	{
		$string = file_get_contents($this->Dir.'tellstick_conf.json');
		$result = json_decode($string, true);

		// Store values in session, to remember if reset and edit
		$_SESSION['tellstick_app_name'] = $result['app_name'];
		$_SESSION['tellstick_ip'] = $result['ip'];

		return $result;
	}





	/**
	* Create MQTT config
	* 
	* Take variables and write them as a json file
	*
	* @param  	String		$server			MQTT server
	* @param  	String		$port			Port
	* @param  	String		$username		Username
	* @param  	String		$password		Password
	* @param  	String		$client_id		Client ID
	* @return  	String		$write			Outputs the resource ID for file write
	*/
	public function create_mqtt_conf($server, $port, $username, $password, $client_id)
	{
		$data = array('server'=> $server, 'port'=> $port, 'username'=> $username, 'password'=> $password, 'client_id'=> $client_id);

		$fp = fopen($this->Dir.'mqtt_conf.json', 'w') or die("Unable to open file!");
		$write = fwrite($fp, json_encode($data));
		fclose($fp);

		return $write;
	}



	/**
	* Fetch MQTT data from json config file
	* 
	* It also stores the config data in SESSION, to make it remember if user removes the config for edit.
	*
	* @return  	String		$result		Outputs content in json as array
	*/
	public function get_mqtt_conf()
	{
		$string = file_get_contents($this->Dir.'mqtt_conf.json');
		$result = json_decode($string, true);

		// Store values in session, to remember if reset and edit
		$_SESSION['mqtt_server'] = $result['server'];
		$_SESSION['mqtt_port'] = $result['port'];
		$_SESSION['mqtt_username'] = $result['username'];
		$_SESSION['mqtt_password'] = $result['password'];
		$_SESSION['mqtt_client_id'] = $result['client_id'];

		return $result;
	}





	/**
	* Remove all config
	* 
	* Deletes the content in the .json files
	*/
	public function remove_config()
	{
		//unlink($this->filepath_dir.'tellstick_conf.json');
		//unlink($this->filepath_dir.'token.json');

		$fp = fopen($this->Dir.'tellstick_conf.json', 'w') or die("Unable to open file!");
		$write = fwrite($fp, '');

		$fp = fopen($this->Dir.'token.json', 'w') or die("Unable to open file!");
		$write = fwrite($fp, '');
		fclose($fp);

		$fp = fopen($this->Dir.'mqtt_conf.json', 'w') or die("Unable to open file!");
		$write = fwrite($fp, '');
		fclose($fp);
	}


	/**
	* Remove Tellstick config
	*/
	public function remove_config_tellstick()
	{
		$fp = fopen($this->Dir.'tellstick_conf.json', 'w') or die("Unable to open file!");
		$write = fwrite($fp, '');
	}


	/**
	* Remove Tellstick Auth Token config
	*/
	public function remove_config_token()
	{
		$fp = fopen($this->Dir.'token.json', 'w') or die("Unable to open file!");
		$write = fwrite($fp, '');
	}


	/**
	* Remove MQTT config
	*/
	public function remove_config_mqtt()
	{
		$fp = fopen($this->Dir.'mqtt_conf.json', 'w') or die("Unable to open file!");
		$write = fwrite($fp, '');
	}

}