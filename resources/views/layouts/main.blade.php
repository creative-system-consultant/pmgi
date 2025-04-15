@extends('layouts.base')

@section('body')
<main class="h-screen bg-gray-50">
    @include('navigation.navbar')

    <div class="flex flex-col pt-16 bg-gray-50">
        @livewire('session-status-banner')
        <div id="main-content" class="relative px-8 mx-auto w-full max-w-screen-2xl h-full bg-gray-50">
            @yield('content')
        </div>
    </div>
</main>
@endsection