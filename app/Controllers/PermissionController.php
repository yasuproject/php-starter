<?php

require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/../Config/Session.php';

class PermissionController {
    
    public function index() {
        Session::requireAuth();
        
        // Add security headers
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;");
        
        $username = Session::get('admin_username');
        
        // Get current permissions (in a real app, this would come from the database)
        $permissions = $_SESSION['permissions'] ?? [
            'user' => [
                'view_dashboard' => true,
                'create_users' => false,
                'edit_users' => false,
                'delete_users' => false,
                'view_reports' => true,
                'export_data' => false,
            ],
            'guest' => [
                'view_dashboard' => false,
                'create_users' => false,
                'view_reports' => false,
            ]
        ];
        
        require __DIR__ . '/../Views/permissions.php';
    }
    
    public function save() {
        Session::requireAuth();
        Session::init();
        
        $permissions = $_POST['permissions'] ?? [];
        
        // Validate permissions
        $validPermissions = [
            'view_dashboard',
            'create_users',
            'edit_users',
            'delete_users',
            'manage_permissions',
            'view_reports',
            'export_data',
            'system_settings'
        ];
        
        $sanitizedPermissions = [];
        
        foreach (['user', 'guest'] as $role) {
            $sanitizedPermissions[$role] = [];
            foreach ($validPermissions as $permission) {
                $sanitizedPermissions[$role][$permission] = isset($permissions[$role][$permission]);
            }
        }
        
        // Save to session (in a real app, save to database)
        $_SESSION['permissions'] = $sanitizedPermissions;
        
        // Log the action
        error_log('Permissions updated by user: ' . Session::get('admin_username'));
        
        $_SESSION['success_message'] = 'Permissions updated successfully!';
        header('Location: /admin/permissions');
        exit;
    }
}
