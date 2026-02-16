<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Create User - Admin Panel</title>
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
            --input-bg: #f9fafb;
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
            --input-bg: #374151;
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
        
        /* Form */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
            max-width: 600px;
        }
        
        .card-title { font-size: 18px; font-weight: 600; margin-bottom: 24px; }
        
        .form-group { margin-bottom: 20px; }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .form-input, .form-select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            background: var(--input-bg);
            color: var(--text-primary);
        }
        
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--ion-color-primary);
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
        
        .btn-secondary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--text-primary);
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
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
                <a href="/admin/users/create" class="nav-item active">
                    <ion-icon name="person-add-outline"></ion-icon>
                    <span>Create User</span>
                </a>
                <a href="/admin/permissions" class="nav-item">
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
                <h1 class="page-title">Create User</h1>
                <button class="dark-mode-toggle" onclick="toggleDarkMode()">
                    <ion-icon class="sun-icon" name="sunny-outline"></ion-icon>
                    <ion-icon class="moon-icon" name="moon-outline" style="display:none"></ion-icon>
                </button>
            </header>
            
            <div class="content">
                <div class="card">
                    <h2 class="card-title">Create New User</h2>
                    
                    <form action="/admin/users/store" method="POST">
                        <div class="form-group">
                            <label class="form-label" for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" class="form-input" placeholder="Enter full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-input" placeholder="Enter username" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="Enter email" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Enter password (min 8 chars)" required minlength="8">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="role">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="">Select role</option>
                                <option value="admin">Administrator</option>
                                <option value="user">User</option>
                                <option value="guest">Guest</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <ion-icon name="person-add-outline"></ion-icon>
                                Create User
                            </button>
                            <a href="/admin/dashboard" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
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
