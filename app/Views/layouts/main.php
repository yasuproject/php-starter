<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <title><?= $title ?? 'Admin Panel' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionic@latest/css/ionic.bundle.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>
        
        <!-- Sidebar Navigation Drawer -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <ion-icon name="shield-checkmark"></ion-icon>
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="nav-menu">
                <a href="/admin/dashboard" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                    <ion-icon name="grid-outline"></ion-icon>
                    <span class="nav-text">Dashboard</span>
                </a>
                
                <a href="/admin/users/create" class="nav-item <?= $activePage === 'create-user' ? 'active' : '' ?>">
                    <ion-icon name="person-add-outline"></ion-icon>
                    <span class="nav-text">Create User</span>
                </a>
                
                <a href="/admin/permissions" class="nav-item <?= $activePage === 'permissions' ? 'active' : '' ?>">
                    <ion-icon name="key-outline"></ion-icon>
                    <span class="nav-text">Permissions Control</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?= strtoupper(substr($username ?? 'A', 0, 1)) ?>
                    </div>
                    <div class="user-details">
                        <div class="user-name"><?= htmlspecialchars($username ?? 'Admin') ?></div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                <a href="/logout" class="logout-btn">
                    <ion-icon name="log-out-outline"></ion-icon>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <main class="main-content" id="mainContent">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                        <ion-icon name="menu-outline"></ion-icon>
                    </button>
                    <h1 class="page-title"><?= $pageTitle ?? 'Dashboard' ?></h1>
                </div>
                <div class="header-right">
                    <button class="dark-mode-toggle" onclick="toggleDarkMode()" aria-label="Toggle dark mode">
                        <ion-icon class="sun-icon" name="sunny-outline"></ion-icon>
                        <ion-icon class="moon-icon" name="moon-outline"></ion-icon>
                    </button>
                </div>
            </header>
            
            <!-- Content -->
            <div class="content">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 16px; border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(16, 185, 129, 0.2);">
                        <?= htmlspecialchars($_SESSION['success_message']) ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 16px; border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(239, 68, 68, 0.2);">
                        <?= htmlspecialchars($_SESSION['error_message']) ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/ionic@latest/js/ionic.bundle.js"></script>
    <script>
        // Dark mode
        const html = document.documentElement;
        if (localStorage.getItem('theme') === 'dark' || 
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.setAttribute('data-theme', 'dark');
        }

        function toggleDarkMode() {
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        }

        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.classList.toggle('mobile-open');
                mobileOverlay.classList.toggle('active');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Save state
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            }
        }
        
        function toggleMobileMenu() {
            sidebar.classList.remove('mobile-open');
            mobileOverlay.classList.remove('active');
        }
        
        // Restore sidebar state on desktop
        if (window.innerWidth > 768 && localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
        }
        
        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>
