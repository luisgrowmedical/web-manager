<?php
if (!function_exists('dashboard_h')) {
    function dashboard_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('dashboard_update_icon')) {
    function dashboard_update_icon($item, $type) {
        $name = $item['name'] ?? 'Update';
        $icon = $item['icon'] ?? null;
        $class = 'update-avatar update-avatar-' . $type;

        if ($icon) {
            return '<span class="' . $class . '"><img src="' . dashboard_h($icon) . '" alt="" loading="lazy" onerror="this.remove(); this.parentNode.textContent=\'' . dashboard_h(strtoupper(substr($name, 0, 1))) . '\';"></span>';
        }

        return '<span class="' . $class . '">' . dashboard_h(strtoupper(substr($name, 0, 1))) . '</span>';
    }
}

if (!function_exists('dashboard_render_updates_rows')) {
    function dashboard_render_updates_rows($items, $type) {
        if (empty($items)) {
            echo '<tr><td colspan="6" class="updates-empty">No detailed update data is available for this category.</td></tr>';
            return;
        }

        foreach ($items as $item) {
            $id = $type . '-' . ($item['id'] ?? md5($item['name'] ?? uniqid('', true)));
            echo '<tr>';
            echo '<td><input type="checkbox" class="updates-item-check" data-updates-item="' . dashboard_h($type) . '" value="' . dashboard_h($id) . '"></td>';
            echo '<td><div class="update-item-title">' . dashboard_update_icon($item, $type) . '<div><strong>' . dashboard_h($item['name'] ?? 'Unknown') . '</strong><span>' . dashboard_h(implode(', ', array_slice($item['sites'] ?? [], 0, 3))) . (count($item['sites'] ?? []) > 3 ? ' +' . (count($item['sites']) - 3) : '') . '</span></div></div></td>';
            echo '<td>' . dashboard_h($item['site_count'] ?? 0) . '</td>';
            echo '<td>' . dashboard_h($item['current_version'] ?? 'Unknown') . '</td>';
            echo '<td>' . dashboard_h($item['new_version'] ?? 'Unknown') . '</td>';
            echo '<td><span class="badge badge-warning">Pending</span></td>';
            echo '</tr>';
        }
    }
}

$available_updates = $available_updates ?? ['plugins' => [], 'themes' => [], 'wordpress' => []];
$plugin_updates_total = (int)($stats['plugin_updates'] ?? 0);
$theme_updates_total = (int)($stats['theme_updates'] ?? 0);
$wordpress_updates_total = count($available_updates['wordpress'] ?? []);
$detailed_updates_total = count($available_updates['plugins'] ?? []) + count($available_updates['themes'] ?? []) + $wordpress_updates_total;
$multisite_networks = $multisite_networks ?? [];
?>

<div class="updates-summary-grid">
    <div class="stat-card">
        <div class="stat-label">Available Updates</div>
        <div class="stat-value <?php echo ($stats['total_updates'] ?? 0) > 0 ? 'stat-danger' : ''; ?>">
            <?php echo number_format($stats['total_updates'] ?? 0); ?>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Plugins</div>
        <div class="stat-value <?php echo $plugin_updates_total > 0 ? 'stat-danger' : ''; ?>"><?php echo number_format($plugin_updates_total); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Themes</div>
        <div class="stat-value <?php echo $theme_updates_total > 0 ? 'stat-danger' : ''; ?>"><?php echo number_format($theme_updates_total); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">WordPress</div>
        <div class="stat-value <?php echo $wordpress_updates_total > 0 ? 'stat-danger' : ''; ?>"><?php echo number_format($wordpress_updates_total); ?></div>
    </div>
</div>

<div class="card updates-module" data-updates-module>
    <div class="updates-module-header">
        <div>
            <h2>Available Updates</h2>
            <p>Select plugins, themes, or WordPress sites to prepare an update action.</p>
        </div>
        <div class="updates-actions">
            <button type="button" class="btn btn-primary" disabled title="Pending: update execution requires a secure endpoint with permissions and audit logs.">Update Selected</button>
            <button type="button" class="btn btn-outline" disabled title="Pending: bulk update execution is not available yet.">Update All</button>
            <button type="button" class="btn btn-outline" disabled title="Pending: backups, rollback, and health checks are required before safe updates can run.">Safe Update</button>
        </div>
    </div>

    <div class="updates-tabs" role="tablist" aria-label="Update categories">
        <button type="button" class="updates-tab active" data-updates-tab="plugins" role="tab" aria-selected="true">Plugins <span><?php echo count($available_updates['plugins'] ?? []); ?></span></button>
        <button type="button" class="updates-tab" data-updates-tab="themes" role="tab" aria-selected="false">Themes <span><?php echo count($available_updates['themes'] ?? []); ?></span></button>
        <button type="button" class="updates-tab" data-updates-tab="wordpress" role="tab" aria-selected="false">WordPress <span><?php echo $wordpress_updates_total; ?></span></button>
    </div>

    <?php if ($detailed_updates_total === 0): ?>
        <div class="updates-note">
            The dashboard has update counts, but detailed names and versions are not stored yet. Sync sites after updating the connector plugin to populate this module.
        </div>
    <?php endif; ?>

    <div class="updates-panel active" data-updates-panel="plugins" role="tabpanel">
        <div class="updates-toolbar">
            <label><input type="checkbox" class="updates-select-all" data-updates-select-all="plugins"> Select all plugins</label>
            <span data-updates-selected-count="plugins">0 selected</span>
        </div>
        <div class="table-container">
            <table class="updates-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Plugin</th>
                        <th>Sites</th>
                        <th>Current Version</th>
                        <th>Available Version</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody><?php dashboard_render_updates_rows($available_updates['plugins'] ?? [], 'plugins'); ?></tbody>
            </table>
        </div>
    </div>

    <div class="updates-panel" data-updates-panel="themes" role="tabpanel">
        <div class="updates-toolbar">
            <label><input type="checkbox" class="updates-select-all" data-updates-select-all="themes"> Select all themes</label>
            <span data-updates-selected-count="themes">0 selected</span>
        </div>
        <div class="table-container">
            <table class="updates-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Theme</th>
                        <th>Sites</th>
                        <th>Current Version</th>
                        <th>Available Version</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody><?php dashboard_render_updates_rows($available_updates['themes'] ?? [], 'themes'); ?></tbody>
            </table>
        </div>
    </div>

    <div class="updates-panel" data-updates-panel="wordpress" role="tabpanel">
        <div class="updates-toolbar">
            <label><input type="checkbox" class="updates-select-all" data-updates-select-all="wordpress"> Select all WordPress sites</label>
            <span data-updates-selected-count="wordpress">0 selected</span>
        </div>
        <div class="table-container">
            <table class="updates-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>WordPress Site</th>
                        <th>Sites</th>
                        <th>Current Version</th>
                        <th>Available Version</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody><?php dashboard_render_updates_rows($available_updates['wordpress'] ?? [], 'wordpress'); ?></tbody>
            </table>
        </div>
    </div>

    <p class="updates-safe-note">Safe updates are pending until verified backups, rollback, and pre/post-update health checks are implemented.</p>
</div>

<section class="card multisite-module">
    <div class="updates-module-header">
        <div>
            <h2>WordPress Multisite Networks</h2>
            <p>Detected networks reported by connected WordPress sites.</p>
        </div>
        <div class="multisite-total">
            <span class="stat-label">Total Networks</span>
            <strong><?php echo count($multisite_networks); ?></strong>
        </div>
    </div>

    <?php if (empty($multisite_networks)): ?>
        <div class="empty-state">No WordPress Multisite networks have been detected yet.</div>
    <?php else: ?>
        <div class="multisite-list">
            <?php foreach ($multisite_networks as $network): ?>
                <div class="multisite-row">
                    <span class="multisite-icon" aria-hidden="true">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><path d="M14 17h7"></path><path d="M17.5 14v7"></path></svg>
                    </span>
                    <div class="multisite-main">
                        <strong><?php echo dashboard_h($network['name']); ?></strong>
                        <span><?php echo (int)$network['connected_sites']; ?> connected record<?php echo (int)$network['connected_sites'] === 1 ? '' : 's'; ?></span>
                    </div>
                    <div class="multisite-meta">
                        <span><?php echo (int)$network['site_count']; ?> sites</span>
                        <span class="badge <?php echo dashboard_h($network['status_class']); ?>"><?php echo dashboard_h($network['status']); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
