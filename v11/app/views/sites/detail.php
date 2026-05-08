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
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <div>
        <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
            <div style="padding: 16px 24px; border-bottom: 1px solid var(--border-color); background: #f9fafb; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 16px;">Visual Preview (Above the Fold)</h2>
                <span style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Live Snapshot</span>
            </div>
            <div style="width: 100%; background: #f3f4f6; position: relative; overflow: hidden;">
                <?php 
                    $preview_url = "https://s0.wp.com/mshots/v1/" . urlencode($site['url']) . "?w=1280";
                ?>
                <img src="<?php echo $preview_url; ?>" alt="Site Preview" style="width: 100%; display: block; min-height: 300px; object-fit: cover; object-position: top; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            </div>
        </div>

        <div class="card">
            <h2>Site Inventory</h2>
            <div class="table-container">
                <table style="border: none;">
                    <tr>
                        <td style="border: none; font-weight: 600;">Site Name</td>
                        <td style="border: none;"><?php echo $site['name']; ?></td>
                    </tr>
                    <tr>
                        <td style="border: none; font-weight: 600;">URL</td>
                        <td style="border: none;"><a href="<?php echo $site['url']; ?>" target="_blank"><?php echo $site['url']; ?></a></td>
                    </tr>
                    <tr>
                        <td style="border: none; font-weight: 600;">Active Theme</td>
                        <td style="border: none;"><?php echo $metrics['active_theme'] ?? 'Unknown'; ?> (<?php echo $metrics['themes_count'] ?? 0; ?> installed)</td>
                    </tr>
                    <tr>
                        <td style="border: none; font-weight: 600;">Plugins</td>
                        <td style="border: none;"><?php echo $metrics['active_plugins_count'] ?? 0; ?> active / <?php echo $metrics['total_plugins_count'] ?? 0; ?> total</td>
                    </tr>
                    <tr>
                        <td style="border: none; font-weight: 600;">Estimated Weight</td>
                        <td style="border: none;"><?php echo $metrics['site_weight'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <td style="border: none; font-weight: 600;">Last Sincronization</td>
                        <td style="border: none;"><?php echo $metrics['sync_date'] ? date('M j, Y - H:i:s', strtotime($metrics['sync_date'])) : 'Never'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <h2>Connection Status</h2>
            <div style="text-align: center; padding: 20px 0;">
                <div style="width: 80px; height: 80px; background: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#39b54a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <p style="font-weight: 700; color: #166534;">Active Connection</p>
                <p style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">The site is responding correctly to API requests.</p>
            </div>
        </div>

        <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="card" style="border: 1px solid #fee2e2;">
            <h3 style="font-size: 14px; color: #dc2626; margin-bottom: 12px;">Admin Actions</h3>
            <a href="/web-manager/public/index.php?route=admin&action=access&id=<?php echo $site['id']; ?>" class="btn btn-outline" style="width: 100%; margin-bottom: 8px;">Manage User Access</a>
            <a href="/web-manager/public/index.php?route=sites&action=delete&id=<?php echo $site['id']; ?>" class="btn btn-outline" style="width: 100%; color: #dc2626; border-color: #fca5a5;" onclick="return confirm('Delete this site connection?')">Delete Connection</a>
        </div>
        <?php endif; ?>
    </div>
</div>
