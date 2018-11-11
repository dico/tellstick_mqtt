<?php
require_once('/var/www/html/core.php');
require_once('/var/www/html/vendor/bluerhinos/phpmqtt/phpMQTT.php');


// Get config data
$obj = new TellstickMQTT\Setup\Setup();
$tellstick_conf = $obj->get_tellstick_conf();
$mqtt_conf = $obj->get_mqtt_conf();

if (!empty($tellstick_conf['ip'])) {
	$telldus = new TellstickMQTT\Telldus\TellstickLocalApi($tellstick_conf['app_name'], $tellstick_conf['ip']);
	$tellstick_access_token = $telldus->get_stored_access_token();
}


if (isset($tellstick_access_token['token']) && !empty($tellstick_access_token['token'])) {

	// Connect to MQTT
	$mqtt = new Bluerhinos\phpMQTT($mqtt_conf['server'], $mqtt_conf['port'], $mqtt_conf['client_id']);
	if ($mqtt->connect(true, NULL, $mqtt_conf['username'], $mqtt_conf['password'])) {


	
		// Get sensors
		$sensors = $telldus->rpc('sensors/list');

		// Loop sensors if any
		if (count($sensors->sensor) > 0) {
			foreach ($sensors->sensor as $key => $thisDevice) {

				$device_name = urlencode(strtolower($thisDevice->name));

				// Get sensor values
				$sensors_values = $telldus->rpc('sensor/info?id='.$thisDevice->id);

				if (count($sensors_values->data) > 0) {
					foreach ($sensors_values->data as $key => $data) {

						// Build topic and publish
						$topic = $mqtt_conf['client_id']."/sensor/".$device_name."/".$data->name;
						echo "$topic - $data->value \n";

						$mqtt->publish($topic, $data->value, 0);
					}
				} //end-if-sensor-data

			} //end-foreach-sensors
		} // end-if-count-sensors

	} //end-mqtt-connect

	$mqtt->close();
} //end-if-tellstick-token