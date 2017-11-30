@extends('policy.layout')

@push('content')

	<div class="form-group">
	<label for="id" class="control-label">Policy (ID-Description)</label>
	<div class="row">
	<div class="col-xs-9">
	<select name="id" class="form-control" id="policy">
		@foreach($policies as $policy)
			<option value="{{$policy['id']}}">{{$policy['id'].'-'. $policy['description']}}</option>
		@endforeach
	</select>
	</div>
	<div class="col-xs-3">
	<button class="btn btn-success" id="getBtn">Get</button>
	</div>
	</div>
	</div>
	<br>
	<div class="" id="xmlDisplay" style="overflow: auto; width: 100%; height: 300px; background-color: #EBF7F0; border-radius: 1em;" >

	</div>

	
@endpush

@push('js')
<!-- <script src="{{ asset('js/syntaxhighlighter.js') }}"></script>
<pre class="brush: js" >

function foo()
{
}
</pre> -->
<script type="text/javascript">
		$(document).ready(function(){
			var policy = $('#policy');
			var getBtn = $('#getBtn');
			var xmlDisplay = $('#xmlDisplay');
			// SyntaxHighlighter.all();
			// var text = $('#test');
			getBtn.on('click', function(){
				var id = policy.val();
				xmlDisplay.text('');
				$.ajax({
					'url' : "{{route('policy.getXmlFile')}}",
					'method' : "GET",
					'data' : {
						'id' : id
					}, 
					'success': function(res){
						console.log(res.data);
						xmlDisplay.text(res.data);
						// test.text(res.data);
					}
				});
			});
		});
	</script>
@endpush

	