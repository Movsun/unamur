@extends('action.layout')

@push('content')
	<form method="post" action="{{route('action.create-on-demand')}}">
		{{csrf_field()}}
		<div class="form-group">
		    <label for="device_eui" class="control-label">Device EUI</label>
		    <div class="">
		        <select id="device_eui" class="form-control" name="device_id" value="{{ old('device_eui') }}" required autofocus>
		        	@if (isset($devices))
		        		@foreach ($devices as $device)
			        		<option value="{{$device['id']}}"> {{$device['device_eui'].'-'.$device['device_name']}} </option>
			        	@endforeach
		        	@endif
		       	</select>
		    </div>
		</div>

		<div class="form-group">
		    <label for="interface_model" class="control-label">Interface Model</label>
		    <div class="">
		        <select id="interface_model" class="form-control" name="interface_model" value="{{ old('interface_model') }}" required>
		        	<option value="1">Model 1 (On/Off)</option>
		        	<option value="2">Model 2 (Get)</option>
		        	<option value="3">Model 3 (Not Available)</option>
		        	<option value="4">Model 4 (Not Available)</option>
		       	</select>
		    </div>
		</div>

		<br><br>

		<div class="form-group" align="center">
	            <div class="" >
	                <button type="submit" class="btn btn-primary">
	                    Set
	                </button>
	                <button type="button" class="btn btn-default">
	                    Cancel
	                </button>
	            </div>
	        </div>
	</form>
@endpush
