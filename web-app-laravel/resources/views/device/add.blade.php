@extends('device.layout')

@push('content')
    	<form class="" id="myForm" role="form" method="POST" action="{{route('device.store')}}">

    		<div class="form-group">
			    <label for="device_eui" class="control-label">Device EUI | BLE MAC (0000BLE-MACADDRESS)</label>
			    <div class="">
			        <input id="device_eui" type="text" class="form-control hex-only-input" name="device_eui" value="{{ old('device_eui') }}" required autofocus>
			    </div>
			</div>
			
	        @include('device.field')

	        <div class="form-group" align="center">
	            <div class="" >
	                <button type="submit" class="btn btn-primary">
	                    Add
	                </button>
	                <button type="button" class="btn btn-default">
	                    Cancel
	                </button>
	            </div>
	        </div>
		</form>      
@endpush

@push('js')
 <script type="text/javascript">
 	$(document).ready(function(){
 		var dev_eui = $('#device_eui');
 		var form = $('#myForm');
 		form.on('submit', function(){
 			if (validateHexKeyInput(dev_eui.val(), 16)) {
 				return true;
 			} else {
 				return false;
 			}

 			e.preventDefault();
 		})


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
 	});
 </script>
@endpush


  
