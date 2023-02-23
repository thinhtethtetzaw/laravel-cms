<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pages') }}
        </h2>
    </x-slot>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                @livewire('pages')
            </div>
        </div>
</x-app-layout>