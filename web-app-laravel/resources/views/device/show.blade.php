@extends('device.layout')

@push('content')
	<div class="table-responsive">
	<table class="table table-bordered">
		<thead> 
			<tr> 
				<th align="center">EUI</th>
				<th align="center">Name </th>
				<th align="center">Type</th>
				<th align="center">Ver</th>
				<th align="center">Des</th>
			</tr>
		</thead>

		<tbody>
			@if(isset($devices))
				@foreach($devices as $device)
				<tr>
					<td> {{$device['device_eui']}} </td>
					<td> {{$device['device_name']}} </td>
					<td> {{$device['device_type']}} </td>
					<td> {{$device['device_version']}} </td>
					<td> {{$device['device_description']}} </td>
				</tr>
				@endforeach
			@endif
		</tbody>

	</table>
	</div>
	<br><br>
@endpush