@extends('layouts.base')

@section('body')
<main class="h-screen bg-gray-50">
    @include('navigation.navbar')

    <div class="flex flex-col pt-16 bg-gray-50">

        <!-- Define Sidebar based on route -->
        @php
            $sidebar = null;

            if (request()->routeIs('maintenance.*')) {
                $sidebar = 'navigation.maintenance-sidebar';
            } elseif (request()->routeIs('exceptionReport.*')) {
                $sidebar = 'navigation.exception-report-sidebar';
            } elseif (request()->routeIs('report.admin.*')) {
                $sidebar = 'navigation.report-sidebar';
            }
        @endphp

        @if ($sidebar)
        <div class="flex h-full">
            <!-- Include the sidebar based on the route -->
            @include($sidebar)

            <!-- Main Content with left margin to avoid overlap with the sidebar -->
            <div class="relative px-4 mx-auto w-full max-w-screen-2xl h-full bg-gray-50">
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

        // List of all parameter and label names
        const labels = ['label1','label2', 'label3', 'label4', 'label5'];
        const params = ['param1', 'param2', 'param3', 'param4', 'param5'];

        // Loop through each parameter and label, add its HTML if not null
        labels.forEach((lb, index) => {
            if (e[lb] != null) {
                swalHtml += `
                <div class="mb-4">
                    <label class="w-80 text-start block text-base font-medium text-gray-600 ml-10">${e[lb]}:</label>
                    <input type="text" class="w-96 p-2 border bg-gray-100 border-gray-500 rounded-md mb-2" value="${e[params[index]]}" readonly/>
                </div>
                `;
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
    
        Livewire.on('refreshPage', () => {
        setTimeout(() => {
            location.reload();        
        }, 700);
    });
</script>    
@endsection
