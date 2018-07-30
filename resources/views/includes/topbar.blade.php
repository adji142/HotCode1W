<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <div class="col-md-8 text-left">
                <ul class="nav navbar-nav">              
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </ul>
            </div>
            
            <!--<div class="col-md-auto text-right smenu">
                <div class="dropdown col-md-auto text-right smenu" style="top:11px;">
                    <button type="button" class="btn dropdown-toggle sbmenu" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                        <span style="right:11px; top:11px; font-size: 15px; position:fixed; background-color:transparent" class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </button>
                   <ul class="dropdown-menu dropdown-menu-right pull-right" style="top:30px; right:10px">
                        <li>
                            <a data-toggle="tooltip" data-placement="top" title="Settings">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                &nbsp;&nbsp;&nbsp;&nbsp;Setting
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                                &nbsp;&nbsp;&nbsp;&nbsp;Full Screen
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tooltip" data-placement="top" title="Lock">
                                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                                &nbsp;&nbsp;&nbsp;&nbsp;Lock
                            </a>               
                        </li>
                        <li>
                            <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ url('/logout') }}">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                                &nbsp;&nbsp;&nbsp;&nbsp;Logout
                            </a>             
                        </li>
                    </ul>
                </div>
            </div>-->

            
            <!--
            <div class="hidden-small">
                <a data-toggle="tooltip" data-placement="top" title="Settings">
                    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                </a>
                <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                    <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                </a>
                <a data-toggle="tooltip" data-placement="top" title="Lock">
                    <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                </a>
                <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ url('/logout') }}">
                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                </a>
            </div>
            -->


            <!-- <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/img/gravatar.png') }}" alt="Avatar of User">
                        John Doe
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                    </ul>
                </li>

                <li role="presentation" class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-green">6</span>
                    </a>
                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        <li>
                            <a>
                                <span class="image"><img src="assets/img/img.jpg" alt="Profile Image" /></span>
                                <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image"><img src="assets/img/img.jpg" alt="Profile Image" /></span>
                                <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image"><img src="assets/img/img.jpg" alt="Profile Image" /></span>
                                <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                            </a>
                        </li>
                        <li>
                            <a>
                                <span class="image"><img src="assets/img/img.jpg" alt="Profile Image" /></span>
                                <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                                <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                            </a>
                        </li>
                        <li>
                            <div class="text-center">
                                <a>
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul> -->
        </nav>
    </div>
</div>
<!-- /top navigation -->
