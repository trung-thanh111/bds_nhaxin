<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.component.head')
    @vite('resources/css/app.scss')
</head>
@if (isset($schema))
    {!! $schema !!}
@endif

<body>
    @include('frontend.component.header')
    @yield('content')
    @include('frontend.component.footer')
    @include('frontend.component.script')
    @vite('resources/js/app.js')
</body>

</html>
