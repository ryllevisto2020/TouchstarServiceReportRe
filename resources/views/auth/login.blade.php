<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Touchstar Medical Enterprises Inc. — Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#115272',
            'primary-dark': '#0d3f55',
            'primary-light': '#1a6e96',
          }
        }
      }
    }
  </script>
  <style>
    body {
      font-family: "Montserrat", system-ui, -apple-system, Arial, sans-serif;
    }
    
    .card-shadow {
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .input-transition {
      transition: all 0.3s ease;
    }
    
    .gradient-bg {
      background: linear-gradient(135deg, #115272 0%, #0d3f55 100%);
    }
    
    .logo-text {
      text-shadow: 2px 4px 8px rgba(0,0,0,0.28);
    }
    
    .form-section {
      position: relative;
      overflow: hidden;
    }
    
    .form-section::before {
      content: "";
      position: absolute;
      width: 200%;
      height: 200%;
      top: -50%;
      left: -50%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
      transform: rotate(-5deg);
      z-index: 0;
    }
    
    .logo-placeholder {
      width: 48px;
      height: 48px;
      background: linear-gradient(45deg, #2563eb, #3b82f6);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 20px;
    }

    .error-alert {
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

    .error-input {
      border-color: #dc2626;
      background-color: #fef2f2;
    }

    .error-input:focus {
      border-color: #dc2626;
      box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .success-alert {
      animation: slideDown 0.3s ease-out;
    }

    .info-alert {
      animation: slideDown 0.3s ease-out;
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-cover bg-center bg-fixed p-4 bg-gray-100"
  style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><rect width=\"100\" height=\"100\" fill=\"%23f0f4f8\"/><path d=\"M0 0L100 100\" stroke=\"%23dbeafe\" stroke-width=\"2\"/><path d=\"M100 0L0 100\" stroke=\"%23dbeafe\" stroke-width=\"2\"/></svg>

  <div class="flex w-full max-w-4xl h-auto md:h-[550px] rounded-xl card-shadow overflow-hidden bg-white">

    <!-- Left Branding -->
    <div class="hidden md:flex w-2/5 items-center justify-center gradient-bg p-8 relative">
      <div class="absolute top-6 left-6 flex items-center">
       
      </div>
      
      <div class="text-center z-10">
        <div class="flex flex-col items-center justify-center mb-4">
          <!-- Logo -->
          <div class="flex items-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Touchstar" class="h-12">
            <h1 class="text-white font-semibold ml-2 text-2xl">Touchstar</h1>
          </div>
          <div class="text-sm font-bold tracking-widest text-white/80 mb-8">
            TOUCHSTAR MEDICAL ENTERPRISES INC.
          </div>
        </div>
        
        <div class="w-64 h-64 mx-auto mb-8 flex items-center justify-center">
          <div class="w-full h-full rounded-full border-4 border-white/20 flex items-center justify-center">
            <div class="w-11/12 h-11/12 rounded-full border-4 border-white/30 flex items-center justify-center">
              <div class="w-5/6 h-5/6 rounded-full border-4 border-white/40 flex items-center justify-center">
                <i class="fas fa-users text-white/30 text-5xl"></i>
              </div>
            </div>
          </div>
        </div>
        
        <p class="text-white/70 text-sm max-w-xs mx-auto pb-12">
          Empowering your workforce with seamless system solutions. Welcome to Touchstar Web System.
        </p>
      </div>
      
      <div class="absolute bottom-6 left-0 right-0 text-center text-white/50 text-xs">
        © 2006 Touchstar Medical Enterprises Inc.
      </div>
    </div>

    <!-- Right Login Form -->
    <div class="flex w-full md:w-3/5 items-center justify-center bg-white p-8 md:p-10 form-section">
      <div class="w-full max-w-sm z-10">
        <!-- Mobile Logo (visible only on small screens) -->
        <div class="md:hidden flex justify-center mb-6">
          <div class="flex items-center">
            <div class="logo-placeholder">TS</div>
            <h1 class="text-primary font-semibold ml-2 text-2xl">Touchstar</h1>
          </div>
        </div>
        
        <div class="text-center mb-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back</h2>
          <p class="text-gray-600 text-sm">Sign in to access your Touchstar account</p>
        </div>

        <!-- Error Messages (Laravel Validation) -->
        @if ($errors->any())
          <div class="error-alert bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-lg mb-6 text-sm">
            <div class="flex items-start">
              <div class="flex-shrink-0 mr-3 pt-0.5">
                <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
              </div>
              <div class="flex-1">
                @foreach ($errors->all() as $error)
                  <p class="font-medium mb-1">{{ $error }}</p>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Success Message -->
        @if(session('success'))
          <div class="success-alert bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-lg mb-6 text-sm">
            <div class="flex items-start">
              <div class="flex-shrink-0 mr-3 pt-0.5">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
              </div>
              <div class="flex-1">
                <p class="font-medium">{{ session('success') }}</p>
              </div>
            </div>
          </div>
        @endif

        <!-- Info Message (Session Expired, etc) -->
        @if(session('info'))
          <div class="info-alert bg-blue-50 border border-blue-200 text-blue-700 px-4 py-4 rounded-lg mb-6 text-sm">
            <div class="flex items-start">
              <div class="flex-shrink-0 mr-3 pt-0.5">
                <i class="fas fa-info-circle text-blue-500 text-lg"></i>
              </div>
              <div class="flex-1">
                <p class="font-medium">{{ session('info') }}</p>
              </div>
            </div>
          </div>
        @endif

        <!-- Warning Message -->
        @if(session('warning'))
          <div class="info-alert bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-4 rounded-lg mb-6 text-sm">
            <div class="flex items-start">
              <div class="flex-shrink-0 mr-3 pt-0.5">
                <i class="fas fa-warning text-yellow-500 text-lg"></i>
              </div>
              <div class="flex-1">
                <p class="font-medium">{{ session('warning') }}</p>
              </div>
            </div>
          </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="/login/auth" class="space-y-5">
          @csrf
          
          <!-- Email Input -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope {{ $errors->has('email') ? 'text-red-500' : 'text-gray-400' }}"></i>
              </div>
              <input 
                id="email" 
                name="email" 
                type="email" 
                placeholder="Enter your email address" 
                required
                value="{{ old('email') }}"
                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent input-transition {{ $errors->has('email') ? 'error-input' : '' }}"
              />
            </div>
            @if($errors->has('email'))
              <p class="mt-1 text-xs text-red-600 flex items-center">
                <i class="fas fa-times-circle mr-1"></i>
                {{ $errors->first('email') }}
              </p>
            @endif
          </div>

          <!-- Password Input -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
              </div>
              <input 
                id="password" 
                name="password" 
                type="password" 
                placeholder="Enter your password" 
                required
                class="w-full pl-10 pr-10 py-3 rounded-lg border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-transparent input-transition"
              />
              <button 
                type="button" 
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors" 
                id="togglePassword"
                tabindex="-1"
              >
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Remember -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input 
                id="remember" 
                name="remember" 
                type="checkbox" 
                class="h-4 w-4 text-primary focus:ring-2 focus:ring-primary border-gray-300 rounded cursor-pointer"
              />
              <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                Remember me
              </label>
            </div>
          </div>
          <!-- Submit Button -->
          <button 
            type="submit"
            class="w-full py-3 px-4 rounded-lg gradient-bg text-white font-semibold shadow-md hover:shadow-lg hover:opacity-90 transition-all duration-300 flex items-center justify-center group"
          >
            <span>Sign In</span>
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
          </button>
         <div class="text-center mt-4">
              <a href="https://touchstar-enterprises.com" 
              class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-primary transition-colors duration-200">
              
              <svg xmlns="http://www.w3.org/2000/svg" 
                  class="h-4 w-4 mr-2" 
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke="currentColor" 
                  stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
              </svg>

              Back to Home Page
            </a>
          </div>
        </form>

        <!-- Security Notice (added from new design) -->
        <div class="mt-6 pt-4 border-t border-gray-200">
          <p class="text-xs text-gray-500 text-center flex items-center justify-center">
            <i class="fas fa-shield-alt text-primary mr-1"></i>
            Secure login • Rate limited • Single session
          </p>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function(e) {
      e.preventDefault();
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      // Toggle eye icon
      this.querySelector('i').classList.toggle('fa-eye');
      this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Add focus/blur styles to inputs
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        if (!this.classList.contains('error-input')) {
          this.classList.add('border-primary', 'ring-2', 'ring-primary-light');
        }
      });
      
      input.addEventListener('blur', function() {
        if (!this.classList.contains('error-input')) {
          this.classList.remove('border-primary', 'ring-2', 'ring-primary-light');
          this.classList.add('border-gray-300');
        }
      });
    });

    // Auto-hide success messages after 5 seconds
    const successAlert = document.querySelector('.success-alert');
    if (successAlert) {
      setTimeout(() => {
        successAlert.style.transition = 'opacity 0.3s ease-out';
        successAlert.style.opacity = '0';
        setTimeout(() => successAlert.remove(), 300);
      }, 5000);
    }
  </script>
</body>

</html>