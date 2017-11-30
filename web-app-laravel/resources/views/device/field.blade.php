{{ csrf_field() }}

<div class="form-group">
    <label for="device_name" class=" control-label">Device Name</label>
    <div class="">
        <input id="device_name" type="text" class="form-control" name="device_name" value="{{ old('device_name') }}" required>
    </div>
</div>
<div class="form-group">
    <label for="device_type" class=" control-label">Device Type</label>
    <div class="">
        <select id="device_type" type="text" class="form-control" name="device_type" required>
            <option value="Actuator">Actuator</option>
            <option value="Sensor">Sensor</option>

        </select>
    </div>
</div>
<div class="form-group">
    <label for="device_version" class=" control-label">Device Version</label>
    <div class="">
        <input id="device_version" type="text" class="form-control" name="device_version" value="{{ old('device_version') }}" required>
    </div>
</div>	
<div class="form-group">
    <label for="device_description" class=" control-label">Device Description</label>
    <div class="">
        <select id="device_description" type="text" class="form-control" name="device_description" value="{{ old('device_description') }}" required>
            <option value="LoRa"> LoRa</option>
            <option value="WiFi"> WiFi</option>
            <option value="BLE"> BLE</option>

        </select>
    </div>
</div>
		    
