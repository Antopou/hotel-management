<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a90e2; /* Softer blue, good with hotel imagery */
            --primary-dark: #3a7bc4;
            --primary-light: #6aaaf7;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-radius: 12px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        * {
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: 
                linear-gradient(135deg, rgba(30,41,59,0.7) 0%, rgba(30,41,59,0.85) 100%),
                url('{{ asset('images/hotel.jpg') }}') center center/cover no-repeat;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100vh;
            position: relative;
        }

        .login-left {
            flex: 55%; /* Adjusted to 55% */
            background: 
                linear-gradient(135deg, rgba(30,41,59,0.7) 0%, rgba(30,41,59,0.85) 100%),
                url('{{ asset('images/hotel.jpg') }}') center center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            display: none;
        }

        .login-left-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .login-left h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login-left p {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .feature-list {
            list-style: none;
            padding: 0;
            text-align: left;
            max-width: 300px;
            margin: 0 auto;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1rem;
            opacity: 0.9;
        }

        .feature-list li i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
            color: var(--primary-light); 
        }

        .login-right {
            flex: 45%; /* Adjusted to 45% */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            position: relative;
        }

        .login-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50px;
            bottom: 0;
            width: 100px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 50%);
            z-index: 1;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: var(--shadow-lg);
        }

        .login-header .logo i {
            font-size: 1.75rem;
            color: white;
        }

        .login-header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background-color: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1); 
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-check-input {
            width: 1.125rem;
            height: 1.125rem;
            margin-right: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 4px;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            cursor: pointer;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.875rem 1.5rem;
            transition: all 0.2s ease;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--shadow-md);
            width: 100%;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #306bae 100%); 
            box-shadow: var(--shadow-lg);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .login-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: none;
            font-size: 0.875rem;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        /* Mobile overlay */
        .login-mobile-overlay {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            display: none;
        }
        .mobile-hotel-img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            position: absolute;
            top: 0; left: 0;
            z-index: 1;
        }
        .login-mobile-form {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            z-index: 2;
            display: flex;
            justify-content: center;
        }
        .login-mobile-form .login-box {
            background: rgba(255,255,255,0.95) !important;
            max-width: 450px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.16);
            padding: 2rem;
        }
        @media (max-width: 500px) {
            .login-mobile-form .login-box {
                max-width: 95vw;
                padding: 1.5rem !important;
            }
        }
        @media (max-width: 767.98px) {
            .login-container {
                display: none !important;
            }
            .login-mobile-overlay {
                display: block;
            }
        }

        /* Loading Animation */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Floating Animation */
        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left d-none d-md-flex">
            <div class="login-left-content">
                <div class="floating">
                    <h1><i class="bi bi-building"></i></h1>
                </div>
                <h1>Hotel Management</h1>
                <p>Professional hotel management system for modern hospitality</p>
                <ul class="feature-list">
                    <li><i class="bi bi-check-circle-fill"></i> Room Management</li>
                    <li><i class="bi bi-check-circle-fill"></i> Guest Services</li>
                    <li><i class="bi bi-check-circle-fill"></i> Booking System</li>
                    <li><i class="bi bi-check-circle-fill"></i> Revenue Analytics</li>
                </ul>
            </div>
        </div>
        
        <div class="login-right">
            <div class="login-box">
                <div class="login-header">
                    <div class="logo">
                        <i class="bi bi-building"></i>
                    </div>
                    <h2>Welcome Back</h2>
                    <p>Sign in to your account to continue</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               required 
                               autofocus
                               placeholder="Enter your email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               required
                               placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary" id="loginBtn">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    </button>
                </form>

                <div class="login-footer">
                    {{-- <a href="{{ route('register') }}">
                        Don't have an account? <strong>Create one here</strong>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="login-mobile-overlay d-block d-md-none">
        <img src="{{ asset('images/hotel.jpg') }}" alt="Hotel" class="mobile-hotel-img">
        <div class="login-mobile-form">
            <div class="login-box">
                <div class="login-header">
                    <div class="logo">
                        <i class="bi bi-building"></i>
                    </div>
                    <h2>Welcome Back</h2>
                    <p>Sign in to your account</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email_mobile" class="form-label">Email Address</label>
                        <input type="email" 
                               name="email" 
                               id="email_mobile" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               required 
                               autofocus
                               placeholder="Enter your email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_mobile" class="form-label">Password</label>
                        <input type="password" 
                               name="password" 
                               id="password_mobile" 
                               class="form-control @error('password') is-invalid @enderror" 
                               required
                               placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember_mobile" class="form-check-input">
                        <label for="remember_mobile" class="form-check-label">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    </button>
                </form>

                <div class="login-footer">
                    <a href="{{ route('register') }}">Don't have an account? Register</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');

            if (loginForm && loginBtn) {
                loginForm.addEventListener('submit', function() {
                    loginBtn.classList.add('btn-loading');
                    loginBtn.disabled = true;
                });
            }

            // Add focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
</body>
</html>