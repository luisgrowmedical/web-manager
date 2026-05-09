<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'web-manager'; ?> | web-manager</title>
    <?php $style_version = filemtime(__DIR__ . '/../../../public/assets/css/style.css'); ?>
    <link rel="stylesheet" href="<?php echo wm_asset_url('css/style.css'); ?>?v=<?php echo $style_version; ?>">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="has-sidebar">
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar collapsed" id="mainSidebar">
            <div class="sidebar-header">
                <a href="/web-manager/public/index.php" class="logo-link">
                    <div class="logo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                        <span>web-manager</span>
                    </div>
                </a>
            </div>

            <ul class="nav-list">
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=dashboard" class="nav-link <?php echo ($_GET['route'] ?? '') === 'dashboard' ? 'active' : ''; ?>">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=sites" class="nav-link <?php echo ($_GET['route'] ?? '') === 'sites' ? 'active' : ''; ?>">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>
                        <span>Sites</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=seo" class="nav-link <?php echo ($_GET['route'] ?? '') === 'seo' ? 'active' : ''; ?>">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span>SEO</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=clients" class="nav-link <?php echo ($_GET['route'] ?? '') === 'clients' ? 'active' : ''; ?>">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        <span>Clients</span>
                    </a>
                </li>
                
                <li class="nav-item nav-item-spacer">
                    <a href="/web-manager/public/index.php?route=profile" class="nav-link <?php echo ($_GET['route'] ?? '') === 'profile' ? 'active' : ''; ?>">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>My Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/web-manager/public/index.php?route=settings" class="nav-link <?php echo in_array(($_GET['route'] ?? ''), ['settings', 'admin'], true) ? 'active' : ''; ?>">
                        <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span>Settings</span>
                    </a>
                    <?php endif; ?>
                </li>
            </ul>

            <div class="user-info">
                <div class="user-summary">
                    <?php if (isset($_SESSION['avatar']) && $_SESSION['avatar']): ?>
                        <img src="<?php echo $_SESSION['avatar']; ?>" alt="" class="avatar avatar-sm">
                    <?php else: ?>
                        <div class="avatar avatar-sm avatar-initial">
                            <?php echo substr($_SESSION['username'], 0, 1); ?>
                        </div>
                    <?php endif; ?>
                    <div class="user-details">
                        <p class="user-name"><?php echo $_SESSION['username']; ?></p>
                        <p class="user-role"><?php echo $_SESSION['role']; ?></p>
                    </div>
                </div>
                <a href="/web-manager/public/index.php?route=logout" class="logout-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span class="logout-text">Logout</span>
                </a>
            </div>

            <div class="toggle-sidebar" id="sidebarToggle">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content expanded" id="mainContent">
            <header class="top-header">
                <h1><?php echo $title ?? 'web-manager'; ?></h1>
                <?php if (isset($header_action)): ?>
                    <?php echo $header_action; ?>
                <?php endif; ?>
            </header>

            <?php $flash = get_message(); if ($flash): ?>
                <div class="flash-message <?php echo $flash['type'] === 'error' ? 'flash-error' : 'flash-success'; ?>">
                    <?php echo $flash['text']; ?>
                </div>
            <?php endif; ?>
