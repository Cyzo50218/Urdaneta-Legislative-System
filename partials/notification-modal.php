<div id="notificationOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden flex items-center justify-end p-4 transition-all duration-300">
    <div class="bg-white w-full max-w-md h-fit max-h-[90vh] rounded-3xl shadow-2xl overflow-hidden animate-in slide-in-from-right-8 duration-200 flex flex-col">

        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <div class="text-[10px] font-bold text-[var(--ph-blue)] uppercase tracking-widest">System Alerts</div>
                <h2 class="text-xl font-bold text-gray-900">Notifications</h2>
            </div>
            <button onclick="toggleNotifications(false)" class="p-2 bg-transparent hover:bg-gray-100 rounded-full transition-all duration-200">
                <i data-lucide="x" class="w-6 h-6 text-[var(--primary-green)]"></i>
            </button>
        </div>

        <div id="notificationListContainer" class="overflow-y-auto p-4 space-y-3 custom-scrollbar min-h-[300px] flex flex-col">
            <div id="emptyNotificationState" class="flex-1 flex flex-col items-center justify-center py-12 text-center">
                <div class="bg-gray-50 p-4 rounded-full mb-4">
                    <i data-lucide="bell-off" class="w-8 h-8 text-gray-300"></i>
                </div>
                <p class="text-gray-500 font-medium">No notifications yet</p>
                <p class="text-gray-400 text-xs mt-1">We'll alert you when something happens.</p>
            </div>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex gap-2">
            <button
                onclick="markAllAsRead()"
                class="flex-1 py-2.5 text-xs font-bold text-gray-500 uppercase hover:text-gray-700 transition">
                Mark all as read
            </button>
            <button onclick="toggleNotifications(false)"
                class="flex-1 py-2.5 bg-transparent text-[var(--primary-green)] border border-[var(--primary-green)] rounded-xl text-xs font-bold uppercase tracking-tight hover:bg-[var(--primary-green)] hover:text-white transition-all duration-200">
                Close
            </button>
        </div>
    </div>
</div>

<div id="notificationDetailsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[110] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900" id="detailsTitle">Notification Details</h3>
            <button onclick="closeDetailsModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
        </div>
        <div class="p-8 flex flex-col items-center text-center">
            <div id="detailsIconContainer" 
                 class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4 shadow-sm"
                 style="background-color: var(--primary-green-light);">
            </div>
            <h2 id="detailsHeader" class="text-xl font-bold text-gray-900 mb-2"></h2>
            <p id="detailsMessage" class="text-gray-600 leading-relaxed mb-6"></p>
            <div id="detailsTime" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"></div>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100 flex flex-col gap-2">
            <div class="flex gap-3">
                <button onclick="closeDetailsModal()" class="flex-1 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition">Dismiss</button>
                
                <button id="detailsOpenFileBtn" 
                    class="flex-1 py-3 text-white rounded-xl text-sm font-bold text-center hover:opacity-90 transition hidden"
                    style="background-color: var(--primary-green);">
                    <i data-lucide="external-link" class="inline-block w-4 h-4 mr-1"></i> Open File
                </button>
            </div>
            
            <a id="detailsActionBtn" href="#" class="w-full py-3 bg-[var(--primary-green)] text-white rounded-xl text-sm font-bold text-center hover:opacity-90 transition hidden">View Assigned Tab</a>
        </div>
    </div>
</div>