@extends('layouts.base')

@section('body')
<main class="bg-gray-50 ">
    @include('navigation.navbar')

    @yield('content')
</main>
@endsection