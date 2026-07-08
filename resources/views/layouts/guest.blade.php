<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Student of the Year') }} — @yield('title', 'Login')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f5f5f0;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #e8e8e3;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 10px 40px -10px rgba(0,0,0,0.08);
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    @livewireStyles
</head>

<body class="font-sans antialiased min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Brand -->
    <div class="mb-8 text-center fade-in">
        <div class="w-14 h-14 rounded-2xl bg-gray-900 flex items-center justify-center mx-auto mb-4 shadow-md">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
        </div>
        <h1 class="font-display font-black text-gray-900 text-2xl tracking-tight">Student of the Year</h1>
        <p class="text-gray-400 text-sm mt-1">Learning Management System</p>
    </div>

    <!-- Card -->
    <div class="login-card w-full max-w-md rounded-2xl p-8 fade-in">
        {{ $slot }}
    </div>

    <!-- Footer -->
    <p class="mt-8 text-xs text-gray-400">&copy; {{ date('Y') }} Student of the Year. All rights reserved.</p>

    @livewireScripts
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 4000,
        };

        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        @if (session('error'))
            toastr.error('{{ session('error') }}');
        @endif
    </script>
</body>

</html>
