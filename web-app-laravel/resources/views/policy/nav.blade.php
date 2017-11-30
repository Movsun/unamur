
<table class="table">
 <tbody >
  <tr style="margin-top: 0!important;">
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('policy.add')}}" @if(Route::currentRouteName() == 'policy.add') style="color: green;" @endif>
   Add </a>
  </td>
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('policy.edit')}}" @if(Route::currentRouteName() == 'policy.edit') style="color: green;" @endif> Modify </a>
  </td >
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('policy.remove')}}" @if(Route::currentRouteName() == 'policy.remove') style="color: green;" @endif> Remove</a>
  </td>
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('policy.show')}}" @if(Route::currentRouteName() == 'policy.show') style="color: green;" @endif> Show </a>
  </td>
</tr>
 </tbody>
</table>