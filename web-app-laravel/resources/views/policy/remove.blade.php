@extends('policy.layout')


@push('content')
	
	<form class="" id="myForm" role="form" method="POST" action="{{route('policy.delete')}}">
		{{csrf_field()}}
	
	<div class="form-group">
		<select name="policy" class="form-control" id="policySelectBox">

			@foreach($policies as $policy)
				<option value="{{$policy['id']}}">{{$policy['description']}}</option>
			@endforeach
		</select>
	</div>
		
	<div class="form-group" align="center">
		<button class="btn btn-danger" type="submit">Remove</button>
		<button type="button" class="btn btn-default">Cancel</button>
	</div>

	</form>
	

@endpush

@push('js')
	
@endpush