<x-layouts.app>
    @section('title', 'Taking Exam')
    <livewire:student.exam-take :exam="request()->route('exam')" />
</x-layouts.app>