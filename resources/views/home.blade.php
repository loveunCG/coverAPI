@extends('layouts.app')

@section('content')

    <div class="static-content">
        <div class="page-content">
            <ol class="breadcrumb">

                <li class="">
                    <a href="index.html">Home</a>
                </li>
                <li class="active">
                    <a href="index.html">Dashboard</a>
                </li>

            </ol>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-tile tile-orange">
                            <div class="tile-icon">
                                <i class="ti ti-shopping-cart-full"></i>
                            </div>
                            <div class="tile-heading">
                                <span>Orders</span>
                            </div>
                            <div class="tile-body">
                                <span>2,150</span>
                            </div>
                            <div class="tile-footer">
                                <span class="text-success">22.5%
                                    <i class="fa fa-level-up"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-tile tile-success">
                            <div class="tile-icon">
                                <i class="ti ti-bar-chart"></i>
                            </div>
                            <div class="tile-heading">
                                <span>Revenues</span>
                            </div>
                            <div class="tile-body">
                                <span>$75,100</span>
                            </div>
                            <div class="tile-footer">
                                <span class="text-danger">12.7%
                                    <i class="fa fa-level-down"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-tile tile-info">
                            <div class="tile-icon">
                                <i class="ti ti-stats-up"></i>
                            </div>
                            <div class="tile-heading">
                                <span>Earnings</span>
                            </div>
                            <div class="tile-body">
                                <span>$40,150</span>
                            </div>
                            <div class="tile-footer">
                                <span class="text-success">5.2%
                                    <i class="fa fa-level-up"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-tile tile-danger">
                            <div class="tile-icon">
                                <i class="ti ti-bar-chart-alt"></i>
                            </div>
                            <div class="tile-heading">
                                <span>Visitors</span>
                            </div>
                            <div class="tile-body">
                                <span>12,600</span>
                            </div>
                            <div class="tile-footer">
                                <span class="text-danger">10.5%
                                    <i class="fa fa-level-down"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
<!-- Load page level scripts-->
<script type="text/javascript" src="{{asset('public/plugins/sparklines/jquery.sparklines.min.js')}}"></script>
<!-- Sparkline -->
<script type="text/javascript" src="{{asset('public/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
<!-- jVectorMap -->
<script type="text/javascript" src="{{asset('public/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jVectorMap -->
<script type="text/javascript" src="{{asset('public/plugins/switchery/switchery.js')}}"></script>
<!-- Switchery -->
<script type="text/javascript" src="{{asset('public/plugins/fullcalendar/moment.min.js')}}"></script>
<!-- Initialize scripts for this page-->
<!-- End loading page level scripts-->
@endsection