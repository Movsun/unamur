@extends('layouts.app')

@push('title')
    <div align="right"> Register </div>
@endpush

@push('content')

    <form class="" role="form" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('fullname') ? ' has-error' : '' }}">
            <label for="fullname" class=" control-label">Fullname</label>

            <div class="">
                <input id="fullname" type="text" class="form-control" name="fullname" value="{{ old('fullname') }}" required autofocus>

                @if ($errors->has('fullname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fullname') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label for="username" class=" control-label">Username</label>

            <div class="">
                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class=" control-label">E-Mail Address</label>

            <div class="">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class=" control-label">Password</label>

            <div class="">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password-confirm" class=" control-label">Confirm Password</label>

            <div class="">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group" align="center">
            <div class="" >
                <button type="submit" class="btn btn-primary">
                    Register
                </button>

                <button type="button" class="btn btn-default">
                    Cancel
                </button>
            </div>
        </div>

        <div class="help-block" align="center">
            Help text!
        </div>

    </form>
@endpush
