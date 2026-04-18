<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Touchstar Medical Enterprise Inc.')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <meta name="theme-color" content="#3B82F6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/icon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-180.png') }}">

    <style>
        /* Logo Placeholder */
        .logo-placeholder {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #f59e0b 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Sidebar Styles - FIXED */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 16rem;
            background: #1565c0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateX(0);
            transition: transform 0.3s ease-in-out;
            z-index: 40;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-closed {
            transform: translateX(-16rem);
        }

        /* Main content adjustments - FIXED */
        .main-content {
            margin-left: 16rem;
            transition: margin-left 0.3s ease-in-out;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - 16rem); /* ADDED: Ensure proper width */
        }

        .main-content-expanded {
            margin-left: 0;
            width: 100%; /* ADDED: Full width when sidebar closed */
        }

        /* Navigation Item Styles */
        .nav-item { transition: all 0.2s ease; }

        .nav-button {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 12px 16px;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.9);
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .nav-button:hover { background-color: rgba(255, 255, 255, 0.1); color: white; }
        .nav-button.active { background-color: rgba(255, 255, 255, 0.2); color: white; }

        /* Dropdown Styles */
        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, padding 0.3s ease-out;
            padding: 0 0 0 36px;
        }

        .dropdown-content.open {
            max-height: 500px;
            padding: 8px 0 8px 36px;
        }

        .dropdown-link {
            display: block;
            padding: 8px 16px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 6px;
            transition: all 0.2s ease;
            margin-bottom: 4px;
            text-decoration: none;
        }

        .dropdown-link:hover { background-color: rgba(255, 255, 255, 0.1); color: white; transform: translateX(4px); }

        .chevron { transition: transform 0.3s ease; }
        .chevron.rotated { transform: rotate(180deg); }

        /* Mobile responsive - FIXED */
        @media (max-width: 768px) {
            .sidebar { 
                transform: translateX(-16rem); 
                box-shadow: none;
            }
            .sidebar.mobile-open { 
                transform: translateX(0);
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            }
            .main-content { 
                margin-left: 0; 
                width: 100%;
            }
            .main-content-expanded {
                margin-left: 0;
                width: 100%;
            }
        }

        /* User profile section */
        .user-profile {
            margin-top: auto;
            padding: 16px;
            background: rgba(0, 0, 0, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Backdrop for mobile */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 30;
            display: none;
        }

        .sidebar-backdrop.show { display: block; }

        /* Smooth scrollbar for sidebar */
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.1); }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.3); border-radius: 4px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.5); }

        /* Notification dropdown */
        #notificationDropdown { 
            animation: slideDown 0.3s ease-out; 
            position: absolute;
            right: 1rem;
            top: 4rem;
            z-index: 50;
        }
        
        @keyframes slideDown { 
            from {opacity: 0; transform: translateY(-10px);} 
            to {opacity:1; transform: translateY(0);} 
        }
        
        .notification-item { transition: all 0.2s; }
        .notification-item:hover { background-color: #f9fafb; }
        .notification-item.critical { background-color: #fef2f2; border-left: 4px solid #dc2626; }

        /* ADDED: Fix for content padding */
        .content-wrapper {
            padding: 1.5rem;
            flex: 1;
        }

        /* ADDED: Ensure navbar stays within main content */
        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans antialiased overflow-x-hidden">
    {{-- Sidebar Backdrop for Mobile --}}
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeSidebar()"></div>

    {{-- Sidebar Partial --}}
    @include('clientbar.sidebar')

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">
        
        {{-- Navbar Partial --}}
        @include('clientbar.navbar')

        {{-- Page Content --}}
        <div class="content-wrapper">
            @yield('content')
        </div>

        {{-- Footer Partial --}}
        @include('clientbar.footer')
    </div>
  
    {{-- JavaScript --}}
    <script>
        // Toggle dropdown
        function toggleDropdown(dropdownId, chevronId) {
            const dropdown = document.getElementById(dropdownId);
            const chevron = document.getElementById(chevronId);

            if(dropdown && chevron) {
                dropdown.classList.toggle('open');
                chevron.classList.toggle('rotated');

                document.querySelectorAll('.dropdown-content').forEach((dd, idx) => {
                    if(dd.id !== dropdownId) {
                        dd.classList.remove('open');
                        document.querySelectorAll('.chevron')[idx]?.classList.remove('rotated');
                    }
                });
            }
        }

        // Sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mainContent = document.getElementById('mainContent');

            if(window.innerWidth < 768){
                sidebar.classList.toggle('mobile-open');
                backdrop.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
            } else {
                sidebar.classList.toggle('sidebar-closed');
                mainContent.classList.toggle('main-content-expanded');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mainContent = document.getElementById('mainContent');

            if(window.innerWidth < 768){
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.remove('sidebar-closed');
                mainContent.classList.remove('main-content-expanded');
            }
        }

        // Active nav
        function setActiveNav(el) {
            document.querySelectorAll('.nav-button').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');
            if(window.innerWidth < 768) closeSidebar();
        }

        // Notification dropdown
        let notificationDropdownOpen = false;
        
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            if (notificationDropdownOpen) { 
                dropdown.classList.add('hidden'); 
                notificationDropdownOpen = false; 
            } else { 
                dropdown.classList.remove('hidden'); 
                notificationDropdownOpen = true; 
            }
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const button = document.getElementById('notificationButton');

            if (notificationDropdownOpen && dropdown && button && 
                !dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.classList.add('hidden');
                notificationDropdownOpen = false;
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mainContent = document.getElementById('mainContent');

            // Set initial state based on screen size
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-closed', 'mobile-open');
                backdrop.classList.remove('show');
                mainContent.classList.remove('main-content-expanded');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }
        });

        // Handle resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mainContent = document.getElementById('mainContent');

            if (window.innerWidth >= 768) {
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
                
                if (!sidebar.classList.contains('sidebar-closed')) {
                    mainContent.classList.remove('main-content-expanded');
                }
            } else {
                sidebar.classList.remove('sidebar-closed');
                if (!sidebar.classList.contains('mobile-open')) {
                    backdrop.classList.remove('show');
                }
            }
        });

        // Close dropdown links on mobile
        document.querySelectorAll('.dropdown-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) closeSidebar();
            });
        });
    </script>

    @stack('scripts')
</body>
</html>