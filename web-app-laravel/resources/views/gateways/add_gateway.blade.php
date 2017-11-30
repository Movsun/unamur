@extends('gateways.layout')

@section('content')
<form action="{{route('gateway.storeGateway')}}" method="post">
  {{ csrf_field() }}
 <div class="form-group">
   <label for="eui">Gateway EUI:</label>
   <input type="text" class="form-control" id="eui" name='eui'>
 </div>
 <div class="form-group">
   <label for="name">name:</label>
   <input type="text" class="form-control" id="name" name='name'>
 </div>
 <button type="submit" class="btn btn-default">Submit</button>
</form>

<h3>Gateways List:</h3>
<table class="table">
  <thead>
    <td> EUI </td>
    <td> Name </td>
    <td> Action</td>
  </thead>

@foreach($gateways as $gateway)
<tr>
  <td> {{$gateway['eui']}}</td>
  <td> {{$gateway['name']}} </td>
  <td> <a href="{{route('gateway.removeGateway', [$gateway['id']])}}">delete</a></td>
</tr>
@endforeach

<table>
@endsection
