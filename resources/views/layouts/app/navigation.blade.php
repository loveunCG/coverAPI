<div class="static-sidebar">
    <div class="sidebar">
        <div class="widget">
            <div class="widget-body">
                <div class="userinfo">
                    <div class="avatar">
                        <img src="http://placehold.it/300&text=Placeholder" class="img-responsive img-circle">
                    </div>
                    <div class="info">
                        <span class="username">{{Auth::user()->name}}</span>
                        <span class="useremail">{{Auth::user()->email}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="widget stay-on-collapse" id="widget-sidebar">
            <nav role="navigation" class="widget-body">
                <ul class="acc-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="fa fa-user"></i>
                            <span>Admin manage</span>
                        </a>
                        <ul class="acc-menu">
                            <li>
                                <a href="{{url('adminMg')}}">
                                    <i class="fa  fa-list-alt"></i>
                                    <span>&nbsp;Admin manage</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="fa fa-group"></i>
                            <span>&nbsp;User Manage</span>
                        </a>
                        <ul class="acc-menu">
                            <li>
                                <a href="{{url('agentMg')}}">
                                    <i class="fa fa-user"></i>
                                    <span>&nbsp;Agent</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{url('custom')}}">
                                    <i class="fa fa-user"></i>
                                    <span>&nbsp;Customer</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="fa fa-th-list"></i>
                            <span>Job Manage</span>
                        </a>
                        <ul class="acc-menu">
                            <li>
                                <a href="{{url('jobs/')}}">
                                    <i class="fa fa-square"></i>
                                    <span>&nbsp;Jobs List</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="fa fa-cog"></i>
                            <span>Setting</span>
                        </a>
                        <ul class="acc-menu">
                            <li>
                                <a href="{{url('setting/insurance')}}">
                                    <i class="fa fa-square"></i>
                                    <span>&nbsp;Add Insurance Type</span>
                                </a>

                                <a href="{{url('setting/company')}}">
                                    <i class="fa fa-indent"></i>
                                    <span>&nbsp;Company Info</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
