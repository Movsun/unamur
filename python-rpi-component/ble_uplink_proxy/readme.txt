This python module scan for BLE advertise data, and then publish those data over mqtt broker

1. We use set our sensor data in advertisement as Manufacturer data.
2. We defined company identify in manufacturer data as 1234.
3. For example: We use 'AT+GAPSETADVDATA=05-ff-12-34-00-54' to set sensor value =0054 in adafruit bluefruit.
  05 - indicate the number of bytes
  ff - the data type define in GAP for Manufacturer isNewData
  1234 - the company identify code
  0054 - the hex value of sensor data
