import redis
import paho.mqtt.client as mqtt
import json
import time

f = open('mqtt-config.txt', 'r')
mqtt_config = json.loads(f.read());
f.close()

r = redis.StrictRedis()

def on_connect(client, userdata, flags, rc):
    print("Connected with result code "+str(rc))
    for t in userdata['mqtt_config']['topics']:
        print('subscribe to ' + t)
        client.subscribe(t)

def on_message(client, userdata, msg):
    print(msg.topic, msg.payload)
    userdata['redis_client'].set(msg.topic, msg.payload)

mqttc = mqtt.Client()
mqttc.user_data_set({'mqtt_config': mqtt_config, 'redis_client': r})
mqttc.on_connect = on_connect
mqttc.on_message = on_message
if mqtt_config['username'] != '' and mqtt_config['password'] != '':
    print('set mqtt username password')
    mqttc.username_pw_set(username=mqtt_config['username'], password=mqtt_config['password'])
print('connecting to ' + mqtt_config['host'])
mqttc.connect(host=mqtt_config['host'], port=mqtt_config['port'])

mqttc.loop_start()

p = r.pubsub();
p.psubscribe('*')
while True:
    message = p.get_message()
    if message:
        print(message)
        mqttc.publish(topic= message['channel'], payload=str(message['data']))
    time.sleep(0.001)
