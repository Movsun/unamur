@extends('policy.layout')


@push('content')

	<form class="" id="myForm" role="form" method="POST" action="{{route('policy.store')}}">
		{{csrf_field()}}
		<div class="form-group">
		    <label for="policy_description" class="control-label">Policy Description</label>
		    <div class="">
		        <input placeholder="Policy Description" id="policy_description" type="text" class="form-control" name="policy_description" value="{{ old('policy_description') }}" required autofocus>
		    </div>
		</div>
		
	    <div class="form-group">
		    <label for="subject" class="control-label">Subject</label>
		    <div class="">
		        <select id="subject" type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
		        	@foreach($users as $user)
		        		<option value={{$user->userProfile()->first()->id}}> {{$user['username']}} </option>
		        	@endforeach
		        </select>
		    </div>
		</div>

		<div class="form-group">
		    <label for="action" class="control-label">Action</label>
		    <div class="">
		        <select id="action" type="text" class="form-control" name="action" value="{{ old('action') }}" required>
		        	<option value="Get">Get</option>
		           	<option value="TurnOn">Turn On</option>
		        	<option value="TurnOff">Turn Off</option>

		        </select>
		    </div>
		</div>

		<div class="form-group">
		    <label for="resource" class="control-label">Resource</label>
		    <div class="">
		        <select id="resource" type="text" class="form-control" name="resource" value="{{ old('resource') }}" required>
		        	@foreach($devices as $device)
		        		<option value={{$device['id']}}> {{$device['device_name']. '-'. $device['device_eui']}} </option>
		        	@endforeach
		        </select>
		    </div>
		</div>

		<div class="form-group">
		    <label for="condition" class="control-label">Condition Type</label>
		    <div class="">
		    	<div class="">
		    		
		    		<div class="">
		    			<select name="condition_type" class="form-control">
				    		<option value="and">And Condition</option>
				    		<option value="or">Or Condition</option>
				    	</select>
		    		</div>

		    	</div>
		    </div>
	    </div>	
	    <div class="form-group">
		    <label for="condition" class="control-label">Condition</label>

		    <div class="">
		        <div class="row" id="">
		        	<div>
		        		<div class="col-xs-6">
			        	<select class="form-control" name="eui_name[]" value="{{ old('condition') }}" required>
			        	@foreach($devices as $device)
			        		<option value={{$device['id']}}> {{$device['device_name']. '-'. $device['device_eui']}} </option>
			        	@endforeach
				        </select>
				        </div>

				        <div class="col-xs-5" style="padding: 0!important;">
					        <input type="test" class="form-control eui-value" name="eui_value[]" >
					    </div>
					    <div class="col-xs-1" style="padding-left: 5px!important;">
					    	<button class="btn btn-xs btn-danger" type="button" style="display:none;">x</button>
					    </div>
		        	</div>
			    </div>
			    <div class="row">
			    <div class="col-xs-11" align="right" style="padding-right: 0!important;">
			    	<button type="button" class="btn btn-xs btn-info add-more-btn" id="">Add</button>
			    </div>
			    </div>
		    </div>


		    <div class="">
		        <div class="row" id="">
		        	<div>
		        		<div class="col-xs-6">
			        	<select class="form-control" name="env_type[]" required>
			        		
			        		@foreach($policyAttributes as $policyAttribute)
			        			@if ($policyAttribute['name'] != 'Sensor')

			        				<option value="{{$policyAttribute['name']}}">{{$policyAttribute['name']}}</option>
			        			@endif
			        		@endforeach

				        </select>
				        </div>
				        <div class="col-xs-5" style="padding: 0!important;">
					        <input type="test" class="form-control env-value" name="env_value[]" >
					    </div>
					    <div class="col-xs-1" style="padding-left: 5px!important;">
					    	<button class="btn btn-xs btn-danger" type="button" style="display:none;">x</button>
					    </div>
		        	</div>
			    </div>
			    <div class="row">
				    <div class="col-xs-11" align="right" style="padding-right: 0!important;">
				    	<button type="button" class="btn btn-xs btn-info add-more-btn" id="">Add</button>
				    </div>
			    </div>
		    </div>

		</div>
		<div class="form-group">
		    <label for="condition" class="control-label">Policy Type</label>
		    <div class="">
		    	<div class="">
		    		
		    		<div class="">
		    			<select name="policy_type" class="form-control">
				    		<option value="automatic">Automatic</option>
				    		<option value="on-demand">On-Demand</option>
				    	</select>
		    		</div>

		    	</div>
		    </div>
	    </div>	
		
	    <div class="form-group" align="center">
	        <div class="" >
	            <button type="submit" class="btn btn-primary">
	                Submit
	            </button>
	            <button type="button" class="btn btn-default">
	                Cancel
	            </button>
	        </div>
	    </div>
	</form>      

@endpush

@push('js')
	<script type="text/javascript">
		$(document).ready(function(){
			// var addMoreEuiBtn = $('#add-more-eui-btn');
			// var euiValueDiv = $('#euiValueDiv');

			// var euiDiv = euiValueDiv.children(':first').clone();
			// euiDiv.find('button').show();
			// addMoreEuiBtn.on('click', function(){
			// 	var cloneEui = euiDiv.clone();
			// 	var xBtn = cloneEui.find('button').on('click', function(){
			// 		$(this).parent().parent().remove();
			// 	});
			// 	cloneEui.appendTo(euiValueDiv);
			// });

			var addMoreBtn = $('.add-more-btn');
			addMoreBtn.each(function(){
				var divToClone = $(this).parent().parent().parent().children(':first').children(':first').clone();
				$(this).on('click', function(){
					var cloneDiv = divToClone.clone();
					var xBtn = cloneDiv.find('button');
					xBtn.show();
					xBtn.on('click', function(){
						$(this).parent().parent().remove();
					});
					var selects = $(this).parent().parent().parent().find('select');
					selects.each(function(){
						cloneDiv.find('option[value="' + $(this).val() + '"]').remove();
					});
					// $(this).parent().parent().parent().find('select').prop('disabled', true);
					$(this).parent().parent().parent().children(':first').append(cloneDiv);


					validateEnvironment();
					validateEuiValue();

				});
			});

			validateEnvironment();
			validateEuiValue();

			$('#myForm').on('submit', function() {
				var euiValue = $('.eui-value');
				euiValue.each(function(){
					// todo: check operation
				});

			});

			function validateEnvironment(){
				var envValue = $(".env-value");
				envValue.off();
				envValue.blur(function(){
					var tmp = $(this).parent().prev().children(':first').val();
					var value = $(this).val();
					if (value == '') return;
					if (tmp == 'Time'){
						var times = value.split('-');
						if (Date.parse('01/01/2011 ' + times[0]) <= Date.parse('01/01/2011 ' + times[1])) {
							
						} else {
							alert('Time is invalid');
							// $(this).focus();
						}
					}

					if (tmp == 'Date'){
						var times = value.split('-');
						if (Date.parse(times[0]) <= Date.parse(times[1])) {
							
						} else {
							alert('Date is invalid');
							// $(this).focus();
						}
					}

					if (tmp == 'Risk Acessment/History' || tmp == 'Risk Acessment/Location'){
						if (value >= 0 && value <=1){

						} else {
							alert('Risk Acessment is invalid');
						}
					}

				});
			}

			function validateEuiValue(){
				var envValue = $(".eui-value");
				envValue.off();
				envValue.blur(function(){
					var value = $(this).val();
					if (value == '') return;
					var op = value[0];
					if (op == '>' || op == '<') {
						if (isNaN(value.substring(1, value.length))){
							alert('Value after > or < must be number');
							// $(this).focus();
						}
					}
					if (op == '=' || op == '>' || op == '<') {

					} else {
						alert('Device value is invalid');
						// $(this).focus();
					}
				});
			}

		});


	</script>
@endpush
