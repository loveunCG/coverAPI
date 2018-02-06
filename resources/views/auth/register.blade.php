@extends('layouts.auth')
@section('content')
    <div class="container" id="registration-form">
        <a href="index.html" class="login-logo"><img src="{{asset('public/img/logo-big.png')}}"></a>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>Registration</h2>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" id="registerForm" method="POST" action="{{ route('register') }}" >
                            {{ csrf_field() }}
                            <div class="form-group mb-md{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-xs-4 control-label">Username</label>
                                <div class="col-xs-8">
                                    <input type="text" data-parsley-minlength="6" class="form-control" name="name"  value="{{ old('name') }}" placeholder="Username" required>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group mb-md{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-xs-4 control-label">Email</label>
                                <div class="col-xs-8">
                                    <input type="text" data-parsley-type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group mb-md{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="Password" class="col-xs-4 control-label">Password</label>
                                <div class="col-xs-8">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group mb-md">
                                <label for="ConfirmPassword" class="col-xs-4 control-label">Confirm</label>
                                <div class="col-xs-8">
                                    <input type="password" data-parsley-equalto="#password" name="password_confirmation" class="form-control"  placeholder="Confirm Password" required>
                                </div>
                            </div>
                            <div class="form-group mb-n">
                                <div class="col-xs-12">
                                    <div class="checkbox icheck">
                                        <label for=""><input type="checkbox" required /> I accept the <a href="#">user agreement</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-footer">
                        <div class="clearfix">
                            <button type="button" id="register_btn" class="btn btn-primary pull-right">Register</button>
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
            $('#registerForm').parsley().validate();
            $('#register_btn').click(function () {
                $('#registerForm').submit();
            });
        })
    </script>
@endsection

