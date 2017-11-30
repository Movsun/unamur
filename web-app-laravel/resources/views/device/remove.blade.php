@extends('device.layout')

@push('content')
	<form method="post" action="{{route('device.delete')}}">
		{{csrf_field()}}
		<div class="form-group">
		    <label for="device_eui" class="control-label">Device EUI</label>
		    <div class="">
		        <select id="device_eui" class="form-control" name="device_eui" value="{{ old('device_eui') }}" required autofocus>
		        	@if (isset($devices))
		        		@foreach ($devices as $device)
			        		<option value="{{$device['id']}}"> {{$device['device_eui'].'-'.$device['device_name']}} </option>
			        	@endforeach
		        	@endif
		       	</select>
		    </div>
		</div>

		<br><br>

		<div class="form-group" align="center">
	            <div class="" >
	                <button type="submit" class="btn btn-primary">
	                    Remove
	                </button>
	                <button type="button" class="btn btn-default">
	                    Cancel
	                </button>
	            </div>
	        </div>
	</form>
	
@endpush