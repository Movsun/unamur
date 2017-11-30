
<table class="table">
 <tbody >
  <tr style="margin-top: 0!important;">

  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('setting.change-email')}}" @if(Route::currentRouteName() == 'setting.change-email') style="color: green;" @endif>
   Change Email </a>
  </td>
  <td style="border-top: 0!important; padding-top: 0!important; padding-left: 0;">
   <a href="{{route('setting.change-password')}}" @if(Route::currentRouteName() == 'setting.change-password') style="color: green;" @endif> Change Password </a>
  </td>

</tr>
 </tbody>
</table>
