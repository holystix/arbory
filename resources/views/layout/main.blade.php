<!DOCTYPE html>
<html>
    <head>
        <title>Admin/news - Ndpculture</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link href="/application.css" media="all" rel="stylesheet"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body class="view-index">

        @include('leaf::admin.partials.header')
        @include('leaf::admin.partials.menu')

        <main id="main">
            @yield('content')
        </main>

        <div class="notifications" data-close-text="Close"></div>

        <script src="/application.js"></script>
    </body>
</html>
