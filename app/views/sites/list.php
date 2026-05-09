<?php 
$header_action = '<a href="/web-manager/public/index.php?route=sites&action=add" class="btn btn-primary">+ Connect New Site</a>';
?>

<div class="filter-shell">
    <div class="card filter-card">
        <form action="/web-manager/public/index.php" method="GET" class="filter-form">
            <input type="hidden" name="route" value="sites">
            
            <div class="filter-field filter-field-wide">
                <label class="filter-label">Search</label>
                <input type="text" name="search" class="form-control filter-control" placeholder="Name or URL..." value="<?php echo $filters['search']; ?>">
            </div>

            <div class="filter-field">
                <label class="filter-label">Country</label>
                <select name="country" class="form-control filter-control" data-country-select>
                    <option value="">All Countries</option>
                    <?php foreach ($countries as $c): ?>
                        <?php echo wm_country_option($c, $c, $filters['country'] === $c); ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-field">
                <label class="filter-label">Specialty</label>
                <select name="specialty" class="form-control filter-control">
                    <option value="">All Specialties</option>
                    <?php 
                    $specs = ['Urology', 'Dermatology', 'Physiotherapy', 'Dentistry', 'Radiology', 'Gynecology', 'Pediatrics'];
                    foreach ($specs as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $filters['specialty'] === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary filter-button">Filter</button>
            <a href="/web-manager/public/index.php?route=sites" class="btn btn-outline filter-button">Clear</a>
        </form>
    </div>
</div>

<div class="card">
    <div>
        <div class="card-heading-row">
            <div>
                <h2>Connected Sites</h2>
                <p>Manage and monitor all your connected websites</p>
            </div>
            <a href="/web-manager/public/index.php?route=sites&action=add" class="btn btn-primary btn-subtle-pulse primary-cta">
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
                            $url = "/web-manager/public/index.php?route=sites&sort_by=$key&order=$newOrder";
                            foreach ($filters as $fk => $fv) {
                                if ($fk !== 'sort_by' && $fk !== 'order' && $fv) {
                                    $url .= "&$fk=" . urlencode($fv);
                                }
                            }
                            $class = $isActive ? 'sort-link active' : 'sort-link';
                            return "<a href='$url' class='$class'>$label</a>";
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
                            <td colspan="8" class="table-empty">
                                No sites connected. <a href="/web-manager/public/index.php?route=sites&action=add">Connect one now</a>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sites as $site): ?>
                            <tr>
                                <td>
                                    <div class="site-cell">
                                        <?php 
                                            $domain = parse_url($site['url'], PHP_URL_HOST);
                                            $favicon_url = "https://www.google.com/s2/favicons?domain=" . $domain . "&sz=64";
                                        ?>
                                        <div class="site-favicon-shell">
                                            <img src="<?php echo $favicon_url; ?>" alt="" class="site-favicon">
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
                                    <div class="row-actions">
                                        <a href="/web-manager/public/index.php?route=sites&action=view&id=<?php echo $site['id']; ?>" class="btn btn-outline btn-xs">View</a>
                                        <a href="/web-manager/public/index.php?route=sites&action=sync&id=<?php echo $site['id']; ?>" class="btn btn-outline btn-xs btn-success-soft">Sync</a>
                                        <?php if ($_SESSION['role'] === 'admin'): ?>
                                            <a href="/web-manager/public/index.php?route=sites&action=delete&id=<?php echo $site['id']; ?>" class="btn btn-outline btn-xs btn-danger-outline" onclick="return confirm('Delete connection?')">Delete</a>
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
        <div class="pagination-shell">
            <div class="pagination-summary">
                Showing <?php echo count($sites); ?> of <?php echo $total_records; ?> sites
            </div>
            <div class="pagination-list">
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
                    <a href="<?php echo $build_url($page - 1); ?>" class="btn btn-outline pagination-button">Previous</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <a href="<?php echo $build_url($i); ?>" class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-outline'; ?> pagination-number">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="<?php echo $build_url($page + 1); ?>" class="btn btn-outline pagination-button">Next</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
