<div class="col-md-3 left_col menu_fixed">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('/') }}" class="site_title"><i class="fa fa-database"></i> <span>{{config('app.name')}}</span></a>
        </div>
        
        <div class="clearfix"></div>
        
        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ (auth()->user()->foto) ? "data:image/jpeg;base64,".auth()->user()->foto : asset('assets/img/sas.png') }}" alt="Avatar of {{auth()->user()->name}}" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2 id="homesubcabang">{{ auth()->user()->name }} - {{ (session('subcabang')) ? App::make('App\Http\Controllers\MasterController')->cekSubcabang(session('subcabang')) : ''}}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->
        
        <br>
        
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Menu</h3>
                <ul class="nav side-menu">
                    <li>
                        <a href="{{ url('/') }}"><i class="fa fa-tachometer"></i> Dashboard</a>
                    </li>                   
                </ul>
            </div>
            @include('includes/sidebar-dynamic')   
            <br>
            <br>
            
            <div class="dropup smenu" style="padding:0px 0 0 11px; margin-bottom:-35px">
                <button style="border: none; background-color:transparent" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                    <span style="font-size: 23px; color:#E7E7E7" class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                </button>
                
                <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 8px 8px 8px 0; left: 23px; background-color:ffff">
                    <li style="padding-top: 3px; padding-bottom:3px">
                        <a>
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            &nbsp;&nbsp;&nbsp;&nbsp;Setting
                        </a>
                    </li>
                    <li style="padding-top: 3px; padding-bottom:3px">
                        <a>
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            &nbsp;&nbsp;&nbsp;&nbsp;Full Screen
                        </a>
                    </li>
                    <li style="padding-top: 3px; padding-bottom:3px">
                        <a>
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                            &nbsp;&nbsp;&nbsp;&nbsp;Lock
                        </a>               
                    </li>
                    <li style="padding-top: 3px; padding-bottom:3px">
                        <a href="{{ url('/logout') }}">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            &nbsp;&nbsp;&nbsp;&nbsp;Logout
                        </a>             
                    </li>
                </ul>
            </div>       
                
            <div style="height: 50px;"></div>
        </div>
        <!-- /sidebar menu -->
        
        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
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
        <!-- /menu footer buttons -->
    </div>
</div>