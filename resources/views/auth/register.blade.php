<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management - Register</title>
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .register-container {
            display: flex;
            height: 100vh;
        }
        .register-left {
            flex: 0 0 55%;
            background: url('{{ asset("images/hotel.png") }}') center center/cover no-repeat;
        }
        .register-right {
            flex: 0 0 45%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .register-box {
            width: 100%;
            max-width: 450px;
        }

        .register-mobile-overlay {
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
            background: rgba(255,255,255,0.88) !important;
            max-width: 450px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(0,0,0,0.16);
        }
        @media (max-width: 500px) {
            .register-mobile-form .register-box {
                max-width: 95vw;
                padding: 1rem !important;
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
    </style>
</head>
<body>
    <!-- Desktop View -->
    <div class="register-container">
        <div class="register-left d-none d-md-block"></div>
        <div class="register-right">
            <div class="register-box shadow p-4 rounded bg-white">
                <h3 class="text-center mb-4">Register</h3>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" required autofocus value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile View (Form on Image Overlay) -->
    <div class="register-mobile-overlay d-block d-md-none">
        <img src="{{ asset('images/hotel.jpg') }}" alt="Hotel" class="mobile-hotel-img">
        <div class="register-mobile-form">
            <div class="register-box shadow p-4 rounded bg-white bg-opacity-75">
                <h3 class="text-center mb-4">Register</h3>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" required autofocus value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('login') }}">Already have an account? Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
