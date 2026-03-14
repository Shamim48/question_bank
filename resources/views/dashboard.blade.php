<x-layouts.app>
    @php
        // Redirect to the appropriate dashboard
        if (auth()->user()->isAdmin()) {
            header('Location: ' . route('admin.dashboard'));
            exit;
        } else {
            header('Location: ' . route('student.dashboard'));
            exit;
        }
    @endphp
</x-layouts.app>