<header class="header">
    <div class="left-section">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Use the page name passed from layout -->
        <h1 class="page-title">{{ $pageName ?? 'Dashboard' }}</h1>
    </div>

    <div class="header-actions">
        <div class="search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <!--  
        <div class="header-icon">
            <i class="fas fa-bell"></i>
            <div class="notification-indicator"></div>
        </div>

        <div class="header-icon">
            <i class="fas fa-envelope"></i>
        </div>

        <div class="theme-toggle" id="themeToggle"></div>
        -->
    </div>
</header>
