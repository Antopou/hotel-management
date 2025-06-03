<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hotel Management')</title>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Noto Sans Khmer', sans-serif;
            opacity: 0;
            transition: opacity 0.2s ease-in-out; /* Faster transition */
        }
        body.loaded {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div id="app">
        <header class="bg-light py-3 mb-4">
            <div class="container d-flex justify-content-between align-items-center">
                <h1 class="h5 m-0">Hotel Management</h1>
            @if(isset($backUrl))
                <a href="{{ $backUrl }}" class="btn btn-secondary mb-3">Back</a>
            @endif
            </div>
        </header>

        <main class="container">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('loaded');
        });

        document.querySelectorAll('a').forEach(link => {
            const href = link.getAttribute('href');
            if (href && href.startsWith('{{ url('/') }}')) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.body.classList.remove('loaded');
                    setTimeout(() => {
                        window.location.href = href;
                    }, 150);
                });
            }
        });
    </script>
</body>
</html>
