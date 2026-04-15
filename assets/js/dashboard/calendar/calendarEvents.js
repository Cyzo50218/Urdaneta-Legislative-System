
(function () {
    // --- Utils
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const pad = n => String(n).padStart(2, '0');
    const monthName = (y, m) => new Date(y, m, 1).toLocaleDateString(undefined, {
        month: 'long'
    });
    const weekdayName = d => d.toLocaleDateString(undefined, {
        weekday: 'long'
    });
    function chamberLabel(r) {
        if (r.chamber === 'house') return r.committee ? `House Committee on ${r.committee}` : 'House of Representatives';
        if (r.chamber === 'senate') return r.committee ? `Senate Committee on ${r.committee}` : 'Senate';
        if (r.chamber === 'joint') return r.committee ? `Joint: ${r.committee}` : 'Joint Bodies';
        return '';
    }

    // ---- DATA ----
    let SCHEDULES = [];
    let EVENTS = {};
    let pollInterval = null;

    let grid = null, monthLabel = null, addBtn = null, yearLabel = null, prevBtn = null, nextBtn = null, todayBtn = null;
    let filterChecks = [];

    // State
    const today = new Date();
    let viewYear = today.getFullYear();
    let viewMonth = today.getMonth();
    let selected = new Date(today);
    window.getSelectedDate = () => selected;

    window.initCalendarUI = function () {
        const container = document.getElementById('calendarEvents');
        if (!container) return;

        // Assign Elements
        grid = document.getElementById('calendarGrid');
        monthLabel = document.getElementById('monthLabel');

        yearLabel = document.getElementById('yearLabel');
        prevBtn = document.getElementById('prevMonthBtn');
        nextBtn = document.getElementById('nextMonthBtn');
        todayBtn = document.getElementById('todayBtn');
        addBtn = document.getElementById('addEventBtn');
        filterChecks = $$('input[type="checkbox"][data-filter]');

        // Listeners
        if (prevBtn) prevBtn.onclick = () => { viewMonth--; if (viewMonth < 0) { viewMonth = 11; viewYear--; } renderCalendar(); renderSidePanel(); };
        if (nextBtn) nextBtn.onclick = () => { viewMonth++; if (viewMonth > 11) { viewMonth = 0; viewYear++; } renderCalendar(); renderSidePanel(); };
        if (todayBtn) todayBtn.onclick = () => { const t = new Date(); viewYear = t.getFullYear(); viewMonth = t.getMonth(); selected = t; renderCalendar(); renderSidePanel(); };
        addBtn.onclick = openRegisterModal;
        filterChecks.forEach(c => c.addEventListener('change', renderCalendar));
        window.loadSchedules();


        const handleFormSubmit = async (e, url, closeFn) => {
            e.preventDefault();
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.innerHTML = `
        <span class="flex items-center gap-2">
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        </span>
    `;

            const formData = new FormData(form);

            try {
                const response = await fetch(url, { method: 'POST', body: formData });
                const result = await response.json();

                if (result.success) {
                    closeFn();
                    if (window.loadSchedules) window.loadSchedules();
                    window.showToast(result.message || "Success!", "success");
                } else {
                    window.showToast(result.error || "Error occurred while processing the request.", "error");
                }
            } catch (err) {
                console.error("Submission failed:", err);
                window.showToast("Server error. Please try again.", "error");
            } finally {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.innerHTML = originalContent;
            }
        };

        const regForm = document.getElementById('registerEventForm');
        if (regForm) {
            regForm.onsubmit = (e) => handleFormSubmit(e, '/api/schedules/saveCalendarEvent.php', window.closeRegisterModal);
        }

        const editForm = document.getElementById('editEventForm');
        if (editForm) {
            editForm.onsubmit = (e) => handleFormSubmit(e, '/api/schedules/updateCalendarEvent.php', window.closeEditModal);
        }
    }

    window.loadSchedules = async function () {
        if (window.isFetchingSchedules) return;
        window.isFetchingSchedules = true;

        try {
            const response = await fetch('/api/schedules/fetchSchedulesApi.php');
            const result = await response.json();

            if (result.success) {
                SCHEDULES = result.data;
                EVENTS = SCHEDULES.reduce((acc, r) => {
                    const k = r.date;
                    const item = {
                        id: r.event_id,
                        time: r.time,
                        city: r.city,
                        title: r.title,
                        chamber: chamberLabel(r),
                        location: r.location,
                        type: r.type
                    };
                    (acc[k] ||= []).push(item);
                    acc[k].sort((a, b) => a.time.localeCompare(b.time));
                    return acc;
                }, {});

                const isEditing = document.getElementById('editEventModal').classList.contains('show');
                const isRegistering = document.getElementById('registerEventModal').classList.contains('show');

                if (!isEditing && !isRegistering) {
                    renderCalendar();
                    renderSidePanel();
                }
            }
        } catch (error) {
            console.error("Polling error:", error);
        } finally {
            window.isFetchingSchedules = false;
        }
    };

    window.startCalendarPolling = function () {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(() => {
            if (document.getElementById('calendarGrid')) {
                window.loadSchedules();
            } else {
                window.stopCalendarPolling();
            }
        }, 5000);
    };
    window.stopCalendarPolling = function () {
        if (pollInterval) {
            console.log("Real-time polling stopped.");
            clearInterval(pollInterval);
            pollInterval = null;
        }
    };

    const allowedTypes = () => {
        const set = new Set();
        if (filterChecks.length === 0) {
            return new Set(['session', 'committee', 'public']);
        }

        filterChecks.forEach(c => c.checked && set.add(c.dataset.filter));
        return set;
    };
    function renderCalendar() {
        if (!monthLabel || !grid) return;

        monthLabel.textContent = monthName(viewYear, viewMonth);
        if (yearLabel) yearLabel.textContent = viewYear;

        grid.innerHTML = '';
        const first = new Date(viewYear, viewMonth, 1);
        const startDay = first.getDay();
        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        const prevLast = new Date(viewYear, viewMonth, 0).getDate();
        const totalCells = 42;

        for (let i = 0; i < totalCells; i++) {
            let date, inMonth = true;
            if (i < startDay) {
                date = new Date(viewYear, viewMonth - 1, prevLast - (startDay - 1 - i));
                inMonth = false;
            } else if (i >= startDay + daysInMonth) {
                date = new Date(viewYear, viewMonth + 1, i - (startDay + daysInMonth) + 1);
                inMonth = false;
            } else {
                date = new Date(viewYear, viewMonth, i - startDay + 1);
            }

            const k = keyOf(date);
            const isToday = k === keyOf(today);
            const isSelected = k === keyOf(selected);

            const cell = document.createElement('button');
            cell.type = 'button';
            cell.setAttribute('role', 'gridcell');
            cell.setAttribute('aria-label', date.toDateString());

            // GREEN hover/focus
            cell.className = 'bg-white h-24 p-2 text-left relative outline-none transition ' +
                'hover:bg-emerald-50 focus-visible:ring-2 focus-visible:ring-emerald-600 ' +
                'focus-visible:ring-offset-2 focus-visible:ring-offset-white';

            const dayNum = document.createElement('div');
            dayNum.textContent = date.getDate();
            dayNum.className = 'text-sm font-semibold ' + (inMonth ? 'text-gray-800' : 'text-gray-300');

            if (isToday) {
                const halo = document.createElement('span');
                halo.className = 'absolute -top-1 -left-1 w-6 h-6 rounded-full bg-emerald-100';
                cell.appendChild(halo);
            }
            if (isSelected) {
                cell.classList.add('ring-2', 'ring-emerald-600', 'ring-offset-2', 'ring-offset-white');
                const badge = document.createElement('span');
                badge.className = 'absolute top-2 right-2 text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-600 text-white';
                badge.textContent = 'SELECTED';
                cell.appendChild(badge);
            }

            const dotsWrap = document.createElement('div');
            dotsWrap.className = 'absolute bottom-2 left-2 right-2 flex gap-1 flex-wrap';

            const types = allowedTypes();
            (EVENTS[k] || []).filter(e => types.has(e.type)).slice(0, 4).forEach(e => {
                const dot = document.createElement('span');
                dot.className = 'inline-block w-2 h-2 rounded-full';
                // GREEN palette for event types
                dot.style.backgroundColor =
                    e.type === 'session' ? '#10b981' : /* emerald-500 */
                        e.type === 'committee' ? '#047857' : /* emerald-800 */
                            e.type === 'public' ? '#f59e0b' : /* amber-500 */
                                '#9ca3af'; /* gray-400 */
                dotsWrap.appendChild(dot);
            });

            cell.appendChild(dayNum);
            cell.appendChild(dotsWrap);
            cell.addEventListener('click', () => {
                selected = new Date(date);
                renderCalendar();
                renderSidePanel();
                if (window.lucide) setTimeout(() => window.lucide.createIcons(), 0);
            });
            grid.appendChild(cell);
        }

        if (window.lucide) setTimeout(() => window.lucide.createIcons(), 0);
    }

    function renderSidePanel() {
        selectedDateLarge.textContent = selected.toLocaleDateString(undefined, {
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });
        selectedDateWeekday.textContent = weekdayName(selected);

        const k = keyOf(selected);
        const items = (EVENTS[k] || []).filter(e => allowedTypes().has(e.type));
        eventsCount.textContent = items.length;
        if (items.length === 0) {
            eventsList.innerHTML = `
        <div class="py-10 text-center">
            <div class="inline-flex items-center justify-center w-10 h-10 mb-3 text-gray-200">
                <i data-lucide="archive" class="w-8 h-8"></i>
            </div>
            <p class="text-sm font-semibold text-gray-800">No Legislative Records Found</p>
            <p class="text-xs text-gray-400 mt-1">There are no entries filed for this specific calendar date.</p>
        </div>`;
        } else {
            eventsList.innerHTML = '';
        }

        items.forEach(e => {
            const card = document.createElement('div');
            card.className = 'border border-gray-200 rounded-xl p-4 hover:shadow-sm transition';
            const bg =
                e.type === 'session' ? '#d1fae5' : /* emerald-100 */
                    e.type === 'committee' ? '#bbf7d0' : /* green-100-ish */
                        e.type === 'public' ? '#fef3c7' : /* amber-100 */
                            '#f3f4f6'; /* gray-100 */
            const fg =
                e.type === 'session' ? '#065f46' : /* emerald-800 */
                    e.type === 'committee' ? '#166534' : /* green-700 */
                        e.type === 'public' ? '#b45309' : /* amber-700 */
                            '#374151'; /* gray-700 */
            const dot =
                e.type === 'session' ? '#10b981' : /* emerald-500 */
                    e.type === 'committee' ? '#047857' : /* emerald-800 */
                        e.type === 'public' ? '#f59e0b' : /* amber-500 */
                            '#9ca3af'; /* gray-400 */

            card.innerHTML = `
    <div class="flex justify-between items-start">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <i data-lucide="clock" class="w-4 h-4"></i>
            <span class="font-medium">${e.time}</span>
        </div>
        
        <button 
            type="button"
            class="p-1.5 -mr-1 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-md transition-all"
            onclick='openEditModal(${JSON.stringify({ ...e, date: k })})'
            title="Edit Event">
            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
        </button>
    </div>

    <div class="mt-1 font-semibold text-gray-900 leading-tight">${e.title}</div>

    <div class="mt-2 space-y-1">
        <div class="text-xs text-gray-500 flex items-center gap-2">
            <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
            <span>${e.chamber}</span>
        </div>
        <div class="text-xs text-gray-500 flex items-center gap-2">
            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
            <span>${e.location}</span>
        </div>
    </div>

    <div class="mt-3 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border"
         style="background-color:${bg}; color:${fg}; border-color: rgba(0,0,0,0.05)">
        <span class="inline-block w-1.5 h-1.5 rounded-full" style="background-color:${dot}"></span>
        ${e.type}
    </div>`;
            eventsList.appendChild(card);
        });

        if (window.lucide) setTimeout(() => window.lucide.createIcons(), 0);
    }


    const keyOf = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
})();