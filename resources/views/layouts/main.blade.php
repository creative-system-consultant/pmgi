@extends('layouts.base')

@section('body')
<main class="h-screen bg-gray-50">
    @include('navigation.navbar')

    <div class="flex flex-col pt-16 bg-gray-50">

        <!-- Conditionally render sidebar for maintenance route -->
        @if (request()->routeIs('maintenance.*')) <!-- Replace 'maintenance' with your actual route name -->
        <div class="flex h-full">
            <!-- Include the sidebar partial -->
            @include('navigation.maintenance-sidebar')

            <!-- Main Content (with left margin to avoid overlap with the sidebar) -->
            <div class="relative px-2 mx-auto w-full max-w-screen-2xl h-full bg-gray-50">
                @livewire('session-status-banner')
                <div id="main-content">
                    @yield('content')
                </div>
            </div>
        </div>
        @else
        <!-- Main Content without sidebar -->
        <div class="relative px-8 mx-auto w-full max-w-screen-2xl h-full bg-gray-50">
            @livewire('session-status-banner')
            <div id="main-content">
                @yield('content')
            </div>
        </div>
        @endif
    </div>
</main>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal', (payload) => {
            Swal.fire({
                title: payload.title ?? 'Notice',
                text:  payload.text  ?? '',
                icon:  payload.icon  ?? 'info',
                confirmButtonText: 'OK'
            });
        });
    });

    // Listen for the event emitted from PHP
    Livewire.on('swal:confirm', (e) => {
        let swalHtml = '';  // Initialize the variable for HTML content

        // List of all parameter names you want to check
        const params = ['param1', 'param2', 'param3', 'param4', 'param5'];

        // Loop through each parameter and add its HTML if not null
        params.forEach(pr => {
            if (e[pr] != null) {
                swalHtml += `<input type="text" class="w-96 p-2 border bg-gray-100 border-gray-500 rounded-md mb-2" value="${e[pr]}" readonly/>`;
            }
        });

        Swal.fire({
            title: e.title ?? 'Notice',
            text: e.text ?? '',
            icon: e.icon ?? 'info',
            html: swalHtml,
            showCancelButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                const key = e.key;
                Livewire.dispatch('delete', { [key]: e.param });
            }
        });
    });              
</script>    
@endsection
