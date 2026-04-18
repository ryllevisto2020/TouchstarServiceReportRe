<aside class="sidebar" id="sidebar">
    
    <!-- Header -->
    <div class="flex flex-col items-center justify-center py-6 border-b border-white/10">
        <img src="{{asset('images/logo.png')}}" 
             alt="TouchStar PMS" 
             class="w-24 h-24 rounded-full">

        <div class="mt-3 text-center">
            <h1 class="text-white font-semibold text-base leading-tight">
                Touchstar Medical
            </h1>
            <p class="text-blue-200 text-xs">
                Enterprise Inc.
            </p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="px-4 py-6">
        <ul class="space-y-2">
            <!-- Machines -->
            <li>
                <a href="" class="nav-button">
                    <div class="flex items-center">
                        <i class="fa-solid fa-cogs w-5 text-center"></i>
                        <span class="ml-3">Machines</span>
                    </div>
                </a>
            </li>
            <!-- Service Management -->
            <li>
                <a href="" class="nav-button">
                    <div class="flex items-center">
                        <i class="fa-solid fa-screwdriver-wrench w-5 text-center"></i>
                        <span class="ml-3">Service Management</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <!-- Client Profile Section -->
   <div class="user-profile">
        <div class="flex items-center">
           <div class="h-10 w-10 rounded-full overflow-hidden shadow-lg">
                @auth
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                            alt="Profile Picture"
                            class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                            {{ auth()->user()->initials }}
                        </div>
                    @endif
                @else
                    <div class="h-full w-full bg-gray-500 flex items-center justify-center text-white font-semibold">
                        U
                    </div>
                @endauth
            </div>
            <div class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    @auth
                            {{ auth()->user()->client_name }}
                    @endauth
                </p>
                <p class="text-xs text-blue-200 font-medium truncate">
                    @auth
                        {{ strtoupper(auth()->user()->role ?? 'USER') }}
                    @else
                        GUEST
                    @endauth
                </p>
            </div>
            @auth
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
