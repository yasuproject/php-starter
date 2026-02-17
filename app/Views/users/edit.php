<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit User - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --input-bg: #0f172a;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
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
            --input-bg: #f9fafb;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
        }

        .app-container { display: flex; min-height: 100vh; }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 99;
        }

        .mobile-overlay.active { display: block; }

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
            transition: all 0.2s;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--ion-color-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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

        .btn-secondary {
            background: rgba(255,255,255,0.1);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover { background: rgba(255,255,255,0.15); }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .hamburger { display: flex; }
            .card { max-width: 100%; }
        }

        @media (min-width: 769px) {
            .sidebar.closed { transform: translateX(-100%); }
            .main-content.full { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="mobile-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo"><i class="fas fa-shield-alt"></i></div>
                <h2>Admin Panel</h2>
            </div>

            <nav class="nav-menu">
                <a href="/admin/dashboard" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/users" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span>Users</span>
                </a>
                <a href="/admin/users/create" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-user-plus"></i></span>
                    <span>Create User</span>
                </a>
                <a href="/admin/permissions" class="nav-item">
                    <span class="nav-icon"><i class="fas fa-lock"></i></span>
                    <span>Permissions</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?= strtoupper(substr($admin_username ?? 'A', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="user-name"><?= htmlspecialchars($admin_username ?? 'Admin') ?></div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                <a href="/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <main class="main-content" id="mainContent">
            <header class="header">
                <div class="header-left">
                    <button class="hamburger" id="hamburger" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">Edit User</h1>
                </div>
                <div class="header-actions">
                    <button class="icon-btn" onclick="toggleTheme()" title="Toggle Theme">
                        <i class="fas fa-sun" id="themeIcon"></i>
                    </button>
                </div>
            </header>

            <div class="content">
                <div class="card">
                    <h2 class="card-title"><i class="fas fa-user-edit"></i> Edit User Details</h2>

                    <form action="/admin/users/update" method="POST" id="editForm">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">

                        <div class="form-group">
                            <label class="form-label" for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" class="form-input"
                                   value="<?= htmlspecialchars($user['full_name'] ?? '') ?>"
                                   placeholder="Enter full name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" id="username" class="form-input"
                                   value="<?= htmlspecialchars($user['username']) ?>"
                                   disabled style="opacity: 0.6;">
                            <small style="color: var(--text-secondary); font-size: 12px;">
                                <i class="fas fa-info-circle"></i> Username cannot be changed
                            </small>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-input"
                                   value="<?= htmlspecialchars($user['email']) ?>"
                                   placeholder="Enter email" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-input"
                                   value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>"
                                   placeholder="Enter phone number">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">
                                Password <small style="font-weight: normal; color: var(--text-secondary);">(leave blank to keep current)</small>
                            </label>
                            <input type="password" id="password" name="password" class="form-input"
                                   placeholder="Enter new password (min 8 chars)" minlength="8">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="role">Role</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="cashier" <?= ($user['role'] ?? '') === 'cashier' ? 'selected' : '' ?>>Cashier</option>
                                <option value="manager" <?= ($user['role'] ?? '') === 'manager' ? 'selected' : '' ?>>Manager</option>
                                <option value="sales" <?= ($user['role'] ?? '') === 'sales' ? 'selected' : '' ?>>Sales</option>
                                <option value="inventory" <?= ($user['role'] ?? '') === 'inventory' ? 'selected' : '' ?>>Inventory</option>
                                <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="active" <?= ($user['is_active'] ?? 0) ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= !($user['is_active'] ?? 0) ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                            <a href="/admin/users" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
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
            themeIcon.className = 'fas fa-moon';
        } else {
            html.setAttribute('data-theme', 'dark');
            themeIcon.className = 'fas fa-sun';
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
                themeIcon.className = 'fas fa-moon';
            } else {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.className = 'fas fa-sun';
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
