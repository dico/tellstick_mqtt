# Tellstick MQTT

As Home Assistant only supports one Tellstick, I created a MQTT bridge between the Tellstick Local API and MQTT.


## Install

 1. Download repository on to your server.
 2. Run docker-compose up ("docker-compose up -d" to run in background)
 3. Open http://<server_ip>:9005
 4. Follow wizard

![Enter Tellstick configuration](https://raw.githubusercontent.com/dico/tellstick_mqtt/master/screenshots/01_tellstick_conf.jpg)

![Enter MQTT configuration](https://github.com/dico/tellstick_mqtt/blob/master/screenshots/02_mqtt_conf.jpg?raw=true)

![Tellstick Local API auth](https://github.com/dico/tellstick_mqtt/blob/master/screenshots/03_tellstick_auth.jpg?raw=true)

![Tellstick token](https://github.com/dico/tellstick_mqtt/blob/master/screenshots/04_tellstick_auth_token.jpg?raw=true)

![Tellstick data](https://github.com/dico/tellstick_mqtt/blob/master/screenshots/05_tellstick_data_sidebar.jpg?raw=true)

## Home Assistant example

      - platform: mqtt
        state_topic: "tellstick_02/sensor/climate/unknown"
        name: "CO2"
        unit_of_measurement: 'ppm'