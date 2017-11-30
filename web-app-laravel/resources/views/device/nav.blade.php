
<table class="table">
 <tbody >
  <tr style="margin-top: 0!important;">
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('device.add')}}" @if(Route::currentRouteName() == 'device.add') style="color: green;" @endif>
   Add</a>
  </td>
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('device.edit')}}" @if(Route::currentRouteName() == 'device.edit') style="color: green;" @endif>Modify </a>
  </td >
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('device.remove')}}" @if(Route::currentRouteName() == 'device.remove') style="color: green;" @endif> Remove </a>
  </td>
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('device.show')}}" @if(Route::currentRouteName() == 'device.show') style="color: green;" @endif> Show </a>
  </td>
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('device.setting')}}" @if(Route::currentRouteName() == 'device.setting') style="color: green;" @endif> Setting </a>
  </td>
</tr>
 </tbody>
</table>