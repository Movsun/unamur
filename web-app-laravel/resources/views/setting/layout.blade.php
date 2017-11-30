@extends('layouts.app')

@push('title')
  <div class="row">
  	<div class="col-xs-3">
	  	<a href="{{route('home')}}"> Home </a>
	  </div>
	  <div class="col-xs-9" align="right">
	  	Setting
	  </div>
  </div>
@endpush

@push('nav')
@include('setting.nav')
@endpush