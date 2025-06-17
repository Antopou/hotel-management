<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management - Login</title>
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .login-container {
            display: flex;
            height: 100vh;
        }
        .login-left {
            flex: 0 0 55%;
            background: url('{{ asset("images/hotel.png") }}') center center/cover no-repeat;
        }
        .login-right {
            flex: 0 0 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-box {
            width: 100%;
            max-width: 450px;
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
            background: rgba(255,255,255,0.88) !important;
            max-width: 450px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.16);
        }
        @media (max-width: 500px) {
            .login-mobile-form .login-box {
                max-width: 95vw;
                padding: 1rem !important;
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
    </style>
</head>
<body>
    <!-- Desktop View -->
    <div class="login-container">
        <div class="login-left d-none d-md-block"></div>
        <div class="login-right">
            <div class="login-box shadow p-4 rounded bg-white">
                <h3 class="text-center mb-4">Login</h3>
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control" required autofocus>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    {{-- <a href="{{ route('register') }}">Don’t have an account? Register</a> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile View (Form on Image Overlay) -->
    <div class="login-mobile-overlay d-block d-md-none">
        <img src="{{ asset('images/hotel.png') }}" alt="Hotel" class="mobile-hotel-img">
        <div class="login-mobile-form">
            <div class="login-box shadow p-4 rounded bg-white bg-opacity-75">
                <h3 class="text-center mb-4">Login</h3>
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control" required autofocus>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}">Don’t have an account? Register</a>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
