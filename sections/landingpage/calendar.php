<section id="calendar" class="hash-section py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Section Header -->
    <div class="mb-10 flex items-center gap-3">
      <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center shadow-sm">
        <i data-lucide="calendar" class="text-white w-6 h-6"></i>
      </div>
      <div>
        <h2 class="text-3xl lg:text-4xl font-black text-gray-900">Official Urdaneta Calendar</h2>
        <p class="text-sm text-gray-600">Plenary sessions, committee hearings, and public consultations</p>
      </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 items-start">
      <!-- Calendar Panel -->
      <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] overflow-hidden relative self-start">

        <!-- Calendar Header (GREEN gradient) -->
        <div class="flex items-center justify-between px-5 py-4 border-b"
          style="background: linear-gradient(90deg, var(--ph-blue-dark) 0%, var(--ph-blue) 50%, var(--ph-blue-light) 100%);">
          <div class="flex items-center gap-2">
            <button id="prevMonthBtn" class="rounded-lg p-2 bg-white/10 hover:bg-white/20 transition" aria-label="Previous month">
              <i data-lucide="chevron-left" class="w-5 h-5 text-white"></i>
            </button>
            <button id="nextMonthBtn" class="rounded-lg p-2 bg-white/10 hover:bg-white/20 transition" aria-label="Next month">
              <i data-lucide="chevron-right" class="w-5 h-5 text-white"></i>
            </button>
          </div>

          <div class="text-center">
            <div id="monthLabel" class="text-white tracking-wide text-xl font-extrabold"></div>
            <div id="yearLabel" class="text-white/80 text-xs font-medium"></div>
          </div>

          <div class="flex items-center gap-2">
            <button id="todayBtn"
              class="bg-white text-[color:var(--ph-blue-dark)] hover:bg-[color:var(--ph-blue-light)]/10 px-3 py-1.5 rounded-lg text-sm font-semibold transition">
              Today
            </button>
          </div>
        </div>

        <!-- Weekdays -->
        <div class="grid grid-cols-7 text-center text-[11px] md:text-xs font-semibold text-gray-500 border-b border-gray-100 bg-gray-50/70">
          <div class="py-2">SUN</div>
          <div class="py-2">MON</div>
          <div class="py-2">TUE</div>
          <div class="py-2">WED</div>
          <div class="py-2">THU</div>
          <div class="py-2">FRI</div>
          <div class="py-2">SAT</div>
        </div>

        <!-- Days Grid -->
        <div id="calendarGrid"
          class="grid grid-cols-7 gap-px bg-gray-100"
          role="grid"
          aria-label="Month view calendar">
        </div>
      </div>

      <!-- Side Panel -->
      <aside class="rounded-2xl border border-gray-200 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.06)] p-6 flex flex-col">
        <!-- Selected Date Card (GREEN gradient) -->
        <div class="rounded-xl p-5 mb-5 text-white relative overflow-hidden"
          style="background: linear-gradient(135deg, var(--ph-blue-dark) 0%, var(--ph-blue-light) 100%);">
          <div class="absolute -right-6 -top-6 w-28 h-28 rounded-full bg-white/10"></div>
          <div class="text-white/90 text-xs uppercase tracking-wide">Selected Date</div>
          <div id="selectedDateLarge" class="mt-1 text-3xl font-black leading-tight">—</div>
          <div id="selectedDateWeekday" class="text-white/80 text-sm">—</div>
        </div>

        <!-- Filters (GREEN accents) -->
        <div class="mb-4 grid grid-cols-2 gap-2">
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" class="accent-emerald-600" data-filter="session" checked>
            <span class="inline-flex items-center gap-1">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Session
            </span>
          </label>
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" class="accent-green-700" data-filter="committee" checked>
            <span class="inline-flex items-center gap-1">
              <span class="w-2 h-2 rounded-full bg-green-700"></span> Committee
            </span>
          </label>
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" class="accent-amber-500" data-filter="public" checked>
            <span class="inline-flex items-center gap-1">
              <span class="w-2 h-2 rounded-full bg-amber-500"></span> Public
            </span>
          </label>
          <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" class="accent-gray-500" data-filter="other" checked>
            <span class="inline-flex items-center gap-1">
              <span class="w-2 h-2 rounded-full bg-gray-500"></span> Other
            </span>
          </label>
        </div>

        <!-- Events -->
        <div class="flex items-center justify-between mb-2">
          <h3 class="font-bold text-gray-900">Events</h3>
          <span id="eventsCount" class="text-xs bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full">0</span>
        </div>
        <div id="eventsList" class="space-y-3 overflow-auto max-h-[420px]">
          <div class="text-sm text-gray-500">No events for this date.</div>
        </div>

        <!-- Quick Links -->
        <div class="mt-6 border-t pt-4 grid grid-cols-1 gap-2">
          <a href="/pages/landingpage/schedules"
            class="text-sm inline-flex items-center justify-between px-3 py-2 rounded-lg border hover:bg-gray-50">
            Session Calendar <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </a>
          <a href="/pages/landingpage/schedules"
            class="text-sm inline-flex items-center justify-between px-3 py-2 rounded-lg border hover:bg-gray-50">
            Committee Agenda <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </a>
          <a href="/pages/landingpage/schedules"
            class="text-sm inline-flex items-center justify-between px-3 py-2 rounded-lg border hover:bg-gray-50">
            Public Consultations <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </a>
        </div>
      </aside>
    </div>
  </div>
</section>

<script>
  (function() {
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

    // ---- DATA ----
    let SCHEDULES = [];
    // Group events by date
    let EVENTS = {};

    async function loadSchedules() {
      try {
        const response = await fetch('/api/schedules/fetchSchedulesApi.php');
        const result = await response.json();

        if (result.success) {
          SCHEDULES = result.data;
          EVENTS = SCHEDULES.reduce((acc, r) => {
            const k = r.date;
            const item = {
              time: r.time,
              title: r.title,
              chamber: chamberLabel(r),
              location: r.location,
              type: r.type
            };
            (acc[k] ||= []).push(item);
            acc[k].sort((a, b) => a.time.localeCompare(b.time));
            return acc;
          }, {});
          renderCalendar();
          renderSidePanel();
        }
      } catch (error) {
        console.error("Failed to fetch schedules:", error);
      }
    }
    loadSchedules();
    const keyOf = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;

    function chamberLabel(r) {
      if (r.chamber === 'house') return r.committee ? `House Committee on ${r.committee}` : 'House of Representatives';
      if (r.chamber === 'senate') return r.committee ? `Senate Committee on ${r.committee}` : 'Senate';
      if (r.chamber === 'joint') return r.committee ? `Joint: ${r.committee}` : 'Joint Bodies';
      return '';
    }

    

    // Els
    const grid = document.getElementById('calendarGrid');
    const monthLabel = document.getElementById('monthLabel');
    const yearLabel = document.getElementById('yearLabel');
    const prevBtn = document.getElementById('prevMonthBtn');
    const nextBtn = document.getElementById('nextMonthBtn');
    const todayBtn = document.getElementById('todayBtn');

    const selectedDateLarge = document.getElementById('selectedDateLarge');
    const selectedDateWeekday = document.getElementById('selectedDateWeekday');
    const eventsList = document.getElementById('eventsList');
    const eventsCount = document.getElementById('eventsCount');

    const filterChecks = $$('input[type="checkbox"][data-filter]');

    // State
    const today = new Date();
    let viewYear = today.getFullYear();
    let viewMonth = today.getMonth();
    let selected = new Date(today);

    const allowedTypes = () => {
      const set = new Set();
      filterChecks.forEach(c => c.checked && set.add(c.dataset.filter));
      return set;
    };

    function renderCalendar() {
      monthLabel.textContent = monthName(viewYear, viewMonth);
      yearLabel.textContent = viewYear;
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
      eventsList.innerHTML = items.length ? '' : '<div class="text-sm text-gray-500">No events for this date.</div>';

      items.forEach(e => {
        const card = document.createElement('div');
        card.className = 'border border-gray-200 rounded-xl p-4 hover:shadow-sm transition';
        // badges & dots use green map
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
        <div class="flex items-center gap-2 text-sm text-gray-600">
          <i data-lucide="clock" class="w-4 h-4"></i><span class="font-medium">${e.time}</span>
        </div>
        <div class="mt-1 font-semibold text-gray-900">${e.title}</div>
        <div class="mt-1 text-sm text-gray-600 flex items-center gap-2">
          <i data-lucide="landmark" class="w-4 h-4"></i><span>${e.chamber}</span>
        </div>
        <div class="mt-1 text-sm text-gray-600 flex items-center gap-2">
          <i data-lucide="map-pin" class="w-4 h-4"></i><span>${e.location}</span>
        </div>
        <div class="mt-3 inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full"
             style="background-color:${bg}; color:${fg}">
          <span class="inline-block w-1.5 h-1.5 rounded-full" style="background-color:${dot}"></span>
          ${e.type.charAt(0).toUpperCase() + e.type.slice(1)}
        </div>`;
        eventsList.appendChild(card);
      });

      if (window.lucide) setTimeout(() => window.lucide.createIcons(), 0);
    }

    // Nav & init
    document.getElementById('prevMonthBtn').addEventListener('click', () => {
      if (viewMonth === 0) {
        viewMonth = 11;
        viewYear--;
      } else {
        viewMonth--;
      }
      renderCalendar();
    });
    document.getElementById('nextMonthBtn').addEventListener('click', () => {
      if (viewMonth === 11) {
        viewMonth = 0;
        viewYear++;
      } else {
        viewMonth++;
      }
      renderCalendar();
    });
    todayBtn.addEventListener('click', () => {
      const t = new Date();
      viewYear = t.getFullYear();
      viewMonth = t.getMonth();
      selected = t;
      renderCalendar();
      renderSidePanel();
    });

    document.addEventListener('keydown', (e) => {
      const move = (days) => {
        selected.setDate(selected.getDate() + days);
        viewYear = selected.getFullYear();
        viewMonth = selected.getMonth();
        renderCalendar();
        renderSidePanel();
      };
      if (e.key === 'ArrowLeft') move(-1);
      if (e.key === 'ArrowRight') move(+1);
      if (e.key === 'ArrowUp') move(-7);
      if (e.key === 'ArrowDown') move(+7);
    });
    $$('input[type="checkbox"][data-filter]').forEach(c => c.addEventListener('change', () => {
      renderCalendar();
      renderSidePanel();
    }));

  })();
</script>