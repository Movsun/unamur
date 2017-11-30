@extends('setting.layout')
	


@push('content')
	<form action="{{route('setting.store')}}" method="POST">
		@include('setting.field')
		<div class="form-group" align="center">
			<button type="submit" class="btn btn-primary">Submit</button>
			<button type="button" class="btn btn-default">Cancel</button>
		</div>
	</form>
	
@endpush