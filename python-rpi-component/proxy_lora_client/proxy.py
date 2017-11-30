import paho.mqtt.client as mqtt
import json
import time
import paho.mqtt.publish as publish

f = open("network_info.txt", "r")
network_infos = json.loads(f.read())
f.close()

f = open("network.txt", "r")
networks = json.loads(f.read())
f.close()

f = open("broker.txt", "r")
broker = json.loads(f.read())
f.close()

list_devices = dict()
downlink_network = dict()

#on connect
def on_connect(client, userdata, flags, rc):
    topic = userdata['topic']
    print('subscribe to topic: ',topic)
    client.subscribe(topic)

#on uplink message arrives
def on_uplink_message(client, userdata, msg):
    try:
        payload = json.loads(msg.payload)
        message = extract_uplink_message(userdata['name'], payload)
        list_devices[message['device_eui'].lower()] = {'network': userdata['name'], 'device_id': message['device_id']}
        print('publishing uplink message: ', message)
        # publish uplink message to proxy mqtt broker
        userdata['broker'].publish(topic="node/"+message['device_eui']+"/up", payload=json.dumps(message))
    except Exception as e:
        print(e)

#extract json object for device eui, value, and gateways
def extract_uplink_message(name, payload):
    network = networks[name]
    structure = network['uplink']['message']
    node_value = payload[structure['value']]
    device_eui = payload[structure['device_eui']].lower()
    device_id = payload[structure['device_id']]
    gateways = find_gateways(structure['gateways'], payload)
    return {'payload': node_value, 'device_eui': device_eui, 'gateways': gateways, 'device_id': device_id}

#search for gateways in json payload
def find_gateways(name, payload):
    item = []
    if name in payload:
        val = payload[name].lower()
        return [val]
    for idx in payload:
        tmp = payload[idx]
        if isinstance(tmp, dict):
            i = find_gateways(name, tmp)
            if i is not None:
                item = item + i
        if isinstance(tmp, list):
            for t in tmp:
                i = find_gateways(name, t)
                if i is not None:
                    item = item + i
    return item

#downlink message subscribe
def on_user_downlink_message(client, userdata, msg):
    # parse json payload to array
    message = json.loads(msg.payload)
    # extract device eui from mqtt topic
    device_eui = msg.topic[5:21].lower()
    # extract message value
    port = message['port']
    confirmed = message['confirmed']
    payload = message['payload']
    if device_eui in list_devices:
        name = list_devices[device_eui]['network']
        network_info = get_network_info(name)
        # form downlink message according to the network server that send uplink message
        downlink = networks[name]['downlink']
        topic = downlink['topic']
        topic = topic.replace("<app_id>", network_info['app_id'])
        topic = topic.replace("<dev_id>", list_devices[device_eui]['device_id'])
        topic = topic.replace("<dev_eui>", device_eui)
        data = downlink['message']
        data = data.replace("<port>", str(port))
        data = data.replace("<confirmed>", str(confirmed).lower())
        data = data.replace("<payload>", payload)
        # publish downlink message to last lora network server
        downlink_network[name].publish(topic=topic, payload=data)
        print('start sending downlink to server: ', name)
    else:
        print('can not select network for downlink message, device never send any data to show its network location')

#subscribe to user downlink message in local broker
client = mqtt.Client()
client.on_message = on_user_downlink_message
client.user_data_set({'topic': 'node/+/down'})
client.on_connect = on_connect
if broker['username'] != "":
    client.username_pw_set(username=broker['username'], password=broker['password'])
client.connect(host=broker['host'], port=broker['port'])
# client.subscribe('node/+/down')
client.loop_start()

#uplink message subscribe
for network_info in network_infos:
    mqttc = mqtt.Client()
    name = network_info['name']
    network = networks[name]
    info = network_info['info']
    app_id = info['app_id']
    if "username" in info and info['username'] != "":
        mqttc.username_pw_set(username=info['username'], password=info['password'])
    try:
        mqttc.user_data_set({'name': name, 'broker': client, 'topic': network['uplink']['topic']})
        print('connect to ', name, ' with ', info['host'], ' on port ', info['port'])
        mqttc.on_message = on_uplink_message
        mqttc.on_connect = on_connect
        mqttc.connect(host=info['host'], port=info['port'])
        # topic = network['uplink']['topic']
        # print('subscribe to topic: ',topic)
        # mqttc.subscribe(topic)
        mqttc.loop_start()
        downlink_network[name] = mqttc
    except Exception as e:
        print(e)

def get_network_info(name):
    for network_info in network_infos:
        if name == network_info['name']:
            return network_info['info']

while True:
    # loop forever 
    time.sleep(10)
