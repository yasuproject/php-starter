<?php
$title = 'Permissions Control - Admin Panel';
$pageTitle = 'Permissions Control';
$activePage = 'permissions';
$username = $username ?? 'Admin';

ob_start();
?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 class="card-title" style="margin-bottom: 0;">Role Permissions</h2>
        <button type="button" class="btn btn-primary" onclick="savePermissions()">
            <ion-icon name="save-outline"></ion-icon>
            Save Changes
        </button>
    </div>
    
    <form id="permissionsForm" action="/admin/permissions/save" method="POST">
        <div class="permissions-container">
            <table class="permissions-table">
                <thead>
                    <tr>
                        <th>Permission</th>
                        <th style="text-align: center;">Administrator</th>
                        <th style="text-align: center;">User</th>
                        <th style="text-align: center;">Guest</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>View Dashboard</strong>
                            <br><small style="color: var(--text-secondary);">Access to view dashboard statistics</small>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[admin][view_dashboard]" checked disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[user][view_dashboard]" <?= ($permissions['user']['view_dashboard'] ?? true) ? 'checked' : '' ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[guest][view_dashboard]" <?= ($permissions['guest']['view_dashboard'] ?? false) ? 'checked' : '' ?>>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <strong>Create Users</strong>
                            <br><small style="color: var(--text-secondary);">Ability to create new user accounts</small>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[admin][create_users]" checked disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[user][create_users]" <?= ($permissions['user']['create_users'] ?? false) ? 'checked' : '' ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[guest][create_users]" <?= ($permissions['guest']['create_users'] ?? false) ? 'checked' : '' ?>>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <strong>Edit Users</strong>
                            <br><small style="color: var(--text-secondary);">Modify existing user information</small>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[admin][edit_users]" checked disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[user][edit_users]" <?= ($permissions['user']['edit_users'] ?? false) ? 'checked' : '' ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[guest][edit_users]" disabled>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <strong>Delete Users</strong>
                            <br><small style="color: var(--text-secondary);">Remove user accounts from system</small>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[admin][delete_users]" checked disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[user][delete_users]" <?= ($permissions['user']['delete_users'] ?? false) ? 'checked' : '' ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[guest][delete_users]" disabled>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <strong>Manage Permissions</strong>
                            <br><small style="color: var(--text-secondary);">Control role-based access permissions</small>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[admin][manage_permissions]" checked disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[user][manage_permissions]" disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[guest][manage_permissions]" disabled>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <strong>View Reports</strong>
                            <br><small style="color: var(--text-secondary);">Access system reports and analytics</small>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[admin][view_reports]" checked disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[user][view_reports]" <?= ($permissions['user']['view_reports'] ?? true) ? 'checked' : '' ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[guest][view_reports]" <?= ($permissions['guest']['view_reports'] ?? false) ? 'checked' : '' ?>>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <strong>Export Data</strong>
                            <br><small style="color: var(--text-secondary);">Download and export system data</small>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[admin][export_data]" checked disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[user][export_data]" <?= ($permissions['user']['export_data'] ?? false) ? 'checked' : '' ?>>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[guest][export_data]" disabled>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <strong>System Settings</strong>
                            <br><small style="color: var(--text-secondary);">Modify system configuration</small>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[admin][system_settings]" checked disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[user][system_settings]" disabled>
                        </td>
                        <td style="text-align: center;">
                            <input type="checkbox" class="permission-checkbox" name="permissions[guest][system_settings]" disabled>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>

<!-- Legend -->
<div class="card" style="margin-top: 24px;">
    <h3 style="font-size: 16px; margin-bottom: 16px; color: var(--text-primary);">Permission Legend</h3>
    <div style="display: flex; gap: 24px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" checked disabled style="width: 18px; height: 18px;">
            <span style="color: var(--text-secondary); font-size: 14px;">Granted</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" disabled style="width: 18px; height: 18px;">
            <span style="color: var(--text-secondary); font-size: 14px;">Not Granted</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <input type="checkbox" checked disabled style="width: 18px; height: 18px; opacity: 0.5;">
            <span style="color: var(--text-secondary); font-size: 14px;">Always granted for Admin</span>
        </div>
    </div>
</div>

<script>
    function savePermissions() {
        document.getElementById('permissionsForm').submit();
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/main.php';
