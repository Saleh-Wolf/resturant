<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Restaurant System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    @include('layouts.admin.navbar')

    @include('layouts.admin.sidebar')

    <div class="content-wrapper p-4">
        @yield('content')
    </div>

    @include('layouts.admin.footer')

</div>
</body>
</html>
