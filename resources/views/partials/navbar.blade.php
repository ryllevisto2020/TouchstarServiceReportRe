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
            
            <div class="ml-4 relative">
                <button 
                    onclick="toggleNotificationDropdown()" 
                    id="notificationButton"
                    class="p-2 text-white hover:text-blue-300 focus:outline-none relative transition"
                >
                    <i class="fa-solid fa-bell text-xl"></i>
                    <span 
                        id="notificationBadge" 
                        class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center font-bold"
                        style="display: none;"
                    >
                        0
                    </span>
                    <!-- Pulse animation for new notifications -->
                    <span 
                        id="notificationPulse"
                        class="absolute top-0 right-0 w-5 h-5 bg-red-500 rounded-full animate-ping"
                        style="display: none;"
                    ></span>
                </button>

                <!-- Notification Dropdown -->
                <div 
                    id="notificationDropdown" 
                    class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border border-gray-200"
                    style="max-height: 500px; overflow-y: auto;"
                >
                    <!-- Dropdown Header -->
                    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-t-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-white font-bold text-lg">
                                <i class="fas fa-bell mr-2"></i>Notifications
                            </h3>
                            <span id="dropdownBadge" class="bg-white text-indigo-600 text-xs font-bold px-2 py-1 rounded-full">
                                0
                            </span>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="notificationLoading" class="p-4 text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                    </div>

                    <!-- Notifications List -->
                    <div id="notificationsList" class="divide-y divide-gray-200" style="display: none;">
                        <!-- Notifications will be dynamically inserted here -->
                    </div>

                    <!-- Empty State -->
                    <div id="notificationEmpty" class="p-8 text-center text-gray-500" style="display: none;">
                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                        <p class="font-medium">No pending issues</p>
                        <p class="text-sm mt-1">All caught up! 🎉</p>
                    </div>

                    <!-- Dropdown Footer -->
                    <div class="p-3 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                        <a 
                            href="" 
                            class="block text-center text-indigo-600 hover:text-indigo-800 font-medium text-sm transition"
                        >
                            View All Issues →
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="ml-4 relative">
                <button class="flex items-center focus:outline-none">
                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">U</div>
                       <p class="text-sm font-medium text-white">
                            {{-- {{ auth()->user()->first_name }} {{ auth()->user()->last_name }} --}}
                        </p>
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

{{-- <!-- Notification Script -->
<script>
    let lastIssueCount = 0;
    let notificationDropdownOpen = false;

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
        
        // Initial fetch
        fetchNotifications();
        
        // Poll for new notifications every 30 seconds
        setInterval(checkForNewNotifications, 30000);
    });

    // Fetch notifications
    async function fetchNotifications() {
        try {
            const response = await fetch('/notifications', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                updateNotificationUI(data);
                
                // Check for new issues
                if (data.has_new && lastIssueCount > 0 && data.total_count > lastIssueCount) {
                    const newCount = data.total_count - lastIssueCount;
                    showNewIssueNotification(newCount);
                }
                
                lastIssueCount = data.total_count;
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }

    // Update notification UI
    function updateNotificationUI(data) {
        const badge = document.getElementById('notificationBadge');
        const pulse = document.getElementById('notificationPulse');
        const dropdownBadge = document.getElementById('dropdownBadge');
        const loading = document.getElementById('notificationLoading');
        const list = document.getElementById('notificationsList');
        const empty = document.getElementById('notificationEmpty');

        // Update badge
        if (data.total_count > 0) {
            badge.textContent = data.total_count;
            badge.style.display = 'flex';
            pulse.style.display = 'block';
            dropdownBadge.textContent = data.total_count;
        } else {
            badge.style.display = 'none';
            pulse.style.display = 'none';
            dropdownBadge.textContent = '0';
        }

        // Update dropdown content
        loading.style.display = 'none';
        
        if (data.notifications.length > 0) {
            list.style.display = 'block';
            empty.style.display = 'none';
            
            list.innerHTML = data.notifications.map(notification => `
                <a href="/issues" 
                   class="notification-item block p-4 hover:bg-gray-50 transition ${notification.is_critical ? 'critical' : ''}"
                   onclick="markNotificationsAsSeen()">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center ${
                                notification.is_critical ? 'bg-red-100' : 'bg-indigo-100'
                            }">
                                <i class="fas fa-${notification.is_critical ? 'exclamation-triangle text-red-600' : 'file-alt text-indigo-600'}"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">
                                ${notification.title}
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                ${notification.client} • ${notification.incident_id}
                            </p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                                    notification.is_critical 
                                        ? 'bg-red-100 text-red-800' 
                                        : 'bg-yellow-100 text-yellow-800'
                                }">
                                    ${notification.status}
                                </span>
                                <span class="text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>${notification.time}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            `).join('');
        } else {
            list.style.display = 'none';
            empty.style.display = 'block';
        }
    }

    // Toggle notification dropdown
    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        
        if (notificationDropdownOpen) {
            dropdown.classList.add('hidden');
            notificationDropdownOpen = false;
        } else {
            dropdown.classList.remove('hidden');
            notificationDropdownOpen = true;
            fetchNotifications();
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const button = document.getElementById('notificationButton');
        
        if (notificationDropdownOpen && !dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
            notificationDropdownOpen = false;
        }
    });

    // Check for new notifications
    async function checkForNewNotifications() {
        try {
            const response = await fetch('/notifications/count', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.total_count > lastIssueCount) {
                const newCount = data.total_count - lastIssueCount;
                showNewIssueNotification(newCount);
                fetchNotifications();
            }
        } catch (error) {
            console.error('Error checking notifications:', error);
        }
    }

    // Show new issue notification
    function showNewIssueNotification(count) {
        const message = count === 1 ? 'New issue reported!' : `${count} new issues reported!`;
        
        // Play sound
        const audio = document.getElementById('globalNotificationSound');
        if (audio) {
            audio.play().catch(e => console.log('Could not play sound:', e));
        }
        
        // Browser notification
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('Issue Tracker Alert', {
                body: message,
                icon: '/favicon.ico',
                tag: 'new-issue',
                requireInteraction: false
            });
        }
        
        // Show pulse animation
        const pulse = document.getElementById('notificationPulse');
        if (pulse) {
            pulse.style.display = 'block';
            setTimeout(() => {
                pulse.style.display = 'none';
            }, 3000);
        }
    }

    // Mark notifications as seen
    async function markNotificationsAsSeen() {
        try {
            await fetch('/notifications/mark-seen', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        } catch (error) {
            console.error('Error marking notifications as seen:', error);
        }
    }
</script> --}}