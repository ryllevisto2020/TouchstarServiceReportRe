{{--
  resources/views/clientbar/csat-notification.blade.php
  Drop this inside your existing navbar partial where the notification bell lives.
  Pass $csatPendingCount from your layout or a View Composer.
--}}

@php
    $csatCount = $csatPendingCount ?? 0;
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false">

    {{-- Bell trigger --}}
    <button
        @click="open = !open"
        class="relative flex items-center justify-center w-10 h-10 rounded-full
               bg-white border border-gray-200 shadow-sm
               hover:bg-blue-50 hover:border-blue-300
               transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400"
        aria-label="CSAT Notifications"
    >
        {{-- Bell icon --}}
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                     a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                     C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436
                     L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        {{-- Badge --}}
        @if($csatCount > 0)
        <span
            class="absolute -top-1 -right-1 flex items-center justify-center
                   min-w-[18px] h-[18px] px-1
                   bg-red-500 text-white text-[10px] font-bold
                   rounded-full ring-2 ring-white
                   animate-pulse"
        >
            {{ $csatCount > 9 ? '9+' : $csatCount }}
        </span>
        @endif
    </button>

    {{-- Dropdown panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl
               border border-gray-100 overflow-hidden z-50"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="px-4 py-3 bg-gradient-to-r from-[#1565c0] to-[#1565c0] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#1565c0]" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969
                             0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755
                             1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197
                             -1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588
                             -1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-white text-sm font-semibold tracking-wide">Satisfaction Surveys</span>
            </div>
            @if($csatCount > 0)
            <span class="text-[10px] font-bold bg-red-500 text-white px-2 py-0.5 rounded-full">
                {{ $csatCount }} Pending
            </span>
            @endif
        </div>

        {{-- Body --}}
        <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
            @if($csatCount > 0)
                {{-- Show up to 4 pending items inline; rest via "View All" --}}
                @foreach($pendingCsatItems ?? [] as $item)
                <a
                    href="{{ route('client.csat.rate', $item->id) }}"
                    onclick="openCsatModal({{ $item->id }}); return false;"
                    class="flex items-start gap-3 px-4 py-3 hover:bg-amber-50 transition-colors group"
                >
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2
                                     2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate group-hover:text-[#0B1F3A]">
                            {{ $item->service_type_display ?? 'Service' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Engineer: {{ $item->service_engineer ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ $item->formatted_service_date ?? '' }}
                        </p>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#C9A84C] flex-shrink-0 mt-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">All caught up!</p>
                    <p class="text-xs text-gray-400 mt-1">No pending surveys at this time.</p>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        @if($csatCount > 0)
        <div class="px-4 py-2.5 bg-gray-50 border-t border-gray-100">
            <a
                href="{{ route('client.csat.index') }}"
                class="flex items-center justify-center gap-1.5 text-xs font-semibold text-[#0B1F3A]
                       hover:text-[#C9A84C] transition-colors"
            >
                View all {{ $csatCount }} pending surveys
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
        @endif
    </div>
</div>