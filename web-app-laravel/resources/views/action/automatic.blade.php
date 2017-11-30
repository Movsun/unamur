@extends('action.layout')


@push('content')
	<form method="post" action="{{route('action.create-automatic')}}">
		{{csrf_field()}}
		<!-- <div class="form-group">
			
		</div> -->
		<div class="form-group">
			<label for="" class="control-label">Actuators :</label> <br>
		    <label for="device_name" class="control-label">Device Name</label>
		    <div class="">
		        <select id="device_name" class="form-control" name="actuator_id" value="{{ old('device_id') }}" required autofocus>
		        	@if (isset($devices))
		        		@foreach ($devices as $device)
		        		   @if($device['device_type'] == 'Actuator')
			        		<option value="{{$device['id']}}"> {{$device['device_name']}} </option>
		        		   @endif     
			        	@endforeach
		        	@endif
		       	</select>
		    </div>
		</div>

		<div class="form-group">
			<label for="" class="control-label">Sensors :</label> <br>
		    <label for="sensor_name" class="control-label">Device Name</label>
		    <div class="row">
		        <div class="col-xs-6">
		        	<select id="sensor_name" class="form-control" name="sensor_id" value="{{ old('device_id') }}" required >
		        	@if (isset($devices))
		        		@foreach ($devices as $device)
		        			@if ($device['device_type'] == 'Sensor')
			        			<option value="{{$device['id']}}"> {{$device['device_name']}} </option>
			        		@endif
			        	@endforeach
		        	@endif
		       	</select>
		        </div>
		        <div class="col-xs-6">
		        	<input id="sensor_value" class="form-control" name="sensor_value" required>
		       	</select>
		        </div>
		        
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