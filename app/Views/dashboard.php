<?php
$title = 'Dashboard - Admin Panel';
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
$username = $username ?? 'Admin';

ob_start();
?>

<!-- Stats Grid -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <ion-icon name="people-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <h3>Total Users</h3>
            <p><?= $totalUsers ?? 0 ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <ion-icon name="person-add-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <h3>New Users Today</h3>
            <p><?= $newUsersToday ?? 0 ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <ion-icon name="time-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <h3>Active Sessions</h3>
            <p><?= $activeSessions ?? 0 ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon danger">
            <ion-icon name="shield-checkmark-outline"></ion-icon>
        </div>
        <div class="stat-info">
            <h3>Admin Users</h3>
            <p><?= $adminUsers ?? 0 ?></p>
        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="card">
    <h2 class="card-title">Welcome Back!</h2>
    <p style="color: var(--text-secondary); line-height: 1.6;">
        This is your admin dashboard. From here you can manage users, control permissions, 
        and monitor system activity. Use the navigation menu on the left to access different sections.
    </p>
    
    <div style="margin-top: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="/admin/users/create" class="btn btn-primary">
            <ion-icon name="person-add-outline"></ion-icon>
            Create New User
        </a>
        <a href="/admin/permissions" class="btn btn-secondary">
            <ion-icon name="key-outline"></ion-icon>
            Manage Permissions
        </a>
    </div>
</div>

<!-- Recent Activity Card -->
<div class="card" style="margin-top: 24px;">
    <h2 class="card-title">Recent Activity</h2>
    
    <?php if (!empty($recentActivity)): ?>
        <table class="permissions-table" style="margin-top: 16px;">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>User</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentActivity as $activity): ?>
                    <tr>
                        <td><?= htmlspecialchars($activity['action']) ?></td>
                        <td><?= htmlspecialchars($activity['user']) ?></td>
                        <td><?= htmlspecialchars($activity['time']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color: var(--text-secondary); padding: 20px 0; text-align: center;">
            <ion-icon name="information-circle-outline" style="font-size: 24px; margin-bottom: 8px; display: block;"></ion-icon>
            No recent activity to display
        </p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
?>
