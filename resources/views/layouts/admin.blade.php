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
    <script src="{{ asset('js/tailwind.js') }}"></script>
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
            @if(session('success'))
            <div class=" p-4 bg-green-100 text-green-500 rounded shadow">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class=" mx-auto mb-4 p-4 bg-red-100 text-red-800 rounded shadow">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Custom JS -->
    <script src="{{ asset('js/index.js') }}"></script>
</body>

</html>