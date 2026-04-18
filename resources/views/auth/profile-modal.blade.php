<div class="max-w-5xl mx-auto">
    <!-- Profile Header -->
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-32"></div>
        <div class="px-6 pb-6">
            <div class="flex flex-col md:flex-row items-start md:items-end -mt-16 mb-4">
                <div class="relative">
                    @if($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" 
                             alt="{{ $user->first_name }} {{ $user->last_name }}"
                             class="h-32 w-32 rounded-full border-4 border-white object-cover shadow-lg">
                    @else
                        <div class="h-32 w-32 rounded-full border-4 border-white bg-blue-500 flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                        </div>
                    @endif
                    
                    @if($user->status === 'active')
                        <span class="absolute bottom-2 right-2 h-6 w-6 bg-green-500 rounded-full border-2 border-white"></span>
                    @elseif($user->status === 'on_leave')
                        <span class="absolute bottom-2 right-2 h-6 w-6 bg-yellow-500 rounded-full border-2 border-white"></span>
                    @else
                        <span class="absolute bottom-2 right-2 h-6 w-6 bg-red-500 rounded-full border-2 border-white"></span>
                    @endif
                </div>
                
                <div class="md:ml-6 mt-4 md:mt-0 flex-1">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</h1>
                    <p class="text-lg text-gray-600">{{ $user->position ?? 'No Position' }}</p>
                    <p class="text-sm text-gray-500">{{ $user->department ?? 'No Department' }}</p>
                </div>
                
                <div class="flex gap-2 mt-4 md:mt-0">
                    <button onclick="loadEditForm({{ $user->id }})" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </button>
                    <form action="{{ route('users.toggle-leave', $user->id) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 {{ $user->status === 'on_leave' ? 'bg-green-600 hover:bg-green-700' : 'bg-yellow-600 hover:bg-yellow-700' }} text-white rounded-lg transition-colors">
                            <i class="fas {{ $user->status === 'on_leave' ? 'fa-user-check' : 'fa-user-clock' }} mr-2"></i>
                            {{ $user->status === 'on_leave' ? 'Mark Active' : 'Mark On Leave' }}
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="mt-4">
                @if($user->status === 'active')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i> Active
                    </span>
                @elseif($user->status === 'on_leave')
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i> On Leave
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-2"></i> Inactive
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Information Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-address-book mr-2 text-blue-600"></i>
                Contact Information
            </h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <i class="fas fa-envelope w-6 text-gray-400 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-gray-800">{{ $user->email }}</p>
                    </div>
                </div>
                
                @if($user->phone)
                <div class="flex items-start">
                    <i class="fas fa-phone w-6 text-gray-400 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="text-gray-800">{{ $user->phone }}</p>
                    </div>
                </div>
                @endif
                
                @if($user->viber_number)
                <div class="flex items-start">
                    <i class="fab fa-viber w-6 text-purple-600 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Viber</p>
                        <p class="text-gray-800">+63 {{ $user->viber_number }}</p>
                    </div>
                </div>
                @endif
                
                @if($user->social_media)
                <div class="flex items-start">
                    <i class="fab fa-facebook w-6 text-blue-600 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Social Media</p>
                        <a href="{{ $user->social_media }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all">
                            {{ $user->social_media }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Employment Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-briefcase mr-2 text-blue-600"></i>
                Employment Information
            </h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <i class="fas fa-id-badge w-6 text-gray-400 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Position</p>
                        <p class="text-gray-800">{{ $user->position ?? 'Not Specified' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <i class="fas fa-building w-6 text-gray-400 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Department</p>
                        <p class="text-gray-800">{{ $user->department ?? 'Not Specified' }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <i class="fas fa-user-tag w-6 text-gray-400 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Role</p>
                        <p class="text-gray-800 capitalize">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <i class="fas fa-calendar-alt w-6 text-gray-400 mt-1"></i>
                    <div class="ml-3">
                        <p class="text-sm text-gray-500">Joined</p>
                        <p class="text-gray-800">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-history mr-2 text-blue-600"></i>
            Recent Activity
        </h2>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-800">Account Created</p>
                    <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                </div>
            </div>
            
            @if($user->updated_at != $user->created_at)
            <div class="flex items-start">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-edit text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-800">Profile Updated</p>
                    <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>