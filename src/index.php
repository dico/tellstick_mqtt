<?php
	require_once('core.php');

	

	$obj = new TellstickMQTT\Setup\Setup();
	$tellstick_conf = $obj->get_tellstick_conf();
	$mqtt_conf = $obj->get_mqtt_conf();

	if (!empty($tellstick_conf['ip'])) {
		$telldus = new TellstickMQTT\Telldus\TellstickLocalApi($tellstick_conf['app_name'], $tellstick_conf['ip']);
		$tellstick_access_token = $telldus->get_stored_access_token();

		/* echo "<pre>";
			print_r($tellstick_access_token);
		echo "</pre>"; */
	}

	/* echo "<pre>";
		print_r($tellstick_conf);
	echo "</pre>"; */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Telldus to MQTT</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/buttons.css" />

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>
<body>
	

	<div class="main">




		<!--
			
			Tellstick setup

			Enter App name and local IP to tellstick

		-->
		<?php if (empty($tellstick_conf['ip'])): ?>
			<?php
				if (!isset($_SESSION['tellstick_app_name']) || empty($_SESSION['tellstick_app_name'])) {
					$_SESSION['tellstick_app_name'] = 'HA_Tellstick_MQTT';
				}
			?>


			<h1>Tellstick configuration</h1>

			<p>Enter your local Tellstick IP-address and a name for this app.</p> 

			<form action="app/api/Setup.php?a=tellstickSetup" method="POST">
				<div class="white-box" style="padding:30px 25px;">
					<div class="form-elm" style="display:inline-block; width:49%;">
						<label for="tellstick_app_name">App name</label>
						<input type="text" name="tellstick_app_name" id="tellstick_app_name" value="<?php echo $_SESSION['tellstick_app_name']; ?>" required>
					</div>

					<div class="form-elm" style="display:inline-block; width:49%;">
						<label for="tellstick_ip">Tellstick IP</label>
						<input type="text" name="tellstick_ip" id="tellstick_ip" value="<?php echo $_SESSION['tellstick_ip']; ?>" autofocus required>
					</div>
				</div>

				<div style="text-align:right; margin-top:15px;">
					<button class="button button-primary button-pill button-large" type="submit">Connect</button>
				</div>
			</form>








		<!--
			
			MQTT setup

			Enter configuration for MQTT server

		-->
		<?php elseif (empty($mqtt_conf['server'])): ?>
			<?php
				if (!isset($_SESSION['tellstick_app_name']) || empty($_SESSION['tellstick_app_name'])) {
					$_SESSION['tellstick_app_name'] = 'HA_Tellstick_MQTT';
				}
			?>


			<h1>MQTT configuration</h1>

			<p>Enter server and credentials for your MQTT server</p> 

			<form action="app/api/Setup.php?a=mqttSetup" method="POST">
				<div class="white-box" style="padding:30px 25px;">
					<div class="form-elm" style="display:inline-block; width:49%;">
						<label for="mqtt_server">Server</label>
						<input type="text" name="mqtt_server" id="mqtt_server" value="<?php echo $_SESSION['mqtt_server']; ?>" autofocus required>
					</div>

					<div class="form-elm" style="display:inline-block; width:49%;">
						<label for="mqtt_port">Port</label>
						<input type="text" name="mqtt_port" id="mqtt_port" value="<?php echo $_SESSION['mqtt_port']; ?>" required>
					</div>

					<div class="form-elm" style="display:inline-block; width:49%;">
						<label for="mqtt_username">Username</label>
						<input type="text" name="mqtt_username" id="mqtt_username" value="<?php echo $_SESSION['mqtt_username']; ?>">
					</div>

					<div class="form-elm" style="display:inline-block; width:49%;">
						<label for="mqtt_password">Password</label>
						<input type="text" name="mqtt_password" id="mqtt_password" value="<?php echo $_SESSION['mqtt_password']; ?>">
					</div>

					<div class="form-elm" style="display:inline-block; width:49%;">
						<label for="mqtt_client_id">Client ID</label>
						<input type="text" name="mqtt_client_id" id="mqtt_client_id" value="<?php echo $_SESSION['mqtt_client_id']; ?>">
						<div class="help">
							Make sure this is unique for connecting to sever
						</div>
					</div>
				</div>

				<div style="text-align:right; margin-top:15px;">
					<button class="button button-primary button-pill button-large" type="submit">Save</button>
				</div>
			</form>








		<!--
			
			Tellstick token

			Generate token for Tellstick

		-->
		<?php elseif (empty($tellstick_access_token['token'])): ?>

			<?php			
				$telldus = new TellstickMQTT\Telldus\TellstickLocalApi($tellstick_conf['app_name'], $tellstick_conf['ip']);
				$get_request_token = $telldus->get_request_token();
			?>


			<h2>Step 1: Authenticate</h2>
			<div class="white-box" style="padding:15px 25px;">
				<p>
					First you will need to authenticate with your Telldus-account.<br />
					This will generate an login-token, that will be sent to your Tellstick in step 2.
				</p>
				<a class="button button-primary button-pill button-large" href="<?php echo $get_request_token->authUrl; ?>" target="_blank">
					Authenticate the app
				</a>
				
				<div style="margin-top:8px;">
					<p style="font-size:13px; font-style:italic;">
						The button should open a new window with Telldus login form.
						If this doesn't work, you have probably entered the wrong IP-address.
						Reset config in the bottom, to start over.
					</p>
				</div>
				
			</div>


			<h2>Step 2: Get access token</h2>

			<div class="white-box" style="padding:15px 25px;">
				<p>
					Get access token from your Tellstick.<br />
					This token will be stored as a .json file in the web-root.<br />
					The access token will be used to communicate with your Tellstick until it expires.
				</p>

				<?php if (isset($get_request_token->token) && !empty($get_request_token->token)): ?>
					<a class="button button-primary button-pill button-large" href="app/api/Telldus.php?a=getAccessToken&token=<?php echo $get_request_token->token; ?>" target="_blank">
						Get access token
					</a>
				<?php else: ?>
					<div style="color:red;">
						Authenticate before continue with step 2!
					</div>
				<?php endif; ?>
				
				
				<br /><br />
			</div>







		<!--
			
			If all is configured, show data from Tellstick

		-->
		<?php else: ?>

			<h1>Tellstick data</h1>

			<?php if (!$tellstick_access_token['allowRenew']): ?>
				<div class="alert alert-warning">
					Autorenew token is off!
				</div>
			<?php endif; ?>

			<?php if ($tellstick_access_token['expires'] < time()): ?>
				<div class="alert alert-danger">
					Token has expired!
				</div>
			<?php else: ?>
				<div class="alert alert-warning">
					Token will expire at <?php echo date('Y-m-d H:i:s', $tellstick_access_token['expires']); ?>
				</div>
			<?php endif; ?>

			<?php
				$devices = $telldus->rpc('devices/list?supportedMethods=3');
				$sensors = $telldus->rpc('sensors/list');
			?>

			<h2>Sensors</h2>
			<?php if (count($sensors->sensor) > 0): ?>
				<?php foreach ($sensors->sensor as $key => $thisDevice): ?>

					<?php
						//$device_name = urlencode(strtolower($thisDevice->name));
						//$topic = $mqtt_conf['client_id']."/sensor/".$device_name."/".$data->name;
					?>

					<a class="sensor-row white-box" href="javascript:getSensorData(<?php echo $thisDevice->id; ?>, '<?php echo $mqtt_conf['client_id']; ?>');">

						<div class="sensor-id">
							<label for="sensor-id">ID</label>
							<?php echo $thisDevice->id; ?>
						</div>

						<div class="sensor-name">
							<label for="sensor-name">Name</label>
							<?php echo $thisDevice->name; ?>
						</div>

						<div class="sensor-battery">
							<label for="sensor-battery">Battery</label>
							<?php if (isset($thisDevice->battery)): ?>
								<?php echo $thisDevice->battery; ?>
							<?php endif; ?>
						</div>

						

						<div class="sensor-model">
							<label for="sensor-model">Model</label>
							<?php echo $thisDevice->model; ?>
						</div>

						

						<div class="sensor-novalues">
							<label for="sensor-novalues">No values</label>
							<?php echo $thisDevice->novalues; ?>
						</div>

						<div class="sensor-protocol">
							<label for="sensor-protocol">Protocol</label>
							<?php echo $thisDevice->protocol; ?>
						</div>

						<div class="sensor-sensorId">
							<label for="sensor-sensorId">Sensor ID</label>
							<?php echo $thisDevice->sensorId; ?>
						</div>
						
					</a>
				<?php endforeach ?>
			<?php endif; ?>

			<h2>Devices</h2>
			<?php if (count($devices->device) > 0): ?>
				<?php foreach ($devices->device as $key => $thisDevice): ?>
					<div class="device-row white-box">

						<div class="device-id">
							<label for="device-id">ID</label>
							<?php echo $thisDevice->id; ?>
						</div>

						<div class="device-name">
							<label for="device-name">Name</label>
							<?php echo $thisDevice->name; ?>
						</div>

						<div class="device-methods">
							<label for="device-methods">Methods</label>
							<?php echo $thisDevice->methods; ?>
						</div>

						<div class="device-state">
							<label for="device-state">State</label>
							<?php echo $thisDevice->state; ?>
						</div>

						<div class="device-statevalue">
							<label for="device-statevalue">State value</label>
							<?php echo $thisDevice->statevalue; ?>
						</div>

						<div class="device-type">
							<label for="device-type">Type</label>
							<?php echo $thisDevice->type; ?>
						</div>

					</div>
				<?php endforeach ?>
			<?php endif; ?>

		<?php endif; ?>
		





		<!--
			
			Reset buttons, to reset config files

		-->
		<div style="margin-top:25px;">
			<?php if (!empty($tellstick_conf['ip'])): ?>
				<a class="button button-caution button-pill button-small" href="app/api/Setup.php?a=removeConfig">
					Reset all config
				</a>
			<?php endif; ?>

			<?php if (!empty($tellstick_conf['ip'])): ?>
				<a class="button button-highlight button-pill button-small" href="app/api/Setup.php?a=removeConfigTellstick">
					Reset Tellstick config
				</a>
			<?php endif; ?>

			<?php if (!empty($tellstick_access_token['token'])): ?>
				<a class="button button-highlight button-pill button-small" href="app/api/Setup.php?a=removeConfigTellstickToken">
					Reset Tellstick token
				</a>
			<?php endif; ?>

			<?php if (!empty($mqtt_conf['server'])): ?>
				<a class="button button-highlight button-pill button-small" href="app/api/Setup.php?a=removeConfigMqtt">
					Reset MQTT config
				</a>
			<?php endif; ?>
		</div>
		
	</div>



	


	<!--
			
		Sidebar to show device/sensor values

	-->
	<div class="sidebar">
		<div class="close" onClick="close_sidebar();">X</div>
		<div class="sidebar-inner" id="sidebar">
		</div>
	</div>


	



	<script>

		/*
			Append sensor values to sidebar
		*/
		function getSensorData(id, client_id)
		{
			$('.sidebar').hide();

			$.ajax({
				type: "GET",
				url: "app/api/Telldus.php?a=getSensorData&id="+id,
				data: {},
				cache: false,
				success: function(response) {
					console.log(response);
					$('#sidebar').html('');

					var devicename = encodeURIComponent(response.name);
					devicename = devicename.toLowerCase();

					var topic = client_id+'/sensor/'+devicename+'/';
					console.log(topic);

					$('#sidebar').append('<h2>'+response.name+'</h2>');

					$.each(response.data, function(index) {
						$('#sidebar').append('<h4>'+response.data[index].name+'</h4><div class="gray-box"><b>Value:</b> '+response.data[index].value+'<br /><b>Topic:</b> '+topic+response.data[index].name+'</div><br />');
						
					});


					$('.sidebar').fadeIn();
				},
				error: function(xhr, status, error) {
					console.log("Ajax error: " + xhr.responseText);
					console.log("Ajax error: " + status);
					console.log("Ajax error: " + error);
				},
				dataType: "json"
			});
		}


		/*
			Close sidebar
		*/
		function close_sidebar()
		{
			$('.sidebar').hide();
			$('#sidebar').html('');
		}
	</script>


</body>
</html>