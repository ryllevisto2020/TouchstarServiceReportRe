@extends('layouts.app')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
        font-family: 'Inter', sans-serif;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .form-step {
        display: none;
    }
    
    .form-step.active {
        display: block;
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from { transform: translateX(20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .step-indicator {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        background-color: #e5e7eb;
        color: #6b7280;
    }
    
    .step-indicator.active {
        background-color: #0ea5e9;
        color: white;
    }
    
    .step-indicator.completed {
        background-color: #10b981;
        color: white;
    }
    
    .progress-bar {
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background-color: #0ea5e9;
        transition: width 0.3s ease;
    }
    
    .input-error {
        border-color: #ef4444;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>

<!-- Main Content -->
<main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Title -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">TouchStar Medical Machines</h2>
            <p class="text-gray-600">List of all machines with their Preventive Maintenance Schedules</p>
        </div>
        <div>
            <button type="button" 
                id="open-machine-modal"
                class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">
                + Add Machine
            </button>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Functional Filters -->
     <form method="GET" action="{{ route('machines.index') }}" id="filter-form" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 items-end">
                
                <!-- Status Filter with Searchable Dropdown -->
                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="status" class="search-select w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-slate-400"
                        onchange="filterTable()">
                        <option value="">All Statuses</option>
                        <option value="Operational" {{ request('status') == 'Operational' ? 'selected' : '' }}>✓ Operational</option>
                        <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>⚙ Maintenance</option>
                        <option value="Standby" {{ request('status') == 'Standby' ? 'selected' : '' }}>⏸ Standby</option>
                        <option value="Not Operational" {{ request('status') == 'Not Operational' ? 'selected' : '' }}>✗ Not Operational</option>
                    </select>
                </div>

                <!-- Location Filter with Searchable Dropdown - NO AUTO-SUBMIT -->
                <div class="group relative">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Location</label>
                    <button type="button" id="location-trigger" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-left flex items-center justify-between hover:border-slate-400">
                        <span id="location-display">Select Location</span>
                        <span class="text-slate-400">▼</span>
                    </button>
                    
                    <!-- Dropdown with Search Inside -->
                    <div id="location-dropdown" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                        <!-- Search Input INSIDE Dropdown -->
                        <div class="p-3 border-b border-slate-200 sticky top-0 bg-white rounded-t-lg">
                            <input type="text" id="location-search-input" placeholder="Search locations..." 
                                class="w-full px-3 py-2 rounded border border-slate-300 text-sm text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                autocomplete="off">
                        </div>
                        
                        <!-- Options List -->
                        <div id="location-options-container" class="max-h-48 overflow-y-auto">
                            <button type="button" data-value="" class="location-option w-full text-left px-4 py-2.5 hover:bg-blue-50 text-slate-700 text-sm border-b border-slate-100">
                                ✓ All Locations
                            </button>
                            @if(isset($locations))
                                @foreach($locations as $location)
                                    <button type="button" data-value="{{ $location }}" class="location-option w-full text-left px-4 py-2.5 hover:bg-blue-50 text-slate-700 text-sm border-b border-slate-100">
                                        📍 {{ $location }}
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
                    <!-- Hidden select for form submission -->
                    <select name="location" id="location-filter" class="hidden">
                        <option value="">All Locations</option>
                        @if(isset($locations))
                            @foreach($locations as $location)
                                <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>
                                    {{ $location }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Serial Number Search -->
                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Serial Number</label>
                    <div class="relative">
                        <input type="text" name="serial_search" id="serial-search" placeholder="Search serial..." 
                            value="{{ request('serial_search') }}"
                            class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            autocomplete="off" list="serial-list">
                        <datalist id="serial-list">
                            @if(isset($serialNumbers))
                                @foreach($serialNumbers as $serial)
                                    <option value="{{ $serial }}">
                                @endforeach
                            @endif
                        </datalist>
                        @if(request('serial_search'))
                            <button type="button" onclick="document.getElementById('serial-search').value = ''; filterTable();" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                ✕
                            </button>
                        @endif
                    </div>
                </div>

                <!-- PMS Due Filter -->
                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">PMS Due</label>
                    <select name="pms_due" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:border-slate-400"
                        onchange="filterTable()">
                        <option value="">Any Time</option>
                        <option value="Next 7 Days" {{ request('pms_due') == 'Next 7 Days' ? 'selected' : '' }}>📅 Next 7 Days</option>
                        <option value="Next 30 Days" {{ request('pms_due') == 'Next 30 Days' ? 'selected' : '' }}>📅 Next 30 Days</option>
                        <option value="Overdue" {{ request('pms_due') == 'Overdue' ? 'selected' : '' }}>⚠ Overdue</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium px-4 py-2.5 rounded-lg transition-all shadow-sm hover:shadow-md">
                        Apply Filters
                    </button>
                    <a href="{{ route('machines.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-lg transition-all">
                        Clear
                    </a>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request('status') || request('location') || request('serial_search') || request('pms_due'))
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <p class="text-sm text-slate-600 mb-2">Active Filters:</p>
                    <div class="flex flex-wrap gap-2">
                        @if(request('status'))
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                Status: {{ request('status') }}
                                <a href="{{ route('machines.index', array_merge(request()->query(), ['status' => null])) }}" class="hover:text-blue-900">✕</a>
                            </span>
                        @endif
                        @if(request('location'))
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                Location: {{ request('location') }}
                                <a href="{{ route('machines.index', array_merge(request()->query(), ['location' => null])) }}" class="hover:text-green-900">✕</a>
                            </span>
                        @endif
                        @if(request('serial_search'))
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                                Serial: {{ request('serial_search') }}
                                <a href="{{ route('machines.index', array_merge(request()->query(), ['serial_search' => null])) }}" class="hover:text-purple-900">✕</a>
                            </span>
                        @endif
                        @if(request('pms_due'))
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">
                                PMS: {{ request('pms_due') }}
                                <a href="{{ route('machines.index', array_merge(request()->query(), ['pms_due' => null])) }}" class="hover:text-orange-900">✕</a>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </form>


    <!-- View Toggle -->
    <div class="flex justify-end mb-4">
        <div class="inline-flex rounded-md shadow-sm" role="group">
            <button id="card-view-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-l-md border border-blue-600 hover:bg-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-th-large mr-1"></i> Card View
            </button>
            <button id="table-view-btn" class="px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-r-md border border-blue-600 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-table mr-1"></i> Table View
            </button>
        </div>
    </div>

        @if($machines->count() > 0)
        <!-- Machine Cards -->
        <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($machines as $machine)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="relative">
                    <img class="h-48 w-full object-cover" 
                        src="{{ $machine->image_path ? Storage::url($machine->image_path) : asset('machines/default-machine.jpg') }}" 
                        alt="{{ $machine->name }}">
                    <div class="absolute top-4 right-4 h-16 w-16">
                        <img class="h-16 w-16 rounded-md object-cover border-2 border-white shadow-md" 
                            src="{{ $machine->image_path ? Storage::url($machine->image_path) : asset('machines/default-machine.jpg') }}" 
                            alt="{{ $machine->name }}">
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $machine->name }}</h3>
                            <p class="text-sm text-gray-500">ID: {{ $machine->serial_number }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            @if($machine->status == 'Operational') bg-green-100 text-green-800
                            @elseif($machine->status == 'Maintenance') bg-yellow-100 text-yellow-800
                            @elseif($machine->status == 'Standby') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $machine->status }}
                        </span>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Location</p>
                            <p class="text-sm font-medium">{{ $machine->client_location }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Last Service</p>
                            <p class="text-sm font-medium">{{ $machine->last_service_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Next PMS</p>
                            <p class="text-sm font-medium 
                                @if($machine->next_service_date->isPast()) text-red-600
                                @elseif($machine->next_service_date->diffInDays(now()) <= 7) text-yellow-600
                                @else text-green-600 @endif">
                                {{ $machine->next_service_date->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Region</p>
                            <p class="text-sm font-medium">{{ $machine->region }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-between">
                        <button class="text-blue-600 hover:text-blue-900 view-details" 
                                data-machine-id="{{ $machine->id }}">
                            <i class="fas fa-eye mr-1"></i> Details
                        </button>
                        <button class="text-gray-600 hover:text-gray-900 edit-machine" 
                                data-machine-id="{{ $machine->id }}">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Table View (Hidden by default) -->
        <div id="table-view" class="bg-white shadow rounded-lg overflow-hidden hidden">
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="align-middle inline-block min-w-full">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Machine
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Location
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Last Service
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Next PMS
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($machines as $machine)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                        src="{{ $machine->image_path ? Storage::url($machine->image_path) : asset('machines/default-machine.jpg') }}" 
                                                        alt="{{ $machine->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $machine->model }}</div>
                                                    <div class="text-xs text-gray-400">{{ $machine->serial_number }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $machine->client_location }}</div>
                                            <div class="text-sm text-gray-500">{{ $machine->city }}, {{ $machine->region }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($machine->status == 'Operational') bg-green-100 text-green-800
                                                @elseif($machine->status == 'Maintenance') bg-yellow-100 text-yellow-800
                                                @elseif($machine->status == 'Standby') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $machine->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $machine->last_service_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm 
                                            @if($machine->next_service_date->isPast()) text-red-600
                                            @elseif($machine->next_service_date->diffInDays(now()) <= 7) text-yellow-600
                                            @else text-green-600 @endif">
                                            {{ $machine->next_service_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3 view-details" 
                                                    data-machine-id="{{ $machine->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-gray-600 hover:text-gray-900 edit-machine" 
                                                    data-machine-id="{{ $machine->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900 delete-machine" 
                                                    data-machine-id="{{ $machine->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-b-lg shadow mt-6">
            {{ $machines->appends(request()->query())->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-tools"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No machines found</h3>
            <p class="text-gray-500 mb-4">
                @if(request()->hasAny(['status', 'location', 'pms_due']))
                    No machines match your current filter criteria. Try adjusting your filters or clearing them.
                @else
                    Get started by adding your first medical machine to the system.
                @endif
            </p>
            @if(request()->hasAny(['status', 'location', 'pms_due']))
                <a href="{{route('machines.index')}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Clear Filters
                </a>
            @else
                <button type="button" id="open-machine-modal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Add Your First Machine
                </button>
            @endif
        </div>
    @endif
</main>

<!-- Add/Edit Machine Modal -->
<div id="machine-modal" class="fixed z-10 inset-0 overflow-y-auto hidden animate-fade-in">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <!-- Fixed form action to match your routes -->
            <form id="machine-form" action="{{ route('machines.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit-machine-id" name="machine_id" value="">
                
                <!-- Progress Header -->
                <div class="bg-white px-6 pt-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 id="modal-title" class="text-2xl font-bold text-gray-900">Register New Medical Machine</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500 close-modal">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Progress Steps -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <div class="step-indicator active" data-step="1">
                                    <span>1</span>
                                    <i class="fas fa-check hidden"></i>
                                </div>
                                <span class="text-sm font-medium text-blue-600">Basic Info</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="step-indicator" data-step="2">
                                    <span>2</span>
                                    <i class="fas fa-check hidden"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-500">Location</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="step-indicator" data-step="3">
                                    <span>3</span>
                                    <i class="fas fa-check hidden"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-500">Maintenance</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="step-indicator" data-step="4">
                                    <span>4</span>
                                    <i class="fas fa-check hidden"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-500">Review</span>
                            </div>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 25%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white px-6 pb-6">
                    <!-- Step 1: Basic Information -->
                    <div class="form-step active" data-step="1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Machine Name *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-toolbox text-gray-400"></i>
                                    </div>
                                    <input type="text" name="name" id="name" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="name-error"></p>
                            </div>
                            
                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-microchip text-gray-400"></i>
                                    </div>
                                    <input type="text" name="model" id="model" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="model-error"></p>
                            </div>
                            
                            <div>
                                <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">Serial Number *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-barcode text-gray-400"></i>
                                    </div>
                                    <input type="text" name="serial_number" id="serial_number" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="serial-number-error"></p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 pt-3 flex items-start pointer-events-none">
                                        <i class="fas fa-align-left text-gray-400"></i>
                                    </div>
                                    <textarea name="description" id="description" rows="3"
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3"></textarea>
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="description-error"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Location Information -->
                    <div class="form-step" data-step="2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="client_location" class="block text-sm font-medium text-gray-700 mb-2">Hospital/Client Name *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-hospital text-gray-400"></i>
                                    </div>
                                    <input type="text" name="client_location" id="client_location" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="client-location-error"></p>
                            </div>
                            
                            <div>
                                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Region *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                    <select name="region" id="region" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 appearance-none">
                                        <option value="">Select Region</option>
                                        <option value="NCR">National Capital Region</option>
                                        <option value="CAR">Cordillera Administrative Region</option>
                                        <option value="ILOCOS">Ilocos Region</option>
                                        <option value="CAGAYAN">Cagayan Valley</option>
                                        <option value="CENTRAL_LUZON">Central Luzon</option>
                                        <option value="CALABARZON">CALABARZON</option>
                                        <option value="MIMAROPA">MIMAROPA</option>
                                        <option value="BICOL">Bicol Region</option>
                                        <option value="WESTERN_VISAYAS">Western Visayas</option>
                                        <option value="CENTRAL_VISAYAS">Central Visayas</option>
                                        <option value="EASTERN_VISAYAS">Eastern Visayas</option>
                                        <option value="ZAMBOANGA">Zamboanga Peninsula</option>
                                        <option value="NORTHERN_MINDANAO">Northern Mindanao</option>
                                        <option value="DAVAO">Davao Region</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="region-error"></p>
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-city text-gray-400"></i>
                                    </div>
                                    <input type="text" name="city" id="city" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="city-error"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3: Maintenance Information -->
                    <div class="form-step" data-step="3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-cog text-gray-400"></i>
                                    </div>
                                    <select name="status" id="status" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 appearance-none">
                                        <option value="Operational">Operational</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Standby">Standby</option>
                                        <option value="Not Operational">Not Operational</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="status-error"></p>
                            </div>
                            
                            <div>
                                <label for="installation_date" class="block text-sm font-medium text-gray-700 mb-2">Installation Date *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                    <input type="date" name="installation_date" id="installation_date" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="installation-date-error"></p>
                            </div>
                            
                            <div>
                                <label for="last_service_date" class="block text-sm font-medium text-gray-700 mb-2">Last Service Date *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-wrench text-gray-400"></i>
                                    </div>
                                    <input type="date" name="last_service_date" id="last_service_date" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="last-service-date-error"></p>
                            </div>
                            
                            <div>
                                <label for="service_interval_days" class="block text-sm font-medium text-gray-700 mb-2">Service Interval (Days) *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-day text-gray-400"></i>
                                    </div>
                                    <input type="number" name="service_interval_days" id="service_interval_days" value="90" min="1" required
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3">
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="service-interval-days-error"></p>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Machine Image</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                    <div class="space-y-1 text-center">
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload an image</span>
                                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                                <p class="error-message hidden mt-1 text-sm text-red-600" id="image-error"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 4: Review Information -->
                    <div class="form-step" data-step="4">
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Please review the information</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="border-l-4 border-blue-500 pl-3 py-1">
                                    <p class="text-sm font-medium text-gray-500">Machine Name</p>
                                    <p id="review-name" class="font-semibold">-</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-3 py-1">
                                    <p class="text-sm font-medium text-gray-500">Model</p>
                                    <p id="review-model" class="font-semibold">-</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-3 py-1">
                                    <p class="text-sm font-medium text-gray-500">Serial Number</p>
                                    <p id="review-serial" class="font-semibold">-</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-3 py-1">
                                    <p class="text-sm font-medium text-gray-500">Client</p>
                                    <p id="review-client" class="font-semibold">-</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-3 py-1">
                                    <p class="text-sm font-medium text-gray-500">Location</p>
                                    <p id="review-location" class="font-semibold">-</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-3 py-1">
                                    <p class="text-sm font-medium text-gray-500">Installation Date</p>
                                    <p id="review-installation" class="font-semibold">-</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-3 py-1">
                                    <p class="text-sm font-medium text-gray-500">Service Interval</p>
                                    <p id="review-interval" class="font-semibold">- days</p>
                                </div>
                                <div class="border-l-4 border-blue-500 pl-3 py-1">
                                    <p class="text-sm font-medium text-gray-500">Next Service</p>
                                    <p id="review-next-service" class="font-semibold">-</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                            <label for="terms" class="ml-2 block text-sm text-gray-700">
                                I confirm that all information provided is accurate
                            </label>
                        </div>
                        <p class="error-message hidden mt-1 text-sm text-red-600" id="terms-error"></p>
                    </div>
                </div>
                
                <!-- Footer with navigation buttons -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-colors prev-step hidden">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </button>
                    
                    <div class="flex space-x-3 ml-auto">
                        <button type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors close-modal">
                            Cancel
                        </button>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors next-step">
                            Continue <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors hidden submit-btn">
                            <i class="fas fa-check-circle mr-2"></i> Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="details-modal" class="fixed z-10 inset-0 overflow-y-auto hidden animate-fade-in">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="details-modal-title" class="text-2xl font-bold text-gray-900">Machine Details</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 close-details-modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <img id="details-image" class="w-full h-64 object-cover rounded-lg" src="" alt="Machine Image">
                    </div>
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900" id="details-name">-</h4>
                            <p class="text-gray-600" id="details-model">-</p>
                            <p class="text-sm text-gray-500">Serial: <span id="details-serial">-</span></p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <span id="details-status" class="px-2 py-1 text-xs font-semibold rounded-full">-</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Location</p>
                                <p id="details-location" class="text-sm">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Installation Date</p>
                                <p id="details-installation" class="text-sm">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Service Interval</p>
                                <p id="details-interval" class="text-sm">- days</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Last Service</p>
                                <p id="details-last-service" class="text-sm">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Next Service</p>
                                <p id="details-next-service" class="text-sm">-</p>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-2">Description</p>
                            <p id="details-description" class="text-sm text-gray-700">-</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors close-details-modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    // Fixed JavaScript for Machine Management
class MachineManager {
    constructor() {
        this.isEditMode = false;
        this.currentEditId = null;
        this.currentStep = 1;
        this.totalSteps = 4;
        this.debounceTimer = null;
        this.baseURL = window.location.origin; 
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupFormValidation();
        this.initializeViewToggle();
        this.handleKeyboardShortcuts();
    }

    bindEvents() {
        // Modal controls
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', () => this.closeModal());
        });

        document.querySelectorAll('.close-details-modal').forEach(btn => {
            btn.addEventListener('click', () => this.closeDetailsModal());
        });

        // Modal triggers
        document.getElementById('open-machine-modal')?.addEventListener('click', () => {
            this.openAddModal();
        });

        // Form navigation
        document.querySelector('.next-step')?.addEventListener('click', () => this.nextStep());
        document.querySelector('.prev-step')?.addEventListener('click', () => this.prevStep());
        
        // Form submission
        document.getElementById('machine-form')?.addEventListener('submit', (e) => {
            this.handleFormSubmit(e);
        });

        // View toggle
        document.getElementById('card-view-btn')?.addEventListener('click', () => {
            this.toggleView('card');
        });
        
        document.getElementById('table-view-btn')?.addEventListener('click', () => {
            this.toggleView('table');
        });

        // Machine actions
        this.bindMachineActions();

        // Close modals on outside click
        document.getElementById('machine-modal')?.addEventListener('click', (e) => {
            if (e.target.id === 'machine-modal') this.closeModal();
        });

        document.getElementById('details-modal')?.addEventListener('click', (e) => {
            if (e.target.id === 'details-modal') this.closeDetailsModal();
        });

        // Filter handling
        this.setupFilterHandling();
    }

    setupFilterHandling() {
        const filterForm = document.getElementById('filter-form');
        if (!filterForm) return;

        const inputs = filterForm.querySelectorAll('select:not(#location-filter), input:not(#location-search-input)');
        inputs.forEach(input => {
            const eventType = input.type === 'text' ? 'input' : 'change';
            input.addEventListener(eventType, () => {
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(() => {
                    filterForm.submit();
                }, input.type === 'text' ? 500 : 300);
            });
        });
    }

    bindMachineActions() {
        this.bindViewDetails();
        this.bindEditMachine();
        this.bindDeleteMachine();
    }

    bindViewDetails() {
        document.querySelectorAll('.view-details').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const machineId = btn.getAttribute('data-machine-id');
                this.fetchMachineDetails(machineId);
            });
        });
    }

    bindEditMachine() {
        document.querySelectorAll('.edit-machine').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const machineId = btn.getAttribute('data-machine-id');
                this.fetchMachineForEdit(machineId);
            });
        });
    }

    bindDeleteMachine() {
        document.querySelectorAll('.delete-machine').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const machineId = btn.getAttribute('data-machine-id');
                this.deleteMachine(machineId);
            });
        });
    }

    async fetchMachineDetails(machineId) {
        try {
            this.showLoading('Loading machine details...');
            
            const response = await fetch(`${this.baseURL}/machine/${machineId}/details`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            this.populateDetailsModal(data);
            this.openDetailsModal();
            
        } catch (error) {
            console.error('Error fetching machine details:', error);
            this.showError('Failed to load machine details. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    async fetchMachineForEdit(machineId) {
        try {
            this.showLoading('Loading machine data...');
            
            const response = await fetch(`${this.baseURL}/machine/${machineId}/edit`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            this.setupEditMode(machineId, data);
            this.openModal();
            
        } catch (error) {
            console.error('Error fetching machine data:', error);
            this.showError('Failed to load machine data. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    async deleteMachine(machineId) {
        if (!confirm('Are you sure you want to delete this machine? This action cannot be undone.')) {
            return;
        }

        try {
            this.showLoading('Deleting machine...');
            
            const response = await fetch(`${this.baseURL}/machine/${machineId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken(),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Unknown error occurred' }));
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message);
                this.removeMachineFromDOM(machineId);
            } else {
                this.showError(result.message || 'Failed to delete machine');
            }
        } catch (error) {
            console.error('Error deleting machine:', error);
            this.showError(error.message || 'Network error. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        if (!this.validateCurrentStep()) {
            return;
        }

        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn?.innerHTML || 'Submit';

        try {
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
                submitBtn.disabled = true;
            }

            // Fixed URL construction - using consistent /machine endpoint
            const url = this.isEditMode 
                ? `${this.baseURL}/machine/${this.currentEditId}` 
                : `${this.baseURL}/machine`;
            
            console.log('Submitting to URL:', url);
            console.log('Edit mode:', this.isEditMode);

            // For PUT requests (edit mode), add method override
            if (this.isEditMode) {
                formData.append('_method', 'PUT');
            }

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            console.log('Response status:', response.status);

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ 
                    success: false, 
                    message: `Server error: ${response.status}` 
                }));
                
                if (response.status === 422 && errorData.errors) {
                    this.handleFormErrors(errorData.errors);
                    this.showError(errorData.message || 'Please correct the errors and try again');
                } else {
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }
                return;
            }

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message);
                this.closeModal();
                
                // Reload page after a short delay to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                if (result.errors) {
                    this.handleFormErrors(result.errors);
                }
                this.showError(result.message || 'Please correct the errors and try again');
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            this.showError(error.message || 'Network error. Please try again.');
        } finally {
            if (submitBtn) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }
    }

    handleFormErrors(errors) {
        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        document.querySelectorAll('.input-error').forEach(el => {
            el.classList.remove('input-error');
        });

        // Show new errors
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            const errorElement = document.getElementById(`${field}-error`) || 
                                document.getElementById(field.replace('_', '-') + '-error');

            if (input) {
                input.classList.add('input-error');
            }

            if (errorElement) {
                errorElement.textContent = Array.isArray(errors[field]) 
                    ? errors[field][0] 
                    : errors[field];
                errorElement.classList.remove('hidden');
            }
        });
    }

    populateDetailsModal(data) {
        // Safely update elements
        this.updateElementText('details-modal-title', `${data.name} Details`);
        this.updateElementText('details-name', data.name);
        this.updateElementText('details-model', data.model);
        this.updateElementText('details-serial', data.serial_number);
        this.updateElementText('details-location', `${data.client_location}, ${data.city}, ${data.region}`);
        this.updateElementText('details-installation', this.formatDate(data.installation_date));
        this.updateElementText('details-interval', `${data.service_interval_days} days`);
        this.updateElementText('details-last-service', data.last_service_date || 'Not available');
        this.updateElementText('details-next-service', data.next_service_date || 'Not calculated');
        this.updateElementText('details-description', data.description || 'No description available');

        // Set image with fallback
        const imageElement = document.getElementById('details-image');
        if (imageElement) {
            const defaultImage = '/images/default-machine.jpg';
            imageElement.src = data.image_path || defaultImage;
            imageElement.onerror = () => { imageElement.src = defaultImage; };
        }

        // Set status with styling
        const statusElement = document.getElementById('details-status');
        if (statusElement) {
            statusElement.textContent = data.status;
            statusElement.className = 'px-2 py-1 text-xs font-semibold rounded-full';

            const statusClasses = {
                'Operational': ['bg-green-100', 'text-green-800'],
                'Maintenance': ['bg-yellow-100', 'text-yellow-800'],
                'Standby': ['bg-blue-100', 'text-blue-800'],
                'Not Operational': ['bg-red-100', 'text-red-800']
            };

            if (statusClasses[data.status]) {
                statusElement.classList.add(...statusClasses[data.status]);
            }
        }
    }

    setupEditMode(machineId, data) {
        this.isEditMode = true;
        this.currentEditId = machineId;

        // Update modal title
        this.updateElementText('modal-title', 'Edit Medical Machine');

        // Populate form fields
        const fields = [
            'name', 'model', 'serial_number', 'description', 'client_location',
            'region', 'city', 'status', 'installation_date', 'last_service_date',
            'service_interval_days'
        ];

        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element && data[field] !== undefined) {
                element.value = data[field];
            }
        });

        this.goToStep(1);
    }

    openAddModal() {
        this.isEditMode = false;
        this.currentEditId = null;
        this.updateElementText('modal-title', 'Register New Medical Machine');
        this.resetForm();
        this.openModal();
    }

    openModal() {
        document.getElementById('machine-modal')?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    closeModal() {
        document.getElementById('machine-modal')?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        this.resetForm();
    }

    openDetailsModal() {
        document.getElementById('details-modal')?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    closeDetailsModal() {
        document.getElementById('details-modal')?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Multi-step form functionality
    goToStep(step) {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(stepEl => {
            stepEl.classList.remove('active');
        });

        // Show current step
        const currentStep = document.querySelector(`.form-step[data-step="${step}"]`);
        if (currentStep) {
            currentStep.classList.add('active');
        }

        // Update step indicators
        document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
            const stepNumber = index + 1;
            indicator.classList.remove('active', 'completed');

            if (stepNumber === step) {
                indicator.classList.add('active');
            } else if (stepNumber < step) {
                indicator.classList.add('completed');
                const span = indicator.querySelector('span');
                const icon = indicator.querySelector('i');
                if (span) span.classList.add('hidden');
                if (icon) icon.classList.remove('hidden');
            } else {
                const span = indicator.querySelector('span');
                const icon = indicator.querySelector('i');
                if (span) span.classList.remove('hidden');
                if (icon) icon.classList.add('hidden');
            }
        });

        // Update progress bar
        const progressFill = document.querySelector('.progress-fill');
        if (progressFill) {
            const progressPercentage = ((step - 1) / (this.totalSteps - 1)) * 100;
            progressFill.style.width = `${progressPercentage}%`;
        }

        // Update navigation buttons
        this.toggleElementVisibility('.prev-step', step !== 1);
        this.toggleElementVisibility('.next-step', step !== this.totalSteps);
        this.toggleElementVisibility('.submit-btn', step === this.totalSteps);

        // Update review section
        if (step === this.totalSteps) {
            this.updateReviewSection();
        }

        this.currentStep = step;
    }

    nextStep() {
        if (this.validateCurrentStep() && this.currentStep < this.totalSteps) {
            this.goToStep(this.currentStep + 1);
        }
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.goToStep(this.currentStep - 1);
        }
    }

    validateCurrentStep() {
        let isValid = true;

        // Clear previous errors
        this.clearErrors();

        // Step-specific validation
        switch(this.currentStep) {
            case 1:
                isValid = this.validateStep1();
                break;
            case 2:
                isValid = this.validateStep2();
                break;
            case 3:
                isValid = this.validateStep3();
                break;
            case 4:
                isValid = this.validateStep4();
                break;
        }

        return isValid;
    }

    validateStep1() {
        let isValid = true;
        const requiredFields = ['name', 'model', 'serial_number'];
        
        requiredFields.forEach(field => {
            const element = document.getElementById(field);
            if (!element || !element.value.trim()) {
                this.showFieldError(field, `Please enter a ${field.replace('_', ' ')}`);
                isValid = false;
            }
        });

        return isValid;
    }

    validateStep2() {
        let isValid = true;
        const requiredFields = ['client_location', 'region', 'city'];
        
        requiredFields.forEach(field => {
            const element = document.getElementById(field);
            if (!element || !element.value.trim()) {
                this.showFieldError(field, `Please ${field === 'region' ? 'select' : 'enter'} a ${field.replace('_', ' ')}`);
                isValid = false;
            }
        });

        return isValid;
    }

    validateStep3() {
        let isValid = true;
        
        const installationDate = document.getElementById('installation_date');
        const lastServiceDate = document.getElementById('last_service_date');
        const serviceInterval = document.getElementById('service_interval_days');

        if (!installationDate || !installationDate.value) {
            this.showFieldError('installation_date', 'Please select an installation date');
            isValid = false;
        }

        if (!lastServiceDate || !lastServiceDate.value) {
            this.showFieldError('last_service_date', 'Please select a last service date');
            isValid = false;
        }

        if (!serviceInterval || !serviceInterval.value || serviceInterval.value < 1) {
            this.showFieldError('service_interval_days', 'Please enter a valid service interval');
            isValid = false;
        }

        // Validate date relationship
        if (installationDate && lastServiceDate && installationDate.value && lastServiceDate.value) {
            const installDate = new Date(installationDate.value);
            const serviceDate = new Date(lastServiceDate.value);
            
            if (serviceDate < installDate) {
                this.showFieldError('last_service_date', 'Last service date cannot be before installation date');
                isValid = false;
            }
        }

        return isValid;
    }

    validateStep4() {
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox || !termsCheckbox.checked) {
            this.showError('Please confirm that all information is accurate');
            return false;
        }
        return true;
    }

    showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`${fieldId}-error`) || 
                           document.getElementById(fieldId.replace('_', '-') + '-error');

        if (field) {
            field.classList.add('input-error');
        }

        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    updateReviewSection() {
        const reviewElements = {
            'review-name': 'name',
            'review-model': 'model', 
            'review-serial': 'serial_number',
            'review-client': 'client_location',
            'review-installation': 'installation_date',
            'review-interval': 'service_interval_days'
        };

        Object.keys(reviewElements).forEach(reviewId => {
            const element = document.getElementById(reviewElements[reviewId]);
            const reviewElement = document.getElementById(reviewId);
            
            if (element && reviewElement) {
                let value = element.value;
                if (reviewId === 'review-installation' && value) {
                    value = this.formatDate(value);
                } else if (reviewId === 'review-interval' && value) {
                    value = `${value} days`;
                }
                reviewElement.textContent = value || '-';
            }
        });

        // Special handling for location
        const city = document.getElementById('city');
        const region = document.getElementById('region');
        const locationReview = document.getElementById('review-location');
        if (city && region && locationReview) {
            locationReview.textContent = `${city.value}, ${region.value}`;
        }

        // Calculate and show next service date
        const lastServiceDate = document.getElementById('last_service_date');
        const intervalDays = document.getElementById('service_interval_days');
        const nextServiceReview = document.getElementById('review-next-service');
        
        if (lastServiceDate && intervalDays && nextServiceReview && lastServiceDate.value && intervalDays.value) {
            const nextServiceDate = new Date(lastServiceDate.value);
            nextServiceDate.setDate(nextServiceDate.getDate() + parseInt(intervalDays.value));
            nextServiceReview.textContent = this.formatDate(nextServiceDate.toISOString().split('T')[0]);
        }
    }

    resetForm() {
        const form = document.getElementById('machine-form');
        if (form) {
            form.reset();
        }
        this.goToStep(1);
        this.clearErrors();
        this.isEditMode = false;
        this.currentEditId = null;
    }

    clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        document.querySelectorAll('.input-error').forEach(el => {
            el.classList.remove('input-error');
        });
    }

    setupFormValidation() {
        const form = document.getElementById('machine-form');
        if (!form) return;

        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });

            input.addEventListener('input', () => {
                if (input.classList.contains('input-error')) {
                    this.validateField(input);
                }
            });
        });
    }

    validateField(field) {
        const fieldId = field.id;
        const value = field.value.trim();

        // Clear previous error
        field.classList.remove('input-error');
        const errorElement = document.getElementById(`${fieldId}-error`) || 
                           document.getElementById(fieldId.replace('_', '-') + '-error');
        if (errorElement) {
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
        }

        // Basic validation
        if (field.required && !value) {
            this.showFieldError(fieldId, `${field.name || fieldId.replace('_', ' ')} is required`);
            return false;
        }

        return true;
    }

    initializeViewToggle() {
        this.toggleView('card');
    }

    toggleView(viewType) {
        const cardView = document.getElementById('card-view');
        const tableView = document.getElementById('table-view');
        const cardBtn = document.getElementById('card-view-btn');
        const tableBtn = document.getElementById('table-view-btn');

        if (viewType === 'card') {
            cardView?.classList.remove('hidden');
            tableView?.classList.add('hidden');
            
            cardBtn?.classList.add('text-white', 'bg-blue-600');
            cardBtn?.classList.remove('text-blue-600', 'bg-white');
            tableBtn?.classList.add('text-blue-600', 'bg-white');
            tableBtn?.classList.remove('text-white', 'bg-blue-600');
        } else {
            cardView?.classList.add('hidden');
            tableView?.classList.remove('hidden');
            
            tableBtn?.classList.add('text-white', 'bg-blue-600');
            tableBtn?.classList.remove('text-blue-600', 'bg-white');
            cardBtn?.classList.add('text-blue-600', 'bg-white');
            cardBtn?.classList.remove('text-white', 'bg-blue-600');
        }
    }

 

    removeMachineFromDOM(machineId) {
        document.querySelectorAll(`[data-machine-id="${machineId}"]`).forEach(el => {
            const container = el.closest('.bg-white, tr');
            if (container) {
                container.remove();
            }
        });

        // Check if no machines are left
        const cardView = document.getElementById('card-view');
        const tableView = document.getElementById('table-view');
        
        if (cardView && cardView.children.length === 0) {
            this.showNoMachinesMessage();
        }
    }

    showNoMachinesMessage() {
        const cardView = document.getElementById('card-view');
        
        if (cardView) {
            const noDataHtml = `
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No machines found</h3>
                        <p class="text-gray-500 mb-4">
                            No machines match your current criteria or all machines have been removed.
                        </p>
                        <button type="button" id="add-first-machine" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Add Machine
                        </button>
                    </div>
                </div>
            `;
            
            cardView.innerHTML = noDataHtml;
            document.getElementById('add-first-machine')?.addEventListener('click', () => {
                this.openAddModal();
            });
        }
    }

    // Utility methods
    updateElementText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        }
    }

    toggleElementVisibility(selector, show) {
        const element = document.querySelector(selector);
        if (element) {
            element.classList.toggle('hidden', !show);
        }
    }

    formatDate(dateString) {
        if (!dateString) return '-';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        } catch (error) {
            return dateString;
        }
    }

    showLoading(message = 'Loading...') {
        let overlay = document.getElementById('loading-overlay');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            overlay.innerHTML = `
                <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span id="loading-message">${message}</span>
                </div>
            `;
            document.body.appendChild(overlay);
        } else {
            const messageEl = document.getElementById('loading-message');
            if (messageEl) messageEl.textContent = message;
            overlay.classList.remove('hidden');
        }
        document.body.classList.add('overflow-hidden');
    }

    hideLoading() {
        const overlay = document.getElementById('loading-overlay');
        if (overlay) {
            overlay.classList.add('hidden');
        }
        document.body.classList.remove('overflow-hidden');
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-md ${
            type === 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 
            type === 'error' ? 'bg-red-100 text-red-700 border border-red-200' : 
            'bg-blue-100 text-blue-700 border border-blue-200'
        }`;
        
        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-${
                        type === 'success' ? 'check-circle' : 
                        type === 'error' ? 'exclamation-triangle' : 
                        'info-circle'
                    } text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="inline-flex text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.transition = 'opacity 0.5s, transform 0.5s';
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 500);
            }
        }, 5000);
    }

    getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    handleKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!document.getElementById('machine-modal')?.classList.contains('hidden')) {
                    this.closeModal();
                }
                if (!document.getElementById('details-modal')?.classList.contains('hidden')) {
                    this.closeDetailsModal();
                }
            }
            
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                const form = document.getElementById('machine-form');
                if (form && !document.getElementById('machine-modal')?.classList.contains('hidden')) {
                    if (this.currentStep === this.totalSteps) {
                        form.dispatchEvent(new Event('submit'));
                    } else {
                        this.nextStep();
                    }
                }
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.machineManager = new MachineManager();
});

// Export for potential use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MachineManager;
}
</script>

<script>
// Location Search Dropdown - NO AUTO-SUBMIT
document.addEventListener('DOMContentLoaded', function() {
    const locationTrigger = document.getElementById('location-trigger');
    const locationDropdown = document.getElementById('location-dropdown');
    const locationSearchInput = document.getElementById('location-search-input');
    const locationFilter = document.getElementById('location-filter');
    const locationDisplay = document.getElementById('location-display');
    const locationOptions = document.querySelectorAll('.location-option');

    // Toggle dropdown on trigger click
    locationTrigger.addEventListener('click', (e) => {
        e.preventDefault();
        locationDropdown.classList.toggle('hidden');
        if (!locationDropdown.classList.contains('hidden')) {
            locationSearchInput.focus();
        }
    });

    // Search functionality - NO AUTO-SUBMIT, just filter options
    locationSearchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        let visibleCount = 0;
        
        locationOptions.forEach(option => {
            const text = option.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            option.classList.toggle('hidden', !isVisible);
            if (isVisible) visibleCount++;
        });
        
        // Show "No results" message if needed
        if (visibleCount === 0) {
            let noResultsMsg = document.getElementById('location-no-results');
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.id = 'location-no-results';
                noResultsMsg.className = 'px-4 py-3 text-center text-gray-500 text-sm';
                document.getElementById('location-options-container').appendChild(noResultsMsg);
            }
            noResultsMsg.textContent = 'No locations found';
        } else {
            const noResultsMsg = document.getElementById('location-no-results');
            if (noResultsMsg) noResultsMsg.remove();
        }
    });

    // Handle option selection
    locationOptions.forEach(option => {
        option.addEventListener('click', (e) => {
            e.preventDefault();
            const value = option.getAttribute('data-value');
            const text = option.textContent.trim();
            
            locationFilter.value = value;
            locationDisplay.textContent = text || 'Select Location';
            locationSearchInput.value = '';
            locationDropdown.classList.add('hidden');
            // ONLY submit when option is clicked, not during typing
            filterTable();
        });
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.group') && !e.target.closest('#location-trigger')) {
            locationDropdown.classList.add('hidden');
        }
    });

    // Display current selection on page load
    const selectedValue = locationFilter.value;
    if (selectedValue) {
        const selectedOption = locationFilter.querySelector(`option[value="${selectedValue}"]`);
        if (selectedOption) {
            locationDisplay.textContent = selectedOption.textContent.trim();
        }
    }
});

function filterTable() {
    document.getElementById('filter-form').submit();
}
</script>
@endsection