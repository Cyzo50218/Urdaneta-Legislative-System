function toggleNotifications(show) {
    const overlay = document.getElementById('notificationOverlay');
    const badge = document.querySelector('.notification-badge');

    if (show) {
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        if (badge) badge.style.display = 'none';
    } else {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
    }
}
document.querySelector('.notification-btn').addEventListener('click', function () {
    toggleNotifications(true);
});
document.getElementById('notificationOverlay').addEventListener('click', function (e) {
    if (e.target === this) {
        toggleNotifications(false);
    }
});

function truncateMessage(message, limit = 60) {
    if (message.length <= limit) return message;
    return message.substring(0, limit) + "...";
}

function loadNotifications() {
    renderNotificationList();
}

function renderNotificationList() {
    const container = document.getElementById('notificationListContainer');
    if (!container) return;

    if (globalNotifications.length > 0) {
        const html = globalNotifications.map(note => {
            const safeNote = JSON.stringify(note).replace(/'/g, "\\'");
            const shortMessage = truncateMessage(note.message, 30);

            return `
                <div onclick='openNotificationDetails(${safeNote})' 
                     class="p-4 rounded-2xl ${note.is_read ? 'bg-white border-gray-100' : 'bg-blue-50 border-blue-100 shadow-sm'} border transition cursor-pointer hover:shadow-md hover:border-blue-200">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                             style="background-color: ${getIconBg(note.type)}">
                                <i data-lucide="${getIconName(note.type)}" class="text-white w-5 h-5"></i>
                        </div>
                        <div class="space-y-1 overflow-hidden">
                            <p class="text-sm font-semibold text-gray-900">${note.title}</p>
                            <p class="text-xs text-gray-600 line-clamp-2">${shortMessage}</p>
                            <p class="text-[10px] text-gray-400 font-medium pt-1">Recently</p>
                        </div>
                    </div>
                </div>`;
        }).join('');

        container.innerHTML = html;
        if (window.lucide) lucide.createIcons();
    } else {
        showEmptyState();
    }
}

function showEmptyState() {
    const container = document.getElementById('notificationListContainer');
    container.innerHTML = `
        <div class="flex-1 flex flex-col items-center justify-center py-12 text-center">
            <div class="bg-gray-50 p-4 rounded-full mb-4">
                <i data-lucide="bell-off" class="w-8 h-8 text-gray-300"></i>
            </div>
            <p class="text-gray-500 font-medium">No notifications yet</p>
            <p class="text-gray-400 text-xs mt-1">We'll alert you when something happens.</p>
        </div>`;
    if (window.lucide) lucide.createIcons();
}
function getIconBg(type) {
    if (type === 'approved') return '#10b981';
    if (type === 'deadline') return '#f97316';
    return 'var(--primary-green)';
}

function getIconName(type) {
    if (type === 'approved') return 'check-circle';
    if (type === 'deadline') return 'clock';
    return 'bell';
}

function timeAgo(date) {
    return "Recently";
}
function toggleNotifications(show) {
    const overlay = document.getElementById('notificationOverlay');
    const badge = document.querySelector('.notification-badge');

    if (show) {
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        if (badge) badge.remove();

        loadNotifications();
    } else {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
    }
}

async function markAllAsRead() {
    try {
        globalNotifications = globalNotifications.map(n => ({ ...n, is_read: true }));
        globalUnreadCount = 0;
        renderNotificationList();

        const badge = document.querySelector('.notification-badge');
        if (badge) badge.remove();
        await fetch('/api/account/markAllAsRead.php', { method: 'POST' });

    } catch (error) {
        console.error('Failed to mark all as read:', error);
    }
}

async function markAsRead(id) {
    try {
        globalNotifications = globalNotifications.map(n =>
            n.id === id ? { ...n, is_read: 1 } : n
        );
        globalUnreadCount = globalNotifications.filter(n => !n.is_read).length;
        renderNotificationList();
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            if (globalUnreadCount > 0) {
                badge.textContent = globalUnreadCount;
            } else {
                badge.remove();
            }
        }
        const response = await fetch('/api/account/markAsRead.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        });

        const result = await response.json();
        if (!result.success) {
            console.error('Database update failed:', result.message);
        }
    } catch (error) {
        console.error('Error in markAsRead:', error);
    }
}

function openNotificationDetails(note) {
    const modal = document.getElementById('notificationDetailsModal');
    document.getElementById('detailsHeader').textContent = note.title;
    document.getElementById('detailsMessage').textContent = note.message;
    document.getElementById('detailsTime').textContent = note.created_at ? `Received: ${note.created_at}` : "Received: Recently";

    const iconContainer = document.getElementById('detailsIconContainer');
    iconContainer.className = "w-16 h-16 rounded-2xl flex items-center justify-center mb-4 shadow-lg";
    const bgColor = getIconBg(note.type);
    if (bgColor.startsWith('var') || bgColor.startsWith('#')) {
        iconContainer.style.backgroundColor = bgColor;
    } else {
        iconContainer.classList.add(bgColor);
    }
    iconContainer.innerHTML = `<i data-lucide="${getIconName(note.type)}" class="text-white w-8 h-8 animate-bell"></i>`;

    const openFileBtn = document.getElementById('detailsOpenFileBtn');
    const trackingMatch = note.message.match(/(RES|ORD)-[0-9]{4}-[0-9]+/i);

    if (trackingMatch && openFileBtn) {
        const trackingNo = trackingMatch[0];
        openFileBtn.classList.remove('hidden');
        openFileBtn.onclick = function () {
            closeDetailsModal();
            if (typeof openRecordDialog === "function") {
                openRecordDialog(trackingNo);
            }
        };
    } else if (openFileBtn) {
        openFileBtn.classList.add('hidden');
    }

    const actionBtn = document.getElementById('detailsActionBtn');
    if (note.link && note.link !== '#') {
        actionBtn.href = note.link;
        actionBtn.classList.remove('hidden');
    } else {
        actionBtn.classList.add('hidden');
    }

    modal.classList.remove('hidden');
    if (window.lucide) lucide.createIcons();

    if (!note.is_read) {
        markAsRead(note.id);
        if (typeof loadNotifications === "function") {
            loadNotifications();
        }
    }
}

function closeDetailsModal() {
    document.getElementById('notificationDetailsModal').classList.add('hidden');
}
document.getElementById('notificationDetailsModal').addEventListener('click', function (e) {
    if (e.target === this) closeDetailsModal();
});
document.querySelector('.notification-btn').addEventListener('click', () => toggleNotifications(true));