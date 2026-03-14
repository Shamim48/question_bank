<?php
$baseDir = __DIR__ . '/resources/views';

function processFile($filepath) {
    if (!is_file($filepath) || pathinfo($filepath, PATHINFO_EXTENSION) !== 'php') return;

    $content = file_get_contents($filepath);
    $original = $content;

    $normalizedPath = str_replace('\\', '/', $filepath);
    
    if (strpos($normalizedPath, 'components/layouts/app.blade.php') !== false) {
        // Fix root variables
        $content = str_replace('--glass-bg: rgba(15, 23, 42, 0.7);', '--glass-bg: rgba(255, 255, 255, 0.9);', $content);
        $content = str_replace('--glass-border: rgba(99, 102, 241, 0.2);', '--glass-border: rgba(99, 102, 241, 0.1);', $content);
        
        // Build Body CSS
        $content = preg_replace(
            '/body\s*\{\s*background-color:\s*#020617;[\s\S]*?background-attachment:\s*fixed;\s*\}/',
            "body {\n            background-color: #f8fafc;\n            background-image:\n                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),\n                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.1) 0px, transparent 50%),\n                radial-gradient(at 50% 100%, rgba(30, 64, 175, 0.1) 0px, transparent 50%);\n            background-attachment: fixed;\n        }",
            $content
        );
        
        // glass-card
        $content = preg_replace(
            '/\.glass-card\s*\{\s*background:\s*rgba\(255, 255, 255, 0\.03\);[\s\S]*?\}/',
            ".glass-card {\n            background: rgba(255, 255, 255, 1);\n            backdrop-filter: blur(8px);\n            border: 1px solid rgba(0, 0, 0, 0.05);\n            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);\n            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);\n        }",
            $content
        );
        
        $content = preg_replace(
            '/\.glass-card:hover\s*\{[\s\S]*?\}/',
            ".glass-card:hover {\n            border-color: rgba(99, 102, 241, 0.3);\n            transform: translateY(-4px);\n            box-shadow: 0 12px 40px -12px rgba(99, 102, 241, 0.15);\n        }",
            $content
        );

        // inputs
        $content = preg_replace(
            '/input,\s*select,\s*textarea\s*\{\s*background:\s*rgba[^}]*\}/',
            "input,\n        select,\n        textarea {\n            background: rgba(255, 255, 255, 0.8) !important;\n            border: 1px solid rgba(0, 0, 0, 0.2) !important;\n            color: #0f172a !important;\n        }",
            $content
        );

        // Dropdown specific fixes
        $content = str_replace('class="absolute right-0 mt-3 w-56 glass rounded-2xl p-2 shadow-2xl border-white/5"', 'class="absolute right-0 mt-3 w-56 bg-white rounded-2xl p-2 shadow-2xl border border-gray-100"', $content);
        $content = str_replace('text-gray-200', 'text-gray-900', $content);
        $content = str_replace('text-gray-300', 'text-gray-700', $content);
        $content = str_replace('text-gray-400', 'text-gray-600', $content);
        $content = str_replace('text-gray-500', 'text-gray-500', $content);
        
        // Headers/texts
        $content = str_replace('text-white', 'text-gray-900', $content);
        $content = str_replace('border-white/5', 'border-indigo-500/10', $content);
        $content = str_replace('border-white/10', 'border-indigo-500/10', $content);
        
        // Sidebar & Header bg
        $content = str_replace('bg-white/5', 'bg-indigo-500/5', $content);
        $content = str_replace('hover:bg-white/5', 'hover:bg-indigo-500/10', $content);
        $content = str_replace('hover:bg-white/10', 'hover:bg-indigo-500/10', $content);
        $content = str_replace('hover:text-white', 'hover:text-indigo-700', $content);
        
        // HTML Dark class
        $content = str_replace('<html lang="{{ str_replace(\'_\', \'-\', app()->getLocale()) }}" class="dark">', '<html lang="{{ str_replace(\'_\', \'-\', app()->getLocale()) }}">', $content);
        
        // Dropdown menu items color fix:
        $content = str_replace('class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-black/5 text-sm transition-colors text-gray-900"', 'class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-black/5 text-sm transition-colors text-black"', $content);
        // Force dropdown items to be black
        $content = preg_replace('/hover:bg-indigo-500\/10 text-sm transition-colors text-gray-700/', 'hover:bg-indigo-50 text-sm transition-colors text-black', $content);
        $content = preg_replace('/<a href="#"([^>]*?)text-gray-300/', '<a href="#"$1text-black', $content);
    } else {
        // Prevent breaking specific buttons that need white text in other files
        $content = preg_replace('/bg-([a-z]+)-(500|600)(.*?)text-white/s', 'bg-$1-$2$3TEXT_WHITE_KEEP', $content);
        $content = preg_replace('/text-white(?!_KEEP)/', 'text-gray-900', $content);
        $content = str_replace('TEXT_WHITE_KEEP', 'text-white', $content);
        
        $content = str_replace('text-gray-400', 'text-gray-600', $content);
        $content = str_replace('text-gray-300', 'text-gray-700', $content);
        
        // Handle strings safely without infinite loop
        $content = preg_replace_callback('/class="([^"]*)\b(glass-card)\b([^"]*)"/', function ($matches) {
            $inner = $matches[1] . $matches[2] . $matches[3];
            if (strpos($inner, 'text-gray-900') === false) {
                return 'class="' . ltrim($matches[1]) . $matches[2] . ' text-gray-900 ' . ltrim($matches[3]) . '"';
            }
            return $matches[0];
        }, $content);
        
        // Table headers and borders
        $content = str_replace('border-white/5', 'border-indigo-500/10', $content);
        $content = str_replace('border-white/10', 'border-indigo-500/10', $content);
        $content = str_replace('bg-white/5', 'bg-indigo-500/5', $content);
    }
        
    if ($content !== $original) {
        file_put_contents($filepath, $content);
        echo "Updated $filepath\n";
    }
}

function walkSync($dir) {
    if (!is_dir($dir)) return;
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (substr($path, -10) === '.blade.php') {
                processFile($path);
            }
        } else if ($value != "." && $value != "..") {
            walkSync($path);
        }
    }
}

walkSync($baseDir);
?>
