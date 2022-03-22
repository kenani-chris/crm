  <!-- ========== Left Sidebar Start ========== -->
  <div class="left-side-menu">

<div class="h-100" data-simplebar>

    <!-- User box -->
    <div class="user-box text-center">
    <img class="round" width="30" height="30" avatar="{{ Auth::user()->name }}">
        <div class="dropdown">
            <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                data-toggle="dropdown">{{ ucwords(Auth::user()->name) }}</a>
            <div class="dropdown-menu user-pro-dropdown">

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-user mr-1"></i>
                    <span>My Account</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-settings mr-1"></i>
                    <span>Settings</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-lock mr-1"></i>
                    <span>Lock Screen</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="fe-log-out mr-1"></i>
                    <span>Logout</span>
                </a>

            </div>
        </div>
        <p class="text-muted">Admin Head</p>
    </div>

    <!--- Sidemenu -->
    <div id="sidebar-menu">

        <ul id="side-menu">

        <li>
        <a class="nav-link  nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                               
                               <img class="round" width="30" height="30" avatar="{{ Auth::user()->name }}">

                               <span class="pro-user-name ml-1">
                               {{ ucwords(Auth::user()->name) }}
                               </span>
                               <p class="mute" style="font-size:12px; margin-left:22%; font-weight:400">
                               @if(Auth::user()->level=="Supervisor")
                                 {{"Team Lead"}}
                                @else
                                    {{ucwords(Auth::user()->level)}}
                                @endif
                              </p>
                           </a>
                         
        </li>

       

            <li class="menu-title">Navigation</li>



            <li class="{{ (request()->is('dashboard')) ? 'menu-active' : '' }}">
                    <a href="{{route('home')}}">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li class="{{ (request()->is('customer-feedbacks')) ? 'menu-active' : '' }}">
                    <a href="{{route('toyota.case')}}">
                        <i data-feather="message-circle"></i>
                        <span> Customer Feedback</span>
                    </a>
                </li>

                <li>
                    <a href="#sidebarLeads" data-toggle="collapse" class="{{ (request()->is('leads*')) ? 'menu-active-a' : '' }}">
                        <i data-feather="trending-up"></i>
                        
                        <span> Leads</span>
                    </a>
                    <div class="collapse" id="sidebarLeads">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('leads.user')}}">Leads</a>
                            </li>
                            @if(Auth::user()->role->slug=='admin')
                                <li>
                                    <a href="{{route('leads.create')}}">Upload Leads</a>
                                </li>
                                <li>
                                    <a href="{{ route('leads.tracker') }}">Leads Tracker</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>

                @if(Auth::user()->role->slug=='admin')
                <li class="{{ (request()->is('resolution-rates')) ? 'menu-active' : '' }}">
                    <a href="{{route('resolutions')}}">
                        <i data-feather="pie-chart"></i>
                        <span> Resolution Rates </span>
                    </a>
                </li>

                <li>
               
                <a href="#sidebaReports" data-toggle="collapse"  class="{{ (request()->is('reports*')) ? 'menu-active-a' : '' }}">
                    <i data-feather="download"></i>
                    
                    <span> Reports</span>
                </a>
                <div class="collapse" id="sidebaReports">
                    <ul class="nav-second-level">
                        @foreach(App\Models\Campaign::all() as $menuItem)
                            <li>
                                <a href="{{route('reports.index',[$menuItem->id,strtolower($menuItem->slug)])}}">{{$menuItem->name}}</a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('reports.overallcsi') }}">Overall CSI</a>
                        </li>
                        <li>
                            <a href="{{ route('reports.npscall') }}">NPS Score</a>
                        </li>
                    </ul>
                </div>
            </li>

        <li>
                <a href="#sidebarUsers" data-toggle="collapse">
                    <i data-feather="user"></i>
                    
                    <span> Users</span>
                </a>
                <div class="collapse" id="sidebarUsers">
                    <ul class="nav-second-level">
                    
                    <li>
                            <a href="{{route('list.users')}}">Users</a>
                        </li>
                        <li>
                            <a href="{{route('new.user')}}">Add User</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif

            
                
                
          
           


          
            
 
            <li>
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
            </li>

          
                    </ul>
                </div>
            </li>
        </ul>

    </div>
    <div class="clearfix"></div>
</div>
</div>
