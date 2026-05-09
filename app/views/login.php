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
        <div class="auth-header">
            <div class="logo auth-logo">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                <span>web-manager</span>
            </div>
            <p class="auth-copy">Sign in to your account</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="auth-error">
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
            <button type="submit" class="btn btn-primary auth-submit">Sign In</button>
        </form>

        <div class="auth-footer">
            <p>&copy; <?php echo date('Y'); ?> web-manager. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
