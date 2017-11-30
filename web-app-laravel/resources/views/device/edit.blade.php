@extends('device.layout')


@push('content')
	
	<form method="post" action="{{route('device.update')}}">

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

		@include('device.field')

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
@endpush

@push('js')
	<script type="text/javascript">
		$(document).ready(function(){
			
			var devices = {!! json_encode($devices->toArray()) !!};
			// console.log(devices);
			var device_eui = $('#device_eui');
			var device_name = $('#device_name');
			var device_version = $('#device_version');
			var device_type = $('#device_type');
			var device_description = $('#device_description');
			changeFieldValue(device_eui.val());
			device_eui.on('change', function(){
				var id = $(this).val();
				changeFieldValue(id);
			});


			function changeFieldValue(id){
				var result = $.grep(devices, function(e){ return e.id == id; });
				device_name.val(result[0]['device_name']);
				device_type.val(result[0]['device_type']);
				device_version.val(result[0]['device_version']);
				device_description.val(result[0]['device_description']);
			}
		});
	</script>
@endpush