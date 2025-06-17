<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Management</title>

    <script>
        // This script needs to run early to prevent FOUC (Flash Of Unstyled Content)
        // by applying the expanded-sidebar class before the page renders.
        if (localStorage.getItem('sidebarExpanded') === 'true') {
            document.documentElement.classList.add('expanded-sidebar');
        }
        // Add js-ready class to disable transitions on initial load
        document.addEventListener("DOMContentLoaded", function() {
            document.documentElement.classList.add('js-ready');
        });
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@100..900&display=swap" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-5.3.3/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            /* Removed display: flex; as per suggestion */
            overflow-x: hidden; /* Ensure body doesn't overflow horizontally */
        }

        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 60px; /* Initial width for collapsed sidebar */
            /* ... other sidebar styles like background-color, z-index, etc. */
            transition: width 0.3s ease;
            z-index: 1050; /* Ensure sidebar is above content but below toasts */
        }

        .expanded-sidebar #sidebar {
            width: 200px; /* Expanded width */
        }

        #content {
            margin-left: 60px; /* Initial margin for collapsed sidebar */
            transition: margin-left 0.3s ease;
            flex-grow: 1; /* This is generally good to keep if you have other flexible elements, though not strictly needed if body is not flex */
            min-width: 0; /* Allows content to shrink without overflow */
            overflow-x: auto; /* Add this to allow horizontal scrolling within content if necessary */
            padding: 1rem; /* Kept your existing padding */
        }

        .expanded-sidebar #content {
            margin-left: 200px; /* Margin for expanded sidebar */
        }

        /* Prevent transitions on initial load to avoid flickering */
        html:not(.js-ready) #sidebar,
        html:not(.js-ready) #content {
            transition: none !important;
        }

        /* NEW CSS FOR DASHBOARD UI IMPROVEMENTS - Keep these here as they are general UI */
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

        #sidebar,
        #sidebar * {
            color: hsla(0, 0%, 0%, 0.764) !important;
        }
    </style>
</head>
<body>
    @include('partials.sidebar') {{-- Include the sidebar partial here --}}

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
            // Toast initialization remains here as it's general UI
            const successToast = document.getElementById('successToast');
            const errorToast = document.getElementById('errorToast');
            if (successToast) new bootstrap.Toast(successToast, { delay: 1000 }).show();
            if (errorToast) new bootstrap.Toast(errorToast, { delay: 3000 }).show();
        });
    </script>

    @yield('scripts')
</body>
</html>