<?php 
$header_action = '<a href="/web-manager/public/index.php?route=sites&action=sync&id=' . $site['id'] . '" class="btn btn-primary">Refresh Data</a>';
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Pages</div>
        <div class="stat-value"><?php echo $metrics['pages_count'] ?? 0; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Posts</div>
        <div class="stat-value"><?php echo $metrics['posts_count'] ?? 0; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Drafts</div>
        <div class="stat-value"><?php echo $metrics['drafts_count'] ?? 0; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Images</div>
        <div class="stat-value"><?php echo $metrics['images_count'] ?? 0; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Pending Updates</div>
        <div class="stat-value <?php echo ($metrics['updates_count'] ?? 0) > 0 ? 'stat-danger' : ''; ?>">
            <?php echo $metrics['updates_count'] ?? 0; ?>
        </div>
    </div>
</div>

<div class="detail-layout">
    <div>
        <div class="card card-flat">
            <div class="preview-header">
                <h2>Visual Preview (Above the Fold)</h2>
                <span class="preview-kicker">Live Snapshot</span>
            </div>
            <div class="preview-frame">
                <?php 
                    $preview_url = "https://s0.wp.com/mshots/v1/" . urlencode($site['url']) . "?w=1280";
                ?>
                <img src="<?php echo $preview_url; ?>" alt="Site Preview" class="preview-image">
            </div>
        </div>

        <div class="card">
            <h2>Site Inventory</h2>
            <div class="table-container">
                <table class="inventory-table">
                    <tr>
                        <td class="inventory-label">Site Name</td>
                        <td><?php echo $site['name']; ?></td>
                    </tr>
                    <tr>
                        <td class="inventory-label">URL</td>
                        <td><a href="<?php echo $site['url']; ?>" target="_blank"><?php echo $site['url']; ?></a></td>
                    </tr>
                    <tr>
                        <td class="inventory-label">Active Theme</td>
                        <td><?php echo $metrics['active_theme'] ?? 'Unknown'; ?> (<?php echo $metrics['themes_count'] ?? 0; ?> installed)</td>
                    </tr>
                    <tr>
                        <td class="inventory-label">Plugins</td>
                        <td><?php echo $metrics['active_plugins_count'] ?? 0; ?> active / <?php echo $metrics['total_plugins_count'] ?? 0; ?> total</td>
                    </tr>
                    <tr>
                        <td class="inventory-label">Estimated Weight</td>
                        <td><?php echo $metrics['site_weight'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <td class="inventory-label">Last Synchronization</td>
                        <td><?php echo $metrics['sync_date'] ? date('M j, Y - H:i:s', strtotime($metrics['sync_date'])) : 'Never'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <h2>Connection Status</h2>
            <div class="connection-status">
                <div class="status-icon-large">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#39b54a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <p class="status-title">Active Connection</p>
                <p class="status-copy">The site is responding correctly to API requests.</p>
            </div>
        </div>

        <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="card danger-card">
            <h3 class="danger-title">Admin Actions</h3>
            <div class="stacked-actions">
                <a href="/web-manager/public/index.php?route=admin&action=access&id=<?php echo $site['id']; ?>" class="btn btn-outline btn-block">Manage User Access</a>
                <a href="/web-manager/public/index.php?route=sites&action=delete&id=<?php echo $site['id']; ?>" class="btn btn-outline btn-block btn-danger-outline" onclick="return confirm('Delete this site connection?')">Delete Connection</a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
