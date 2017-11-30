@extends('setting.layout')



@push('content')
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>
					Client ID
				</th>
				<th> 
					Username
				</th>
				<th>
					Password
				</th>	
			</tr>
		</thead>
		<tbody>
			@foreach($settings as $setting)
			<tr> 
				<td>{{$setting['client_id']}}</td>
				<td>{{$setting['username']}}</td>
				<td>{{$setting['password']}}</td>
			</tr>
			@endforeach
		</tbody>	
		</tbody>
	</table>
	
@endpush