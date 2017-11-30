@extends('setting.layout')


@push('content')
	
    
	<form action="{{route('setting.update-email')}}" method="POST">
		{{csrf_field()}}		

		<div class="form-group">
		<label class="control-label">Password</label>
		<input type="password" name="password" class="form-control" required>
		</div>

		<div class="form-group">
		<label class="control-label">New Email</label>
		<input type="email" name="email" class="form-control" required>
		</div>

		

		<div class="form-group" align="center">
		<button class="btn btn-success" type="submit">Submit</button>
		<button class="btn btn-default" type="button">Cancel</button>
		</div>


	</form>
@endpush
