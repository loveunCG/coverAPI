<!DOCTYPE html>
<html lang="en" class="coming-soon">
<head>
    <meta charset="utf-8">
    <title>easycover admin</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <link type="text/css" href="{{asset('public/plugins/iCheck/skins/minimal/blue.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('public/fonts/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('public/fonts/themify-icons/themify-icons.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('public/css/styles.css')}}" rel="stylesheet">
</head>
<body class="focused-form animated-content">
@yield('content')
</body>
<script type="text/javascript" src="{{asset('public/js/jquery.min.js')}}"></script>
<!-- Load jQuery -->
<script type="text/javascript" src="{{asset('public/js/jqueryui-1.10.3.min.js')}}"></script>
<!-- Load jQueryUI -->
<script type="text/javascript" src="{{asset('public/js/bootstrap.min.js')}}"></script>
<!-- Load Bootstrap -->
<script type="text/javascript" src="{{asset('public/js/enquire.min.js')}}"></script>
<!-- Load Enquire -->
<script type="text/javascript" src="{{asset('public/plugins/velocityjs/velocity.min.js')}}"></script>
<!-- Load Velocity for Animated Content -->
<script type="text/javascript" src="{{asset('public/plugins/velocityjs/velocity.ui.min.js')}}"></script>
<script type="text/javascript" src="{{asset('public/plugins/wijets/wijets.js')}}"></script>
<!-- Wijet -->
<script type="text/javascript" src="{{asset('public/plugins/codeprettifier/prettify.js')}}"></script>
<!-- Code Prettifier  -->
<script type="text/javascript" src="{{asset('public/plugins/bootstrap-switch/bootstrap-switch.js')}}"></script>
<!-- Swith/Toggle Button -->
<script type="text/javascript" src="{{asset('public/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js')}}"></script>
<!-- Bootstrap Tabdrop -->
<script type="text/javascript" src="{{asset('public/plugins/iCheck/icheck.min.js')}}"></script>
<!-- iCheck -->
<script type="text/javascript" src="{{asset('public/plugins/nanoScroller/js/jquery.nanoscroller.min.js')}}"></script>
<!-- nano scroller -->
<script type="text/javascript" src="{{asset('public/js/application.js')}}"></script>
<script type="text/javascript" src="{{asset('public/plugins/form-parsley/parsley.js')}}"></script>
<!-- End loading site level scripts -->
@yield('javascript')