<nav class="bg-[#1565c0] border-b border-gray-200 sticky top-0 z-20">
    <div class="px-6 py-3 flex items-center justify-between">
        <div class="flex items-center">
            <button class="text-white focus:outline-none mr-4" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            
            <div class="flex items-center">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-8">
                    <h1 class="text-white font-semibold ml-2 text-l">Touchstar Medical Enterprise Inc.<h1>
                @else
                    <div class="logo-placeholder">{{ substr(config('app.name', 'L'), 0, 1) }}</div>
                    <span class="ml-2 text-xl font-semibold text-white">{{ 'Laravel' }}</span>
                @endif
            </div>
        </div>
        
        <div class="flex items-center">
            <div class="relative mr-4 hidden md:block">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="ml-4 relative" id="csatBellWrapper">
                <button
                    onclick="toggleCsatDropdown()"
                    id="csatBellBtn"
                    class="relative p-2 text-white hover:text-yellow-300 focus:outline-none transition"
                    aria-label="Pending CSAT Surveys"
                >
                    <i class="fa-solid fa-star text-xl"></i>

                    @if(($csatPendingCount ?? 0) > 0)
                    <span class="absolute top-0 right-0 flex items-center justify-center
                                min-w-[18px] h-[18px] px-1 bg-red-500 text-white
                                text-[10px] font-bold rounded-full ring-2 ring-[#1565c0] animate-pulse">
                        {{ $csatPendingCount > 9 ? '9+' : $csatPendingCount }}
                    </span>
                    @endif
                </button>

                {{-- Dropdown --}}
                <div id="csatDropdown"
                    class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl
                            border border-gray-100 overflow-hidden z-50"
                    style="animation: slideDown 0.25s ease-out;">

                    {{-- Header --}}
                    <div class="px-4 py-3 bg-gradient-to-r from-[#1565c0] to-[#1565c0] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-star text-[#C9A84C] text-sm"></i>
                            <span class="text-white text-sm font-semibold tracking-wide">Satisfaction Surveys</span>
                        </div>
                        @if(($csatPendingCount ?? 0) > 0)
                        <span class="text-[10px] font-bold bg-red-500 text-white px-2 py-0.5 rounded-full">
                            {{ $csatPendingCount }} Pending
                        </span>
                        @endif
                    </div>

                    {{-- Items --}}
                    <div class="max-h-64 overflow-y-auto divide-y divide-gray-50">
                        @if(($csatPendingCount ?? 0) > 0)
                            @foreach(($pendingCsatItems ?? []) as $item)
                            <a href="{{ route('client.csat.rate', $item->id) }}"
                            class="flex items-start gap-3 px-4 py-3 hover:bg-amber-50 transition group">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center mt-0.5">
                                    <i class="fa-solid fa-clipboard-check text-amber-600 text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate group-hover:text-[#0B1F3A]">
                                        {{ $item->service_type_display ?? 'Service Report' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Engineer: {{ $item->service_engineer ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $item->formatted_service_date }}</p>
                                </div>
                                <i class="fa-solid fa-chevron-right text-gray-300 group-hover:text-[#C9A84C] text-xs mt-1 transition"></i>
                            </a>
                            @endforeach
                        @else
                            <div class="py-8 px-4 text-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-check text-green-500"></i>
                                </div>
                                <p class="text-sm font-medium text-gray-700">All caught up!</p>
                                <p class="text-xs text-gray-400 mt-1">No pending surveys.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer --}}
                    @if(($csatPendingCount ?? 0) > 0)
                    <div class="px-4 py-2.5 bg-gray-50 border-t border-gray-100 text-center">
                        <a href="{{ route('client.csat.index') }}"   {{-- ✅ CORRECT --}}
                        class="text-xs font-semibold text-[#0B1F3A] hover:text-[#C9A84C] transition">
                            View all {{ $csatPendingCount }} pending surveys →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="ml-4 relative">
              <button class="flex items-center focus:outline-none space-x-2">
        
        <div class="h-8 w-8 rounded-full overflow-hidden shadow-md">
            @auth
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                         alt="Profile Picture"
                         class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm">
                        {{ auth()->user()->initials }}
                    </div>
                @endif
            @else
                <div class="h-full w-full bg-gray-500 flex items-center justify-center text-white font-semibold text-sm">
                    U
                </div>
            @endauth
        </div>

        @auth
            <p class="text-sm font-medium text-white">
                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
            </p>
        @endauth

    </button>
</div>
        </div>
    </div>
</nav>
<audio id="globalNotificationSound" preload="auto">
    <source src="https://cdn.pixabay.com/audio/2021/08/04/audio_0625c1539c.mp3" type="audio/mpeg">
</audio>

<style>
    #notificationDropdown {
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .notification-item {
        transition: all 0.2s;
    }
    
    .notification-item:hover {
        background-color: #f9fafb;
    }
    
    .notification-item.critical {
        background-color: #fef2f2;
        border-left: 4px solid #dc2626;
    }
</style>
<script>
    let csatDropdownOpen = false;

    function toggleCsatDropdown() {
        const dropdown = document.getElementById('csatDropdown');
        csatDropdownOpen = !csatDropdownOpen;
        dropdown.classList.toggle('hidden', !csatDropdownOpen);
    }

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('csatDropdown');
        const btn = document.getElementById('csatBellBtn');
        if (csatDropdownOpen && dropdown && !dropdown.contains(e.target) && !btn.contains(e.target)) {
            dropdown.classList.add('hidden');
            csatDropdownOpen = false;
        }
    });
</script>