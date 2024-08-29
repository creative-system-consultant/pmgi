@extends('layouts.base')

@section('body')
<main class="h-screen bg-gray-50">
    @include('navigation.navbar')

    <div class="flex pt-16 bg-gray-50 ">
        <div id="main-content" class="relative w-full h-full px-8 mx-auto max-w-screen-2xl bg-gray-50">
            @yield('content')
        </div>
    </div>
</main>
@endsection