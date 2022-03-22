
  <div class="navbar-custom">
                <div class="container-fluid">
                    <ul class="list-unstyled topnav-menu float-right mb-0">

                      
    
                        <li class="dropdown d-inline-block d-lg-none">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fe-search noti-icon"></i>
                            </a>
                            <div class="dropdown-menu dropdown-lg dropdown-menu-right p-0">
                                <form class="p-3">
                                    <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                </form>
                            </div>
                        </li>
    
                        <li class="dropdown d-none d-lg-inline-block">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-toggle="fullscreen" href="#">
                                <i class="fe-maximize noti-icon"></i>
                            </a>
                        </li>
    
                    
    
                       
            
                     
    
                        <li class="dropdown notification-list topbar-dropdown">
                            <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                               
                                <img class="round" width="30" height="30" avatar={{ Auth::user()->name }}>

                                <span class="pro-user-name ml-1">
                                {{ ucwords(Auth::user()->name) }}<i class="mdi mdi-chevron-down"></i> 
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome {{ ucwords(Auth::user()->name) }}!</h6>
                                </div>
    
                                <!-- item-->
                              <a href="{{route('profile.show')}}" class="dropdown-item notify-item">
                                    <i class="fe-user"></i>
                                    <span>My Profile</span>
                                </a>
    
                               
                                <a href="{{route('password.change')}}" class="dropdown-item notify-item">
                                    <i class="fe-lock"></i>
                                    <span>Change Password</span>
                                </a>
    
                                <!-- item-->
                                <a class="dropdown-item notify-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                       <i class="fe-log-out"></i>
                                    <span>Logout</span>
                                    </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
    
                            </div>
                        </li>
    
                    
    
                    </ul>
    
                    <!-- LOGO -->
                    <div class="logo-box">
                        <a href="{{url('home')}}" class="logo logo-dark" >
                            <span class="logo-sm">
                                <img src="{{asset('assets/images/logowhite.jpeg')}}" alt="TOYOTA KENYA" height="70" style="margin-top:5px">  
                                <!-- <span class="logo-lg-text-light">UBold</span> -->
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('assets/images/logowhite.jpeg')}}" alt="TOYOTA KENYA" height="70" style="margin-top:5px">
                                <!-- <span class="logo-lg-text-light">U</span> -->
                            </span>
                        </a>
    
                        <a href="{{url('home')}}" class="logo logo-light" >
                            <span class="logo-sm">
                                <img src="{{asset('assets/images/logowhite.jpeg')}}" alt="TOYOTA KENYA" height="70" style="margin-top:5px">  
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('assets/images/logowhite.jpeg')}}" alt="TOYOTA KENYA" height="70" style="margin-top:5px">  
                            </span>
                        </a>
                    </div>
    
                    <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                        <li>
                            <button class="button-menu-mobile waves-effect waves-light">
                                <i class="fe-menu"></i>
                            </button>
                        </li>

                        <li>
                            <!-- Mobile menu toggle (Horizontal Layout)-->
                            <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </li>   
            
                       
    
                       
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
           