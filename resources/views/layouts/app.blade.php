<!DOCTYPE html>
<html lang="en">
@include('layouts.app.head')
<body class="animated-content">
@include('layouts.app.header')
<div id="wrapper">
    <div id="layout-static">
        <div class="static-sidebar-wrapper sidebar-default">
            @include('layouts.app.navigation')
        </div>
        <div class="static-content-wrapper">
            @yield('content')
        </div>
    </div>
</div>
</body>
@include('layouts.app.javascript')
@yield('javascript')
</html>