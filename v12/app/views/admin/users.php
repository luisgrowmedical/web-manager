<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Add User Form -->
    <div>
        <div class="card">
            <h2>Create New User</h2>
            <form action="/web-manager/public/index.php?route=admin&action=users" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_user" value="1">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select name="role_id" id="role_id" class="form-control" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['id']; ?>"><?php echo ucfirst($role['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="avatar">Profile Image</label>
                    <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                    <small style="color: var(--text-muted); font-size: 11px;">Optional. JPG, PNG or GIF.</small>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Create User</button>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <div>
        <div class="card">
            <h2>Existing Users</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td style="display: flex; align-items: center; gap: 12px;">
                                    <?php if ($u['avatar']): ?>
                                        <img src="<?php echo $u['avatar']; ?>" alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb;">
                                    <?php else: ?>
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; text-transform: uppercase;">
                                            <?php echo substr($u['username'], 0, 1); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?php echo $u['username']; ?></strong><br>
                                        <small style="color: var(--text-muted);"><?php echo $u['email']; ?></small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge <?php echo $u['role_name'] === 'admin' ? 'badge-success' : 'badge-warning'; ?>">
                                        <?php echo $u['role_name']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="/web-manager/public/index.php?route=admin&action=access&id=<?php echo $u['id']; ?>" class="btn btn-outline" style="padding: 4px 12px; font-size: 11px;">Site Access</a>
                                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                            <a href="/web-manager/public/index.php?route=admin&action=delete_user&id=<?php echo $u['id']; ?>" class="btn btn-outline" style="padding: 4px 12px; font-size: 11px; color: #dc2626; border-color: #fecaca;" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
