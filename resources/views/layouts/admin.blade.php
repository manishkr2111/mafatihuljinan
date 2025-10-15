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

    <!-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> -->
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar (sticky) -->
        <x-sidebar class="flex-shrink-0" />

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <x-header :page-name="View::yieldContent('title', 'Default Page Title')" />

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="p-4 bg-green-100 text-green-500 rounded shadow mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 bg-red-100 text-red-800 rounded shadow mb-4">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>


</html>