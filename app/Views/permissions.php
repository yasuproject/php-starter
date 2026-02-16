<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Permissions - Admin Panel</title>
    <style>
        :root {
            --ion-color-primary: #6366f1;
            --bg: #0f172a;
            --card-bg: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --sidebar-bg: #0f172a;
            --sidebar-active: #1e3a5f;
            --sidebar-active-border: #818cf8;
            --border: #334155;
        }
        
        [data-theme="light"] {
            --bg: #f3f4f6;
            --card-bg: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --sidebar-bg: #f9fafb;
            --sidebar-active: #eff6ff;
            --sidebar-active-border: #6366f1;
            --border: #e5e7eb;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
        }
        
        .app-container { display: flex; min-height: 100vh; }
        
        /* Mobile Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 99;
        }
        
        .mobile-overlay.active { display: block; }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }
        
        .sidebar.closed { transform: translateX(-100%); }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar-logo {
            width: 32px;
            height: 32px;
            background: var(--ion-color-primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .sidebar-header h2 { font-size: 18px; font-weight: 600; }
        
        .nav-menu { flex: 1; padding: 16px 12px; overflow-y: auto; }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 16px;
            margin-bottom: 4px;
            border-radius: 12px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 15px;
        }
        
        .nav-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--text-primary);
        }
        
        .nav-item.active {
            background: var(--sidebar-active);
            color: var(--ion-color-primary);
            border-left: 3px solid var(--sidebar-active-border);
        }
        
        .nav-icon { font-size: 20px; width: 24px; text-align: center; }
        
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(99, 102, 241, 0.1);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--ion-color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }
        
        .user-name { font-weight: 600; font-size: 14px; }
        .user-role { font-size: 12px; color: var(--text-secondary); }
        
        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s;
        }
        
        .main-content.full { margin-left: 0; }
        
        .header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .hamburger {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 24px;
            cursor: pointer;
            padding: 4px;
        }
        
        .page-title { font-size: 22px; font-weight: 700; }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .icon-btn {
            background: rgba(99, 102, 241, 0.1);
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            padding: 10px;
            border-radius: 10px;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .content { flex: 1; padding: 24px; }
        
        /* Card */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
            margin-bottom: 20px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .card-title { font-size: 18px; font-weight: 600; }
        
        /* Table */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .permissions-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }
        
        .permissions-table th,
        .permissions-table td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        .permissions-table th {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            background: rgba(99, 102, 241, 0.05);
        }
        
        .permissions-table td {
            color: var(--text-primary);
            font-size: 14px;
        }
        
        .permissions-table tr:hover {
            background: rgba(99, 102, 241, 0.03);
        }
        
        .permission-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--ion-color-primary);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }
        
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3); }
        
        /* Legend */
        .legend {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 13px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .hamburger { display: flex; }
            .card-header { flex-direction: column; align-items: flex-start; }
            .permissions-table th, .permissions-table td { padding: 10px 8px; font-size: 13px; }
        }
        
        @media (min-width: 769px) {
            .sidebar.closed { transform: translateX(-100%); }
            .main-content.full { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>
        
        <!-- Sidebar Navigation Drawer -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">🛡️</div>
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="nav-menu">
                <a href="/admin/dashboard" class="nav-item">
                    <span class="nav-icon">📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/users/create" class="nav-item">
                    <span class="nav-icon">➕</span>
                    <span>Create User</span>
                </a>
                <a href="/admin/permissions" class="nav-item active">
                    <span class="nav-icon">🔐</span>
                    <span>Permissions</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?= strtoupper(substr($username ?? 'A', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="user-name"><?= htmlspecialchars($username ?? 'Admin') ?></div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                <a href="/logout" class="logout-btn">
                    <span>🚪</span>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <header class="header">
                <div class="header-left">
                    <button class="hamburger" id="hamburger" onclick="toggleSidebar()">☰</button>
                    <h1 class="page-title">Permissions</h1>
                </div>
                <div class="header-actions">
                    <button class="icon-btn" onclick="toggleTheme()" title="Toggle Theme">
                        <span id="themeIcon">☀️</span>
                    </button>
                </div>
            </header>
            
            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Role Permissions</h2>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('permForm').submit()">
                            <span>💾</span>
                            Save
                        </button>
                    </div>
                    
                    <form id="permForm" action="/admin/permissions/save" method="POST">
                        <div class="table-container">
                            <table class="permissions-table">
                                <thead>
                                    <tr>
                                        <th>Permission</th>
                                        <th style="text-align:center">Admin</th>
                                        <th style="text-align:center">User</th>
                                        <th style="text-align:center">Guest</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $perms = [
                                        'view_dashboard' => 'View Dashboard',
                                        'create_users' => 'Create Users',
                                        'edit_users' => 'Edit Users',
                                        'delete_users' => 'Delete Users',
                                        'manage_permissions' => 'Manage Permissions',
                                        'view_reports' => 'View Reports',
                                        'export_data' => 'Export Data',
                                        'system_settings' => 'System Settings'
                                    ];
                                    foreach ($perms as $key => $label): 
                                    ?>
                                    <tr>
                                        <td><strong><?= $label ?></strong></td>
                                        <td style="text-align:center">
                                            <input type="checkbox" class="permission-checkbox" checked disabled>
                                        </td>
                                        <td style="text-align:center">
                                            <input type="checkbox" class="permission-checkbox" name="perms[user][<?= $key ?>]" 
                                                <?= ($permissions['user'][$key] ?? false) ? 'checked' : '' ?>
                                                <?= in_array($key, ['manage_permissions', 'system_settings']) ? 'disabled' : '' ?>>
                                        </td>
                                        <td style="text-align:center">
                                            <input type="checkbox" class="permission-checkbox" name="perms[guest][<?= $key ?>]" 
                                                <?= ($permissions['guest'][$key] ?? false) ? 'checked' : '' ?>
                                                <?= in_array($key, ['edit_users', 'delete_users', 'manage_permissions', 'export_data', 'system_settings']) ? 'disabled' : '' ?>>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                
                <div class="card">
                    <div class="legend">
                        <div class="legend-item">
                            <span>☑️</span>
                            <span>Granted</span>
                        </div>
                        <div class="legend-item">
                            <span>⬜</span>
                            <span>Not Granted</span>
                        </div>
                        <div class="legend-item">
                            <span>🔒</span>
                            <span>Always for Admin</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const html = document.documentElement;
        
        const savedTheme = localStorage.getItem('theme');
        const themeIcon = document.getElementById('themeIcon');
        
        if (savedTheme === 'light') {
            html.setAttribute('data-theme', 'light');
            themeIcon.textContent = '🌙';
        } else {
            html.setAttribute('data-theme', 'dark');
            themeIcon.textContent = '☀️';
        }
        
        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('open');
                mobileOverlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('closed');
                mainContent.classList.toggle('full');
            }
        }
        
        function closeSidebar() {
            sidebar.classList.remove('open');
            mobileOverlay.classList.remove('active');
        }
        
        function toggleTheme() {
            const isDark = html.getAttribute('data-theme') === 'dark';
            if (isDark) {
                html.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
                themeIcon.textContent = '🌙';
            } else {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.textContent = '☀️';
            }
        }
        
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>
