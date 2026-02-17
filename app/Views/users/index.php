<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Users - Admin Panel</title>
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
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
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
        
        .btn-add {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
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
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .stat-icon.total { background: rgba(99, 102, 241, 0.2); }
        .stat-icon.active { background: rgba(16, 185, 129, 0.2); }
        .stat-icon.inactive { background: rgba(239, 68, 68, 0.2); }
        
        .stat-info h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .stat-info p {
            font-size: 13px;
            color: var(--text-secondary);
        }
        
        /* User List */
        .user-list {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
        }
        
        .list-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr 1fr 100px;
            padding: 16px 20px;
            background: rgba(99, 102, 241, 0.1);
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .user-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr 1fr 100px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            align-items: center;
            transition: background 0.2s;
        }
        
        .user-item:last-child { border-bottom: none; }
        
        .user-item:hover { background: rgba(99, 102, 241, 0.05); }
        
        .user-info-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        
        .user-details h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .user-details span {
            font-size: 12px;
            color: var(--text-secondary);
        }
        
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .role-cashier { background: rgba(99, 102, 241, 0.2); color: #818cf8; }
        .role-manager { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
        .role-sales { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .role-inventory { background: rgba(139, 92, 246, 0.2); color: #8b5cf6; }
        .role-admin { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-active {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        
        .status-inactive {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        
        .status-active .status-dot { background: #10b981; }
        .status-inactive .status-dot { background: #ef4444; }
        
        .user-actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: rgba(99, 102, 241, 0.1);
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .action-btn:hover { background: rgba(99, 102, 241, 0.2); }
        .action-btn.delete:hover { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        
        /* Mobile List */
        .mobile-list { display: none; }
        
        .mobile-user-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid var(--border);
        }
        
        .mobile-user-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .mobile-user-info h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .mobile-user-info span {
            font-size: 13px;
            color: var(--text-secondary);
        }
        
        .mobile-user-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .mobile-detail {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .mobile-detail label {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
        }
        
        .mobile-detail span {
            font-size: 13px;
            font-weight: 500;
        }
        
        .mobile-user-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--border);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }
        
        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 20px;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .hamburger { display: flex; }
            
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }
            
            .stat-card {
                padding: 16px;
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            
            .stat-info h3 {
                font-size: 20px;
            }
            
            .user-list { display: none; }
            .mobile-list { display: block; }
        }
        
        @media (min-width: 769px) {
            .sidebar.closed { transform: translateX(-100%); }
            .main-content.full { margin-left: 0; }
        }
        
        /* Toast Notifications */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .toast.success { background: var(--success); }
        .toast.error { background: var(--danger); }
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
                <a href="/admin/users" class="nav-item active">
                    <span class="nav-icon">👥</span>
                    <span>Users</span>
                </a>
                <a href="/admin/users/create" class="nav-item">
                    <span class="nav-icon">➕</span>
                    <span>Create User</span>
                </a>
                <a href="/admin/permissions" class="nav-item">
                    <span class="nav-icon">🔐</span>
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
                    <h1 class="page-title">Users</h1>
                </div>
                <div class="header-actions">
                    <a href="/admin/users/create" class="btn-add">
                        <span>➕</span>
                        <span>Add User</span>
                    </a>
                    <button class="icon-btn" onclick="toggleTheme()" title="Toggle Theme">
                        <span id="themeIcon">☀️</span>
                    </button>
                </div>
            </header>
            
            <div class="content">
                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon total">👥</div>
                        <div class="stat-info">
                            <h3><?= $total_users ?? 0 ?></h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon active">✅</div>
                        <div class="stat-info">
                            <h3><?= $active_users ?? 0 ?></h3>
                            <p>Active</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon inactive">❌</div>
                        <div class="stat-info">
                            <h3><?= $inactive_users ?? 0 ?></h3>
                            <p>Inactive</p>
                        </div>
                    </div>
                </div>
                
                <!-- Desktop List -->
                <div class="user-list">
                    <?php if (empty($users)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">👥</div>
                            <h3>No Users Found</h3>
                            <p>Get started by creating your first user</p>
                            <a href="/admin/users/create" class="btn-add">Create User</a>
                        </div>
                    <?php else: ?>
                        <div class="list-header">
                            <div>User</div>
                            <div>Role</div>
                            <div>Contact</div>
                            <div>Status</div>
                            <div>Actions</div>
                        </div>
                        <?php foreach ($users as $user): ?>
                            <div class="user-item">
                                <div class="user-info-cell">
                                    <div class="user-avatar-sm">
                                        <?= strtoupper(substr($user['full_name'] ?? $user['username'], 0, 1)) ?>
                                    </div>
                                    <div class="user-details">
                                        <h4><?= htmlspecialchars($user['full_name'] ?? '') ?></h4>
                                        <span>@<?= htmlspecialchars($user['username']) ?></span>
                                    </div>
                                </div>
                                <div>
                                    <span class="role-badge role-<?= $user['role'] ?? 'cashier' ?>">
                                        <?= ucfirst($user['role'] ?? 'Cashier') ?>
                                    </span>
                                </div>
                                <div class="user-details">
                                    <span><?= htmlspecialchars($user['email']) ?></span>
                                    <?php if (!empty($user['phone_number'])): ?>
                                        <span><?= htmlspecialchars($user['phone_number']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if ($user['is_active'] ?? false): ?>
                                        <span class="status-badge status-active">
                                            <span class="status-dot"></span>
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">
                                            <span class="status-dot"></span>
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="user-actions">
                                    <button class="action-btn" title="Edit">✏️</button>
                                    <button class="action-btn delete" title="Delete">🗑️</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile List -->
                <div class="mobile-list">
                    <?php if (empty($users)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">👥</div>
                            <h3>No Users Found</h3>
                            <p>Get started by creating your first user</p>
                            <a href="/admin/users/create" class="btn-add">Create User</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <div class="mobile-user-card">
                                <div class="mobile-user-header">
                                    <div class="user-avatar-sm">
                                        <?= strtoupper(substr($user['full_name'] ?? $user['username'], 0, 1)) ?>
                                    </div>
                                    <div class="mobile-user-info">
                                        <h4><?= htmlspecialchars($user['full_name'] ?? '') ?></h4>
                                        <span>@<?= htmlspecialchars($user['username']) ?></span>
                                    </div>
                                </div>
                                <div class="mobile-user-details">
                                    <div class="mobile-detail">
                                        <label>Role</label>
                                        <span class="role-badge role-<?= $user['role'] ?? 'cashier' ?>">
                                            <?= ucfirst($user['role'] ?? 'Cashier') ?>
                                        </span>
                                    </div>
                                    <div class="mobile-detail">
                                        <label>Status</label>
                                        <?php if ($user['is_active'] ?? false): ?>
                                            <span class="status-badge status-active">
                                                <span class="status-dot"></span>
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">
                                                <span class="status-dot"></span>
                                                Inactive
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mobile-detail">
                                        <label>Email</label>
                                        <span><?= htmlspecialchars($user['email']) ?></span>
                                    </div>
                                    <div class="mobile-detail">
                                        <label>Phone</label>
                                        <span><?= htmlspecialchars($user['phone_number'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                                <div class="mobile-user-footer">
                                    <div class="user-actions">
                                        <button class="action-btn" title="Edit">✏️</button>
                                        <button class="action-btn delete" title="Delete">🗑️</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Toast Notifications -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="toast success show" id="toast"><?= $_SESSION['success_message'] ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php elseif (isset($_SESSION['error_message'])): ?>
        <div class="toast error show" id="toast"><?= $_SESSION['error_message'] ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
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
        
        // Hide toast after 3 seconds
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>
