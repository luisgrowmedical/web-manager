<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h2 style="margin-bottom: 24px;">Manage Access for: <?php echo $user['username']; ?></h2>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px;">
            Select which sites this user is allowed to view and manage.
        </p>

        <form action="/web-manager/public/index.php?route=admin&action=access&id=<?php echo $user['id']; ?>" method="POST">
            <div class="form-group">
                <label style="margin-bottom: 12px; display: block;">Select Sites</label>
                <?php foreach ($all_sites as $site): ?>
                    <div style="display: flex; align-items: center; margin-bottom: 12px; padding: 12px; border: 1px solid var(--border-color); border-radius: var(--radius);">
                        <input type="checkbox" name="sites[]" value="<?php echo $site['id']; ?>" id="site_<?php echo $site['id']; ?>" style="width: 18px; height: 18px; margin-right: 12px;" <?php echo in_array($site['id'], $current_access) ? 'checked' : ''; ?>>
                        <label for="site_<?php echo $site['id']; ?>" style="margin: 0; cursor: pointer; flex: 1;">
                            <strong><?php echo $site['name']; ?></strong><br>
                            <small style="color: var(--text-muted);"><?php echo $site['url']; ?></small>
                        </label>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($all_sites)): ?>
                    <p style="text-align: center; color: var(--text-muted); padding: 20px;">No sites available. <a href="/web-manager/public/index.php?route=sites&action=add">Add a site first</a>.</p>
                <?php endif; ?>
            </div>

            <div style="margin-top: 32px; display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Access Permissions</button>
                <a href="/web-manager/public/index.php?route=admin&action=users" class="btn btn-outline" style="flex: 1;">Cancel</a>
            </div>
        </form>
    </div>
</div>
