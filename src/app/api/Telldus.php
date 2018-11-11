<?php
require_once('../../core.php');


$obj = new TellstickMQTT\Setup\Setup();
$tellstick_conf = $obj->get_tellstick_conf();



if ($_GET['a'] == 'getAccessToken') {
	$telldus = new TellstickMQTT\Telldus\TellstickLocalApi($tellstick_conf['app_name'], $tellstick_conf['ip']);
	$result = $telldus->get_access_token($_GET['token']);

	if (isset($result->token) && !empty($result->token)) {
		header('Location: ../../index.php');
	}
	
	elseif($result->error) {
		echo '<div style="color:red;">';
			echo $result->error . '<br />';
			echo "Please go back and do step 1 again...<br />Do not refresh page after step 1.";
		echo '</div>';
	}
	
	else {
		die('An error occured. Could not get token. Please go back and retry.');
	}
}



if ($_GET['a'] == 'getSensorData') {
	$telldus = new TellstickMQTT\Telldus\TellstickLocalApi($tellstick_conf['app_name'], $tellstick_conf['ip']);
	$result = $telldus->rpc('sensor/info?id='.$_GET['id']);


	header('Content-Type: application/json');
	echo json_encode($result);
}