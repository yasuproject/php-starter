<?php
$title = 'Create User - Admin Panel';
$pageTitle = 'Create User';
$activePage = 'create-user';
$username = $username ?? 'Admin';

ob_start();
?>

<div class="card">
    <h2 class="card-title">Create New User</h2>
    
    <form action="/admin/users/store" method="POST" class="admin-form">
        <div class="form-group">
            <label class="form-label" for="fullname">Full Name</label>
            <input 
                type="text" 
                id="fullname" 
                name="fullname" 
                class="form-input" 
                placeholder="Enter full name"
                required
                value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"
            >
        </div>
        
        <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                class="form-input" 
                placeholder="Enter username"
                required
                value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
            >
        </div>
        
        <div class="form-group">
            <label class="form-label" for="email">Email Address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-input" 
                placeholder="Enter email address"
                required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            >
        </div>
        
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="form-input" 
                placeholder="Enter password"
                required
                minlength="8"
            >
            <small style="color: var(--text-secondary); margin-top: 4px; display: block;">
                Password must be at least 8 characters long
            </small>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="role">Role</label>
            <select id="role" name="role" class="form-select" required>
                <option value="">Select a role</option>
                <option value="admin" <?= ($_POST['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Administrator</option>
                <option value="user" <?= ($_POST['role'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                <option value="guest" <?= ($_POST['role'] ?? '') === 'guest' ? 'selected' : '' ?>>Guest</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-select" required>
                <option value="active" <?= ($_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($_POST['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <button type="submit" class="btn btn-primary">
                <ion-icon name="person-add-outline"></ion-icon>
                Create User
            </button>
            <a href="/admin/dashboard" class="btn btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
$layoutPath = realpath(__DIR__ . '/layouts/main.php');
if ($layoutPath === false) {
    die('Error: Layout file not found at: ' . __DIR__ . '/layouts/main.php');
}
require $layoutPath;
