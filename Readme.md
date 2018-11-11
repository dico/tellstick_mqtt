# Tellstick MQTT

As Home Assistant only supports one Tellstick, I created a bridge between the Tellstick Local API and MQTT, to get some sensor-values from my second Tellstick into Home Assistant.

This service run cron inside container every minute.

This service auto create MQTT-topics of all sensors. Be aware that there can be issues with sensor-names and non-escaped characters from your Tellstick.

This service does not publish devices yet!


## Install

 1. Download repository on to your server.
 2. Run docker-compose up ("docker-compose up -d" to run in background)
 3. Open http://<server_ip>:9005
 4. Follow wizard

![enter image description here](https://raw.githubusercontent.com/dico/tellstick_mqtt/master/screenshots/01_tellstick_conf.jpg)

![enter image description here](https://github.com/dico/tellstick_mqtt/blob/master/screenshots/02_mqtt_conf.jpg?raw=true)

![enter image description here](https://github.com/dico/tellstick_mqtt/blob/master/screenshots/03_tellstick_auth.jpg?raw=true)

![enter image description here](https://github.com/dico/tellstick_mqtt/blob/master/screenshots/04_tellstick_auth_token.jpg?raw=true)

![enter image description here](https://github.com/dico/tellstick_mqtt/blob/master/screenshots/05_tellstick_data_sidebar.jpg?raw=true)

## Home Assistant example

      - platform: mqtt
        state_topic: "tellstick_02/sensor/climate/unknown"
        name: "CO2"
        unit_of_measurement: 'ppm'

## Change Apache port?

You can change the Apache port in /.docker/telldus/ports.conf.
Remember to change the port in /docker-compose.yaml.
