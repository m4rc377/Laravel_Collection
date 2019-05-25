<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>CRM @yield('title')</title>

    @include('layout.styles')

</head>
<body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">
        @include('layout.header')
        @include('layout.sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    @include('layout.footer')

    @yield('scripts')
</body>
</html>
