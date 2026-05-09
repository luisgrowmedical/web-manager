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

    enhanceCountrySelects();
    initDashboardUpdatesModule();
});

function initDashboardUpdatesModule() {
    const modules = document.querySelectorAll('[data-updates-module]');

    modules.forEach(module => {
        const tabs = module.querySelectorAll('[data-updates-tab]');
        const panels = module.querySelectorAll('[data-updates-panel]');

        const updateCount = type => {
            const checks = Array.from(module.querySelectorAll(`.updates-item-check[data-updates-item="${type}"]`));
            const selected = checks.filter(check => check.checked).length;
            const counter = module.querySelector(`[data-updates-selected-count="${type}"]`);
            const selectAll = module.querySelector(`[data-updates-select-all="${type}"]`);

            if (counter) {
                counter.textContent = `${selected} selected`;
            }

            if (selectAll) {
                selectAll.checked = checks.length > 0 && selected === checks.length;
                selectAll.indeterminate = selected > 0 && selected < checks.length;
            }
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const type = tab.dataset.updatesTab;

                tabs.forEach(item => {
                    const isActive = item === tab;
                    item.classList.toggle('active', isActive);
                    item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                panels.forEach(panel => {
                    panel.classList.toggle('active', panel.dataset.updatesPanel === type);
                });
            });
        });

        module.querySelectorAll('.updates-select-all').forEach(selectAll => {
            selectAll.addEventListener('change', () => {
                const type = selectAll.dataset.updatesSelectAll;
                module.querySelectorAll(`.updates-item-check[data-updates-item="${type}"]`).forEach(check => {
                    check.checked = selectAll.checked;
                });
                updateCount(type);
            });
        });

        module.querySelectorAll('.updates-item-check').forEach(check => {
            check.addEventListener('change', () => updateCount(check.dataset.updatesItem));
        });

        ['plugins', 'themes', 'wordpress'].forEach(updateCount);
    });
}

function enhanceCountrySelects() {
    const countrySelects = document.querySelectorAll('select[data-country-select]');

    countrySelects.forEach(select => {
        if (select.dataset.countrySelectEnhanced === 'true') {
            return;
        }

        const shell = document.createElement('div');
        shell.className = 'country-select-shell';

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'country-select-button';
        button.setAttribute('aria-haspopup', 'listbox');
        button.setAttribute('aria-expanded', 'false');

        const selectedContent = document.createElement('span');
        selectedContent.className = 'country-select-selected';

        const arrow = document.createElement('span');
        arrow.className = 'country-select-arrow';
        arrow.setAttribute('aria-hidden', 'true');
        arrow.textContent = '▾';

        const list = document.createElement('div');
        list.className = 'country-select-menu';
        list.setAttribute('role', 'listbox');
        list.hidden = true;

        select.classList.add('country-select-native');
        select.parentNode.insertBefore(shell, select);
        shell.appendChild(select);
        shell.appendChild(button);
        button.appendChild(selectedContent);
        button.appendChild(arrow);
        shell.appendChild(list);
        select.dataset.countrySelectEnhanced = 'true';

        const makeFlag = option => {
            const flagSrc = option?.dataset.flagSrc || '';
            if (!flagSrc) {
                return null;
            }

            const img = document.createElement('img');
            img.className = 'country-select-flag';
            img.src = flagSrc;
            img.alt = option.dataset.flagAlt || '';
            img.onerror = () => {
                const fallbackSrc = option.dataset.flagFallback || '';
                if (fallbackSrc && img.src !== fallbackSrc) {
                    img.src = fallbackSrc;
                } else {
                    img.remove();
                }
            };

            return img;
        };

        const setButtonContent = () => {
            const selectedOption = select.options[select.selectedIndex];
            selectedContent.replaceChildren();

            const flag = makeFlag(selectedOption);
            if (flag) {
                selectedContent.appendChild(flag);
            }

            const text = document.createElement('span');
            text.className = 'country-select-text';
            text.textContent = selectedOption?.textContent || '';
            selectedContent.appendChild(text);

            list.querySelectorAll('.country-select-option').forEach(item => {
                item.setAttribute('aria-selected', item.dataset.value === select.value ? 'true' : 'false');
            });
        };

        const closeMenu = () => {
            list.hidden = true;
            shell.classList.remove('is-open');
            button.setAttribute('aria-expanded', 'false');
        };

        const openMenu = () => {
            list.hidden = false;
            shell.classList.add('is-open');
            button.setAttribute('aria-expanded', 'true');
        };

        const chooseOption = option => {
            select.value = option.value;
            setButtonContent();
            closeMenu();
            select.dispatchEvent(new Event('change', { bubbles: true }));
            button.focus();
        };

        Array.from(select.children).forEach(child => {
            if (child.tagName === 'OPTGROUP') {
                const groupLabel = document.createElement('div');
                groupLabel.className = 'country-select-group';
                groupLabel.textContent = child.label;
                list.appendChild(groupLabel);

                Array.from(child.children).forEach(option => {
                    list.appendChild(createCountryOption(option, makeFlag, select, chooseOption));
                });
                return;
            }

            if (child.tagName === 'OPTION') {
                list.appendChild(createCountryOption(child, makeFlag, select, chooseOption));
            }
        });

        button.addEventListener('click', () => {
            if (list.hidden) {
                openMenu();
            } else {
                closeMenu();
            }
        });

        button.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                closeMenu();
            }

            if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openMenu();
                const selectedItem = list.querySelector('[aria-selected="true"]') || list.querySelector('.country-select-option');
                selectedItem?.focus();
            }
        });

        list.addEventListener('keydown', event => {
            const options = Array.from(list.querySelectorAll('.country-select-option'));
            const currentIndex = options.indexOf(document.activeElement);

            if (event.key === 'Escape') {
                closeMenu();
                button.focus();
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                options[Math.min(currentIndex + 1, options.length - 1)]?.focus();
            }

            if (event.key === 'ArrowUp') {
                event.preventDefault();
                options[Math.max(currentIndex - 1, 0)]?.focus();
            }

            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                document.activeElement?.click();
            }
        });

        document.addEventListener('click', event => {
            if (!shell.contains(event.target)) {
                closeMenu();
            }
        });

        select.addEventListener('change', setButtonContent);
        setButtonContent();
    });
}

function createCountryOption(option, makeFlag, select, chooseOption) {
    const item = document.createElement('button');
    item.type = 'button';
    item.className = 'country-select-option';
    item.setAttribute('role', 'option');
    item.setAttribute('aria-selected', option.selected ? 'true' : 'false');
    item.dataset.value = option.value;
    item.tabIndex = -1;

    const flag = makeFlag(option);
    if (flag) {
        item.appendChild(flag);
    }

    const text = document.createElement('span');
    text.className = 'country-select-text';
    text.textContent = option.textContent;
    item.appendChild(text);

    item.addEventListener('click', () => {
        Array.from(select.options).forEach(selectOption => {
            selectOption.selected = selectOption === option;
        });

        item.parentNode.querySelectorAll('.country-select-option').forEach(optionNode => {
            optionNode.setAttribute('aria-selected', 'false');
        });
        item.setAttribute('aria-selected', 'true');
        chooseOption(option);
    });

    return item;
}
