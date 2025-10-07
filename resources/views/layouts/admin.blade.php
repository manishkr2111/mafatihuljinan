<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Dynamic Page Title -->
    <title>@yield('title', 'Default Page Title')</title>

    <!-- Favicon (optional) -->
    <link rel="icon" href="{{asset('storage/website/mafa-logo.jpg')}}" type="image/x-icon">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Pass page name to header -->
            <x-header :page-name="View::yieldContent('title', 'Default Page Title')" />

            <!-- Page-specific content -->
            @yield('content')
        </main>
    </div>

    <!-- Custom JS -->
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
