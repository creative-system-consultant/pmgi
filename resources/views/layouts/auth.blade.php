@extends('layouts.base')

@section('body')
    <main class="bg-gray-50 ">
        @yield('content')

        @isset($slot)
            {{ $slot }}
        @endisset
    </main>
@endsection
