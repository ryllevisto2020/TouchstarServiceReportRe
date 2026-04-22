<aside class="sidebar" id="sidebar">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 border-b border-white/10 h-auto py-4">
        <div class="flex flex-col items-center justify-center py-4">
            @if(file_exists(public_path('images/logo.png')))
                <img src="{{ asset('images/logo.png') }}" 
                    alt="TouchStar PMS" 
                    class="w-[130px] h-[120px] rounded-full ml-10"> <!-- custom px size -->
            @else
                <div class="logo-placeholder w-[60px] h-[60px] flex items-center justify-center rounded-full bg-gray-700">
                    <span class="text-lg font-bold text-white">M</span>
                </div>
            @endif

            <div class="mt-2 text-center">
                <h1 class="text-white font-semibold text-base leading-tight ml-10">Touchstar Medical</h1>
                <p class="text-blue-200 text-xs ml-10">Enterprise Inc.</p>
            </div>
        </div>
        <!-- Mobile Close Button -->
        <button class="text-white/80 hover:text-white md:hidden" onclick="closeSidebar()">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    
    <!-- Navigation Menu -->
    <div class="px-4 py-6">
        <ul class="space-y-2">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="" class="nav-button active" onclick="setActiveNav(this)">
                    <div class="flex items-center">
                        <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                        <span class="ml-3">Dashboard</span>
                    </div>
                </a>
            </li>

           

            <!-- Machines Management -->
            <li class="nav-item">
                <button class="nav-button" onclick="toggleDropdown('machines-dropdown', 'machines-chevron')">
                    <div class="flex items-center">
                        <i class="fa-solid fa-cogs w-5 text-center"></i>
                        <span class="ml-3">Machines</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs chevron" id="machines-chevron"></i>
                </button>
                <div class="dropdown-content" id="machines-dropdown">
                    <a href="/machine" class="dropdown-link">
                        <i class="fa-solid fa-plus w-4 mr-2"></i>
                        Installed Machines
                    </a>
                </div>
            </li>



{{-- 
             <li class="nav-item">
                    <button class="nav-button" onclick="toggleDropdown('reports-dropdown', 'reports-chevron')">
                        <div class="flex items-center">
                            <i class="fa-solid fa-chart-line w-5 text-center"></i>
                            <span class="ml-3">Client Report Monitoring</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs chevron" id="reports-chevron"></i>
                    </button>
                    <div class="dropdown-content" id="reports-dropdown">
                        <a href="" class="dropdown-link">
                            <i class="fa-solid fa-clipboard-list w-4 mr-2"></i>
                            Report an Issue
                        </a>
                    </div>
            </li>
             --}}


            
            <!-- Preventive Maintenance -->
            <li class="nav-item">
                <button class="nav-button" onclick="toggleDropdown('pms-dropdown', 'pms-chevron')">
                    <div class="flex items-center">
                        <i class="fa-solid fa-screwdriver-wrench w-5 text-center"></i>
                        <span class="ml-3">Service Management</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs chevron" id="pms-chevron"></i>
                </button>
                <div class="dropdown-content" id="pms-dropdown">
                    <a href="/service" class="dropdown-link">
                        <i class="fa-solid fa-calendar-check w-4 mr-2"></i>
                        Service Reports
                    </a>
                    <a href="/service/history" class="dropdown-link">
                        <i class="fa-solid fa-clipboard-list w-4 mr-2"></i>
                        Service Report History
                    </a>
                </div>
            </li>  
             <li class="nav-item">
                    <button class="nav-button" onclick="toggleDropdown('satisfaction-dropdown', 'satisfaction-chevron')">
                        <div class="flex items-center">
                            <i class="fa-solid fa-chart-line w-5 text-center"></i>
                            <span class="ml-3">Customer Satisfaction</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs chevron" id="satisfaction-chevron"></i>
                    </button>
                    <div class="dropdown-content" id="satisfaction-dropdown">
                        <a href="" class="dropdown-link">
                            <i class="fa-solid fa-clipboard-list w-4 mr-2"></i>
                            Customer Satisfaction Reports
                        </a>
                    </div>
            </li>
           <li class="nav-item">
                <button class="nav-button" onclick="toggleDropdown('user-dropdown', 'user-chevron')">
                    <div class="flex items-center">
                        <i class="fa-solid fa-users w-5 text-center"></i>
                        <span class="ml-3">Employee Management</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs chevron" id="user-chevron"></i>
                </button>

                <div class="dropdown-content" id="user-dropdown">
                    <a href="{{route('employee.register')}}" class="dropdown-link">
                        <i class="fa-solid fa-id-card w-4 mr-2"></i>
                        Employee List
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <button class="nav-button" onclick="toggleDropdown('client-dropdown', 'client-chevron')">
                    <div class="flex items-center">
                        <i class="fa-solid fa-users w-5 text-center"></i>
                        <span class="ml-3">Client Management</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs chevron" id="client-chevron"></i>
                </button>

                <div class="dropdown-content" id="client-dropdown">
                    <a href="{{ route('client.register') }}" class="dropdown-link">
                        <i class="fa-solid fa-id-card w-4 mr-2"></i>
                        Client List
                    </a>
                </div>
            </li>
        </ul>
    </div>
    
    <!-- User Profile Section -->
    <div class="user-profile">
        <div class="flex items-center">
            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold shadow-lg">
                {{ auth()->check() ? strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) : 'U' }}
            </div>
            <div class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    @auth('touchstaraccount')
                        {{$employee_details->emp_first_name}} {{$employee_details->emp_last_name}}
                    @else
                        Guest User
                    @endauth
                </p>
                <p class="text-xs text-blue-200 font-medium truncate">
                    @auth('touchstaraccount')
                        {{-- {{ strtoupper(auth()->user()->role ?? 'USER') }} --}}
                        {{$employee_details->emp_role}}
                    @else
                        GUEST
                    @endauth
                </p>
            </div>
            @auth('touchstaraccount')
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" 
                            class="text-white/70 hover:text-white transition-colors duration-200 p-2 rounded-md hover:bg-white/10" 
                            title="Logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            @endauth
        </div>
    </div>
</aside>
