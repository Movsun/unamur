<table class="table">
 <tbody >
  <tr style="margin-top: 0!important;">
  <td style="border-top: 0!important; padding-top: 0!important">
   <a href="{{route('action.on-demand')}}" @if(Route::currentRouteName() == 'action.on-demand') style="color: green;" @endif>
   On-Demand</a>
  </td>
  <td style="border-top: 0!important; padding-top: 0!important">
   <a href="{{route('action.automatic')}}" @if(Route::currentRouteName() == 'action.automatic') style="color: green;" @endif>Automatic </a>
  </td >
  <td style="border-top: 0!important; padding-top: 0!important">
   <a href="{{route('action.list')}}" @if(Route::currentRouteName() == 'action.list') style="color: green;" @endif> List-Dev </a>
  </td>
  <!-- <td style="border-top: 0!important; padding-top: 0!important">
   <a href="{{route('device.show')}}" @if(Route::currentRouteName() == 'device.show') style="color: green;" @endif> Help </a>
  </td> -->
</tr>
 </tbody>
</table>