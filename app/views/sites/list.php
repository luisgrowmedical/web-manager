<?php 
$header_action = '<a href="/web-manager/public/index.php?route=sites&action=add" class="btn btn-primary">+ Connect New Site</a>';
?>

<div style="margin-bottom: 24px;">
    <div class="card" style="padding: 16px;">
        <form action="/web-manager/public/index.php" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <input type="hidden" name="route" value="sites">
            
            <div style="flex: 2; min-width: 200px;">
                <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 4px;">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Name or URL..." value="<?php echo $filters['search']; ?>" style="padding: 8px 12px;">
            </div>

            <div style="flex: 1; min-width: 120px;">
                <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 4px;">Country</label>
                <select name="country" class="form-control" data-country-select style="padding: 8px 12px;">
                    <option value="">All Countries</option>
                    <?php foreach ($countries as $c): ?>
                        <?php echo wm_country_option($c, $c, $filters['country'] === $c); ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="flex: 1; min-width: 120px;">
                <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 4px;">Specialty</label>
                <select name="specialty" class="form-control" style="padding: 8px 12px;">
                    <option value="">All Specialties</option>
                    <?php 
                    $specs = ['Urologo', 'Dermatólogo', 'Fisioterapeuta', 'Odontólogo', 'Radiólogo', 'Ginecólogo', 'Pediatra'];
                    foreach ($specs as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $filters['specialty'] === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 8px 20px; height: 38px;">Filter</button>
            <a href="/web-manager/public/index.php?route=sites" class="btn btn-outline" style="padding: 8px 20px; height: 38px;">Clear</a>
        </form>
    </div>
</div>

<div class="card">
    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border-color);">
            <div>
                <h2 style="margin: 0; font-size: 20px; color: var(--text-color);">Connected Sites</h2>
                <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Manage and monitor all your connected websites</p>
            </div>
            <a href="/web-manager/public/index.php?route=sites&action=add" class="btn btn-primary btn-subtle-pulse" style="padding: 12px 24px; font-weight: 800; font-size: 15px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); border-radius: 9999px; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Connect New Site
            </a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <?php
                        function sortLink($label, $key, $filters) {
                            $newOrder = ($filters['sort_by'] === $key && $filters['order'] === 'ASC') ? 'DESC' : 'ASC';
                            $isActive = ($filters['sort_by'] === $key);
                            $color = $isActive ? 'var(--primary)' : 'inherit';
                            $url = "/web-manager/public/index.php?route=sites&sort_by=$key&order=$newOrder";
                            foreach ($filters as $fk => $fv) {
                                if ($fk !== 'sort_by' && $fk !== 'order' && $fv) {
                                    $url .= "&$fk=" . urlencode($fv);
                                }
                            }
                            return "<a href='$url' style='text-decoration: none; color: $color; border-bottom: " . ($isActive ? '2px solid var(--primary)' : 'none') . "; padding-bottom: 2px;'>$label</a>";
                        }
                        ?>
                        <th><?php echo sortLink('Site Name', 'name', $filters); ?></th>
                        <th>URL</th>
                        <th><?php echo sortLink('Pages', 'pages_count', $filters); ?></th>
                        <th><?php echo sortLink('Posts', 'posts_count', $filters); ?></th>
                        <th><?php echo sortLink('Drafts', 'drafts_count', $filters); ?></th>
                        <th><?php echo sortLink('Images', 'images_count', $filters); ?></th>
                        <th><?php echo sortLink('Weight', 'site_weight', $filters); ?></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sites)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                                No sites connected. <a href="/web-manager/public/index.php?route=sites&action=add">Connect one now</a>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sites as $site): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php 
                                            $domain = parse_url($site['url'], PHP_URL_HOST);
                                            $favicon_url = "https://www.google.com/s2/favicons?domain=" . $domain . "&sz=64";
                                        ?>
                                        <div style="width: 32px; height: 32px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid #e5e7eb;">
                                            <img src="<?php echo $favicon_url; ?>" alt="" style="width: 18px; height: 18px; border-radius: 2px;">
                                        </div>
                                        <strong><?php echo $site['name']; ?></strong>
                                    </div>
                                </td>
                                <td><small><?php echo $site['url']; ?></small></td>
                                <td><?php echo $site['pages_count'] ?? '-'; ?></td>
                                <td><?php echo $site['posts_count'] ?? '-'; ?></td>
                                <td><?php echo $site['drafts_count'] ?? '-'; ?></td>
                                <td><?php echo $site['images_count'] ?? '-'; ?></td>
                                <td><?php echo $site['site_weight'] ?? '-'; ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="/web-manager/public/index.php?route=sites&action=view&id=<?php echo $site['id']; ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 11px;">View</a>
                                        <a href="/web-manager/public/index.php?route=sites&action=sync&id=<?php echo $site['id']; ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 11px; background: #f0fdf4; border-color: #bcf0da; color: #166534;">Sync</a>
                                        <?php if ($_SESSION['role'] === 'admin'): ?>
                                            <a href="/web-manager/public/index.php?route=sites&action=delete&id=<?php echo $site['id']; ?>" class="btn btn-outline" style="padding: 4px 10px; font-size: 11px; color: #dc2626;" onclick="return confirm('Delete connection?')">Delete</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (isset($total_pages) && $total_pages > 1): ?>
        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 12px; padding: 16px; border-top: 1px solid var(--border-color); margin-top: 16px;">
            <div style="font-size: 13px; color: var(--text-muted);">
                Showing <?php echo count($sites); ?> of <?php echo $total_records; ?> sites
            </div>
            <div style="display: flex; gap: 4px;">
                <?php
                // Generate URL for pagination
                $build_url = function($p) use ($filters) {
                    $url = "/web-manager/public/index.php?route=sites&page=$p";
                    foreach ($filters as $fk => $fv) {
                        if ($fv !== '' && $fv !== null && $fk !== 'page') {
                            $url .= "&$fk=" . urlencode($fv);
                        }
                    }
                    return $url;
                };
                ?>
                
                <?php if ($page > 1): ?>
                    <a href="<?php echo $build_url($page - 1); ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; border-radius: 9999px;">Previous</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <a href="<?php echo $build_url($i); ?>" class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-outline'; ?>" style="width: 32px; height: 32px; padding: 0; display: inline-flex; justify-content: center; align-items: center; font-size: 13px; border-radius: 50%;">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="<?php echo $build_url($page + 1); ?>" class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; border-radius: 9999px;">Next</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
