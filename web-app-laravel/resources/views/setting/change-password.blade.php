@extends('setting.layout')


@push('content')
	<form action="{{route('setting.update-password')}}" method="POST">
		{{csrf_field()}}		

		<div class="form-group">
		<label class="control-label">Old Password</label>
		<input type="password" name="old_password" class="form-control" required>
		</div>

		<div class="form-group">
		<label class="control-label">New Password</label>
		<input type="password" name="new_password" class="form-control" required>
		</div>

		<div class="form-group">
		<label class="control-label">Confirm Password</label>
		<input type="password" name="confirm_password" class="form-control" required>
		</div>

		<div class="form-group" align="center">
		<button class="btn btn-success" type="submit">Submit</button>
		<button class="btn btn-default" type="button">Cancel</button>
		</div>


	</form>
@endpush
