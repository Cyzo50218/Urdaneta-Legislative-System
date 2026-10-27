<?php
define('APP_ENV', 'dev');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

$timeout_duration = 1800; // 30 minutes

if (!isset($_SESSION['user_id'])) {
    header("Location: /api/loginportal/logout.php");
    exit;
}
if (isset($_SESSION['last_login']) && (time() - $_SESSION['last_login'] > $timeout_duration)) {
    header("Location: /api/loginportal/logout.php?error=timeout");
    exit;
}

$_SESSION['last_login'] = time();

require('../../api/connections/connection.php');
?>
<?php include __DIR__ . '/../../partials/_page_head_dashboard.php'; ?>

<body>

    <script>
        window.LIS_SYSTEM = {
            name: <?= json_encode($_SESSION['systemName'] ?? 'City Government') ?>,
            location: <?= json_encode($_SESSION['systemLocation'] ?? '') ?>
        };

        window.USER_SESSION = {
            full_name: <?php echo json_encode($_SESSION['full_name'] ?? 'Guest'); ?>,
            role: <?php echo json_encode($_SESSION['role'] ?? 'None'); ?>
        };

        

        window.refreshHeaderUI = function() {
            const session = window.USER_SESSION;
            if (!session) return;
            const nameEl = document.getElementById('headerUserName');
            if (nameEl) nameEl.textContent = session.full_name;
            const avatarEl = document.getElementById('headerAvatar');
            if (avatarEl && session.full_name) {
                avatarEl.textContent = session.full_name.charAt(0).toUpperCase();
            }
        };
    </script>
    <div class="dashboard-container">

        <?php include __DIR__ . '/../../partials/ai-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/grid-setup-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/hyperlink-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/record-options-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/profile-edit-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/notification-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/add-record-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/add-confirmaction-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/delete-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/delete-modal-task.php'; ?>
        <?php include __DIR__ . '/../../partials/reassign-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/search-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/govcode-admin-modal.php'; ?>
        <?php include __DIR__ . '/../../partials/calendar-edit-modal.php'; ?>

        <div class="record-modal-backdrop" id="recordModal">
            <input type="file" id="recordFileInput" multiple style="display:none" />
            <div id="printContainer" style="display:none"></div>

            <div class="record-modal-dialog">
                <div class="record-modal-header">
                    <div>
                        <div class="record-modal-label">Resolution / Ordinance Details</div>
                        <h2 class="record-modal-title" id="recordModalTitle"></h2>
                        <span class="record-code-pill" id="recordModalCode"></span>

                    </div>
                    <div class="record-modal-right">
                        <button class="record-modal-close" type="button" onclick="closeRecordDialog()">
                            <i data-lucide="x"></i>
                        </button>
                    </div>
                </div>

                <div class="record-modal-body" id="recordModalBody">

                    <!-- filled by JS -->
                </div>

                <div class="record-modal-footer">

                    <!-- feature buttons (role-based) -->
                    <div class="record-footer-actions" id="recordFooterActions"></div>

                    <span class="record-footer-spacer"></span>

                    <button class="record-btn-secondary"
                        type="button"
                        onclick="onAISummaryClicked()">
                        <i data-lucide="sparkles"></i>
                        AI Summary
                    </button>

                    <button class="record-btn-primary" type="button" onclick="closeRecordDialog()">
                        Close
                    </button>
                </div>
            </div>

        </div>

        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

        <?php include __DIR__ . '/../../partials/dashboard_sidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="main-wrapper">
            <!-- Top Header Bar -->
            <header class="top-header">
                <div class="header-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <svg class="icon" id="toggleIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <h2 class="page-title">Dashboard</h2>
                </div>

                <div class="header-right">
                    <button class="notification-btn" style="position: relative;">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </button>


                    <div class="user-menu">
                        <button class="user-btn" onclick="toggleUserDropdown()">
                            <div class="user-avatar" id="headerAvatar"><?= htmlspecialchars($avatarInitial) ?></div>
                            <span class="user-label" id="headerUserName"><?= htmlspecialchars($fullName) ?></span>
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="#profile">My Profile</a>
                            <a href="#logout">Logout</a>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content" id="mainContent">

            </main>

        </div>

        <!-- Floating AI Assistant Button -->
        <button class="floating-ai-btn" onclick="openAssistantModal()" title="AI Assistant">
            <svg class="icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"></path>
                <path d="M5 3v4"></path>
                <path d="M19 17v4"></path>
                <path d="M3 5h4"></path>
                <path d="M17 19h4"></path>
            </svg>
        </button>
        <?php include __DIR__ . '/../../partials/toast.php'; ?>
        <?php include __DIR__ . '/../../partials/ai-modal.php'; ?>
    </div>

    <script>
        let idleTimer;

        function resetTimer() {
            clearTimeout(idleTimer);
            // 1800000ms = 30 minutes
            idleTimer = setTimeout(() => {
                if (typeof showToast === 'function') {
                    showToast("Session expired due to inactivity.", "error", "System");
                }
                setTimeout(() => {
                    window.location.href = "/api/loginportal/logout.php?reason=idle";
                }, 1500);
            }, 1800000);
        }
        ['onload', 'onmousemove', 'onmousedown', 'onclick', 'onkeydown', 'ontouchstart'].forEach(evt => {
            window[evt] = resetTimer;
        });

        <?php include __DIR__ . '/../../assets/js/dashboard/sidebar.js'; ?>
        <?php include __DIR__ . '/../../assets/js/dashboard/dashboard-navigation.js'; ?>
    </script>
    <script src="/../../assets/js/dashboard/header.js"></script>
    <script src="/../../assets/js/dashboard/dashboard/dashboardCards.js"></script>
    <script src="/../../assets/js/dashboard/localgovcode/localGovCode.js"></script>
    <script src="/../../assets/js/dashboard/localgovcode/localGovCode_Actions.js"></script>
    <script src="/../../assets/js/dashboard/tasklist/taskList.js"></script>
    <script src="/../../assets/js/dashboard/calendar/calendarEvents.js"></script>
    <script src="/../../assets/js/dashboard/calendar/calendarEventModal.js"></script>
    <script type="module" src="/../../assets/js/dashboard/profile/profileInfo.js"></script>
    <script src="/../../assets/js/dashboard/menuDropdown.js"></script>
    <script src="/assets/js/api/fetchMyAssignedResolutionCount.js"></script>
    <script src="/assets/js/api/fetchMyAssignedOrdinanceCount.js"></script>
    <script src="/assets/js/api/fetchUpcomingHearings.js"></script>
    <script src="/assets/js/api/loadDashboardDataFast.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/records_state.js"></script>

    <!-- LOADED HELPER -->
    <script src="/../../assets/js/helpers/dateHelpers.js"></script>

    <script src="/assets/js/api/tasks/loadTasksList.js"></script>
    <script src="/assets/js/api/fetchMyTasks.js"></script>



    <script src="/../../assets/js/dashboard/record_modal_features/table_assigned.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/timeline_calendar.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/modal_attachments.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/modal_dialog.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/init_dashboard.js"></script>

    <!-- RECORD DIALOG MODAL FEATURES -->
    <script src="/../../assets/js/dashboard/quickActionsTable.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/actions/record_view_setup.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/actions/record_support_actions.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/actions/record_edit_actions.js"></script>
    <script src="/../../assets/js/dashboard/record_modal_features/actions/record_search_actions.js"></script>

</body>

</html>