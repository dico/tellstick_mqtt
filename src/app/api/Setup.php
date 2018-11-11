<?php
require_once('../../core.php');



if ($_GET['a'] == 'tellstickSetup') {
	$app_name = $_POST['tellstick_app_name'];
	$ip = $_POST['tellstick_ip'];

	$_SESSION['tellstick_app_name'] = $app_name;
	$_SESSION['tellstick_ip'] = $ip;

	$obj = new TellstickMQTT\Setup\Setup();
	$result = $obj->create_tellstick_conf($app_name, $ip);

	header('Location: ../../index.php');
}



if ($_GET['a'] == 'mqttSetup') {
	$server = $_POST['mqtt_server'];
	$port = $_POST['mqtt_port'];
	$username = $_POST['mqtt_username'];
	$password = $_POST['mqtt_password'];
	$client_id = $_POST['mqtt_client_id'];

	$_SESSION['mqtt_server'] = $server;
	$_SESSION['mqtt_port'] = $port;
	$_SESSION['mqtt_username'] = $username;
	$_SESSION['mqtt_password'] = $password;
	$_SESSION['mqtt_client_id'] = $client_id;

	$obj = new TellstickMQTT\Setup\Setup();
	$result = $obj->create_mqtt_conf($server, $port, $username, $password, $client_id);

	header('Location: ../../index.php');
}



if ($_GET['a'] == 'removeConfig') {
	$obj = new TellstickMQTT\Setup\Setup();
	$result = $obj->remove_config();

	header('Location: ../../index.php');
}

if ($_GET['a'] == 'removeConfigTellstick') {
	$obj = new TellstickMQTT\Setup\Setup();
	$result = $obj->remove_config_tellstick();

	header('Location: ../../index.php');
}

if ($_GET['a'] == 'removeConfigTellstickToken') {
	$obj = new TellstickMQTT\Setup\Setup();
	$result = $obj->remove_config_token();

	header('Location: ../../index.php');
}

if ($_GET['a'] == 'removeConfigMqtt') {
	$obj = new TellstickMQTT\Setup\Setup();
	$result = $obj->remove_config_mqtt();

	header('Location: ../../index.php');
}