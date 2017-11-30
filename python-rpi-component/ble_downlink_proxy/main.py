from bluepy.btle import UUID, Peripheral
import paho.mqtt.client as mqtt
import json
import base64

# read file for broker connection information
f = open('config.txt', 'r')
broker = json.loads(f.read())
f.close()

# ble characteristic uuid for Nodic's Uart Service, message write to this characteristic will be read by our arduino node
write_uuid = UUID('6e400002b5a3f393e0a9e50e24dcca9e')

#on connect
def on_connect(client, userdata, flags, rc):
    topic = userdata['topic']
    print('subscribe to topic: ',topic)
    # by subscribe to topic in on connect function, the client will always re subscribe when disconnect and reconnect
    client.subscribe(topic)

# on downlink message receive
def on_message(client, userdata, msg):
    try:
        #connecting to device
        mac = msg.topic[4:21]
        print('connecting to ' + mac + '...')
        p = Peripheral(mac, 'random')
        print('connected')
        #sending message to device
        w = p.getCharacteristics(uuid=write_uuid)[0]
        data = json.loads(msg.payload)
        print('writing message to device: ', base64.b64decode(data['payload']))
        w.write(base64.b64decode(data['payload']))
        # disconnect device
        p.disconnect()
    except Exception as e:
        print(e)

# initialize mqtt client connection
mqttc = mqtt.Client()
if broker['username'] != "":
    # set mqtt client username and password
    mqttc.username_pw_set(username=broker['username'], password=broker['password'])
# set on message callback function
mqttc.on_message = on_message
# set userdata for topic to subscribe
mqttc.user_data_set({'topic': 'ble/+/down'})
# set on connect callback function
mqttc.on_connect = on_connect
# connecting to broker
mqttc.connect(host=broker['host'], port=broker['port'])
# stay connected
mqttc.loop_forever()
