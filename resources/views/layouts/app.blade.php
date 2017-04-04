<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ Configuration::value('application_name') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};

        var Sts = {
            url: "{{ url('/') }}",
            user: {!! Auth::check() ? auth()->user() : '{"name": "Guest"}' !!},
            department_alias: "{{ Configuration::value('department_alias') }}"
        };
    </script>
</head>
<body>
    <div id="app">
        @if (Auth::guest())
            @include('layouts.content.guest')
        @else
            @include('layouts.content.authenticated')
        @endif
    </div>

    <footer>
        <div class="footer">This site is powered by Simplicity Ticket System.</div>
    </footer>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script>
        // jQuery Methods
        $(document).ready(function(){
            $('.nav-tabs a[href="'+location.href+'"]').parents('li').addClass('active');
            $('.nav-pills a[href="'+location.href+'"]').parents('li').addClass('active');
            $('.nav-list a[href="'+location.href+'"]').addClass('active');
            $('.sidebar-nav a[href="'+location.href+'"]').addClass('active');
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>
