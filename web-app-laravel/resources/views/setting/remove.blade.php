@extends('setting.layout')



@push('content')
	<form action="{{route('setting.delete')}}" method="POST">
		{{csrf_field()}}
		<div class="form-group">
			<label class="control-label" for="id">Setting</label>
			<select name="id" class="form-control">
				@foreach($settings as $setting)
					<option value="{{$setting['id']}}">{{$setting['name']}}
					</option>
				@endforeach
			</select>
		</div>
	</form>
	
@endpush