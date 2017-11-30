from bluepy.btle import Scanner, DefaultDelegate
import paho.mqtt.client as mqtt
import json
import base64

# form json message and publish over broker with topic ble/<mac_addr>/up
def publish_to_broker(client, addr, value):
    message = {"payload": base64.b64encode(value), "device_mac": addr}
    topic = 'ble/'+addr + '/up'
    client.publish(topic=topic, payload= json.dumps(message))

# ble device scan handler
class ScanDelegate(DefaultDelegate):
    # init function to setup variable
    def __init__(self, mqttc, manufacturer_id):
        self.mqttc = mqttc
        self.manufacturer_id = manufacturer_id
        DefaultDelegate.__init__(self)
    def handleDiscovery(self, dev, isNewDev, isNewData):
        # only publish when the value is updated
        if isNewData:
            for (adtype, desc, value) in dev.getScanData():
                # only publish our manufacturer data with company id= 1234
                if desc == 'Manufacturer' and value.startswith(self.manufacturer_id):
                    print(value[4:])
                    publish_to_broker(self.mqttc, dev.addr, value[4:])

# read file for broker connection information
f = open('config.txt', 'r')
broker = json.loads(f.read())
f.close()

# connect to broker
mqttc = mqtt.Client()
if broker['username'] != "":
    mqttc.username_pw_set(username=broker['username'], password=broker['password'])
mqttc.connect(host=broker['host'], port=broker['port'])
mqttc.loop_start()

# setup scan delegate and pass mqtt client
scanner = Scanner().withDelegate(ScanDelegate(mqttc, broker['manufacturer_id']))
while True:
    scanner.scan(2)
