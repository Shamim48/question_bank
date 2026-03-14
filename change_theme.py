import os
import re

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content

    if 'components/layouts/app.blade.php' in filepath.replace('\\', '/'):
        # Fix root variables
        content = content.replace('--glass-bg: rgba(15, 23, 42, 0.7);', '--glass-bg: rgba(255, 255, 255, 0.9);')
        content = content.replace('--glass-border: rgba(99, 102, 241, 0.2);', '--glass-border: rgba(99, 102, 241, 0.1);')
        
        # Build Body CSS
        content = re.sub(
            r'body\s*\{\s*background-color:\s*#020617;.*?background-attachment:\s*fixed;\s*\}',
            '''body {
            background-color: #f8fafc;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(30, 64, 175, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }''', content, flags=re.DOTALL)
        
        # glass-card
        content = re.sub(
            r'\.glass-card\s*\{\s*background:\s*rgba\(255, 255, 255, 0\.03\);.*?\}',
            '''.glass-card {
            background: rgba(255, 255, 255, 1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }''', content, flags=re.DOTALL)
        
        content = re.sub(
            r'\.glass-card:hover\s*\{.*?\}',
            '''.glass-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px -12px rgba(99, 102, 241, 0.15);
        }''', content, flags=re.DOTALL)

        # inputs
        content = re.sub(
            r'input,\s*select,\s*textarea\s*\{\s*background:\s*rgba[^}]*\}',
            '''input,
        select,
        textarea {
            background: rgba(255, 255, 255, 0.8) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            color: #0f172a !important;
        }''', content, flags=re.DOTALL)

        # Body tag
        content = content.replace('text-gray-200', 'text-gray-800')

        # Dropdown specific fixes
        content = content.replace("class=\"absolute right-0 mt-3 w-56 glass rounded-2xl p-2 shadow-2xl border-white/5\"", "class=\"absolute right-0 mt-3 w-56 bg-white rounded-2xl p-2 shadow-2xl border border-gray-100\"")
        content = content.replace("text-gray-300", "text-gray-700")
        
        # Headers/texts
        content = content.replace("text-white", "text-gray-900")
        content = content.replace("text-gray-400", "text-gray-600")
        content = content.replace("border-white/5", "border-indigo-500/10")
        content = content.replace("border-white/10", "border-indigo-500/10")
        
        # Sidebar & Header bg
        content = content.replace("bg-white/5", "bg-indigo-500/5")
        content = content.replace("hover:bg-white/5", "hover:bg-indigo-500/10")
        content = content.replace("hover:text-white", "hover:text-indigo-700")

    else:
        # Prevent breaking specific buttons that need white text in other files
        content = re.sub(r'bg-([a-z]+)-(500|600)(.*?)text-white', r'bg-\1-\2\3TEXT_WHITE_KEEP', content)
        content = re.sub(r'text-white(?!_KEEP)', 'text-gray-900', content)
        content = content.replace('TEXT_WHITE_KEEP', 'text-white')
        
        content = content.replace('text-gray-400', 'text-gray-600')
        content = content.replace('text-gray-500', 'text-gray-500')
        content = content.replace('glass-card', 'glass-card text-gray-900')
        
    if content != original:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated {filepath}")

def main():
    base_dir = r"e:\laragon\www\question_bank\resources\views"
    for root, dirs, files in os.walk(base_dir):
        for file in files:
            if file.endswith('.blade.php'):
                process_file(os.path.join(root, file))

if __name__ == '__main__':
    main()
