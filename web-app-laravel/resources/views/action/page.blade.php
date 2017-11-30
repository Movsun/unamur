@extends('action.layout')

@push('content')
	@if(isset($event))
		<?php $device = $event->devices()->first();
			$des = $device['device_description'];
			$deviceEui = $device['device_eui'];
			$deviceType = $device['device_name'];
			$interfaceModel = $event['interface_model'];
		?>

		<div class="row">
		   <div class="col-xs-6">
		   	{{$deviceType}}
		   </div>
		   <div class="col-xs-6" align="right">
		   	{{$deviceEui}}
		   </div>
		</div>

		@if($event['interface_model'] == 1)
			<div>

				<div align="center">

					<br>

					<img src="{{asset('img/1.png')}}" width="120">

					<br>

					@if ($des == 'WiFi')
						<span class="fa fa-circle" id="mqttStatus" style="color:red;">
						</span>
						<span id="mqttText" style="font-size: 11px;"> Device Disconnected</span>
						<a href="#" id="mqttTry" style="font-size: 11px;"> Try</a>

					@endif
					<br>
					<br>

					<button class="btn btn-primary commandBtn" id="btnOn" data-des='{{$des}}' data-val='TurnOn'> On </button>
					<button class="btn btn-primary commandBtn" id="btnOff" data-des='{{$des}}' data-val='TurnOff'> Off </button>

				</div>
			</div>
		@endif


		@if($event['interface_model'] == 2)
			<div align="center">
				<br>
				<div style="width: 90%; height: 200px; background: linear-gradient(#F2F2F2, #B6B6B6); border-radius: 10px; line-height: 200px; overflow: scroll">
					<span style="color: red; font-size: 2em;" id="sensorValue">No Data</span>
				</div>

				<br>

				@if ($des == 'WiFi')
						<span class="fa fa-circle" id="mqttStatus" style="color:red;">
						</span>
						<span id="mqttText" style="font-size: 11px;"> Device Disconnected</span>
						<a href="#" id="mqttTry" style="font-size: 11px;"> Try</a>

				@endif
				<br>
				<br>

				<button class="btn btn-success commandBtn" style="width: 100px;" id="getBtn" data-des='{{$des}}' data-val='Get'> Get </button>

			</div>

		@endif



		<br>

	@endif
@endpush

@push('js')
	<script type="text/javascript">
		$(document).ready(function(){

			function guidGenerator() {
				    var S4 = function() {
				       return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
				    };
				    return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
			}

			var deviceId = "{{$event->devices()->first()['id']}}";
			var deviceDes = '{{$des}}';
      var mqttServerIp = "{{env('MQTT_SERVER_IP')? env('MQTT_SERVER_IP') : $_SERVER['SERVER_ADDR']}}";
			var mqttServerPort = "{{env('MQTT_SERVER_PORT_WEBSOCKET')}}";
			var xml = <?php echo json_encode($xml); ?>;
			console.log(xml);
			var	client = new Paho.MQTT.Client(mqttServerIp, parseInt(mqttServerPort), guidGenerator());


			var commandBtn = $('.commandBtn');
			commandBtn.on('click', function(){
				var des = $(this).data('des');
				var val = $(this).data('val');
				if (xml.hasOwnProperty(val)){
					message = new Paho.MQTT.Message(xml[val].replace('{action}', val));
					// message.destinationName = "{{$deviceEui}}";
					message.destinationName = "PDP-Request";
						client.send(message);
				}
                                /*
                                  if (des == 'LoRa') {
					if (xml.hasOwnProperty(val)){
						message = new Paho.MQTT.Message(xml[val].replace('{action}', val));
						// message.destinationName = "{{$deviceEui}}";
						message.destinationName = "PDP-Request";
				    	client.send(message);
					} else {
						if(val == 'Get'){

						} else {
							// disabled temperary
							// console.log('send downlink command value: ' + val);
							// sendCommand(val);
						}

					}

				}
				if (des == "WiFi") {
					// console.log('publish to broker value: ' + val);
					// message = new Paho.MQTT.Message(val+'');
					if (xml.hasOwnProperty(val)){
						message = new Paho.MQTT.Message(xml[val].replace('{action}', val));
						// message.destinationName = "{{$deviceEui}}";
						message.destinationName = "PDP-Request";
				    	client.send(message);
					} else {
						// disabled temperary
						// message = new Paho.MQTT.Message(val);
						// message.destinationName = "{{$deviceEui}}";
						// client.send(message);
					}

				}
                             */
			});

			function sendCommand(val){
				$.ajax({
				  type: "POST",
				  url: "{{route('action.command')}}",
				  data: {
				  	"device_id": deviceId,
				  	"value": val
				  }});
			}
			console.log(deviceDes);
			if (true) {
				var mqttStatus = $('#mqttStatus');
				var mqttText = $('#mqttText');
				var mqttTry = $('#mqttTry');
				var sensorValue = $('#sensorValue');

				// set callback handlers
				client.onConnectionLost = onConnectionLost;
				client.onMessageArrived = onMessageArrived;
				// connect the client
				client.connect({onSuccess:onConnect});
				// called when the client connects
				function onConnect() {
				  // Once a connection has been made, make a subscription and send a message.
				  mqttStatus.css('color', '#A2C700');
				  mqttText.text('Device Connected');
				  mqttTry.hide();
				  if ("{{$interfaceModel}}" == '2') {
				  	// if (deviceDes == 'WiFi'){
						client.subscribe("decision_{{$deviceEui}}");
				  		console.log('subscribe to ' + "decision_{{$deviceEui}}");

				  	// }
				  }
				}
				// called when the client loses its connection
				function onConnectionLost(responseObject) {
				  mqttStatus.css('color', 'red');
				  mqttText.text('Device Disconnected');
				  mqttTry.show();
				  //location.reload();
				  console.log('lost connection');
				  if (responseObject.errorCode !== 0) {

				    console.log("onConnectionLost:"+responseObject.errorMessage);
				  }
				}
				// called when a message arrives
				function onMessageArrived(message) {
						console.log("onMessageArrived:"+message.payloadString);

					if (message.destinationName == "decision_{{$deviceEui}}"){
						if (message.payloadString == "1"){

							if (deviceDes == 'WiFi' || deviceDes == 'BLE'){
								console.log('subscribe to {{$deviceEui}}');
								client.subscribe("{{$deviceEui}}");
							} else {
								console.log('subscribe to application/{{$appId}}/node/{{$deviceEui}}/rx')
				  				client.subscribe("application/{{$appId}}/node/{{$deviceEui}}/rx");
							}

						}
					} else if (message.destinationName == "{{$deviceEui}}"){
					  var currentdate = new Date();
                                          if (deviceDes == 'BLE') {sensorValue.removeAttr('style'); sensorValue.parent().css('line-height','15px');}
					  sensorValue.text(message.payloadString + ' (' + currentdate.getHours() + ':' + currentdate.getMinutes() + ':' + currentdate.getSeconds() +')' );
					} else if (message.destinationName == "application/{{$appId}}/node/{{$deviceEui}}/rx"){
						// console.log(message.payloadString);
						var data = JSON.parse(message.payloadString).data;
						console.log(data);
						var currentdate = new Date();
						sensorValue.text(window.atob(data) + ' (' + currentdate.getHours() + ':' + currentdate.getMinutes() + ':' + currentdate.getSeconds() +')' );
					}


				}
			}


		})


	</script>
@endpush
