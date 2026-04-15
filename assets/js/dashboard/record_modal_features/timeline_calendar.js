// HELPERS ADDED.
function formatTimeAgo(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    if (seconds < 60) return "Just now";
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes} minutes ago`;
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours} hours ago`;
    const days = Math.floor(hours / 24);
    if (days === 1) return "Yesterday";
    if (days < 7) return `${days} days ago`;
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
function getEventColor(type) {
    switch (type) {
        case 'committee': return 'blue';
        case 'session': return 'green';
        case 'public': return 'orange';
        default: return 'gray';
    }
}
function formatTime(time24) {
    const [h, m] = time24.split(':').map(Number);
    const ampm = h >= 12 ? 'PM' : 'AM';
    const hour12 = h % 12 === 0 ? 12 : h % 12;
    return `${hour12}:${m.toString().padStart(2, '0')} ${ampm}`;
}

let recentActivity = [];
let legislativeEvents = {};
let currentCalendarShownCount = 3;

async function loadActivityFeed() {
    try {
        const response = await fetch('/api/activity/fetchRecentActivityList.php');

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success && Array.isArray(result.data)) {
            recentActivity = result.data.map(item => ({
                time: formatTimeAgo(item.timestamp),
                text: item.text.replace(
                    /(#\d+|'.*?')/g,
                    '<strong>$1</strong>'
                )
            }));

            renderTimeline();
        }
    } catch (error) {
        window.showToast('Failed to load recent activity feed', 'error', 'Activity Feed');
    }
}
async function loadLegislativeEvents() {
    try {
        const res = await fetch('/api/schedules/fetchSchedulesApi.php', {
            method: 'GET',
            credentials: 'include'
        });

        const data = await res.json();

        if (!data.success || !Array.isArray(data.data)) {
            console.warn('No schedules found');
            legislativeEvents = {};
            return {};
        }
        const eventsSnapshot = {};

        data.data.forEach(item => {
            const dateKey = item.date;
            const label = item.title;
            const time = item.time ? formatTime(item.time) : '';
            const color = getEventColor(item.type);

            if (!eventsSnapshot[dateKey]) {
                eventsSnapshot[dateKey] = [];
            }
            eventsSnapshot[dateKey].push({
                label,
                color,
                time,
                chamber: item.chamber,
                committee: item.committee,
                location: item.location,
                city: item.city
            });
        });
        legislativeEvents = eventsSnapshot;
        return legislativeEvents;
    } catch (err) {
        if (typeof window.showToast === 'function') {
            window.showToast('Failed to load legislative schedules', 'error', 'Schedules');
        }
        legislativeEvents = {};
        return {};
    }
}

async function startRealTimeUpdates() {
    if (dashboardInterval) {
        clearInterval(dashboardInterval);
    }
    await loadActivityFeed();
    await loadLegislativeEvents();
    renderCalendarEventsList(legislativeEvents);
    dashboardInterval = setInterval(async () => {
        await loadActivityFeed();
        await loadLegislativeEvents();
        renderCalendarEventsList(legislativeEvents);
    }, 30000);
}

// timeline
function renderTimeline() {
    const container = document.querySelector(".timeline");
    if (!container) return;
    container.innerHTML = "";

    recentActivity.forEach((item) => {
        container.innerHTML += `
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <div class="timeline-time">${item.time}</div>
                    <div class="timeline-text">${item.text}</div>
                </div>
            </div>
        `;
    });
}


// mini-calendar
function formatDateKey(year, monthIndex, day) {
    const m = String(monthIndex + 1).padStart(2, "0");
    const d = String(day).padStart(2, "0");
    return `${year}-${m}-${d}`;
}

function renderMiniCalendar() {
    const monthLabelEl = document.getElementById("calendarMonthLabel");
    const daysContainer = document.getElementById("calendarDays");
    if (!monthLabelEl || !daysContainer) return;

    const base = calendarState.currentDate;
    const year = base.getFullYear();
    const month = base.getMonth(); // 0-11
    const monthName = base.toLocaleString("en-US", { month: "long" });
    monthLabelEl.textContent = `${monthName} ${year}`;
    daysContainer.innerHTML = "";

    const firstDay = new Date(year, month, 1);
    const startingWeekday = firstDay.getDay(); // 0 = Sun, 6 = Sat
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();
    const isSameMonthAsToday =
        today.getFullYear() === year && today.getMonth() === month;

    // Remove old modal if exists
    const oldModal = document.getElementById("calendarEventModal");
    if (oldModal) oldModal.remove();

    for (let i = 0; i < startingWeekday; i++) {
        const emptyDiv = document.createElement("div");
        emptyDiv.className = "calendar-day empty";
        daysContainer.appendChild(emptyDiv);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey = formatDateKey(year, month, day);
        const dayDiv = document.createElement("div");
        dayDiv.classList.add("calendar-day");

        if (isSameMonthAsToday && day === today.getDate()) {
            dayDiv.classList.add("today");
        }

        const events = legislativeEvents?.[dateKey];
        if (events?.length > 0) {
            dayDiv.classList.add("event");
            dayDiv.style.borderBottom = `3px solid ${events[0].color}`;
            dayDiv.title = events.map(ev => ev.label).join(", ");
        }

        dayDiv.textContent = day;
        dayDiv.style.position = "relative";
        dayDiv.addEventListener("click", (e) => {
            e.stopPropagation();
            document.querySelectorAll(".calendar-event-modal").forEach(m => m.remove());

            if (events?.length > 0) {
                const modal = document.createElement("div");
                modal.className = "calendar-event-modal";
                modal.innerHTML = `
            ${events.map(ev => `
                <div class="event-item">
                    <div class="event-dot" style="--dot-color:${ev.color};"></div>
                    <div class="text">
                        <div class="title">${ev.label}</div>
                        <div class="time-location">${ev.time} ${ev.location ? "– " + ev.location : ""}</div>
                    </div>
                </div>
            `).join("")}
            <button class="close-modal-btn">Close</button>
        `;

                document.body.appendChild(modal);

                const reposition = () => {
                    const rect = dayDiv.getBoundingClientRect();
                    const modalRect = modal.getBoundingClientRect();
                    const header = document.querySelector(".top-header");
                    const headerBottom = header ? header.getBoundingClientRect().bottom : 0;

                    const viewportHeight = window.innerHeight;
                    const spacing = 15;
                    const overflowBelow = rect.bottom + spacing + modalRect.height > viewportHeight;

                    modal.style.position = "fixed";
                    modal.style.left = `${rect.left + rect.width / 2}px`;
                    let currentTop;
                    if (overflowBelow) {
                        modal.classList.add('flipped');
                        modal.style.top = "auto";
                        currentTop = rect.top - modalRect.height - spacing;
                        modal.style.bottom = `${viewportHeight - rect.top + spacing}px`;
                    } else {
                        modal.classList.remove('flipped');
                        modal.style.bottom = "auto";
                        currentTop = rect.bottom + spacing;
                        modal.style.top = `${currentTop}px`;
                    }
                    if (currentTop < headerBottom) {
                        modal.style.zIndex = "1";
                        modal.style.opacity = "0";
                        modal.style.pointerEvents = "none";
                    } else {
                        modal.style.zIndex = "10000"; // Back on top
                        modal.style.opacity = "1";
                        modal.style.pointerEvents = "auto";
                    }

                    modal.style.transform = "translateX(-50%)";
                };
                reposition();

                const onScroll = () => reposition();
                window.addEventListener('scroll', onScroll, true);

                const closeModal = () => {
                    modal.remove();
                    window.removeEventListener('scroll', onScroll, true);
                    document.removeEventListener('click', closeModal);
                };

                modal.querySelector(".close-modal-btn").addEventListener("click", (e) => {
                    e.stopPropagation();
                    closeModal();
                });

                modal.addEventListener("click", (e) => e.stopPropagation());
                setTimeout(() => {
                    document.addEventListener('click', closeModal);
                }, 10);
            }
        });


        daysContainer.appendChild(dayDiv);
    }


    if (window.lucide) lucide.createIcons();
}


async function initMiniCalendarNav() {
    const prevBtn = document.getElementById("calendarPrevBtn");
    const nextBtn = document.getElementById("calendarNextBtn");
    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            const d = calendarState.currentDate;
            calendarState.currentDate = new Date(
                d.getFullYear(),
                d.getMonth() - 1,
                1
            );
            renderMiniCalendar(legislativeEvents);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            const d = calendarState.currentDate;
            calendarState.currentDate = new Date(
                d.getFullYear(),
                d.getMonth() + 1,
                1
            );
            renderMiniCalendar(legislativeEvents);
        });
    }

}
async function renderCalendarEventsList(legislativeEvents) {
    const container = document.getElementById("calendarEventsList");
    if (!container) return;
    const eventEntries = Object.entries(legislativeEvents)
        .flatMap(([dateKey, events]) =>
            events.map(ev => ({
                dateKey,
                label: ev.label,
                time: ev.time,
                color: ev.color,
                location: ev.location
            }))
        )
        .sort((a, b) => new Date(a.dateKey + ' ' + a.time) - new Date(b.dateKey + ' ' + b.time));

    container.innerHTML = "";
    const renderBatch = (countToRender) => {
        const batch = eventEntries.slice(0, countToRender);
        container.innerHTML = "";
        batch.forEach((event) => {
            const dateObj = new Date(event.dateKey);
            const formatted = dateObj.toLocaleDateString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
            });

            const eventItem = document.createElement("div");
            eventItem.className = "calendar-event-item";
            eventItem.innerHTML = `
                <div class="event-dot" style="--dot-color: ${event.color};"></div>
                <div class="event-text">
                    <div class="event-title">${event.label}</div>
                    <div class="event-date">${formatted} – ${event.time}</div>
                </div>
            `;
            container.appendChild(eventItem);
        });
        if (countToRender < eventEntries.length) {
            const expandBtn = document.createElement("button");
            expandBtn.className = "expand-events-btn";
            expandBtn.innerHTML = `Show More Events <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>`;
            Object.assign(expandBtn.style, {
                display: "inline-flex",
                alignItems: "center",
                margin: "12px auto",
                background: "transparent",
                border: "1px solid var(--primary-green)",
                color: "var(--primary-green)",
                cursor: "pointer",
                padding: "8px 16px",
                borderRadius: "8px",
                fontWeight: "600"
            });

            expandBtn.addEventListener("click", () => {
                currentCalendarShownCount += 3;
                renderBatch(currentCalendarShownCount);
            });

            container.appendChild(expandBtn);
            if (window.lucide) lucide.createIcons();
        }
    };
    renderBatch(currentCalendarShownCount);
}