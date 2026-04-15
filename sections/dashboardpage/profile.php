<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "Unauthorized access.";
    exit;
}

$fullName = $_SESSION['full_name'] ?? 'User';
$role     = $_SESSION['role'] ?? 'Staff';
$userId   = $_SESSION['user_id'] ?? 'N/A';
$initial  = strtoupper(substr($fullName, 0, 1));
?>

<div id="profileSection" class="hash-section">
    <div class="content-main p-4 md:p-6">
        <div class="max-w-4xl mx-auto">
            
            <div class="rounded-3xl bg-white border border-gray-200 shadow-sm overflow-hidden mb-6">
                <div class="h-24 md:h-32 bg-gradient-to-r from-[var(--ph-blue-dark)] via-[var(--ph-blue)] to-[var(--ph-blue-light)]"></div>
                
                <div class="px-4 md:px-8 pb-8">
                    <div class="relative flex flex-col md:flex-row justify-between items-center md:items-end -mt-12 md:-mt-16 mb-6 gap-4">
                        
                        <div class="flex flex-col md:flex-row items-center md:items-end gap-4 md:gap-6 text-center md:text-left">
                            <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl bg-white p-1 shadow-lg">
                                <div class="w-full h-full rounded-xl bg-gray-100 flex items-center justify-center text-3xl md:text-4xl font-black text-[var(--ph-blue-dark)] border-4 border-white">
                                    <?= $initial ?>
                                </div>
                            </div>
                            
                            <div class="pb-0 md:pb-2">
                                <h1 id="profileFullName" class="text-xl md:text-2xl font-bold text-gray-900"><?= htmlspecialchars($fullName) ?></h1>
                                <p class="text-xs md:text-sm font-medium text-emerald-600 uppercase tracking-wider"><?= htmlspecialchars($role) ?></p>
                            </div>
                        </div>

                        <button onclick="toggleEditProfile(true)" class="w-full md:w-auto px-6 py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-sm font-semibold transition flex items-center justify-center gap-2">
                            <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Profile
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div class="bg-gray-50/50 p-4 rounded-2xl md:bg-transparent md:p-0">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4"></i> Account Details
                            </h3>
                            <div class="space-y-4">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Email Address</span>
                                    <span class="text-sm font-medium text-gray-900 break-all" id="profileEmail">Loading...</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Contact Number</span>
                                    <span class="text-sm font-medium text-gray-900" id="profContact">Loading...</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">User ID</span>
                                    <span class="text-[10px] font-mono text-gray-400"><?= htmlspecialchars($userId) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 p-4 rounded-2xl md:bg-transparent md:p-0">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4"></i> Assigned Jurisdiction
                            </h3>
                            <div class="grid grid-cols-2 gap-y-4 gap-x-2">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Region</span>
                                    <span class="text-sm font-medium text-gray-900" id="profRegion">...</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Province</span>
                                    <span class="text-sm font-medium text-gray-900" id="profProvince">...</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">City/Muni</span>
                                    <span class="text-sm font-medium text-gray-900" id="profCity">...</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Barangay</span>
                                    <span class="text-sm font-medium text-gray-900" id="profBarangay">...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4 md:block">
                    <div class="w-12 h-12 md:w-10 md:h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center md:mb-4 shrink-0">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900" id="countResolutions">0</div>
                        <div class="text-xs text-gray-500 font-medium uppercase">Resolutions Handled</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>