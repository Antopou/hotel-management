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
            flex: 1;
            background: url('{{ asset("images/hotel.jpg") }}') center center/cover no-repeat;
        }
        .login-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left d-none d-md-block"></div>
        <div class="login-right">
            <div class="login-box shadow p-4 rounded bg-white">
                <h3 class="text-center mb-4">Hotel Management Login</h3>
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
