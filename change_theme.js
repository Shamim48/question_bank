const fs = require('fs');
const path = require('path');

function processFile(filepath) {
    let content = fs.readFileSync(filepath, 'utf8');
    let original = content;

    const normalizedPath = filepath.replace(/\\/g, '/');
    if (normalizedPath.includes('components/layouts/app.blade.php')) {
        // Fix root variables
        content = content.replace('--glass-bg: rgba(15, 23, 42, 0.7);', '--glass-bg: rgba(255, 255, 255, 0.9);');
        content = content.replace('--glass-border: rgba(99, 102, 241, 0.2);', '--glass-border: rgba(99, 102, 241, 0.1);');
        
        // Build Body CSS
        content = content.replace(
            /body\s*\{\s*background-color:\s*#020617;[\s\S]*?background-attachment:\s*fixed;\s*\}/,
            `body {
            background-color: #f8fafc;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(30, 64, 175, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }`
        );
        
        // glass-card
        content = content.replace(
            /\.glass-card\s*\{\s*background:\s*rgba\(255, 255, 255, 0\.03\);[\s\S]*?\}/,
            `.glass-card {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }`
        );
        
        content = content.replace(
            /\.glass-card:hover\s*\{[\s\S]*?\}/,
            `.glass-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px -12px rgba(99, 102, 241, 0.15);
        }`
        );

        // inputs
        content = content.replace(
            /input,\s*select,\s*textarea\s*\{\s*background:\s*rgba[^}]*\}/,
            `input,
        select,
        textarea {
            background: rgba(255, 255, 255, 0.8) !important;
            border: 1px solid rgba(0, 0, 0, 0.2) !important;
            color: #0f172a !important;
        }`
        );

        // Body tag
        content = content.replace('text-gray-200', 'text-gray-900');

        // Dropdown specific fixes
        content = content.replace('class="absolute right-0 mt-3 w-56 glass rounded-2xl p-2 shadow-2xl border-white/5"', 'class="absolute right-0 mt-3 w-56 bg-white rounded-2xl p-2 shadow-2xl border border-gray-100"');
        content = content.replace('text-gray-300', 'text-gray-700');
        
        // Headers/texts
        content = content.split('text-white').join('text-gray-900');
        content = content.split('text-gray-400').join('text-gray-600');
        content = content.split('border-white/5').join('border-indigo-500/10');
        content = content.split('border-white/10').join('border-indigo-500/10');
        
        // Sidebar & Header bg
        content = content.split('bg-white/5').join('bg-indigo-500/5');
        content = content.split('hover:bg-white/5').join('hover:bg-indigo-500/10');
        content = content.split('hover:text-white').join('hover:text-indigo-700');

    } else {
        // Prevent breaking specific buttons that need white text in other files
        content = content.replace(/bg-([a-z]+)-(500|600)(.*?)text-white/g, 'bg-$1-$2$3TEXT_WHITE_KEEP');
        content = content.replace(/text-white(?!_KEEP)/g, 'text-gray-900');
        content = content.split('TEXT_WHITE_KEEP').join('text-white');
        
        content = content.split('text-gray-400').join('text-gray-600');
        
        // Handle strings safely without infinite loop
        let previous = '';
        while (previous !== content) {
            previous = content;
            content = content.replace(/class="([^"]*)\b(glass-card)\b([^"]*)"/g, (match, p1, p2, p3) => {
                let inner = p1 + p2 + p3;
                if (!inner.includes('text-gray-900')) {
                    return `class="${p1}${p2} text-gray-900${p3}"`;
                }
                return match;
            });
            break; // just applying once is fine
        }
    }
        
    if (content !== original) {
        fs.writeFileSync(filepath, content, 'utf8');
        console.log(`Updated ${filepath}`);
    }
}

function walkSync(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(file => {
        const filepath = path.join(dir, file);
        const stat = fs.statSync(filepath);
        if (stat && stat.isDirectory()) {
            results = results.concat(walkSync(filepath));
        } else if (file.endsWith('.blade.php')) {
            results.push(filepath);
        }
    });
    return results;
}

const baseDir = path.join('e:', 'laragon', 'www', 'question_bank', 'resources', 'views');
walkSync(baseDir).forEach(processFile);
