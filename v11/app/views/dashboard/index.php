<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Connected Sites</div>
        <div class="stat-value"><?php echo $stats['total_sites']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Pages</div>
        <div class="stat-value"><?php echo number_format($stats['total_pages']); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Posts</div>
        <div class="stat-value"><?php echo number_format($stats['total_posts']); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Images</div>
        <div class="stat-value"><?php echo number_format($stats['total_images']); ?></div>
    </div>
</div>

<div class="card" style="min-width: 1000px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>Recent Sites</h2>
        <div style="display: flex; gap: 8px;">
            <a href="/web-manager/public/index.php?route=sites&action=add" class="btn btn-primary" style="font-size: 12px; padding: 6px 16px;">+ Connect Site</a>
            <a href="/web-manager/public/index.php?route=sites" class="btn btn-outline" style="font-size: 12px; padding: 6px 16px;">View All</a>
        </div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Site Name</th>
                    <th>URL</th>
                    <th>Last Sync</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sites)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">No sites connected yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach (array_slice($sites, 0, 5) as $site): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <?php 
                                        $domain = parse_url($site['url'], PHP_URL_HOST);
                                        $favicon_url = "https://www.google.com/s2/favicons?domain=" . $domain . "&sz=64";
                                    ?>
                                    <div style="width: 28px; height: 28px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #e5e7eb;">
                                        <img src="<?php echo $favicon_url; ?>" alt="" style="width: 16px; height: 16px; border-radius: 2px;">
                                    </div>
                                    <strong><?php echo $site['name']; ?></strong>
                                </div>
                            </td>
                            <td><a href="<?php echo $site['url']; ?>" target="_blank" style="color: var(--text-muted);"><?php echo $site['url']; ?></a></td>
                            <td><?php echo $site['last_sync'] ? date('M j, Y H:i', strtotime($site['last_sync'])) : 'Never'; ?></td>
                            <td>
                                <span class="badge <?php echo $site['last_sync'] ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo $site['last_sync'] ? 'Connected' : 'Pending'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
