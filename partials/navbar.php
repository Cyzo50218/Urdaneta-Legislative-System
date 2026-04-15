<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$portalText = $isLoggedIn ? 'My Dashboard' : 'Admin Portal';
$portalLink = $isLoggedIn ? '/pages/adminpage/dashboard' : '/pages/loginportal';

$active = $active ?? '';
function navClass($key, $base = 'transition-colors px-3 py-2 font-medium flex items-center gap-2')
{
    return $base . ' ' . ($key === $GLOBALS['active']
        ? 'text-yellow-300'
        : 'text-white/90 hover:text-yellow-300');
}
function ariaCurrent($key)
{
    return $key === $GLOBALS['active'] ? 'aria-current="page"' : '';
}
?>
<link rel="stylesheet" href="/assets/css/systemLogo.css">


<nav class="gradient-border navheader-color sticky top-0 z-50 shadow-lg">
    <div class="max-w-8xl mx-auto px-7 sm:px-7 lg:px-9">
        <div class="flex justify-between items-center h-20">
            <div id="logo" class="flex items-center gap-2">
                <div class="ph-seal bg-white/20 p-1 rounded">
                    <i data-lucide="landmark" class="text-white w-5 h-5"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-white">Urdaneta City</span>
                    <span class="text-[10px] text-white/80">Legislative Info System</span>
                </div>
            </div>


            <!-- Desktop -->
            <div class="hidden md:flex items-center gap-2">
                <a href="/index" class="<?= navClass('home') ?>" <?= ariaCurrent('home') ?>>
                    <i data-lucide="home" class="w-4 h-4"></i> Home
                </a>
                <a href="/pages/landingpage/calendar" class="<?= navClass('calendar') ?>" <?= ariaCurrent('calendar') ?>>
                    <i data-lucide="calendar" class="w-4 h-4"></i> Calendar
                </a>
                <a href="/pages/landingpage/schedules" class="<?= navClass('schedules') ?>" <?= ariaCurrent('schedules') ?>>
                    <i data-lucide="clock" class="w-4 h-4"></i> Schedules
                </a>
                <a href="/pages/landingpage/legislation" class="<?= navClass('legislation') ?>" <?= ariaCurrent('legislation') ?>>
                    <i data-lucide="file-text" class="w-4 h-4"></i> Legislation
                </a>
                <a href="/pages/landingpage/aboutus" class="<?= navClass('aboutus') ?>" <?= ariaCurrent('aboutus') ?>>
                    <i data-lucide="users" class="w-4 h-4"></i> About Us
                </a>
                <a href="/pages/landingpage/resources" class="<?= navClass('resources') ?>" <?= ariaCurrent('resources') ?>>
                    <i data-lucide="book-open" class="w-4 h-4"></i> Resources
                </a>
                <a href="/pages/landingpage/localgovcode" class="<?= navClass('localgovcode') ?>" <?= ariaCurrent('localgovcode') ?>>
                    <i data-lucide="landmark" class="w-4 h-4"></i> Local Government Code
                </a>

            </div>

            <!-- CTA -->
            <div class="hidden md:flex items-center gap-3">
                <a href="<?= $portalLink ?>"
                    class="bg-yellow-400 hover:bg-yellow-300 px-6 py-2.5 rounded-lg font-medium shadow-md flex items-center gap-2 transition-all no-underline text-gray-800">
                    <i data-lucide="<?= $isLoggedIn ? 'layout-dashboard' : 'user-circle' ?>" class="w-4 h-4"></i>
                    <span><?= $portalText ?></span>
                </a>
                <br>
                <?php if ($isLoggedIn): ?>
                    <a href="/api/loginportal/logout.php" class="text-white/70 hover:text-white text-sm underline decoration-white/30">Logout</a>
                <?php endif; ?>
            </div>


            <!-- Mobile toggle -->
            <button class="md:hidden p-2" onclick="toggleMobileMenu()">
                <i data-lucide="menu" class="text-white w-6 h-6"></i>
            </button>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="md:hidden py-4 border-t border-white/20 navheader-color" style="display:none;">
            <div class="flex flex-col gap-3">
                <a href="/index" class="text-white hover:text-yellow-300 transition-colors py-2 flex items-center gap-2">
                    <i data-lucide="home" class="w-4 h-4"></i> Home
                </a>
                <a href="/pages/landingpage/calendar" class="text-white hover:text-yellow-300 transition-colors px-3 py-2 font-medium flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i> Calendar
                </a>
                <a href="/pages/landingpage/schedules" class="text-white hover:text-yellow-300 transition-colors px-3 py-2 font-medium flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4"></i> Schedules
                </a>
                <a href="/pages/landingpage/legislation" class="text-white hover:text-yellow-300 transition-colors py-2 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Legislation
                </a>
                <a href="/pages/landingpage/aboutus" class="text-white hover:text-yellow-300 transition-colors py-2 flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4"></i> About Us
                </a>
                <a href="/pages/landingpage/resources" class="text-white hover:text-yellow-300 transition-colors py-2 flex items-center gap-2">
                    <i data-lucide="book-open" class="w-4 h-4"></i> Resources
                </a>
                <a href="/pages/landingpage/localgovcode" class="text-white hover:text-yellow-300 transition-colors py-2 flex items-center gap-2">
                    <i data-lucide="landmark" class="w-4 h-4"></i> Local Government Code
                </a>

                <a href="<?= $portalLink ?>"
                    class="bg-yellow-400 hover:bg-yellow-300 px-6 py-2.5 rounded-lg font-medium w-full mt-2 flex items-center justify-center gap-2 transition-all no-underline text-gray-800">
                    <i data-lucide="<?= $isLoggedIn ? 'layout-dashboard' : 'user-circle' ?>" class="w-4 h-4"></i>
                    <span><?= $portalText ?></span>
                </a>

                <?php if ($isLoggedIn): ?>
                    <a href="/api/loginportal/logout.php" class="text-center text-red-300 py-2 text-sm">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>