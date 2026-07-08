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
            darkMode: 'class',
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
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background: #fff;
            display: flex;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            background: #fff !important;
            border: 1.5px solid #e5e7eb !important;
            color: #111 !important;
            border-radius: 10px !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            width: 100%;
            outline: none;
            transition: border-color 0.15s;
        }

        input:focus {
            border-color: #111 !important;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.08) !important;
        }

        /* Override Breeze label style */
        label.block { color: #374151 !important; font-weight: 600 !important; font-size: 13px !important; }
    </style>

    @livewireStyles
</head>

<body class="font-sans antialiased">

    <div class="flex w-full min-h-screen">

        <!-- Left Brand Panel -->
        <div class="relative hidden lg:flex lg:w-1/2 bg-gray-50 flex-col items-center justify-center p-16 overflow-hidden border-r border-gray-100">
            <!-- Subtle dot grid -->
            <div class="absolute inset-0 opacity-[0.5]"
                style="background-image: radial-gradient(circle, #d1d5db 1px, transparent 1px); background-size: 28px 28px;">
            </div>

            <!-- Soft center glow -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 rounded-full bg-gray-200/60 blur-[80px] pointer-events-none"></div>

            <div class="relative z-10 max-w-sm text-center">
                <!-- Logo mark -->
                <div class="w-20 h-20 rounded-3xl bg-black flex items-center justify-center mx-auto mb-10 shadow-xl shadow-black/10">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>

                <h1 class="font-display font-black text-gray-900 text-5xl leading-tight mb-6 tracking-tight">
                    Student of<br>the Year
                </h1>
                <p class="text-gray-400 text-base leading-relaxed font-light">
                    Your centralised learning and assessment platform. Track progress, ace exams.
                </p>

                <!-- Decorative dots -->
                <div class="flex items-center justify-center gap-2 mt-12">
                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                    <span class="w-1 h-1 rounded-full bg-gray-200"></span>
                </div>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="flex-1 bg-white flex flex-col items-center justify-center p-8 lg:p-16 relative">

            <!-- Mobile logo (shown only on small screens) -->
            <div class="lg:hidden mb-10 text-center">
                <div class="w-14 h-14 rounded-2xl bg-black flex items-center justify-center mx-auto mb-4 shadow-lg shadow-black/10">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h1 class="font-display font-black text-gray-900 text-3xl tracking-tight">SOTY</h1>
            </div>

            <div class="w-full max-w-sm">
                <!-- Heading -->
                <div class="mb-10">
                    <h2 class="font-display font-black text-black text-3xl mb-2 tracking-tight">Welcome back</h2>
                    <p class="text-gray-400 text-sm">Sign in to continue to your dashboard.</p>
                </div>

                <!-- The Livewire login form slot -->
                {{ $slot }}

            </div>

            <!-- Footer -->
            <p class="absolute bottom-8 text-xs text-gray-300">
                &copy; {{ date('Y') }} Student of the Year. All rights reserved.
            </p>
        </div>

    </div>

    @livewireScripts
</body>

</html>
