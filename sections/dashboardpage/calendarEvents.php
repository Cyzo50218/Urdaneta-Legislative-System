<div id="calendarEvents" class="hash-section">
    <div class="content-main">

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
                <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-100">
                    <div class="flex items-baseline gap-3">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-gray-900">Events</h3>
                        <span id="eventsCount" class="text-xs font-medium text-gray-500">0 Events</span>
                    </div>

                    <button id="addEventBtn" class="flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-emerald-700 hover:text-emerald-800 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Register Event</span>
                    </button>
                </div>

                <div id="eventsList" class="space-y-4 overflow-auto max-h-[420px]">
                    <div class="py-10 text-center">
                        <div class="inline-flex items-center justify-center w-10 h-10 mb-3 text-gray-200">
                            <i data-lucide="archive" class="w-8 h-8"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-800">No Legislative Records Found</p>
                        <p class="text-xs text-gray-400 mt-1">There are no entries filed for this specific calendar date.</p>
                    </div>
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
</div>