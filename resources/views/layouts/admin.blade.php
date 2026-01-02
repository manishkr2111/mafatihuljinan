<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic Page Title -->
    <title>@yield('title', 'Default Page Title')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/website/mafa-logo.jpg') }}" type="image/x-icon">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind & TinyMCE -->
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen relative">

        <!-- Sidebar (hidden by default on mobile + tablet) -->
        <aside id="sidebar"
            class="fixed xl:static top-0 left-0 z-50 w-64 h-full bg-white shadow transform -translate-x-full xl:translate-x-0 transition-transform duration-300 ease-in-out">
            
            <!-- Close button (only visible on mobile/tablet) -->
            <div class="flex justify-end p-4 border-b xl:hidden">
                <button id="closeSidebar" class="text-gray-600 hover:text-gray-900 text-2xl focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <x-sidebar />
        </aside>

        <!-- Overlay (for mobile/tablet) -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 w-full">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <!-- Hamburger for mobile/tablet -->
                <button id="openSidebar" class="xl:hidden text-gray-700 text-2xl focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>

                <x-header :page-name="View::yieldContent('title', 'Default Page Title')" />
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-600 rounded shadow mb-4">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const openSidebar = document.getElementById('openSidebar');
            const closeSidebar = document.getElementById('closeSidebar');

            // Function to open sidebar
            const openMenu = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            };

            // Function to close sidebar
            const closeMenu = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            };

            // Event listeners
            openSidebar?.addEventListener('click', openMenu);
            closeSidebar?.addEventListener('click', closeMenu);
            overlay?.addEventListener('click', closeMenu);
        });
    </script>
</body>
</html>
