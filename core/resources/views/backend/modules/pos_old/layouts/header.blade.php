
<div class="header">
    <div class="header-left active">
        <a href="{{route('mcp')}}" class="logo logo-normal">
            <img  src="{{asset(isFileExist($basic->logo))}}" alt="Bdbooks">
        </a>
{{--        <a href="index.html" class="logo logo-white">--}}
{{--            <img src="images/logo-white.png" alt="">--}}
{{--        </a>--}}
{{--        <a href="index.html" class="logo-small">--}}
{{--            <img src="images/logo-small.png" alt="">--}}
{{--        </a>--}}
    </div>
    <a id="mobile_btn" class="mobile_btn d-none" href="#sidebar">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
    </a>
    <ul class="nav user-menu">

        <li class="nav-item nav-searchinputs">
            <div class="top-nav-search">
                <a href="javascript:void(0);" class="responsive-search">
                    <i class="fa fa-search"></i>
                </a>
                <form action="#" class="dropdown">
                    <div class="searchinputs dropdown-toggle" id="dropdownMenuClickable"
                         data-bs-toggle="dropdown" data-bs-auto-close="false">
                        <input type="text" placeholder="Search">
                        <div class="search-addon">
                            <span><i data-feather="x-circle" class="feather-14"></i></span>
                        </div>
                    </div>
                    <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuClickable">

                    </div>

                </form>
            </div>
        </li>







        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <i data-feather="maximize"></i>
            </a>
        </li>
        <li class="nav-item nav-item-box">
            <a href="email.html">
                <i data-feather="mail"></i>
                <span class="badge rounded-pill">0</span>
            </a>
        </li>

        <li class="nav-item dropdown nav-item-box">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <i data-feather="bell"></i><span class="badge rounded-pill">0</span>
            </a>
            <div class="dropdown-menu notifications">
{{--                <div class="topnav-dropdown-header">--}}
{{--                    <span class="notification-title">Notifications</span>--}}
{{--                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>--}}
{{--                </div>--}}
{{--                <div class="noti-content">--}}
{{--                    <ul class="notification-list">--}}
{{--                        <li class="notification-message">--}}
{{--                            <a href="activities.html">--}}
{{--                                <div class="media d-flex">--}}
{{--                                            <span class="avatar flex-shrink-0">--}}
{{--                                                <img alt="" src="images/avatar-02.jpg">--}}
{{--                                            </span>--}}
{{--                                    <div class="media-body flex-grow-1">--}}
{{--                                        <p class="noti-details"><span class="noti-title">John Doe</span> added--}}
{{--                                            new task <span class="noti-title">Patient appointment booking</span>--}}
{{--                                        </p>--}}
{{--                                        <p class="noti-time"><span class="notification-time">4 mins ago</span>--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </li>--}}

{{--                    </ul>--}}
{{--                </div>--}}
{{--                <div class="topnav-dropdown-footer">--}}
{{--                    <a href="activities.html">View all Notifications</a>--}}
{{--                </div>--}}
            </div>
        </li>

        <li class="nav-item nav-item-box">
            <a href="general-settings.html"><i data-feather="settings"></i></a>
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                        <span class="user-info">
                            <span class="user-letter">
                                <img src="{{asset(Auth::user()->photo)}}" alt="" class="img-fluid">
                            </span>
                            <span class="user-detail">
                                <span class="user-name">{{Auth::user()->name}}</span>
                                <span class="user-role">{{Auth::user()->role->name}}</span>
                            </span>
                        </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                                <span class="user-img"><img src="{{asset(Auth::user()->photo)}}" alt="">
                                    <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>{{Auth::user()->name}}</h6>
                            <h5>{{Auth::user()->role->name}}</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="{{route('profile')}}"> <i class="me-2" data-feather="user"></i> My
                        Profile</a>
                    <a class="dropdown-item" href="general-settings.html"><i class="me-2"
                                                                             data-feather="settings"></i>Settings</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="{{ route('logout') }}"><img src="{{asset('helps/pos_new/images/log-out.svg')}}"
                                                                                 class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>


    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
           aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{route('profile')}}">My Profile</a>
            <a class="dropdown-item" href="general-settings.html">Settings</a>
            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
        </div>
    </div>

</div>