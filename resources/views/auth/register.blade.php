<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management - Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .register-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .register-left {
            flex: 1;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.9) 0%, rgba(29, 78, 216, 0.9) 100%), 
                        url('{{ asset("images/hotel.png") }}') center center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .register-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.8) 0%, rgba(29, 78, 216, 0.9) 100%);
            z-index: 1;
        }

        .register-left-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .register-left h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .register-left p {
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
            color: #10b981;
        }

        .register-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            position: relative;
        }

        .register-right::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50px;
            bottom: 0;
            width: 100px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 50%);
            z-index: 1;
        }

        .register-box {
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 2;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header .logo {
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

        .register-header .logo i {
            font-size: 1.75rem;
            color: white;
        }

        .register-header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .register-header p {
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
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
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e40af 100%);
            box-shadow: var(--shadow-lg);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .register-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .register-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .register-footer a:hover {
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

        .alert-danger ul {
            margin: 0;
            padding-left: 1rem;
        }

        /* Mobile overlay */
        .register-mobile-overlay {
            position: relative;
            width: 100%;
            min-height: 100vh;
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
        .register-mobile-form {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            z-index: 2;
            display: flex;
            justify-content: center;
        }
        .register-mobile-form .register-box {
            background: rgba(255,255,255,0.95) !important;
            max-width: 450px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.16);
            padding: 2rem;
        }
        @media (max-width: 500px) {
            .register-mobile-form .register-box {
                max-width: 95vw;
                padding: 1.5rem !important;
            }
        }
        @media (max-width: 767.98px) {
            .register-container {
                display: none !important;
            }
            .register-mobile-overlay {
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
    <!-- Desktop View -->
    <div class="register-container">
        <div class="register-left d-none d-md-flex">
            <div class="register-left-content">
                <div class="floating">
                    <h1><i class="bi bi-building"></i></h1>
                </div>
                <h1>Join Our Platform</h1>
                <p>Create your account and start managing your hotel efficiently</p>
                <ul class="feature-list">
                    <li><i class="bi bi-check-circle-fill"></i> Easy Setup</li>
                    <li><i class="bi bi-check-circle-fill"></i> Secure Platform</li>
                    <li><i class="bi bi-check-circle-fill"></i> 24/7 Support</li>
                    <li><i class="bi bi-check-circle-fill"></i> Free Trial</li>
                </ul>
            </div>
        </div>
        
        <div class="register-right">
            <div class="register-box">
                <div class="register-header">
                    <div class="logo">
                        <i class="bi bi-building"></i>
                    </div>
                    <h2>Create Account</h2>
                    <p>Fill in your details to get started</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}"
                               required 
                               autofocus
                               placeholder="Enter your full name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               required
                               placeholder="Enter your email address">
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
                               placeholder="Create a strong password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="form-control" 
                               required
                               placeholder="Confirm your password">
                    </div>

                    <button type="submit" class="btn btn-primary" id="registerBtn">
                        <i class="bi bi-person-plus me-2"></i>
                        Create Account
                    </button>
                </form>

                <div class="register-footer">
                    <a href="{{ route('login') }}">
                        Already have an account? <strong>Sign in here</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile View (Form on Image Overlay) -->
    <div class="register-mobile-overlay d-block d-md-none">
        <img src="{{ asset('images/hotel.jpg') }}" alt="Hotel" class="mobile-hotel-img">
        <div class="register-mobile-form">
            <div class="register-box">
                <div class="register-header">
                    <div class="logo">
                        <i class="bi bi-building"></i>
                    </div>
                    <h2>Create Account</h2>
                    <p>Get started today</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Please fix the errors:</strong>
                        <ul class="mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name_mobile" class="form-label">Full Name</label>
                        <input type="text" 
                               name="name" 
                               id="name_mobile" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}"
                               required 
                               autofocus
                               placeholder="Enter your full name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email_mobile" class="form-label">Email Address</label>
                        <input type="email" 
                               name="email" 
                               id="email_mobile" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}"
                               required
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
                               placeholder="Create password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation_mobile" class="form-label">Confirm Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation_mobile" 
                               class="form-control" 
                               required
                               placeholder="Confirm password">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>
                        Create Account
                    </button>
                </form>

                <div class="register-footer">
                    <a href="{{ route('login') }}">Already have an account? Sign in</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerForm = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');

            if (registerForm && registerBtn) {
                registerForm.addEventListener('submit', function() {
                    registerBtn.classList.add('btn-loading');
                    registerBtn.disabled = true;
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

            // Password strength indicator
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    const strength = calculatePasswordStrength(password);
                    // You can add visual feedback here
                });
            }
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }
    </script>
</body>
</html>
