<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            @include('partials.sidebar')
            @include('partials.navbar')

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    @yield('content')
                </div>

                @include('partials.footer')
            </div>
            <!-- /page content -->
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Gentelella JS -->
    <script src="{{ asset('assets/js/gentelella.js') }}"></script>

    <!-- Page-specific scripts -->
    @yield('scripts')

    <!-- Additional scripts -->
    @stack('scripts')
</body>
</html>
