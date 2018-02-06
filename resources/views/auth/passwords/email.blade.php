@extends('layouts.auth')
@section('content')


<div class="container" id="forgotpassword-form">
    <a href="#" class="login-logo"><img src="{{asset('public/img/logo-big.png')}}"></a>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>ReSet Password</h2>
                </div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" id="resetPasswdForm" action="{{ route('password.email') }}" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group mb-n">
                            <div class="col-xs-12">
                                <p>Enter your email to reset your password</p>
                                <div class="input-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<span class="input-group-addon">
										<i class="ti ti-user"></i>
									</span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email Address">
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer">
                    <div class="clearfix">
                        <a href="{{route('login')}}" class="btn btn-default pull-left">Go Back</a>
                        <a href="" id="resetPasswd" class="btn btn-primary pull-right">Reset</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
    <script>
        $('#resetPasswdForm').parsley().validate();
        $(function () {
            $('#resetPasswd').click(function () {
                $('#resetPasswdForm').submit();
            });
        })
    </script>
@endsection
