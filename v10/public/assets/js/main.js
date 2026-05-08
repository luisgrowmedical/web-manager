/**
 * web-manager - Main JS
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('web-manager initialized.');

    // Auto-dismiss alerts or simple interactions
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Add active class to nav links based on URL (already handled by PHP but good to have)
    const currentPath = window.location.search;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href').includes(currentPath) && currentPath !== '') {
            link.classList.add('active');
        }
    });

    // Sidebar Toggle Logic
    const sidebar = document.getElementById('mainSidebar');
    const content = document.getElementById('mainContent');
    const toggle = document.getElementById('sidebarToggle');
    
    if (sidebar && toggle) {
        // Load saved state (default to true if not set)
        const savedState = localStorage.getItem('sidebarCollapsed');
        const isCollapsed = savedState === null ? true : savedState === 'true';
        
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            content.classList.add('expanded');
            toggle.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
        } else {
            sidebar.classList.remove('collapsed');
            content.classList.remove('expanded');
            toggle.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
        }

        toggle.addEventListener('click', () => {
            const nowCollapsed = sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
            localStorage.setItem('sidebarCollapsed', nowCollapsed);
            
            // Change icon
            if (nowCollapsed) {
                toggle.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
            } else {
                toggle.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
            }
        });
    }
});
