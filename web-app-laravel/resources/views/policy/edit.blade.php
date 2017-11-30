@extends('policy.layout')


@push('content')

	
	<div class="form-group">
		<select name="policy" class="form-control" id="policySelectBox">

			@foreach($policies as $policy)
				<option value="{{$policy['id']}}">{{$policy['description']}}</option>
			@endforeach
		</select>
	</div>
	
	<form class="" id="myForm" role="form" method="POST" action="{{route('policy.update')}}">
		{{csrf_field()}}
		<div class="form-group">
		    <label for="policy_description" class="control-label">Policy Description</label>
		    <div class="">
		        <input id="policy_description" type="text" class="form-control" name="policy_description" value="{{ old('policy_description') }}" required autofocus>
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
		    			<select id="condition_type" name="condition_type" class="form-control">
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
					    	<button class="btn btn-xs btn-danger x-btn" type="button" style="display:none;">x</button>
					    </div>
		        	</div>
			    </div>
			    <div class="row">
			    <div class="col-xs-11" align="right" style="padding-right: 0!important;">
			    	<button type="button" class="btn btn-xs btn-info add-more-btn" id="euiAddBtn">Add</button>
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
					    	<button class="btn btn-xs btn-danger x-btn" type="button" style="display:none;">x</button>
					    </div>
		        	</div>
			    </div>
			    <div class="row">
				    <div class="col-xs-11" align="right" style="padding-right: 0!important;">
				    	<button type="button" class="btn btn-xs btn-info add-more-btn" id="envAddBtn">Add</button>
				    </div>
			    </div>
		    </div>

		</div>
		<div class="form-group">
		    <label for="condition" class="control-label">Policy Type</label>
		    <div class="">
		    	<div class="">
		    		
		    		<div class="">
		    			<select name="policy_type" class="form-control" id="policy_type">
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



		var policies = <?php echo json_encode($policies->toarray()) ?>;
		// console.log(policies);
		var policySelectBox = $('#policySelectBox');
		var policyDescription = $('#policy_description');
		var policySubject = $('#subject');
		var policyResrouce = $('#resource');
		var policyAction = $('#action');
		var policyCondition = $('#condition_type');
		var policyType = $('#policy_type');
		var envAddBtn = $('#envAddBtn');
		var euiAddBtn = $('#euiAddBtn');
		policySelectBox.on('change', function(){
			updateFieldValue($(this).val());
		});
		updateFieldValue(policySelectBox.val());
		function updateFieldValue(id){
			console.log(getPolicy(id));
			var policy = getPolicy(id);
			// console.log(policy.policy-description);
			policyDescription.val(policy['description']);
			policySubject.val(policy['subject_id']);
			policyResrouce.val(policy['device_id']);
			policyAction.val(policy['action']);
			policyCondition.val(policy['condition_type']);
			policyType.val(policy['type']);
			var conditions = JSON.parse(policy['request'])['condition'];
			// console.log(conditions);
			updateConditionField(conditions);
		}

		function getPolicy(id){
		    for(var i = 0 ; i< policies.length; i++){
		        var obj = policies[i];
		        if(obj['id'] == id){
		        	return obj
		        }
    		}
		}	
		
		function updateConditionField(conditions){
			var sensorCount = 0;
			var envCount = 0;
			// clear/remove old condition
			var xBtn = $('.x-btn');
			$('input[name="eui_value[]"]').each(function(){
				$(this).val('');
			});
			$('input[name="env_value[]"]').each(function(){
				$(this).val('');
			});
			xBtn.each(function(){
				$(this).trigger('click');
			})

			// add condition
			$.each(conditions, function(){
				var type = $(this)[0]['type'];
				var name = $(this)[0]['name'];
				var value = $(this)[0]['value'];
				if (type == 'Sensor'){
					if (sensorCount == 0){
						var euiName = $('select[name="eui_name[]"]');
						var euiValue = $('input[name="eui_value[]"]');
					} else {
						euiAddBtn.trigger('click');
						var euiName = $('select[name="eui_name[]"]').eq(sensorCount);
						var euiValue = $('input[name="eui_value[]"]').eq(sensorCount);

					}
					euiName.find('option').each(function(){
							if($(this).text().indexOf(name) >=0){
								euiName.val($(this).val());
							}
						});
					euiValue.val(value);
					sensorCount += 1;
				}else {
					if (envCount == 0){
						var envType = $('select[name="env_type[]"]');
						var envValue = $('input[name="env_value[]"]');
					} else {
						envAddBtn.trigger('click');
						var envType = $('select[name="env_type[]"]').eq(envCount);
						var envValue = $('input[name="env_value[]"]').eq(envCount);

					}
					envType.val(type);
					envValue.val(value);
					envCount += 1;
				}
			});
		}

		function searchOptionValueInSelectByText(select, text){

			select.find('option').each(function(){
				return $(this).text();
				if($(this).text().indexOf(text) >= 0){
					return $(this).text();
				}
			});
		}

		});



	</script>
@endpush
