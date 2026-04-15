const mainContentEl = document.getElementById('mainContent');
const pageTitleEl = document.querySelector('.page-title');
let dashboardInterval = null;

const PAGE_TITLES = {
    dashboard: 'Dashboard',
    assigned: 'My Assigned Reso & Ords',
    localgovcode: 'Local Government Code E-Library',
    calendarEvents: 'Sessions & Hearings',
    documents: 'Documents & Attachments',
    tasks: 'Task List / Work Queue',
    messages: 'Messages / Requests',
    profile: 'Profile Info'
};

let notificationPollingInterval = null;
let globalNotifications = [];
let globalUnreadCount = 0;

function startGlobalNotificationPolling() {
    checkUnreadNotifications();
    notificationPollingInterval = setInterval(checkUnreadNotifications, 5000);
}

async function checkUnreadNotifications() {
    const btn = document.querySelector('.notification-btn');
    if (!btn) return;

    try {
        const response = await fetch('/api/account/fetchNotificationList.php');
        const data = await response.json();

        if (data.success) {
            globalNotifications = data.notifications;
            globalUnreadCount = data.unread_count;
            let badge = btn.querySelector('.notification-badge');

            if (globalUnreadCount > 0) {
                const overlay = document.getElementById('notificationOverlay');
                if (overlay && overlay.classList.contains('hidden')) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'notification-badge animate-bounce-short';
                        btn.appendChild(badge);
                    }
                    badge.textContent = globalUnreadCount;
                    badge.style.display = 'flex';
                }
            } else {
                if (badge) badge.remove();
            }
            const overlay = document.getElementById('notificationOverlay');
            if (overlay && !overlay.classList.contains('hidden')) {
                renderNotificationList();
            }
        }
    } catch (error) {
        console.error('Polling Error:', error);
    }
}

function setActiveMenu(link, event) {
    if (event) event.preventDefault();

    const page = link.dataset.page;
    if (!page) return;
    document
        .querySelectorAll('.sidebar-nav .nav-item')
        .forEach(item => item.classList.remove('active'));
    link.classList.add('active');

    loadPage(page, true);
    if (window.innerWidth <= 768) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        const toggleIcon = document.getElementById('toggleIcon');

        if (sidebar) {
            sidebar.classList.add('closed');
            sidebar.classList.remove('open');
        }
        if (overlay) {
            overlay.classList.remove('show');
        }
        // reset burger icon state
        if (toggleIcon) {
            toggleIcon.innerHTML = `
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6"  x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            `;
        }
    }
}
function loadPage(page, pushToHistory = false) {
    if (!page) page = 'dashboard';
    const url = '../../sections/dashboardpage/' + page + '.php';

    mainContentEl.classList.add('is-loading');
    mainContentEl.innerHTML = '<div class="loading-state">Loading…</div>';

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status + ' → ' + url);
            }
            return response.text();
        })
        .then(html => {
            mainContentEl.classList.remove('is-loading');
            mainContentEl.innerHTML = html;

            const title = PAGE_TITLES[page] || 'Urdaneta LIS';
            if (pageTitleEl) pageTitleEl.textContent = title;

            if (window.lucide && typeof lucide.createIcons === 'function') {
                lucide.createIcons();
            }
            if (page === 'dashboard') {
                updateDashboardLabels();
                loadMyUpcomingHearings();
                initDashboardPage();
            } else {
                if (typeof dashboardInterval !== 'undefined' && dashboardInterval) {
                    clearInterval(dashboardInterval);
                    dashboardInterval = null;
                    console.log("Dashboard background updates stopped.");
                }
            }
            if (page === 'assigned' && typeof initUsedAssigned === 'function') {
                initUsedAssigned();
                renderMyAssignedOnly();
            }
            if (page === 'localgovcode') {
                if (typeof window.initMagazineEngine === 'function') {
                    setTimeout(window.initMagazineEngine, 100);
                }
            }

            if (page === 'calendarEvents') {
                if (typeof window.initCalendarUI === 'function') {
                    window.initCalendarUI();
                    window.startCalendarPolling();
                }
            } else {
                if (typeof window.stopCalendarPolling === 'function') {
                    window.stopCalendarPolling();
                }
            }

            if (page === 'taskList') {
                if (typeof TaskManager !== 'undefined') TaskManager.init();
            } else {
                if (typeof TaskManager !== 'undefined' && typeof TaskManager.stopPolling === 'function') {
                    TaskManager.stopPolling();
                }
            }

            if (page === 'profile') {
                if (typeof window.loadUserProfile === 'function') {
                    window.loadUserProfile();
                }
            }

            if (pushToHistory) {
                history.pushState({ page: page }, '', '#' + page);
            }
        })
        .catch(err => {
            mainContentEl.classList.remove('is-loading');
            console.error('Page load error:', err);
            mainContentEl.innerHTML =
                '<div class="error-state">Unable to load page "' + page + '"</div>';
        });
}



window.addEventListener('popstate', function (event) {
    const page = (event.state && event.state.page) ||
        (location.hash ? location.hash.substring(1) : 'dashboard');
    const targetLink = document.querySelector(
        '.sidebar-nav .nav-item[data-page="' + page + '"]'
    );
    if (targetLink) {
        document.querySelectorAll('.sidebar-nav .nav-item')
            .forEach(item => item.classList.remove('active'));
        targetLink.classList.add('active');

    }

    loadPage(page, false);
});

document.addEventListener('DOMContentLoaded', function () {
    startGlobalNotificationPolling();
    let initialPage = location.hash ? location.hash.substring(1) : 'dashboard';

    const initialLink = document.querySelector(
        '.sidebar-nav .nav-item[data-page="' + initialPage + '"]'
    );

    if (!initialLink) {
        initialPage = 'dashboard';
    }
    document
        .querySelectorAll('.sidebar-nav .nav-item')
        .forEach(item => item.classList.remove('active'));

    const activeLink = document.querySelector(
        '.sidebar-nav .nav-item[data-page="' + initialPage + '"]'
    );
    if (activeLink) activeLink.classList.add('active');

    loadPage(initialPage, false);
});
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);

    document.getElementById('btnLight').classList.toggle('active', theme === 'light');
    document.getElementById('btnDark').classList.toggle('active', theme === 'dark');
}

function toggleUserDropdown() {
    const dd = document.getElementById('userDropdown');
    dd.classList.toggle('open');
}