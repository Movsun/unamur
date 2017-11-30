
@extends('gateways.layout')

@section('content')

<h3>Nodes List:</h3>
<table class="table">
  <thead>
    <td> EUI </td>
    <td> Name </td>
    <td> Information</td>
  </thead>

@foreach($nodes as $node)
<tr>
  <td> {{$node['eui']}}</td>
  <td> {{$node['name']}} </td>
  <td id='{{$node["eui"]}}'> No Information</td>
<tr>
@endforeach

<table>


<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>

<script>

  client = new Paho.MQTT.Client('138.48.33.164', Number(9001), "clientIdasdfasdfd");

  // set callback handlers
  client.onConnectionLost = onConnectionLost;
  client.onMessageArrived = onMessageArrived;

  // connect the client
  client.connect({onSuccess:onConnect});


  // called when the client connects
    function onConnect() {
      // Once a connection has been made, make a subscription and send a message.
      console.log("onConnect");
      client.subscribe("movsun/devices/#");

    }

    // called when the client loses its connection
    function onConnectionLost(responseObject) {
      if (responseObject.errorCode !== 0) {
        console.log("onConnectionLost:"+responseObject.errorMessage);
      }
    }

    // called when a message arrives
    function onMessageArrived(message) {
      var msg = message.payloadString;
      var obj = JSON.parse(msg);
      var eui = obj.hardware_serial;
      var gateways = obj.metadata.gateways;
      var time = obj.metadata.time;
      var len = gateways.length;
      console.log(eui);
      console.log(time);
      var str = time+ " - ";
      var i = 0;
      for (i=0; i< len; i++){
        var gtw_id = gateways[i].gtw_id;
        str = str + gtw_id + ', ';
        console.log(gtw_id);
      }
      var myElement = document.getElementById(eui);
      console.log(str);
      myElement.innerHTML = str;
    }
</script>


@endsection
