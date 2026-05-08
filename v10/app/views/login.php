<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | web-manager</title>
    <link rel="stylesheet" href="/web-manager/public/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="card auth-card">
        <div style="text-align: center; margin-bottom: 32px;">
            <div class="logo" style="justify-content: center; font-size: 24px; margin-bottom: 12px;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                <span>web-manager</span>
            </div>
            <p style="color: var(--text-muted);">Sign in to your account</p>
        </div>

        <?php if (isset($error)): ?>
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: var(--radius); margin-bottom: 20px; font-size: 14px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="/web-manager/public/index.php?route=login" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Sign In</button>
        </form>

        <div style="margin-top: 32px; text-align: center; font-size: 12px; color: var(--text-muted);">
            <p>&copy; <?php echo date('Y'); ?> web-manager. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
