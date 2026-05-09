<?php
if (!function_exists('settings_h')) {
    function settings_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('settings_connector_status_badge')) {
    function settings_connector_status_badge($status) {
        if ($status === 'outdated') {
            return '<span class="badge badge-danger">Outdated</span>';
        }
        if ($status === 'unknown') {
            return '<span class="badge badge-warning">Unknown</span>';
        }

        return '<span class="badge badge-success">Current</span>';
    }
}

$connector_update = $connector_update ?? ['available_version' => 'Unknown', 'outdated_count' => 0, 'unknown_count' => 0, 'sites' => []];
?>

<div class="settings-layout">
    <section class="settings-section">
        <h2>Manage Users</h2>
        <div class="settings-users-grid">
            <div class="card">
                <form action="/web-manager/public/index.php?route=admin&action=users" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="add_user" value="1">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="role_id">Role</label>
                        <select name="role_id" id="role_id" class="form-control" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo ucfirst($role['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="avatar">Profile Image</label>
                        <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                        <small class="form-help">Optional. JPG, PNG, or GIF.</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Create User</button>
                </form>
            </div>

            <div class="card">
                <h3 class="section-subtitle">Existing Users</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <div class="user-cell">
                                            <?php if ($u['avatar']): ?>
                                                <img src="<?php echo $u['avatar']; ?>" alt="" class="avatar avatar-md avatar-border">
                                            <?php else: ?>
                                                <div class="avatar avatar-md avatar-initial">
                                                    <?php echo substr($u['username'], 0, 1); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?php echo settings_h($u['username']); ?></strong><br>
                                                <small class="muted-text"><?php echo settings_h($u['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $u['role_name'] === 'admin' ? 'badge-success' : 'badge-warning'; ?>">
                                            <?php echo settings_h($u['role_name']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="row-actions">
                                            <a href="/web-manager/public/index.php?route=admin&action=access&id=<?php echo $u['id']; ?>" class="btn btn-outline btn-xs">Site Access</a>
                                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                                <a href="/web-manager/public/index.php?route=admin&action=delete_user&id=<?php echo $u['id']; ?>" class="btn btn-outline btn-xs btn-danger-outline" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="card settings-section">
        <div class="updates-module-header">
            <div>
                <h2>Plugin Connector</h2>
                <p>Published version: <strong><?php echo settings_h($connector_update['available_version'] ?? 'Unknown'); ?></strong>. Outdated sites: <strong><?php echo (int)($connector_update['outdated_count'] ?? 0); ?></strong>.</p>
            </div>
            <div class="updates-actions">
                <button type="button" class="btn btn-primary" disabled title="Pending: a secure authenticated WordPress endpoint is required before remote updates can run.">Update Selected</button>
            </div>
        </div>

        <?php if (($connector_update['unknown_count'] ?? 0) > 0): ?>
            <div class="updates-note">
                Some sites have not reported their connector version yet. Run a sync check to refresh metrics with the updated plugin.
            </div>
        <?php endif; ?>

        <div class="table-container">
            <table class="updates-table connector-table">
                <thead>
                    <tr>
                        <th>Site</th>
                        <th>Installed Version</th>
                        <th>Available Version</th>
                        <th>Last Check</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($connector_update['sites'])): ?>
                        <tr><td colspan="6" class="updates-empty">No connected sites yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($connector_update['sites'] as $connector_site): ?>
                            <tr>
                                <td>
                                    <strong><?php echo settings_h($connector_site['site_name']); ?></strong>
                                    <span class="connector-site-url"><?php echo settings_h($connector_site['site_url']); ?></span>
                                </td>
                                <td><?php echo settings_h($connector_site['installed_version']); ?></td>
                                <td><?php echo settings_h($connector_site['available_version']); ?></td>
                                <td><?php echo !empty($connector_site['last_sync']) ? settings_h(date('M j, H:i', strtotime($connector_site['last_sync']))) : 'No date'; ?></td>
                                <td><?php echo settings_connector_status_badge($connector_site['status']); ?></td>
                                <td><a class="btn btn-outline btn-sm" href="/web-manager/public/index.php?route=sites&action=sync&id=<?php echo (int)$connector_site['site_id']; ?>" title="Query WordPress and force wp_update_plugins through the connector.">Run Check</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <p class="updates-safe-note">Bulk remote updates remain disabled until the WordPress endpoint supports authenticated execution with audit logs, backups, and rollback.</p>
    </section>
</div>
