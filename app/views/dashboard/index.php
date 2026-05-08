<?php
if (!function_exists('dashboard_sync_visual')) {
    function dashboard_sync_visual($site, $stale_days = 7) {
        $sync_status = $site['sync_status'] ?? null;
        if ($sync_status === 'error') {
            return ['badge-danger', 'Disconnected', 'Last sync failed'];
        }
        if (empty($site['last_sync'])) {
            return ['badge-warning', 'Never synced', 'No sync data yet'];
        }

        $last_sync_time = strtotime($site['last_sync']);
        if ($last_sync_time && $last_sync_time < strtotime("-{$stale_days} days")) {
            return ['badge-warning', 'Stale', date('M j, Y H:i', $last_sync_time)];
        }

        return ['badge-success', 'Synced recently', date('M j, Y H:i', $last_sync_time)];
    }
}

$attention_sites = $attention_sites ?? [];
$recent_activity = $recent_activity ?? [];
$stale_sync_days = $dashboard_meta['stale_sync_days'] ?? 7;
$plugin_updates_total = (int)($stats['plugin_updates'] ?? 0);
$theme_updates_total = (int)($stats['theme_updates'] ?? 0);
?>

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
    <div class="stat-card">
        <div class="stat-label">Updates Pending</div>
        <div class="stat-value" style="color: <?php echo ($stats['total_updates'] ?? 0) > 0 ? '#dc2626' : 'var(--primary)'; ?>">
            <?php echo number_format($stats['total_updates'] ?? 0); ?>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sites Requiring Attention</div>
        <div class="stat-value" style="color: <?php echo count($attention_sites) > 0 ? '#dc2626' : 'var(--primary)'; ?>">
            <?php echo number_format(count($attention_sites)); ?>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 16px;">
            <div>
                <h2 style="margin-bottom: 4px;">Sites Requiring Attention</h2>
                <p style="color: var(--text-muted); font-size: 13px; margin: 0;">Disconnected, failed sync, stale sync, or pending updates.</p>
            </div>
            <span class="badge <?php echo count($attention_sites) > 0 ? 'badge-danger' : 'badge-success'; ?>">
                <?php echo count($attention_sites); ?> sites
            </span>
        </div>

        <?php if (empty($attention_sites)): ?>
            <p style="color: var(--text-muted); font-size: 14px; margin: 0;">No sites need attention right now.</p>
        <?php else: ?>
            <div style="display: grid; gap: 10px;">
                <?php foreach (array_slice($attention_sites, 0, 4) as $item): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                        <div style="min-width: 0;">
                            <strong style="display: block; overflow: hidden; text-overflow: ellipsis;"><?php echo $item['site']['name']; ?></strong>
                            <span style="color: var(--text-muted); font-size: 12px;"><?php echo implode(', ', $item['issues']); ?></span>
                        </div>
                        <a href="/web-manager/public/index.php?route=sites&action=view&id=<?php echo $item['site']['id']; ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 11px;">View</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <h2 style="margin-bottom: 16px;">Updates Breakdown</h2>
        <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px;">
            <div style="border: 1px solid var(--border-color); border-radius: var(--radius); padding: 12px;">
                <div class="stat-label">Plugins</div>
                <div class="stat-value" style="font-size: 22px; color: <?php echo $plugin_updates_total > 0 ? '#dc2626' : 'var(--primary)'; ?>"><?php echo number_format($plugin_updates_total); ?></div>
            </div>
            <div style="border: 1px solid var(--border-color); border-radius: var(--radius); padding: 12px;">
                <div class="stat-label">Themes</div>
                <div class="stat-value" style="font-size: 22px; color: <?php echo $theme_updates_total > 0 ? '#dc2626' : 'var(--primary)'; ?>"><?php echo number_format($theme_updates_total); ?></div>
            </div>
            <div style="border: 1px solid var(--border-color); border-radius: var(--radius); padding: 12px;">
                <div class="stat-label">Core</div>
                <div class="stat-value" style="font-size: 22px; color: var(--text-muted);">N/A</div>
            </div>
        </div>
        <p style="color: var(--text-muted); font-size: 12px; margin: 12px 0 0 0;">Core updates are not available in the current metrics data.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(280px, 0.8fr); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="margin-bottom: 0;">
        <h2 style="margin-bottom: 16px;">Recent Activity</h2>
        <?php if (empty($recent_activity)): ?>
            <p style="color: var(--text-muted); font-size: 14px; margin: 0;">No recent site activity available.</p>
        <?php else: ?>
            <div style="display: grid; gap: 12px;">
                <?php foreach ($recent_activity as $activity): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                        <div style="min-width: 0;">
                            <span class="badge <?php echo $activity['badge']; ?>" style="margin-right: 8px;"><?php echo $activity['label']; ?></span>
                            <strong><?php echo $activity['site']; ?></strong>
                        </div>
                        <span style="color: var(--text-muted); font-size: 12px;">
                            <?php echo $activity['time'] ? date('M j, H:i', strtotime($activity['time'])) : 'No date'; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <h2 style="margin-bottom: 16px;">Quick Actions</h2>
        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px;">
            <a href="/web-manager/public/index.php?route=sites&action=add" class="btn btn-primary" style="font-size: 12px; padding: 8px 12px;">Connect Site</a>
            <span class="btn btn-outline" title="TODO: no global sync route exists yet" style="font-size: 12px; padding: 8px 12px; opacity: 0.55; cursor: not-allowed;">Run Sync</span>
            <span class="btn btn-outline" title="TODO: no updates route exists yet" style="font-size: 12px; padding: 8px 12px; opacity: 0.55; cursor: not-allowed;">View Updates</span>
            <a href="/web-manager/public/index.php?route=sites" class="btn btn-outline" style="font-size: 12px; padding: 8px 12px;">View Sites</a>
        </div>
    </div>
</div>

<div class="card" style="overflow-x: auto;">
    <div style="min-width: 1000px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Recent Sites</h2>
            <div style="display: flex; gap: 8px;">
                <a href="/web-manager/public/index.php?route=sites&action=add" class="btn btn-primary" style="font-size: 12px; padding: 6px 16px; border-radius: 9999px;">+ Connect Site</a>
                <a href="/web-manager/public/index.php?route=sites" class="btn btn-outline" style="font-size: 12px; padding: 6px 16px; border-radius: 9999px;">View All</a>
            </div>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Site Name</th>
                        <th>URL</th>
                        <th>Last Sync</th>
                        <th>Updates</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sites)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted);">No sites connected yet.</td>
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
                                <td>
                                    <?php list($sync_badge_class, $sync_label, $sync_detail) = dashboard_sync_visual($site, $stale_sync_days); ?>
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <span class="badge <?php echo $sync_badge_class; ?>" style="width: fit-content;"><?php echo $sync_label; ?></span>
                                        <span style="color: var(--text-muted); font-size: 12px;"><?php echo $sync_detail; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $plugin_updates = (int)($site['plugin_updates_count'] ?? 0);
                                        $theme_updates = (int)($site['theme_updates_count'] ?? 0);
                                        if (($plugin_updates + $theme_updates) > 0): 
                                    ?>
                                        <span style="display: inline-flex; align-items: center; gap: 10px; color: #dc2626; font-weight: bold;">
                                            <span style="display: inline-flex; align-items: center; gap: 4px;" title="Plugin updates">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22v-5"></path><path d="M9 8V2"></path><path d="M15 8V2"></path><path d="M18 8v5a6 6 0 0 1-12 0V8Z"></path></svg>
                                                <?php echo $plugin_updates; ?>
                                            </span>
                                            <span style="display: inline-flex; align-items: center; gap: 4px;" title="Theme updates">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"></circle><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"></circle><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"></circle><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"></circle><path d="M12 2C6.5 2 2 5.8 2 10.5S5.8 19 10.5 19H12c1.1 0 2 .9 2 2 0 .6.4 1 1 1h.5C19.1 22 22 19.1 22 15.5V12C22 6.5 17.5 2 12 2Z"></path></svg>
                                                <?php echo $theme_updates; ?>
                                            </span>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted);">0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $sync_status = $site['sync_status'] ?? null;
                                        if ($sync_status === 'success' || (!$sync_status && $site['last_sync'])) {
                                            $status_class = 'badge-success';
                                            $status_label = 'Connected';
                                        } elseif ($sync_status === 'error') {
                                            $status_class = 'badge-danger';
                                            $status_label = 'Sync failed';
                                        } else {
                                            $status_class = 'badge-warning';
                                            $status_label = 'Not connected';
                                        }
                                    ?>
                                    <span class="badge <?php echo $status_class; ?>">
                                        <?php echo $status_label; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/web-manager/public/index.php?route=sites&action=view&id=<?php echo $site['id']; ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 11px;">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
