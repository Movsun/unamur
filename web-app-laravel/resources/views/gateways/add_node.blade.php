@extends('gateways.layout')

@section('content')
<form action="{{route('gateway.storeNode')}}" method="post">
  {{ csrf_field() }}
 <div class="form-group">
   <label for="eui">Node EUI:</label>
   <input type="text" class="form-control" id="eui" name='eui'>
 </div>
 <div class="form-group">
   <label for="name">name:</label>
   <input type="text" class="form-control" id="name" name='name'>
 </div>
 <button type="submit" class="btn btn-default">Submit</button>
</form>

<h3>Nodes List:</h3>
<table class="table">
  <thead>
    <td> EUI </td>
    <td> Name </td>
    <td> Action</td>
  </thead>

@foreach($nodes as $node)
<tr>
  <td> {{$node['eui']}}</td>
  <td> {{$node['name']}} </td>
  <td> <a href="{{route('gateway.removeNode', [$node['id']])}}">delete</a></td>
</tr>
@endforeach

<table>
@endsection
