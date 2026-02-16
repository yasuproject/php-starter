<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Permissions - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ionic@latest/css/ionic.bundle.css">
    <style>
        :root {
            --ion-color-primary: #6366f1;
            --bg: #f3f4f6;
            --card-bg: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --sidebar-bg: #f9fafb;
            --sidebar-active: #eff6ff;
            --sidebar-active-border: #6366f1;
            --border: #e5e7eb;
        }
        
        [data-theme="dark"] {
            --bg: #0f172a;
            --card-bg: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --sidebar-bg: #0f172a;
            --sidebar-active: #1e3a5f;
            --sidebar-active-border: #818cf8;
            --border: #334155;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
        }
        
        .app-container { display: flex; min-height: 100vh; }
        
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
        }
        
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar-header ion-icon {
            font-size: 28px;
            color: var(--ion-color-primary);
        }
        
        .sidebar-header h2 {
            font-size: 20px;
            font-weight: 600;
        }
        
        .nav-menu {
            flex: 1;
            padding: 16px 12px;
        }
        
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
        
        .nav-item ion-icon { font-size: 22px; }
        
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            padding: 8px;
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
            font-weight: 600;
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
        }
        
        .header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .page-title { font-size: 24px; font-weight: 700; }
        
        .dark-mode-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            font-size: 22px;
        }
        
        .content { flex: 1; padding: 24px; }
        
        /* Card */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
            margin-bottom: 24px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .card-title { font-size: 18px; font-weight: 600; }
        
        /* Table */
        .permissions-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        
        .permissions-table th,
        .permissions-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        .permissions-table th {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 13px;
            text-transform: uppercase;
            background: rgba(99, 102, 241, 0.05);
        }
        
        .permissions-table td {
            color: var(--text-primary);
        }
        
        .permissions-table tr:hover {
            background: rgba(99, 102, 241, 0.05);
        }
        
        .permission-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--ion-color-primary);
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <ion-icon name="shield-checkmark"></ion-icon>
                <h2>Admin Panel</h2>
            </div>
            
            <nav class="nav-menu">
                <a href="/admin/dashboard" class="nav-item">
                    <ion-icon name="grid-outline"></ion-icon>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/users/create" class="nav-item">
                    <ion-icon name="person-add-outline"></ion-icon>
                    <span>Create User</span>
                </a>
                <a href="/admin/permissions" class="nav-item active">
                    <ion-icon name="key-outline"></ion-icon>
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
                    <ion-icon name="log-out-outline"></ion-icon>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <h1 class="page-title">Permissions Control</h1>
                <button class="dark-mode-toggle" onclick="toggleDarkMode()">
                    <ion-icon class="sun-icon" name="sunny-outline"></ion-icon>
                    <ion-icon class="moon-icon" name="moon-outline" style="display:none"></ion-icon>
                </button>
            </header>
            
            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Role Permissions</h2>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('permForm').submit()">
                            <ion-icon name="save-outline"></ion-icon>
                            Save Changes
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
                    <h3 style="font-size: 16px; margin-bottom: 16px;">Legend</h3>
                    <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                        <span style="color: var(--text-secondary)">☑️ Granted</span>
                        <span style="color: var(--text-secondary)">⬜ Not Granted</span>
                        <span style="color: var(--text-secondary)">☑️(gray) Always for Admin</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/ionic@latest/js/ionic.bundle.js"></script>
    <script>
        const html = document.documentElement;
        if (localStorage.getItem('theme') === 'dark') {
            html.setAttribute('data-theme', 'dark');
            document.querySelector('.sun-icon').style.display = 'none';
            document.querySelector('.moon-icon').style.display = 'block';
        }
        
        function toggleDarkMode() {
            const isDark = html.getAttribute('data-theme') === 'dark';
            html.setAttribute('data-theme', isDark ? 'light' : 'dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
            document.querySelector('.sun-icon').style.display = isDark ? 'block' : 'none';
            document.querySelector('.moon-icon').style.display = isDark ? 'none' : 'block';
        }
    </script>
</body>
</html>
