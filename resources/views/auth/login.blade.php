@extends('layouts.auth')
@section('content')
<div class="container" id="login-form">
    <a href="#" class="login-logo">
        <img src="{{asset('public/img/logo-big.png')}}">
    </a>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Login</h2>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}" id="validate-form">
                        {{ csrf_field() }}
                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                    <span class="input-group-addon">
                                        <i class="ti ti-user"></i>
                                    </span>
                                    <input autofocus id="email" type="email" value="{{ old('email') }}" class="form-control" name="email" required>

                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-md">
                            <div class="col-xs-12">
                                <div class="input-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <span class="input-group-addon">
                                        <i class="ti ti-key"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control" name="password" required>

                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-n">
                            <div class="col-xs-12">
                                <a href="{{ route('password.request') }}" class="pull-left">Forgot password?</a>
                                <div class="checkbox-inline icheck pull-right p-n">
                                    <label for="">
                                        <input type="checkbox" name="remember" {{ old( 'remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <div class="clearfix">
                        {{--<a href="{{ route('register') }}" class="btn btn-default pull-left">Register</a>--}}
                        <button id="login_btn" type="submit" class="btn btn-primary pull-right">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script>
        $(function () {
            $('#login_btn').click(function(){
                $('#validate-form').submit();
            });

            $(function(){
                $('#register_btn').click(function () {
                    alert('this is alert');
                    $('#registerForm').submit();
                });
            });
        })
    </script>
@endsection

