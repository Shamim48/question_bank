<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Ambassador Search — QuestionBank</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } } }
    </script>
    <style>
        .glass {
            background: rgba(30, 27, 75, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(99, 102, 241, 0.15);
        }

        .glass-light {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gradient-text {
            background: linear-gradient(135deg, #818cf8, #c084fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.15);
        }

        .slide-up {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        input:focus,
        select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
        }
    </style>
    @livewireStyles
</head>

<body
    class="font-sans antialiased bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-950 text-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-bold gradient-text">✦ QuestionBank</a>
        </div>
        <livewire:ambassador-search />
    </div>
    @livewireScripts
</body>

</html>