@extends('action.layout')

@push('content')
	<div class="table-responsive">
		<table class="table table-bordered"> 
		<thead>
			<tr> 
				<!-- <th>Device EUI</th> -->
				<th>Device Name</th>
				<th>Interface Model</th>
				<th>Status</th>
				<th>Check</th>
				<th>Remove</th>
			</tr>
		</thead>
		<tbody>
			@if (isset($events))
				
				@foreach ($events as $event)
					<tr>
						<!-- <td>
							{{$event->devices()->first()['device_eui']}}
						</td> -->
						<td>
							{{$event->devices()->first()['device_name']}}
						</td>
						<td>
							{{$event['interface_model']}}
						</td>
						<td>
							{{$event['type']}}
						</td>
						<td>
							
							<a href="{{route('action.page', $event['id'])}}">Check</a>
						</td>
						<td>
							<a href="{{route('action.remove', $event['id'])}}" onclick="return confirm('Are you sure?')">Remove</a>
						</td>
					</tr>
				@endforeach
			@endif
		</tbody>
	</table> 
	</div>
@endpush