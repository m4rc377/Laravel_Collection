<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>CRM</title>

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

</body>

@yield('scripts')
</html>
