<aside class="sidebar dark" id="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-header">
            <div class="ph-seal-with-text flex items-center gap-4 ml-2">
                <div class="ph-seal-smaller bg-white/20">
                    <i data-lucide="landmark" class="text-white w-5 h-5"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-bold text-white">Urdaneta City</span>
                    <span class="text-xs text-white/80">Legislative Information System</span>
                </div>
            </div>
            <button class="sidebar-pin" title="Collapse sidebar" onclick="toggleSidebar()">
                <i data-lucide="chevron-left" class="pin-icon"></i>
            </button>

        </div>

        <!--
        <div class="sidebar-search">
            <span class="sidebar-search-icon"><i data-lucide="search" class="icon-sm"></i></span>
            <input type="text" placeholder="Search menu…" />
        </div>
-->
        <div class="sidebar-section-title">Menu</div>

        <nav class="sidebar-nav">
            <a href="#dashboard"
                class="nav-item active"
                data-page="dashboard"
                onclick="setActiveMenu(this, event)">
                <span class="nav-icon-wrapper">
                    <i data-lucide="layout-dashboard" class="icon"></i>
                </span>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="#assigned"
                class="nav-item"
                data-page="assigned"
                onclick="setActiveMenu(this, event)">
                <span class="nav-icon-wrapper">
                    <i data-lucide="file-check-2" class="icon"></i>
                </span>
                <span class="nav-text">My Assigned Reso &amp; Ords</span>
            </a>

            <a href="#localgovcode"
                class="nav-item"
                data-page="localgovcode"
                onclick="setActiveMenu(this, event)">
                <span class="nav-icon-wrapper">
                    <i data-lucide="files" class="icon"></i>
                </span>
                <span class="nav-text">E-Library Legislative Documents</span>
            </a>

            <a href="#calendarEvents"
                class="nav-item"
                data-page="calendarEvents"
                onclick="setActiveMenu(this, event)">
                <span class="nav-icon-wrapper">
                    <i data-lucide="calendar-range" class="icon"></i>
                </span>
                <span class="nav-text">Sessions &amp; Hearings</span>
            </a>

            <a href="#taskList"
                class="nav-item"
                data-page="taskList"
                onclick="setActiveMenu(this, event)">
                <span class="nav-icon-wrapper">
                    <i data-lucide="list-checks" class="icon"></i>
                </span>
                <span class="nav-text">Task List / Work Queue</span>
            </a>

          <!--  <a href="#messages"
                class="nav-item"
                data-page="messages"
                onclick="setActiveMenu(this, event)">
                <span class="nav-icon-wrapper">
                    <i data-lucide="messages-square" class="icon"></i>
                </span>
                <span class="nav-text">Messages / Requests</span>
            </a> -->

            <a href="#profile"
                class="nav-item"
                data-page="profile"
                onclick="setActiveMenu(this, event)">
                <span class="nav-icon-wrapper">
                    <i data-lucide="user-round-cog" class="icon"></i>
                </span>
                <span class="nav-text">Profile</span>
            </a>
        </nav>


        <div class="sidebar-divider"></div>

        <div id="themeTxt">Theme</div>
        <div class="theme-toggle">
            <button id="btnLight" class="active" onclick="setTheme('light')">
                <i data-lucide="sun" class="theme-icon"></i>
                <span class="theme-text">Light</span>
            </button>

            <button id="btnDark" onclick="setTheme('dark')">
                <i data-lucide="moon" class="theme-icon"></i>
                <span class="theme-text">Dark</span>
            </button>
        </div>


        <?php
        $fullName = $_SESSION['full_name'] ?? 'Staff';
        $role     = $_SESSION['role'] ?? 'Legislative Staff';
        $avatarInitial = strtoupper(substr($fullName, 0, 2)); // first 2 letters for avatar
        ?>
        <div class="user-card">
            <div class="user-avatar"><?= htmlspecialchars($avatarInitial) ?></div>
            <div class="user-meta">
                <div class="user-name"><?= htmlspecialchars($fullName) ?></div>
                <div class="user-role"><?= htmlspecialchars(ucfirst($role)) ?></div>
            </div>
        </div>


    </div>
</aside>