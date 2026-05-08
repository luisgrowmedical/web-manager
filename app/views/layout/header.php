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
    <style>
        /* Sidebar Logo */
        .sidebar-header {
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }
        .user-info {
            margin-top: auto;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }
        
        /* Collapsed State Styles */
        .sidebar.collapsed {
            width: 80px;
            padding: 24px 15px;
        }
        .sidebar.collapsed .logo span,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .user-info p,
        .sidebar.collapsed .user-info .user-details,
        .sidebar.collapsed .logout-text {
            display: none;
        }
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
        }
        .sidebar.collapsed .nav-link svg {
            margin-right: 0 !important;
        }
        .sidebar.collapsed .sidebar-header {
            justify-content: center;
        }
        .sidebar.collapsed .user-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 0 0 0;
        }
        .sidebar.collapsed .user-info > div {
            margin-bottom: 0 !important;
            justify-content: center;
            width: 100%;
            gap: 0 !important;
        }
        .main-content.expanded {
            margin-left: 80px;
        }
        .sidebar.collapsed .logout-text {
            display: none;
        }
        .sidebar.collapsed .logout-link {
            justify-content: center;
        }
        .logout-link {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #dc2626;
            text-decoration: none;
            margin-top: 12px;
        }
        .logout-link svg {
            margin-right: 8px;
        }
        .sidebar.collapsed .logout-link svg {
            margin-right: 0;
        }
        
        .toggle-sidebar {
            position: absolute;
            bottom: 152px;
            right: -15px;
            width: 30px;
            height: 30px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 100;
        }
    </style>
</head>
<body class="has-sidebar">
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar collapsed" id="mainSidebar">
            <div class="sidebar-header">
                <a href="/web-manager/public/index.php" style="text-decoration: none;">
                    <div class="logo">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                        <span>web-manager</span>
                    </div>
                </a>
            </div>

            <ul class="nav-list">
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=dashboard" class="nav-link <?php echo ($_GET['route'] ?? '') === 'dashboard' ? 'active' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=sites" class="nav-link <?php echo ($_GET['route'] ?? '') === 'sites' ? 'active' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>
                        <span>Sites</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=seo" class="nav-link <?php echo ($_GET['route'] ?? '') === 'seo' ? 'active' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span>SEO</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=clients" class="nav-link <?php echo ($_GET['route'] ?? '') === 'clients' ? 'active' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        <span>Clients</span>
                    </a>
                </li>
                
                <li class="nav-item" style="margin-top: auto;">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/web-manager/public/index.php?route=admin&action=users" class="nav-link <?php echo ($_GET['route'] ?? '') === 'admin' ? 'active' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Users & Access</span>
                    </a>
                    <?php endif; ?>
                </li>
                <li class="nav-item">
                    <a href="/web-manager/public/index.php?route=profile" class="nav-link <?php echo ($_GET['route'] ?? '') === 'profile' ? 'active' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span>My Profile</span>
                    </a>
                </li>
            </ul>

            <div class="user-info">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <?php if (isset($_SESSION['avatar']) && $_SESSION['avatar']): ?>
                        <img src="<?php echo $_SESSION['avatar']; ?>" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; text-transform: uppercase;">
                            <?php echo substr($_SESSION['username'], 0, 1); ?>
                        </div>
                    <?php endif; ?>
                    <div class="user-details">
                        <p style="font-size: 13px; font-weight: 600; margin: 0;"><?php echo $_SESSION['username']; ?></p>
                        <p style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; margin: 0;"><?php echo $_SESSION['role']; ?></p>
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
                <div style="padding: 16px; border-radius: var(--radius); margin-bottom: 24px; background: <?php echo $flash['type'] === 'error' ? '#fee2e2' : '#dcfce7'; ?>; color: <?php echo $flash['type'] === 'error' ? '#b91c1c' : '#166534'; ?>; border: 1px solid <?php echo $flash['type'] === 'error' ? '#fca5a5' : '#bcf0da'; ?>;">
                    <?php echo $flash['text']; ?>
                </div>
            <?php endif; ?>
