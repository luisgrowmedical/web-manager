<div class="page-narrow">
    <div class="card">
        <h2>Manage Access for: <?php echo $user['username']; ?></h2>
        <p class="section-copy">
            Select which sites this user is allowed to view and manage.
        </p>

        <form action="/web-manager/public/index.php?route=admin&action=access&id=<?php echo $user['id']; ?>" method="POST">
            <div class="form-group">
                <label class="section-subtitle">Select Sites</label>
                <?php foreach ($all_sites as $site): ?>
                    <div class="access-option">
                        <input type="checkbox" name="sites[]" value="<?php echo $site['id']; ?>" id="site_<?php echo $site['id']; ?>" class="access-checkbox" <?php echo in_array($site['id'], $current_access) ? 'checked' : ''; ?>>
                        <label for="site_<?php echo $site['id']; ?>" class="access-label">
                            <strong><?php echo $site['name']; ?></strong><br>
                            <small class="muted-text"><?php echo $site['url']; ?></small>
                        </label>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($all_sites)): ?>
                    <p class="empty-state">No sites available. <a href="/web-manager/public/index.php?route=sites&action=add">Add a site first</a>.</p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Access Permissions</button>
                <a href="/web-manager/public/index.php?route=settings" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
