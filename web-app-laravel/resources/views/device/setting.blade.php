@extends('device.layout')

@push('content')

	<form method="post" action="{{route('device.updateSetting')}}" id="deviceSettingForm">
		{{csrf_field()}}
		<div class="form-group">
		    <label for="device_eui" class="control-label">Device EUI</label>
		    <div class="">
		        <select id="device_eui" class="form-control" name="device_eui" value="{{ old('device_eui') }}" required autofocus>
		        	@if (isset($devices))
		        		@foreach ($devices as $device)
			        		<option value="{{$device['id']}}"> {{$device['device_eui']}} </option>
			        	@endforeach
		        	@endif
		       	</select>
		    </div>
		</div>

		<div class="form-group">
		    <label for="device_description" class="control-label">Network Type</label>
		    <div class="">
		        <input id="device_description" class="form-control" name="device_description" value="{{ old('device_description') }}" readonly="readonly">
		        	
		       	
		    </div>
		</div>

		<div id="loraForm" style="display:none;">
			<div class="form-group">
		    	<label for="device_activation" class="control-label">Activation</label>
			    <div class="">
			        <label class="radio-inline"><input type="radio" name="device_activation" checked="checked" value="otaa">OTAA</label>
					<label class="radio-inline"><input type="radio" name="device_activation" value="abp">ABP</label>
			    </div>
			</div>
		</div>

		

		<div id="otaaForm" style="display:none;">
			<div class="form-group">
		    	<label for="app_eui" class="control-label">Application EUI (8 bytes hex)</label>
			    <div class="">
			        <input name="app_eui" id="app_eui" type="text" class="form-control hex-only-input">
			    </div>
		    </div>

		    <div class="form-group">
		    	<label for="app_key" class="control-label">Application Key (16 bytes hex)</label>
			    <div class="">
			        <input name="app_key" id="app_key" type="text" class="form-control hex-only-input">
			    </div>
			</div>
			<div align="center" style="font-size: 11px;"> 
				Keys are generated automatically if leave them blank.
			</div>
		</div>


		<div id="abpForm" style="display:none;">
			<div class="form-group">
		    	<label for="app_eui2" class="control-label">Application EUI (8 bytes hex)</label>
			    <div class="">
			        <input name="app_eui2" id="app_eui2" type="text" class="form-control hex-only-input">
			    </div>
		    </div>
			<div class="form-group">
		    	<label for="device_address" class="control-label">Device Address (4 bytes hex)</label>
			    <div class="">
			        <input name="device_address" id="device_address" type="text" class="form-control hex-only-input">
			    </div>
		    </div>

		    <div class="form-group">
		    	<label for="network_session_key" class="control-label">Network Session Key (16 bytes hex)</label>
			    <div class="">
			        <input name="network_session_key" id="network_session_key" type="text" class="form-control hex-only-input">
			    </div>
			</div>

			<div class="form-group">
		    	<label for="application_session_key" class="control-label">Application Session Key (16 bytes hex)</label>
			    <div class="">
			        <input name="application_session_key" id="application_session_key" type="text" class="form-control hex-only-input">
			    </div>
			</div>
			<div align="center" style="font-size: 11px;"> 
				Keys are generated automatically if leave them blank.
			</div>
		</div>

		

		<br><br>

		<div class="form-group" align="center">
            <div class="" >
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
                <button type="button" class="btn btn-default">
                    Cancel
                </button>
            </div>
        </div>
	</form>

	@push('remove-setting')
		<a href="#" style="font-size: 11px; color: red;" id="removeSettingBtn">Remove Setting</a>
		<form id="removeSettingForm" action="{{route('device.removeSetting')}}" method="POST">
			{{csrf_field()}}
		</form>
	@endpush
@endpush

@push('js')
	<script type="text/javascript">
	$(document).ready(function(){
		var devices = {!! json_encode($devices->toArray()) !!};

		var device_description = $('#device_description');
		var device_eui = $('#device_eui');
		var loraForm = $('#loraForm');
		var device_activation = $('input[name=device_activation]');
		var otaaForm = $('#otaaForm');
		var abpForm = $('#abpForm');

		changeFieldValue(device_eui.val());
		device_eui.on('change', function(){
			var id = $(this).val();
			changeFieldValue(id);
			updateFieldValue(id);
		});

		
		device_activation.on('change', function(){
			var val = $('input[name=device_activation]:checked').val();
			otaaAbpInputToggle(val);
		});


		function changeFieldValue(id){
			var result = $.grep(devices, function(e){ return e.id == id; });
			var des = result[0]['device_description'];
			device_description.val(des);
			if (des == "LoRa") {
				loraForm.show();
				otaaAbpInputToggle($('input[name=device_activation]:checked').val());
			} else {
				loraForm.hide();
				otaaForm.hide();
				abpForm.hide();
			}
		}

		function otaaAbpInputToggle(val){
			if (val == 'otaa') {
				otaaForm.show();
				abpForm.hide();
			} else if (val == 'abp'){
				abpForm.show();
				otaaForm.hide();
			} else {
				abpForm.hide();
				otaaForm.hide();
			}
		}

		function validateHexKeyInput(value, size){
			if (value.length != size) {
				return false;
			}

			return /[0-9A-F]{8,32}$/i.test(value);
		}

		$('.hex-only-input').on('keydown', function(e){
			var a = e.which;
			if (a == 8 || a == 46 || a == 37 || a == 39) { // 8 is backspace
		        return;
		    }
			var c = String.fromCharCode(e.which);
			var bol = /[0-9A-F]{1}$/i.test(c);
			if (!bol) {
				e.preventDefault();
			}
		});


		var form = $('#deviceSettingForm');
		var app_key = $('#app_key');
		var app_eui = $('#app_eui');
		var app_eui2 = $('#app_eui2');
		var dev_addr = $('#device_address');
		var netskey = $('#network_session_key');
		var appskey = $('#application_session_key');

		form.on('submit', function(e){
			var type = device_description.val();
			// e.preventDefault();
			if (type == 'LoRa') {

				var activation = $('input[name=device_activation]:checked').val();
				

				if (activation == 'otaa') {

					if (app_eui.val().length == 0 && app_key.val().length == 0) {
						return true;
					}

					else if (validateHexKeyInput(app_eui.val(), 16) && validateHexKeyInput(app_key.val(), 32)) {

						return true;	
					} else {
						// todo: show error message

						return false;
					}
					
				} else if( activation == 'abp'){
					if (app_eui2.val().length == 0 && dev_addr.val().length == 0 && netskey.val().length == 0 && appskey.val().length == 0){
						return true;
					}
					else if (validateHexKeyInput(app_eui2.val(), 16) && validateHexKeyInput(dev_addr.val(), 8) && validateHexKeyInput(netskey.val(), 32) && validateHexKeyInput(appskey.val(), 32)) 
					{

						return true;	
					} else {
						// todo: show error message

						return false;
					}
				}

				return false;
			} else {
				
				return true;
			}
			e.preventDefault();
		});

		//todo: limit hex input size on typing

		var removeSettingBtn = $('#removeSettingBtn');
		var removeSettingForm = $('#removeSettingForm');
		removeSettingBtn.on('click', function(){
			if(confirm("Are you sure?") == true){
				var id = device_eui.val();
				removeSettingForm.append($('<input />').attr('type', 'hidden')
          .attr('name', "device_id")
          .attr('value', id)).submit();

			} 
			
		});
		updateFieldValue(device_eui.val());

		function updateFieldValue(id){
			var result = $.grep(devices, function(e){ return e.id == id; });
			// console.log(result);
			var des = result[0]['lora_activation'][0];
			app_eui.val('');
			app_key.val('');
			app_eui2.val('');
			dev_addr.val('');
			appskey.val('');
			netskey.val('');
			if (des != undefined){
				var mode = des.lora_mode;
				switch(mode){
					case "otaa": 
						$('input[value="otaa"]').trigger('click');
						app_eui.val(des.application_eui);
						app_key.val(des.application_key);
						break;
					case "abp":
						$('input[value="abp"]').trigger('click');
						app_eui2.val(des.application_eui);
						dev_addr.val(des.device_address);
						appskey.val(des.application_session_key);
						netskey.val(des.network_session_key);
						break;
					default:
						break;
				}
				
			}
		}


	});	
	</script>
@endpush