import binascii
import struct
import time
import sys
import paho.mqtt.client as paho
from bluepy.btle import UUID, Peripheral
import json
import base64

class PWR:
    def __init__(self, p, w, r):
        self.p = p
        self.w = w
        self.r = r

write_uuid = UUID('6e400002b5a3f393e0a9e50e24dcca9e')
read_uuid = UUID('6e400003b5a3f393e0a9e50e24dcca9e')
myList = dict()
wList = dict()
f = open('knownUuidList.txt', 'r')
knownUuidList = json.loads(f.read())
f.close()

def read_and_pub(client, topic, peripheral):
    # msg = dict()
    sList = []
    for s in peripheral.getServices():
        # crs = dict()
        # msg[s.uuid.getCommonName()] = crs
        cList = []
        sd = dict()
        for c in s.getCharacteristics():
            cd = dict()
            if c.supportsRead():
                cd[c.uuid.getCommonName()] = base64.b64encode(c.read())
                cList.append(cd)
            else:
                if 'NOTIFY' in c.propertiesToString() and str(c.uuid) in knownUuidList:
                    cd[c.uuid.getCommonName()] = base64.b64encode(c.read())
                    cList.append(cd)
        if cList:
            sd[s.uuid.getCommonName()] = cList
            sList.append(sd)
    print('publish data')
    client.publish(topic=topic, payload=json.dumps(sList))

def sendBle(mac, action, client):
    try:
        if mac in myList:
            pwr = myList[mac]
        else:
            print('connecting to ' + mac + '...')
            p = Peripheral(mac, 'random')
            print('connected')
            w = p.getCharacteristics(uuid=write_uuid)[0]
            r = p.getCharacteristics(uuid=read_uuid)[0]
            pwr = PWR(p, w, r)
            myList[mac] = pwr
        if action == '2':
            read_and_pub(client=client, topic='0000'+mac.replace(':', ''), peripheral=pwr.p)
        else:
            print('sending command : ' + action)
            pwr.w.write(action)
            time.sleep(0.05)
            print('reading reply')
            print(pwr.r.read())
    except Exception as e:
        print('error ', e)
        myList.pop(mac, None)
        pass

broker = '127.0.0.1'
def on_message(client, userdata, message):
    time.sleep(1)
    messageString = message.payload.decode('utf-8')
    print('message arrive: '+ messageString)
    words = messageString.split(' - ')
    mac = ':'.join(words[0][4:][i : i + 2] for i in range(0, 11, 2))
    sendBle(mac, words[1], client)
client = paho.Client()
client.on_message = on_message
print('connecting to broker')
client.connect(broker)
print('subscribing to ble_decision')
client.subscribe('ble_decision')
client.loop_forever()

