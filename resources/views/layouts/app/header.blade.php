
<header id="topnav" class="navbar navbar-default navbar-fixed-top" role="banner">
    <div class="logo-area">
			<span id="trigger-sidebar" class="toolbar-trigger toolbar-icon-bg">
				<a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar">
					<span class="icon-bg">
						<i class="ti ti-menu"></i>
					</span>
				</a>
			</span>
        <a class="navbar-brand" href="#">EasyCover</a>
    </div>
    <!-- logo-area -->
    <ul class="nav navbar-nav toolbar pull-right">
        <li class="toolbar-icon-bg hidden-xs" id="trigger-fullscreen">
            <a href="#" class="toggle-fullscreen">
					<span class="icon-bg">
						<i class="ti ti-fullscreen"></i>
					</span>
                </i>
            </a>
        </li>
        <li class="dropdown toolbar-icon-bg hidden-xs">
            <a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'>
					<span class="icon-bg">
						<i class="ti ti-email"></i>
					</span>
                <span class="badge badge-deeporange">2</span>
            </a>
            <div class="dropdown-menu notifications arrow">
                <div class="topnav-dropdown-header">
                    <span>Messages</span>
                </div>
                <div class="scroll-pane">
                    <ul class="media-list scroll-content">
                        <li class="media notification-message">
                            <a href="#">
                                <div class="media-left">
                                    <img class="img-circle avatar" src="http://placehold.it/300&text=Placeholder" alt="" />
                                </div>
                                <div class="media-body">
                                    <h4 class="notification-heading">
                                        <strong>Vincent Keller</strong>
                                        <span class="text-gray">‒ Design should be ...</span>
                                    </h4>
                                    <span class="notification-time">2 mins ago</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="#">See all messages</a>
                </div>
            </div>
        </li>
        <li class="dropdown toolbar-icon-bg">
            <a href="#" class="hasnotifications dropdown-toggle" data-toggle='dropdown'>
					<span class="icon-bg">
						<i class="ti ti-bell"></i>
					</span>
                <span class="badge badge-deeporange">2</span>
            </a>
            <div class="dropdown-menu notifications arrow">
                <div class="topnav-dropdown-header">
                    <span>Notifications</span>
                </div>
                <div class="scroll-pane">
                    <ul class="media-list scroll-content">
                        <li class="media notification-success">
                            <a href="#">
                                <div class="media-left">
              										<span class="notification-icon">
              											<i class="ti ti-check"></i>
              										</span>
                                </div>
                                <div class="media-body">
                                    <h4 class="notification-heading">Update 1.0.4 successfully pushed</h4>
                                    <span class="notification-time">8 mins ago</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="#">See all notifications</a>
                </div>
            </div>
        </li>
        <li class="dropdown toolbar-icon-bg">
            <a href="#" class="dropdown-toggle username" data-toggle="dropdown">
                <img class="img-circle" src="http://placehold.it/300&text=Placeholder" alt="" />
            </a>
            <ul class="dropdown-menu userinfo arrow">
                <li>
                    <a href="#/" id="account_profile_open">
                        <i class="ti ti-panel"></i>
                        <span>Account</span>
                    </a>
                </li>
                <li>
                    <a href="#/" id="open_reset_password">
                        <i class="fa  fa-key"></i>
                        <span>Reset Password</span>
                    </a>
                </li>
                <li>
                    <a id="signOutBtn">
                        <i class="ti ti-shift-right"></i>
                        <span>Sign Out</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    <div id="Reset_Password" data-iziModal-title="Reset Password"  data-iziModal-icon="icon-home">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-ctrls" data-actions-container=""></div>
            </div>
            <div class="panel-body">
                <form id="reset_password_form" class="form-horizontal row-border">
                    <div class="form-group">
                        <label class="col-md-3 control-label">Old Password</label>
                        <div class="col-md-9">
                            <input type="password" name="old_password" id="old_password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">New Password</label>
                        <div class="col-md-9">
                            <input type="password" name="new_password"  class="form-control">
                        </div>
                        {{ csrf_field() }}
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Confirm Password</label>
                        <div class="col-md-9">
                            <input type="password" name="new_password_confirmation" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-6 col-md-offset-6">
                        <button class="btn-primary btn" type="button" id="reset_password_btn">Reset Password</button>
                        <button class="btn-default btn" data-izimodal-close="">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


  <div id="account_profile" data-iziModal-title="Account Info"  data-iziModal-icon="icon-home">
    <div class="panel panel-profile">
			  <div class="panel-body">
			    <img src="http://placehold.it/300&amp;text=Placeholder" class="img-circle">
			    <div class="name" id="admin_profile_name"></div>
			    <div class="info" id = "admin_profile_email" ></div>
			    <ul class="list-inline text-center">
			    	<li><a href="#" class="profile-facebook-icon"><i class="ti ti-facebook"></i></a></li>
			    	<li><a href="#" class="profile-twitter-icon"><i class="ti ti-twitter"></i></a></li>
			    	<li><a href="#" class="profile-dribbble-icon"><i class="ti ti-dribbble"></i></a></li>
			    </ul>
			  </div>
			</div>
  </div>


</header>
