@extends('layouts.app')

@push('title')
    <div class="row">
        <div class="col-xs-6">
            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
            Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>

        <div class="col-xs-6" align="right">
            <span style="align-self: right; width:auth">{{Auth::user()->username}}</span>
        </div>

    </div>
@endpush

@push('content')

                <div class="panel-body">
                    <div>
                        <a href="{{route('home')}}">
                            Home
                        </a>
                    </div>

                    <div>
                        <a href="{{route('action.list')}}">
                            Action Management
                        </a>
                    </div>

                    <div>
                        <a href="{{route('device')}}">
                            Device Management
                        </a>
                    </div>

                    <div>
                        <a href="{{route('policy.add')}}">
                            Policy Management
                        </a>
                    </div>

                    <div>
                        <a href="{{route('setting')}}">
                            Setting
                        </a>
                    </div>


                    <br><br>

                    <div align="center">
                        <img src="{{asset('img/Android.png')}}" width="120">
                    </div>

                    <br>

                </div>
           
@endpush
