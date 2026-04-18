@extends('layouts.app')
@section('content')
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">User Management System</h1>
        <p class="text-gray-600 mt-2">Manage your organization's employees efficiently</p>
    </header>
    @if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        {{ session('error') }}
    </div>
@endif
@if(session('swal_success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '{{ session("swal_success") }}',
    confirmButtonColor: '#3085d6'
});
</script>
@endif

@if(session('swal_error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '{{ session("swal_error") }}',
    confirmButtonColor: '#d33'
});
</script>
@endif


    <!-- Controls and Stats -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div class="mb-4 md:mb-0">
                <h2 class="text-xl font-semibold text-gray-800">Employee Directory</h2>
                <p class="text-gray-600">Total Employees: <span class="font-medium">{{ $users->total() }}</span></p>
            </div>
            <div class="flex space-x-3">
                <div class="relative">
                    <form method="GET" action="{{ route('register.form') }}" id="searchForm">
                        <input type="text" name="search" placeholder="Search employees..." 
                            value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </form>
                </div>
                <button id="addEmployeeBtn" class="bg-blue-400 hover:bg-secondary text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add Employee
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex justify-center space-x-4">

            <!-- Employee Tab -->
            <a href="{{route('register.form')}}"
            class="px-6 py-2 rounded-lg font-medium 
            {{ request()->routeIs('employees.*') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-users mr-2"></i>
                Employees
            </a>

            <!-- Client Tab -->
            <a href="{{route('client.register.form')}}"
            class="px-6 py-2 rounded-lg font-medium 
            {{ request()->routeIs('clients.*') ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-user-tie mr-2"></i>
                Clients
            </a>

        </div>
    </div>

    <!-- Employee Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center cursor-pointer view-user" data-user-id="{{ $user->id }}">
                                @if($user->profile_picture)
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img src="{{ Storage::url($user->profile_picture) }}" 
                                             alt="{{ $user->full_name }}"
                                             class="h-10 w-10 rounded-full object-cover">
                                    </div>
                                @else
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ $user->initials }}
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-xs text-gray-400">{{ $user->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->position ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->department ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif($user->status === 'on_leave')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    On Leave
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button type="button" onclick="toggleLoginAccess({{ $user->id }})" 
                                    class="px-3 py-1 text-xs rounded {{ $user->login_enabled ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $user->login_enabled ? 'Disable Login' : 'Enable Login' }}
                            </button>
                            @if($user->session_id)
                            <button type="button" onclick="forceLogout({{ $user->id }})" 
                                    class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 hover:bg-yellow-200 rounded">
                                Force Logout
                            </button>
                            @endif

                            <button class="text-blue-500 hover:text-blue-700 mr-3 edit-user" data-user-id="{{ $user->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No employees found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $users->firstItem() }}</span> to 
                        <span class="font-medium">{{ $users->lastItem() }}</span> of 
                        <span class="font-medium">{{ $users->total() }}</span> results
                    </p>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
                <h3 class="text-xl font-semibold text-gray-800">Add New Employee</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
              <form class="mt-8 space-y-6" method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" id="addEmployeeForm">
                  @csrf
                  
                  <!-- Profile Picture -->
                  <div>
                      <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                      <div class="flex items-center space-x-4">
                          <div id="profilePreview" class="flex-shrink-0 h-20 w-20 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                              <i class="fas fa-user text-xl"></i>
                          </div>
                          <div class="flex-1">
                              <input type="file" id="profile_picture" name="profile_picture" accept="image/*" 
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                              <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG up to 5MB</p>
                          </div>
                      </div>
                      @error('profile_picture')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>
                  <div>
                    <label for="signature" class="block text-sm font-medium text-gray-700 mb-2">Signature</label>
                    <div class="flex items-center space-x-4">
                        <div id="signaturePreview" class="flex-shrink-0 h-20 w-20 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 border-2 border-dashed border-gray-300">
                            <i class="fas fa-signature text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" id="signature" name="signature" accept="image/*" 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG up to 2MB (Signature with white/transparent background recommended)</p>
                        </div>
                    </div>
                    @error('signature')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                  <!-- Name Fields -->
                  <div class="grid grid-cols-2 gap-4">
                      <div>
                          <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                          <input id="first_name" name="first_name" type="text" required 
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="First Name" value="{{ old('first_name') }}">
                          @error('first_name')
                              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                          @enderror
                      </div>
                      <div>
                          <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                          <input id="last_name" name="last_name" type="text" required 
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Last Name" value="{{ old('last_name') }}">
                          @error('last_name')
                              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                          @enderror
                      </div>
                  </div>

                  <!-- Email -->
                  <div>
                      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                      <input id="email" name="email" type="email" autocomplete="email" required 
                            class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                            placeholder="Email address" value="{{ old('email') }}">
                      @error('email')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>                

                  <!-- Viber Number -->
                  <div>
                      <label for="viber_number" class="block text-sm font-medium text-gray-700 mb-1">Viber Number</label>
                      <div class="flex">
                          <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                              <i class="fab fa-viber mr-2 text-purple-600"></i> +63
                          </span>
                          <input type="tel" id="viber_number" name="viber_number" placeholder="912 345 6789" 
                                class="appearance-none rounded-r-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                value="{{ old('viber_number') }}">
                      </div>
                      @error('viber_number')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>

                  <!-- Social Media -->
                  <div>
                      <label for="social_media" class="block text-sm font-medium text-gray-700 mb-1">Social Media Account</label>
                      <div class="flex">
                          <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                              <i class="fab fa-facebook text-blue-600"></i>
                          </span>
                          <input type="text" id="social_media" name="social_media" placeholder="https://facebook.com/username" 
                                class="appearance-none rounded-r-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                value="{{ old('social_media') }}">
                      </div>
                      @error('social_media')
                          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                  </div>

                  <!-- Position and Department -->
                  <div class="grid grid-cols-2 gap-4">
                      <div>
                          <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                          <input id="position" name="position" type="text" 
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Position" value="{{ old('position') }}">
                          @error('position')
                              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                      @enderror
                      </div>
                      <div>
                          <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                          <select id="department" name="department" 
                                  class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                              <option value="">Select Department</option>
                              <option value="Tech Engineering" {{ old('department') == 'Tech Engineering' ? 'selected' : '' }}>Tech Engineering</option>
                              <option value="Product Specialist" {{ old('department') == 'Product Specialist' ? 'selected' : '' }}>Product Specialist</option>
                              <option value="Information Technology" {{ old('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                              <option value="Marketing" {{ old('department') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                              <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                              <option value="HR" {{ old('department') == 'HR' ? 'selected' : '' }}>Human Resources</option>
                          </select>
                          @error('department')
                              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                          @enderror
                      </div>
                  </div>

                  <!-- Password Fields -->
                  <div class="grid grid-cols-2 gap-4">
                      <div>
                          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                          <input id="password" name="password" type="password" autocomplete="new-password" required 
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Password">
                          @error('password')
                              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                          @enderror
                      </div>
                      <div>
                          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                          <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                                placeholder="Confirm Password">
                      </div>
                  </div>

                  <!-- Terms and Conditions -->
                  <div class="flex items-center">
                      <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                      <label for="terms" class="ml-2 block text-sm text-gray-900">
                          I agree to the <a href="#" class="text-blue-600 hover:text-blue-500">Terms and Conditions</a>
                      </label>
                  </div>
                  @error('terms')
                      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror

                  <div>
                      <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                          Register
                      </button>
                  </div>
              </form>
            </div>
        </div>
    </div>

    <!-- View Profile Modal -->
    <div id="viewProfileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
                <h3 class="text-xl font-semibold text-gray-800">Employee Profile</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6" id="profileModalContent">
                <!-- Profile content will be loaded here via AJAX -->
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white">
                <h3 class="text-xl font-semibold text-gray-800">Edit Employee Profile</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6" id="editModalContent">
                <!-- Edit form will be loaded here via AJAX -->
            </div>
        </div>
    </div>
<script>
    // ==================== MAIN SCRIPT ====================
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const addEmployeeBtn = document.getElementById('addEmployeeBtn');
        const addEmployeeModal = document.getElementById('addEmployeeModal');
        const closeModal = document.getElementById('closeModal');
        const viewProfileModal = document.getElementById('viewProfileModal');
        const editProfileModal = document.getElementById('editProfileModal');

        // Initialize all event listeners
        initModals();
        initAddFormPreviews();
        initViewButtons();
        initEditButtons();

        // ==================== MODAL FUNCTIONS ====================
        function initModals() {
            // Open add employee modal
            if (addEmployeeBtn) {
                addEmployeeBtn.addEventListener('click', () => {
                    addEmployeeModal.classList.remove('hidden');
                });
            }

            // Close add employee modal
            if (closeModal) {
                closeModal.addEventListener('click', () => {
                    addEmployeeModal.classList.add('hidden');
                });
            }

            // Close modals when clicking outside
            [addEmployeeModal, viewProfileModal, editProfileModal].forEach(modal => {
                if (modal) {
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            modal.classList.add('hidden');
                        }
                    });
                }
            });
        }

        // ==================== VIEW FUNCTIONS ====================
        function initViewButtons() {
            document.querySelectorAll('.view-user').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    loadUserProfile(userId);
                });
            });
        }

        function loadUserProfile(userId) {
            fetch(`/users/${userId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('profileModalContent').innerHTML = html;
                    viewProfileModal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error loading profile:', error);
                    alert('Error loading profile');
                });
        }

        function closeViewModal() {
            if (viewProfileModal) viewProfileModal.classList.add('hidden');
        }

        // ==================== EDIT FUNCTIONS ====================
        function initEditButtons() {
            document.querySelectorAll('.edit-user').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    loadEditForm(userId);
                });
            });
        }

        function loadEditForm(userId) {
            fetch(`/users/${userId}/edit`)
                .then(response => response.json())
                .then(user => {
                    const formHtml = generateEditFormHtml(user);
                    document.getElementById('editModalContent').innerHTML = formHtml;
                    editProfileModal.classList.remove('hidden');
                    
                    // Initialize previews for edit form
                    initEditFormPreviews();
                })
                .catch(error => {
                    console.error('Error loading edit form:', error);
                    alert('Error loading edit form');
                });
        }

        function generateEditFormHtml(user) {
            return `
                <form method="POST" action="/users/${user.id}" enctype="multipart/form-data" id="editEmployeeForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Profile Picture -->
                        <div>
                            <label for="edit_profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                            <div class="flex items-center space-x-4">
                                <div id="editProfilePreview" class="flex-shrink-0 h-20 w-20">
                                    ${user.profile_picture ? 
                                        `<img src="/storage/${user.profile_picture}" class="h-20 w-20 rounded-full object-cover" alt="Current Profile">` :
                                        `<div class="h-20 w-20 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                            ${user.first_name.charAt(0)}${user.last_name.charAt(0)}
                                        </div>`
                                    }
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="edit_profile_picture" name="profile_picture" accept="image/*" 
                                          class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG up to 5MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Signature -->
                        <div>
                            <label for="edit_signature" class="block text-sm font-medium text-gray-700 mb-2">Signature</label>
                            <div class="flex items-center space-x-4">
                                <div id="editSignaturePreview" class="flex-shrink-0 h-20 w-20">
                                    ${user.signature ? 
                                        `<img src="/storage/${user.signature}" class="h-20 w-20 object-contain border rounded-lg" alt="Current Signature">` :
                                        `<div class="h-20 w-20 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 border-2 border-dashed border-gray-300">
                                            <i class="fas fa-signature text-xl"></i>
                                        </div>`
                                    }
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="edit_signature" name="signature" accept="image/*" 
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG up to 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Name Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="edit_first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input id="edit_first_name" name="first_name" type="text" required value="${user.first_name}"
                                      class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="edit_last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input id="edit_last_name" name="last_name" type="text" required value="${user.last_name}"
                                      class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="edit_email" name="email" type="email" required value="${user.email}"
                                  class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" id="edit_phone" name="phone" value="${user.phone || ''}"
                                  class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Viber Number -->
                        <div>
                            <label for="edit_viber_number" class="block text-sm font-medium text-gray-700 mb-1">Viber Number</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    <i class="fab fa-viber mr-2 text-purple-600"></i> +63
                                </span>
                                <input type="tel" id="edit_viber_number" name="viber_number" value="${user.viber_number || ''}"
                                      class="appearance-none rounded-r-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div>
                            <label for="edit_social_media" class="block text-sm font-medium text-gray-700 mb-1">Social Media Account</label>
                            <input type="text" id="edit_social_media" name="social_media" value="${user.social_media || ''}"
                                  class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <!-- Position and Department -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="edit_position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                <input id="edit_position" name="position" type="text" value="${user.position || ''}"
                                      class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="edit_department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <select id="edit_department" name="department" 
                                        class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Select Department</option>
                                    <option value="Tech Engineering" ${user.department == 'Tech Engineering' ? 'selected' : ''}>Tech Engineering</option>
                                    <option value="Product Specialist" ${user.department == 'Product Specialist' ? 'selected' : ''}>Product Specialist</option>
                                    <option value="Information Technology" ${user.department == 'Information Technology' ? 'selected' : ''}>Information Technology</option>
                                    <option value="Marketing" ${user.department == 'Marketing' ? 'selected' : ''}>Marketing</option>
                                    <option value="Sales" ${user.department == 'Sales' ? 'selected' : ''}>Sales</option>
                                    <option value="HR" ${user.department == 'HR' ? 'selected' : ''}>Human Resources</option>
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="edit_status" name="status" required
                                    class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="active" ${user.status == 'active' ? 'selected' : ''}>Active</option>
                                <option value="on_leave" ${user.status == 'on_leave' ? 'selected' : ''}>On Leave</option>
                                <option value="inactive" ${user.status == 'inactive' ? 'selected' : ''}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            Update Profile
                        </button>
                    </div>
                </form>
            `;
        }

        function closeEditModal() {
            if (editProfileModal) editProfileModal.classList.add('hidden');
        }

        // ==================== PREVIEW FUNCTIONS ====================
        function initAddFormPreviews() {
            // Profile picture preview
            setupFilePreview('profile_picture', 'profilePreview', 'h-20 w-20 rounded-full object-cover');
            
            // Signature preview
            setupFilePreview('signature', 'signaturePreview', 'h-20 w-20 object-contain');
        }

        function initEditFormPreviews() {
            // Profile picture preview for edit
            setupFilePreview('edit_profile_picture', 'editProfilePreview', 'h-20 w-20 rounded-full object-cover');
            
            // Signature preview for edit
            setupFilePreview('edit_signature', 'editSignaturePreview', 'h-20 w-20 object-contain border rounded-lg');
        }

        function setupFilePreview(inputId, previewId, previewClass) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            if (input && preview) {
                // Remove existing listeners by cloning and replacing
                const newInput = input.cloneNode(true);
                input.parentNode.replaceChild(newInput, input);
                
                newInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" class="${previewClass}" alt="Preview">`;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        // Make close functions globally available
        window.closeViewModal = closeViewModal;
        window.closeEditModal = closeEditModal;
        window.toggleLoginAccess = toggleLoginAccess;
        window.forceLogout = forceLogout;
    });

    // ==================== USER ACTION FUNCTIONS ====================
    function toggleLoginAccess(userId) {
        if (confirm('Are you sure you want to toggle login access for this user?')) {
            fetch(`/users/${userId}/toggle-login-access`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.success || data.error);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    }

    function forceLogout(userId) {
        if (confirm('Force this user to logout from all sessions?')) {
            fetch(`/users/${userId}/force-logout`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.success || data.error);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    }
</script>
@endsection