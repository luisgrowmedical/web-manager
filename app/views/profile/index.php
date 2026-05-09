<div class="card profile-card">
    <h2>Edit My Profile</h2>
    <form action="/web-manager/public/index.php?route=profile" method="POST" enctype="multipart/form-data">
        <div class="profile-avatar-block">
            <?php if ($user['avatar']): ?>
                <img src="<?php echo $user['avatar']; ?>" alt="" class="avatar avatar-lg">
            <?php else: ?>
                <div class="avatar avatar-lg avatar-initial">
                    <?php echo substr($user['username'], 0, 1); ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="avatar" class="btn btn-outline upload-button">Change Photo</label>
                <input type="file" name="avatar" id="avatar" class="file-input-hidden" accept="image/*" data-avatar-preview>
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

        <div class="centered-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="/web-manager/public/index.php?route=dashboard" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
