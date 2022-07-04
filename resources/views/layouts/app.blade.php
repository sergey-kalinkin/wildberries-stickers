<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <title>Робот Валя</title>
    <meta name="description" content="Описание">
    <meta name="keywords" content="">

    <meta property="og:title" content="Робот Валя">
    <meta property="og:description" content="Описание">
    <meta property="og:image" content="">
    <meta property="og:url" content="">
    <meta property="og:type" content="website">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="theme-color" content="#000">

    <link rel="stylesheet" href="/fonts/SFPro/stylesheet.css">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>

<body>
    @auth('admin')
        @include('layouts.templates.admin.header')
    @endauth

    @auth('web')
        @include('layouts.templates.client.header')
    @endauth
    @guest('web')
        @include('layouts.templates.guest.header')
    @endguest

    <main>

        @yield('content', '')

    </main>

    @include('layouts.templates.footer')

    @include('layouts.templates.svgSprite')

    <script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
