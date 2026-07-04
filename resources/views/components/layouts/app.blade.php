<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'QuestionBank') }} — @yield('title', 'Dashboard')</title>

    <!-- Fonts: Inter & Outfit for headers -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS 4 & Custom Setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script data-navigate-track>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            300: '#a5b4fc', 400: '#818cf8', 500: '#6366f1',
                            600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81',
                        },
                        surface: '#0f172a',
                    }
                }
            }
        }
    </script>

    <!-- External Assets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Custom Premium Styles -->
    <style data-navigate-track>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(99, 102, 241, 0.15);
            --glow-color: rgba(99, 102, 241, 0.2);
        }

        body {
            background-color: #f8fafc;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(30, 64, 175, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }

        .glass {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.08);
        }

        .glass-card {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.5);
            transform: translateY(-4px);
            box-shadow: 0 16px 40px -8px rgba(99, 102, 241, 0.25);
        }

        .gradient-text {
            background: linear-gradient(135deg, #a5b4fc 0%, #818cf8 50%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-item {
            position: relative;
            transition: all 0.2s ease;
        }

        .sidebar-item.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
            color: #4f46e5;
            font-weight: 600;
        }

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #4f46e5;
            box-shadow: 0 0 10px rgba(79, 70, 229, 0.5);
        }

        /* Hide scrollbars but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Smooth inputs */
        input,
        select,
        textarea {
            background: rgba(255, 255, 255, 0.8) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            color: #000000 !important;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        }

        /* Select option text always black */
        select option,
        select option:checked,
        select option:hover {
            color: #000000 !important;
            background: #ffffff;
        }

        /* Custom dropdown menu items */
        .dropdown-item {
            color: #000000 !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
</head>

<body class="font-sans antialiased text-gray-900 min-h-screen overflow-x-hidden" x-data="{ 
        sidebarOpen: true, 
        mobileMenuOpen: false,
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        isLoading: false
      }" 
      x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
      @show-global-loader.window="isLoading = true"
      @hide-global-loader.window="isLoading = false"
      @clear-manual-loaders.window="isLoading = false">

    <!-- Global Loading Indicator -->
    <div x-show="isLoading" x-transition.opacity.duration.300ms class="fixed top-0 left-0 right-0 z-[100] h-1 bg-indigo-600/20" x-cloak>
        <div class="h-full bg-indigo-600 shadow-[0_0_10px_rgba(79,70,229,0.8)]" style="width: 100%; animation: loading-bar 1.5s linear infinite;"></div>
    </div>

    <style>
        @keyframes loading-bar {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>

    <div class="flex min-h-screen relative">
        <!-- Sidebar Shell -->
        <aside :class="sidebarCollapsed ? 'w-20' : 'w-72'"
            class="hidden lg:flex flex-col glass fixed inset-y-0 left-0 z-40 transition-all duration-200 ease-in-out border-r border-white/5 no-scrollbar">
            <!-- Logo Area -->
            <div class="h-20 flex items-center px-6 border-b border-white/5 shrink-0 justify-between">
                <div class="flex items-center gap-3 overflow-hidden" x-show="!sidebarCollapsed"
                    x-transition:enter="delay-200">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-600/30">
                        <i data-lucide="zap" class="w-6 h-6 text-white fill-white"></i>
                    </div>
                    <span class="font-display font-bold text-xl tracking-tight gradient-text whitespace-nowrap">
                        {{ auth()->check() && auth()->user()->isAdmin() ? 'QB Admin' : 'Student Portal' }}
                    </span>
                </div>
                <div x-show="sidebarCollapsed" class="w-full flex justify-center">
                    <div
                        class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-600/30">
                        <i data-lucide="zap" class="w-6 h-6 text-white fill-white"></i>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto no-scrollbar">
                @php
                    $authUser = auth()->user();
                    $isAdmin  = $authUser?->isAdmin();
                    $isTeam   = $authUser?->isTeam();

                    $allAdminItems = [
                        ['route' => 'admin.profile',        'icon' => 'user-round',        'label' => 'My Profile',     'permission' => 'profile-access'],
                        ['route' => 'admin.dashboard',      'icon' => 'layout-dashboard',  'label' => 'Dashboard',      'permission' => 'dashboard-access'],
                        ['route' => 'admin.subjects',       'icon' => 'book-open',         'label' => 'Subjects',       'permission' => 'subjects-list'],
                        ['route' => 'admin.groups',         'icon' => 'users',             'label' => 'Groups',         'permission' => 'groups-list'],
                        ['route' => 'admin.seasons.index',  'icon' => 'calendar',          'label' => 'Seasons',        'permission' => 'seasons-list', 'activePattern' => 'admin.seasons*'],
                        ['route' => 'admin.class-levels',   'icon' => 'graduation-cap',    'label' => 'Classes',        'permission' => 'class-levels-list'],
                        ['route' => 'admin.participants',   'icon' => 'user-check',        'label' => 'Participant Management', 'permission' => 'participants-list'],
                        ['route' => 'admin.rounds',         'icon' => 'layers',            'label' => 'Exam Rounds',    'permission' => 'rounds-list'],
                        ['route' => 'admin.questions',      'icon' => 'database',          'label' => 'Question Bank',  'permission' => 'questions-list'],
                        ['route' => 'admin.exams',          'icon' => 'activity',          'label' => 'Exam Control',   'permission' => 'exams-list'],
                        ['route' => 'admin.marks',          'icon' => 'award',             'label' => 'Manual Marks',   'permission' => 'marks-list'],
                        ['route' => 'admin.offline-marks',  'icon' => 'clipboard-check',   'label' => 'Offline Marks',  'permission' => 'offline-marks-list'],
                        ['route' => 'admin.certificates',   'icon' => 'scroll',            'label' => 'Certificates',   'permission' => 'certificates-list'],
                        ['route' => 'admin.pdf-books',      'icon' => 'book',              'label' => 'PDF Books',      'permission' => 'pdf-books-list'],
                        ['route' => 'ambassadors',          'icon' => 'star',              'label' => 'Ambassadors',    'permission' => 'ambassadors-list'],
                        [
                            'type'      => 'dropdown',
                            'icon'      => 'shield-check',
                            'label'     => 'User Management',
                            'adminOnly' => true,
                            'children'  => [
                                ['route' => 'admin.users.create',  'icon' => 'user-plus', 'label' => 'Add User'],
                                ['route' => 'admin.users.index',   'icon' => 'users',      'label' => 'User List'],
                                ['route' => 'admin.users.pending', 'icon' => 'clock',      'label' => 'Pending User'],
                                ['route' => 'admin.roles',         'icon' => 'tag',        'label' => 'Roles'],
                            ],
                        ],
                    ];

                    if ($isAdmin) {
                        $navItems = $allAdminItems;
                    } elseif ($isTeam) {
                        $navItems = array_filter($allAdminItems, function($item) use ($authUser) {
                            if (!empty($item['adminOnly'])) return false;
                            $perm = $item['permission'] ?? null;
                            return $perm && $authUser->hasPermission($perm);
                        });
                    } else {
                        $navItems = [
                            ['route' => 'student.dashboard',    'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                            ['route' => 'student.exams',        'icon' => 'activity',         'label' => 'Exams'],
                            ['route' => 'student.results',      'icon' => 'award',            'label' => 'Results'],
                            ['route' => 'student.certificates', 'icon' => 'scroll',           'label' => 'Certificates'],
                            ['route' => 'student.pdf-books',    'icon' => 'book',             'label' => 'PDF Books'],
                            ['route' => 'student.profile',      'icon' => 'user-round',       'label' => 'My Profile'],
                        ];
                    }
                @endphp

                @foreach($navItems as $item)
                    @if(($item['type'] ?? null) === 'dropdown')
                        @php $childActive = collect($item['children'])->contains(fn($c) => request()->routeIs($c['route'])); @endphp
                        <div x-data="{ open: {{ $childActive ? 'true' : 'false' }} }">
                            <button type="button" @click="open = !open"
                                class="sidebar-item w-full group flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-medium hover:text-indigo-700 transition-all {{ $childActive ? 'active' : 'text-gray-600 hover:bg-indigo-50' }}"
                                :title="sidebarCollapsed ? '{{ $item['label'] }}' : ''">
                                <i data-lucide="{{ $item['icon'] }}"
                                    class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110"></i>
                                <span x-show="!sidebarCollapsed" x-transition:enter.delay.100ms x-cloak class="flex-1 text-left">{{ $item['label'] }}</span>
                                <i data-lucide="chevron-down" x-show="!sidebarCollapsed" x-cloak
                                    class="w-4 h-4 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="open && !sidebarCollapsed" x-cloak class="pl-6 mt-1 space-y-1">
                                @foreach($item['children'] as $child)
                                    <a href="{{ route($child['route']) }}"
                                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs($child['route']) ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-500 hover:bg-indigo-50 hover:text-indigo-700' }}">
                                        <i data-lucide="{{ $child['icon'] }}" class="w-4 h-4 shrink-0"></i>
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @php $isActive = request()->routeIs($item['activePattern'] ?? $item['route']); @endphp
                        <a href="{{ route($item['route']) }}"
                            class="sidebar-item group flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-medium hover:text-indigo-700 transition-all {{ $isActive ? 'active' : 'text-gray-600 hover:bg-indigo-50' }}"
                            :title="sidebarCollapsed ? '{{ $item['label'] }}' : ''">
                            <i data-lucide="{{ $item['icon'] }}"
                                class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110"></i>
                            <span x-show="!sidebarCollapsed" x-transition:enter.delay.100ms x-cloak>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>

            <!-- Bottom Toggle -->
            <div class="p-4 border-t border-white/5">
                <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="w-full h-12 flex items-center justify-center rounded-xl bg-indigo-50 hover:bg-indigo-100 text-gray-600 hover:text-indigo-700 transition-colors">
                    <i :data-lucide="sidebarCollapsed ? 'chevron-right' : 'chevron-left'" class="w-5 h-5"></i>
                </button>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col transition-all duration-200 ease-in-out"
            :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72'">

            <!-- Top Header -->
            <header class="h-20 glass sticky top-0 z-30 flex items-center px-8 border-b border-white/5 justify-between">
                <div class="flex items-center gap-6">
                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenuOpen = true" class="lg:hidden text-gray-600 hover:text-indigo-700">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>

                    <!-- Breadcrumbs -->
                    <div class="hidden sm:flex items-center gap-2 text-sm text-gray-400">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        <i data-lucide="chevron-right" class="w-3 h-3 text-gray-600"></i>
                        <span
                            class="text-indigo-400 font-medium capitalize">{{ str_replace(['admin.', 'student.', '-'], ['', '', ' '], request()->route()->getName()) }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Global Search -->
                    <div
                        class="hidden md:flex items-center glass-card rounded-full px-4 py-2 border-white/10 group focus-within:border-indigo-500/50">
                        <i data-lucide="search" class="w-4 h-4 text-gray-500 group-focus-within:text-indigo-400"></i>
                        <input type="text" placeholder="Search..."
                            class="bg-transparent border-none focus:ring-0 text-sm w-48 py-0 ml-2">
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center gap-3 pl-6 border-l border-white/10">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-indigo-600 uppercase tracking-widest font-bold">
                                {{ auth()->user()->role }}</p>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="w-10 h-10 rounded-full border-2 border-indigo-500/30 p-0.5 overflow-hidden transition-transform active:scale-95">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff"
                                    class="w-full h-full rounded-full border border-white/10">
                            </button>
                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition-opacity transition-transform duration-200"
                                x-transition:enter-start="scale-95 opacity-0"
                                x-transition:leave="transition-opacity duration-150"
                                x-transition:leave-end="opacity-0"
                                class="absolute right-0 mt-3 w-56 bg-white rounded-2xl p-2 shadow-xl border border-gray-100"
                                x-cloak>
                                <!-- User info header -->
                                <div class="px-3 py-2.5 mb-1">
                                    <p class="text-xs font-bold text-black">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="h-px bg-gray-100 my-1"></div>

                                @if(auth()->user()->isAdmin() || auth()->user()->isTeam())
                                    <a href="{{ route('admin.profile') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-sm font-medium transition-colors text-black">
                                        <i data-lucide="user-round" class="w-4 h-4 text-gray-500"></i> My Profile
                                    </a>
                                @elseif(auth()->user()->isStudent())
                                    <a href="{{ route('student.profile') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-sm font-medium transition-colors text-black">
                                        <i data-lucide="user-round" class="w-4 h-4 text-gray-500"></i> My Profile
                                    </a>
                                @endif

                                <div class="h-px bg-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 text-sm font-medium transition-colors text-red-600">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8 flex-1 animate__animated animate__fadeIn">
                @if (session('success'))
                    <div
                        class="mb-8 glass flex items-center gap-4 rounded-2xl p-4 border-l-4 border-emerald-500 animate__animated animate__slideInRight">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <i data-lucide="check-circle" class="w-6 h-6 text-emerald-400"></i>
                        </div>
                        <p class="text-emerald-100 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-8 glass flex items-center gap-4 rounded-2xl p-4 border-l-4 border-red-500 animate__animated animate__slideInRight">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center shrink-0">
                            <i data-lucide="alert-circle" class="w-6 h-6 text-red-400"></i>
                        </div>
                        <p class="text-red-100 font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="p-8 border-t border-white/5 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Shamim Ahmed
            </footer>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div x-show="mobileMenuOpen" class="lg:hidden fixed inset-0 z-50 overflow-hidden" x-cloak>
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
        <div x-show="mobileMenuOpen" x-transition:enter="transition-transform duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:leave="transition-transform duration-300"
            x-transition:leave-end="-translate-x-full"
            class="absolute inset-y-0 left-0 w-72 glass flex flex-col shadow-2xl">
            <!-- Same content as sidebar, omitted for brevity but should be functional -->
            <div class="p-6 h-full flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <span class="font-display font-bold text-xl gradient-text">
                        {{ auth()->check() && auth()->user()->isAdmin() ? 'QB Admin' : 'Student Portal' }}
                    </span>
                    <button @click="mobileMenuOpen = false" class="text-gray-600 hover:text-indigo-700"><i data-lucide="x"
                            class="w-6 h-6"></i></button>
                </div>
                <nav class="flex-1 space-y-2">
                    @foreach($navItems as $item)
                        @if(($item['type'] ?? null) === 'dropdown')
                            <p class="px-4 pt-3 pb-1 text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $item['label'] }}</p>
                            @foreach($item['children'] as $child)
                                <a href="{{ route($child['route']) }}"
                                    class="flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs($child['route']) ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                                    <i data-lucide="{{ $child['icon'] }}" class="w-5 h-5"></i>
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        @else
                            <a href="{{ route($item['route']) }}"
                                class="flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs($item['route']) ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
                                {{ $item['label'] }}
                            </a>
                        @endif
                    @endforeach
                </nav>
            </div>
        </div>
    </div>

    @livewireScripts
    <script>
        // Init Lucide icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        // Re-init icons on Livewire navigate (Tailwind CDN uses MutationObserver — no need to re-init)
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });

        window.addEventListener('reinitIcons', () => {
            lucide.createIcons();
        });

        // Clear manual loading states on Livewire download
        window.addEventListener('livewire:download', () => {
            window.dispatchEvent(new CustomEvent('hide-global-loader'));
            window.dispatchEvent(new CustomEvent('clear-manual-loaders'));
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('request', ({ uri, options, payload, respond, succeed, fail }) => {
                // Show loader after small delay to avoid flickering on fast requests
                window.loaderTimeout = setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('show-global-loader'));
                }, 150);
            });

            Livewire.hook('request.finished', () => {
                clearTimeout(window.loaderTimeout);
                window.dispatchEvent(new CustomEvent('hide-global-loader'));
            });

            Livewire.hook('morph.updated', ({ el, component }) => {
                lucide.createIcons();
            });
        });
    </script>
    @stack('scripts')
</body>

</html>