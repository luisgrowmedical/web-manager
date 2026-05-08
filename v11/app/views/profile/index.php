<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2>Edit My Profile</h2>
    <form action="/web-manager/public/index.php?route=profile" method="POST" enctype="multipart/form-data">
        <div style="text-align: center; margin-bottom: 24px;">
            <?php if ($user['avatar']): ?>
                <img src="<?php echo $user['avatar']; ?>" alt="" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #e5e7eb; margin-bottom: 12px;">
            <?php else: ?>
                <div style="width: 100px; height: 100px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 32px; text-transform: uppercase; margin: 0 auto 12px;">
                    <?php echo substr($user['username'], 0, 1); ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="avatar" class="btn btn-outline" style="cursor: pointer; display: inline-block;">Change Photo</label>
                <input type="file" name="avatar" id="avatar" style="display: none;" accept="image/*">
            </div>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo $user['username']; ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password (Leave blank to keep current)</label>
            <input type="password" name="new_password" id="new_password" class="form-control">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Save Changes</button>
            <a href="/web-manager/public/index.php?route=dashboard" class="btn btn-outline" style="flex: 1; text-align: center;">Cancel</a>
        </div>
    </form>
</div>

<script>
    // Preview image before upload
    document.getElementById('avatar').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const label = document.querySelector('label[for="avatar"]');
            label.textContent = 'Selected: ' + this.files[0].name;
            label.style.background = '#dcfce7';
            label.style.borderColor = '#bcf0da';
        }
    });
</script>
