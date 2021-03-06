<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ViewIt | @yield('title', '')</title>

        <link href="/img/favicon.ico" rel="SHORTCUT ICON" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat%7CRoboto:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href ="{{ asset('css/responsive.css') }}">

        @yield('extra-css')
    </head>

    <body class="@yield('body-class', '')">

        @include('components.modals')

        <header class="header" id="header">
            @include('components.navbar')
            @include('components.discussionNavbar')
            @include('components.sidepanel')
            @include('components.header')
        </header>
        
        <div class="test">
            @include('components.test')
        </div>


        <div class="content-container" id="content-container">
            @yield('content')
        </div>

        <script type="text/javascript">
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        </script>

        @yield('extra-js')
        @stack('extra-js')
    </body>
</html>
