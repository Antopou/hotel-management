<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hotel Management</title>

    <script>
        if (localStorage.getItem('sidebarExpanded') === 'true') {
            document.documentElement.classList.add('expanded-sidebar');
        }
        document.addEventListener("DOMContentLoaded", function() {
            document.documentElement.classList.add('js-ready');
        });
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        /* Remove sidebar styles */
        /* #sidebar { ... } */

        #content {
            margin-left: 0 !important; /* No sidebar, so no margin */
            transition: none !important;
            flex-grow: 1;
            min-width: 0;
            overflow-x: auto;
            padding: 1rem;
        }

        html:not(.js-ready) #content {
            transition: none !important;
        }

        .card-hover:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-5px);
            transition: all 0.3s ease-in-out;
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.8rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .custom-modal {
            max-width: 1000px;
        }

        .custom-modal .modal-content {
            height: 90vh;
            overflow-y: auto;
        }

        /* Custom CSS for clickable cards and hover effect */
        .clickable-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .clickable-card:hover {
            transform: translateY(-5px); /* Lifts the card slightly on hover */
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; /* Stronger shadow on hover */
        }

        /* Adjust font size for larger icons in summary cards */
        .summary-card-icon i {
            font-size: 2.5rem; /* Adjust as needed */
        }

        .page-link {
            font-size: 1rem !important;
            padding: 0.375rem 0.75rem !important;
        }
    </style>
</head>
<body>
    {{-- Sidebar removed --}}

    <div id="content">
        <div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            @if (session('success'))
                <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('success') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">{{ session('error') }}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>

        @yield('content')
    </div>

    <script src="{{ asset('asset/bootstrap-5.3.3/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const successToast = document.getElementById('successToast');
            const errorToast = document.getElementById('errorToast');
            if (successToast) new bootstrap.Toast(successToast, { delay: 1000 }).show();
            if (errorToast) new bootstrap.Toast(errorToast, { delay: 3000 }).show();
        });
    </script>

    @stack('scripts')
</body>
</html>