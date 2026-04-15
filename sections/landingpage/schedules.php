<section id="schedules" class="hash-section py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex items-center gap-3">
            <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center shadow-sm">
                <i data-lucide="clock" class="text-white w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-3xl lg:text-4xl font-black text-gray-900">Official Schedules</h2>
                <p class="text-sm text-gray-600">Plenary sessions, committee hearings, and public consultations</p>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-[0_8px_30px_rgba(0,0,0,.06)] p-4 mb-4 relative overflow-hidden">

            <div class="grid lg:grid-cols-12 gap-3 items-end">
                <div class="lg:col-span-6">
                    <label class="text-xs font-semibold text-gray-700">AI/Smart Search</label>
                    <div class="relative">
                        <i data-lucide="sparkles" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-600"></i>
                        <input id="schSearch" type="search"
                            placeholder="Try: senate energy hearings next week in Pasay after Oct 20"
                            class="w-full pl-10 pr-24 py-2.5 rounded-xl border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-600" />
                        <button id="schSearchBtn"
                            class="absolute right-1 top-2 px-3 py-1.5 rounded-lg text-white text-sm font-semibold btn-primary">
                            Search
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <label class="text-xs font-semibold text-gray-700">Date range (from)</label>
                    <input id="schFrom" type="date"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600" />
                </div>
                <div class="lg:col-span-3">
                    <label class="text-xs font-semibold text-gray-700">Date range (to)</label>
                    <input id="schTo" type="date"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-600" />
                </div>

                <div class="lg:col-span-12 flex flex-wrap gap-2 pt-1">
                    <div class="flex items-center gap-2 mr-4">
                        <span class="text-xs font-semibold text-gray-700">Type:</span>
                        <label class="text-sm flex items-center gap-1"><input type="checkbox" class="accent-blue-600" data-type="session" checked><span class="w-2 h-2 rounded-full bg-blue-600"></span>Session</label>
                        <label class="text-sm flex items-center gap-1"><input type="checkbox" class="accent-emerald-600" data-type="committee" checked><span class="w-2 h-2 rounded-full bg-emerald-600"></span>Committee</label>
                        <label class="text-sm flex items-center gap-1"><input type="checkbox" class="accent-amber-500" data-type="public" checked><span class="w-2 h-2 rounded-full bg-amber-500"></span>Public</label>
                    </div>
                    <div class="flex items-center gap-2 mr-4">
                        <span class="text-xs font-semibold text-gray-700">Chamber:</span>
                        <label class="text-sm flex items-center gap-1"><input type="checkbox" class="accent-blue-600" data-chamber="house" checked>House</label>
                        <label class="text-sm flex items-center gap-1"><input type="checkbox" class="accent-blue-600" data-chamber="senate" checked>Senate</label>
                        <label class="text-sm flex items-center gap-1"><input type="checkbox" class="accent-blue-600" data-chamber="joint" checked>Joint</label>
                    </div>

                    <div class="ml-auto flex items-center gap-2">
                        <button id="schReset" class="px-3 py-1.5 rounded-lg border text-sm hover:bg-gray-50">Reset</button>
                        <button id="schExport" class="px-3 py-1.5 rounded-lg border text-sm hover:bg-gray-50">Export CSV</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="schAIBox" class="hidden mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-900">
            <div class="flex items-center justify-between mb-2">
                <div class="font-semibold">AI Results</div>
                <button id="schAIClear" class="text-emerald-900/70 hover:text-emerald-900 text-xs underline">Clear</button>
            </div>
            <div id="schAIResultsList" class="space-y-2"></div>
        </div>


        <!-- Table -->
        <div class="overflow-x-auto rounded-2xl border border-gray-200 bg-white shadow-[0_8px_30px_rgba(0,0,0,.06)]">
            <table class="min-w-full text-left">
                <thead class="sticky top-0 z-10 bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Time</th>
                        <th class="px-6 py-3">Event</th>
                        <th class="px-6 py-3">Chamber / Committee</th>
                        <th class="px-6 py-3">Location</th>
                    </tr>
                </thead>
                <tbody id="schTbody" class="divide-y text-sm"></tbody>
            </table>
        </div>


        <!-- Footer bar -->
        <div class="mt-3 flex items-center justify-between text-xs text-gray-600">
            <div id="schCount">0 results</div>
            <div class="flex items-center gap-2">
                <button id="schPrev" class="px-2 py-1 rounded border hover:bg-gray-50">Prev</button>
                <span id="schPage" class="px-2">Page 1</span>
                <button id="schNext" class="px-2 py-1 rounded border hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>

    <!-- Schedules Sidebar (drawer) -->
    <div id="schDrawerBackdrop" class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none transition-opacity z-40"></div>
    <aside id="schDrawer" class="fixed top-0 right-0 h-full w-full sm:w-[440px] bg-white border-l border-gray-200 shadow-2xl translate-x-full transition-transform z-50">
        <div class="sticky top-0 flex items-center justify-between px-5 py-4 border-b"
            style="background: linear-gradient(135deg, var(--ph-blue-dark), var(--ph-blue-light));">
            <div>
                <div id="sdWhen" class="text-white/90 text-xs tracking-wide uppercase">—</div>
                <div id="sdTitle" class="text-white font-bold text-lg leading-tight">—</div>
            </div>
            <button id="sdClose" class="text-white/90 hover:text-white" aria-label="Close">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="p-5 space-y-3 text-sm">
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 rounded-xl border">
                    <div class="text-[11px] text-gray-500">Type</div>
                    <div id="sdType" class="font-semibold"></div>
                </div>
                <div class="p-3 rounded-xl border">
                    <div class="text-[11px] text-gray-500">Chamber / Committee</div>
                    <div id="sdChamber" class="font-semibold"></div>
                </div>
            </div>
            <div class="p-3 rounded-xl border">
                <div class="text-[11px] text-gray-500 mb-1">Location</div>
                <div id="sdLocation" class="font-medium"></div>
            </div>
        </div>
    </aside>

</section>

<script>
    let aiRestrictKeys = null;
    let currentSearchRequestId = 0;
    let isAiSearching = false;
    const makeKey = (r) => `${r.date}|${r.time}|${r.title}`;
    const STOPWORDS = new Set([
        'find', 'show', 'search', 'look', 'for', 'in', 'at', 'on', 'the', 'a', 'an', 'me',
        'please', 'event', 'events', 'schedule', 'schedules', 'next', 'week', 'after', 'before', 'today', 'tomorrow'
    ]);
    let SCHEDULES = [];

    async function loadSchedules() {
        try {
            const response = await fetch('/api/schedules/fetchSchedulesApi.php');
            const result = await response.json();

            if (result.success) {
                SCHEDULES = result.data;
                // Init default dates 
                (function initDates() {
                    const min = SCHEDULES.reduce((m, r) => m < r.date ? m : r.date, SCHEDULES[0].date);
                    const max = SCHEDULES.reduce((m, r) => m > r.date ? m : r.date, SCHEDULES[0].date);
                    $('#schFrom').min = min;
                    $('#schFrom').max = max;
                    $('#schTo').min = min;
                    $('#schTo').max = max;
                })();
                renderTable();
            }
        } catch (error) {
            console.error("Failed to fetch schedules:", error);
        }
    }
    loadSchedules();

    // ===== UTILITIES =====
    let searchDebounceTimer = null;
    let aiAbortController = null;

    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];
    const fmtDate = (iso) => new Date(iso + 'T00:00:00');
    const toHuman = (iso) => fmtDate(iso).toLocaleDateString(undefined, {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
    const pad = n => String(n).padStart(2, '0');

    // Simple smart parser for natural-language queries
    function parseQuery(q) {
        q = (q || '').toLowerCase().trim();

        const res = {
            text: '',
            type: new Set(),
            chamber: new Set(),
            after: null,
            before: null
        };

        if (!q) return res;

        const originalQuery = q;

        const dateMatch = (s) => {
            const d = new Date(s);
            return isNaN(+d) ? null : d;
        };

        const today = new Date();
        const nextWeek = new Date();
        nextWeek.setDate(today.getDate() + 7);

        // Relative range
        if (/next week/.test(q)) {
            res.after = today;
            res.before = nextWeek;
            q = q.replace(/next week/g, '');
        }

        // After / Before dates
        const afterM = q.match(/after ([a-z]{3,}\s?\d{1,2}(,?\s?\d{4})?|\d{4}-\d{2}-\d{2})/);
        if (afterM) {
            const d = dateMatch(afterM[1]);
            if (d) res.after = d;
            q = q.replace(afterM[0], '');
        }

        const beforeM = q.match(/before ([a-z]{3,}\s?\d{1,2}(,?\s?\d{4})?|\d{4}-\d{2}-\d{2})/);
        if (beforeM) {
            const d = dateMatch(beforeM[1]);
            if (d) res.before = d;
            q = q.replace(beforeM[0], '');
        }

        // Type detection
        if (/plenary|session/.test(q)) {
            res.type.add('session');
            q = q.replace(/plenary|session/g, '');
        }

        if (/committee|hearing|twg|briefing/.test(q)) {
            res.type.add('committee');
            q = q.replace(/committee|hearing|twg|briefing/g, '');
        }

        if (/public|consultation|town hall/.test(q)) {
            res.type.add('public');
            q = q.replace(/public|consultation|town hall/g, '');
        }

        // Chamber detection
        if (/house/.test(q)) {
            res.chamber.add('house');
            q = q.replace(/house/g, '');
        }

        if (/senate/.test(q)) {
            res.chamber.add('senate');
            q = q.replace(/senate/g, '');
        }

        if (/joint/.test(q)) {
            res.chamber.add('joint');
            q = q.replace(/joint/g, '');
        }

        const cleaned = q
            .split(/\s+/)
            .filter(Boolean)
            .filter(t => t.length > 2 && !STOPWORDS.has(t))
            .slice(0, 10);

        res.text = cleaned.join(' ');

        return res;
    }

    // ===== RENDERING + FILTERING =====
    const tbody = $('#schTbody');
    const schCount = $('#schCount');
    const schPageLbl = $('#schPage');
    let page = 1,
        pageSize = 7;

    function activeTypeSet() {
        const set = new Set();
        $$('input[data-type]').forEach(cb => {
            if (cb.checked) set.add(cb.dataset.type);
        });
        return set;
    }

    function activeChamberSet() {
        const set = new Set();
        $$('input[data-chamber]').forEach(cb => {
            if (cb.checked) set.add(cb.dataset.chamber);
        });
        return set;
    }

    function matchesFilters(r, qObj, drFrom, drTo) {
        // Date range filter
        if (drFrom && r.date < drFrom) return false;
        if (drTo && r.date > drTo) return false;

        // Type filter (Set)
        if (qObj.type && qObj.type.size > 0 && !qObj.type.has(r.type)) {
            return false;
        }

        const typeSet = activeTypeSet();
        if (typeSet.size > 0 && !typeSet.has(r.type)) {
            return false;
        }

        const chamberSet = activeChamberSet();
        if (chamberSet.size > 0 && !chamberSet.has(r.chamber)) {
            return false;
        }

        // Unified text search (title, committee, location, city, chamber, type)
        if (qObj.text && qObj.text.trim() !== '') {
            const tokens = qObj.text.toLowerCase().trim().split(/\s+/);

            const haystack = `
            ${r.title || ''}
            ${r.committee || ''}
            ${r.location || ''}
            ${r.city || ''}
            ${r.chamber || ''}
            ${r.type || ''}
        `.toLowerCase();

            const matchesAllTokens = tokens.every(token => haystack.includes(token));

            if (!matchesAllTokens) return false;
        }

        return true;
    }

    function stripTime(dt) {
        return new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
    }

    function pill(type) {
        const map = {
            session: {
                bg: '#dbeafe',
                fg: '#1d4ed8',
                dot: '#2563eb',
                label: 'Session'
            },
            committee: {
                bg: '#d1fae5',
                fg: '#047857',
                dot: '#059669',
                label: 'Committee'
            },
            public: {
                bg: '#fef3c7',
                fg: '#b45309',
                dot: '#f59e0b',
                label: 'Public'
            },
        } [type] || {
            bg: '#f3f4f6',
            fg: '#374151',
            dot: '#9ca3af',
            label: 'Other'
        };
        return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px]"
                style="background:${map.bg};color:${map.fg}">
            <span class="inline-block w-1.5 h-1.5 rounded-full" style="background:${map.dot}"></span>${map.label}
          </span>`;
    }

    function chamberBadge(c) {
        const label = c === 'house' ? 'House of Representatives' : c === 'senate' ? 'Senate' : c === 'joint' ? 'Joint Bodies' : '';
        return `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[11px]">
            <i data-lucide="landmark" class="w-3.5 h-3.5"></i>${label}
          </span>`;
    }

    function getVisibleScheduleRows() {
        return [...document.querySelectorAll('#schTbody tr')].map(tr => {
            const obj = {
                key: tr.getAttribute('data-key') || '',
                date: tr.getAttribute('data-date') || '',
                time: tr.getAttribute('data-time') || '',
                title: tr.getAttribute('data-title') || '',
                type: tr.getAttribute('data-type') || '',
                chamber: tr.getAttribute('data-chamber') || '',
                committee: tr.getAttribute('data-committee') || '',
                location: tr.getAttribute('data-location') || '',
                city: tr.getAttribute('data-city') || '',
            };
            obj.line = `${toHuman(obj.date)} — ${obj.time} | ${obj.title} | ${ (obj.chamber + (obj.committee?(' / '+obj.committee):'')) } | ${obj.location}${obj.city?(', '+obj.city):''} | ${obj.type}`;
            return obj;
        });
    }

    function showAIBox() {
        document.getElementById('schAIBox')?.classList.remove('hidden');
    }

    function hideAIBox() {
        document.getElementById('schAIBox')?.classList.add('hidden');
    }

    function clearAIResults() {
        const list = document.getElementById('schAIResultsList');
        if (list) list.innerHTML = '';
    }

    function openScheduleDrawerFromTr(tr) {
        if (!tr) return;
        const title = tr.getAttribute('data-title') || '—';
        const d = tr.getAttribute('data-date') || '';
        const t = tr.getAttribute('data-time') || '';
        const type = tr.getAttribute('data-type') || '';
        const chamber = tr.getAttribute('data-chamber') || '';
        const committee = tr.getAttribute('data-committee') || '';
        const location = tr.getAttribute('data-location') || '';
        const city = tr.getAttribute('data-city') || '';

        document.getElementById('sdWhen').textContent = `${toHuman(d)} — ${t}`;
        document.getElementById('sdTitle').textContent = title;
        document.getElementById('sdType').textContent = type.charAt(0).toUpperCase() + type.slice(1);
        document.getElementById('sdChamber').textContent = [chamber === 'house' ? 'House of Representatives' : chamber === 'senate' ? 'Senate' : chamber === 'joint' ? 'Joint Bodies' : chamber, committee].filter(Boolean).join(' / ');
        document.getElementById('sdLocation').textContent = [location, city].filter(Boolean).join(', ');

        const drawer = document.getElementById('schDrawer');
        const backdrop = document.getElementById('schDrawerBackdrop');
        if (!drawer || !backdrop) return;

        drawer.classList.remove('translate-x-full');
        backdrop.classList.remove('pointer-events-none');
        backdrop.classList.add('opacity-100');
        document.body.style.overflow = 'hidden';
        if (window.lucide) setTimeout(() => window.lucide.createIcons(), 0);
    }

    (function initSchDrawer() {
        const drawer = document.getElementById('schDrawer');
        const backdrop = document.getElementById('schDrawerBackdrop');
        const closeBtn = document.getElementById('sdClose');

        function close() {
            drawer.classList.add('translate-x-full');
            backdrop.classList.add('pointer-events-none');
            backdrop.classList.remove('opacity-100');
            document.body.style.overflow = '';
        }
        closeBtn?.addEventListener('click', close);
        backdrop?.addEventListener('click', close);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && !drawer.classList.contains('translate-x-full')) close();
        });
    })();


    // ===== AI Search =====
    function buildSchedulesContextForAI() {
        // keep it compact: only the essentials the model needs
        const items = SCHEDULES.slice(0, 60).map(e => ({
            date: e.date,
            time: e.time,
            title: e.title,
            type: e.type,
            chamber: e.chamber,
            committee: e.committee || '',
            location: e.location,
            city: e.city
        }));
        return {
            title: document.title,
            url: location.href,
            metaDesc: 'Schedules of plenary sessions, committee hearings, and public consultations.',
            nav: ['Schedules', 'Calendar', 'Legislation', 'Resources'],
            sections: [{
                id: 'schedules',
                name: 'Schedules',
                text: 'Local demo data for sessions, committee hearings, and public consultations with dates, chambers, committees, and locations.'
            }],
            items
        };
    }

    function showAIBox() {
        const box = document.getElementById('schAIBox');
        if (box.classList.contains('hidden')) box.classList.remove('hidden');
    }

    function resetAIBox() {
        const text = document.getElementById('schAIText');
        text.textContent = '';
    }

    function streamAIWrite() {
        const el = document.getElementById('schAIText');
        return (chunk) => {
            // sanitize any faux “seal” tokens if they ever appear
            if (chunk.includes('ph-seal-watermark') || /\[seal\]/i.test(chunk)) {
                chunk = chunk.replace(/ph-seal-watermark/gi, '<img src="/assets/ph_seal.png">')
                    .replace(/\[seal\]/gi, '<img src="/assets/ph_seal.png">');
            }
            el.insertAdjacentHTML('beforeend', chunk);
            el.parentElement.scrollTop = el.parentElement.scrollHeight;
        };
    }

    function pickForAI(naturalQuery) {
        const qObj = parseQuery(naturalQuery);
        const drFrom = $('#schFrom').value ? fmtDate($('#schFrom').value) : null;
        const drTo = $('#schTo').value ? fmtDate($('#schTo').value) : null;

        // Use same logic as table
        const rows = SCHEDULES
            .filter(r => matchesFilters(r, qObj, drFrom, drTo))
            .sort((a, b) => (a.date + a.time).localeCompare(b.date + b.time));

        // Trim to keep payload small
        return rows.slice(0, 30).map(r => ({
            date: r.date,
            time: r.time,
            title: r.title,
            type: r.type,
            chamber: r.chamber,
            committee: r.committee || '',
            location: r.location,
            city: r.city
        }));
    }

    function getTableItemsFromDOM() {
        const rows = [...document.querySelectorAll('#schTbody tr')];
        return rows.map(tr => {
            const item = {
                date: tr.getAttribute('data-date') || '',
                time: tr.getAttribute('data-time') || '',
                title: tr.getAttribute('data-title') || '',
                type: tr.getAttribute('data-type') || '',
                chamber: tr.getAttribute('data-chamber') || '',
                committee: tr.getAttribute('data-committee') || '',
                location: tr.getAttribute('data-location') || '',
                city: tr.getAttribute('data-city') || '',
            };

            item.line = [
                `${toHuman(item.date)} — ${item.time}`,
                item.title,
                [item.chamber, item.committee].filter(Boolean).join(' / '),
                [item.location, item.city].filter(Boolean).join(', '),
                item.type
            ].join(' | ');

            return item;
        });
    }

    function extractAIKeys(text, candidateKeySet) {
        let cleaned = text.trim()
            .replace(/^```[a-zA-Z]*\s*/g, '') // opening ``` or ```json
            .replace(/```$/g, '') // closing ```
            .trim();

        const arraySlice = cleaned.startsWith('[') ?
            cleaned :
            (cleaned.match(/\[[\s\S]*\]/)?.[0] || cleaned);

        let arr = null;
        try {
            arr = JSON.parse(arraySlice);
        } catch (_) {
            const m = cleaned.match(/"key"\s*:\s*"([^"]+)"/g) || [];
            const keys = m
                .map(s => s.replace(/.*"key"\s*:\s*"|".*/g, ''))
                .filter(Boolean);
            return [...new Set(keys)].filter(k => candidateKeySet.has(k));
        }
        let keys = [];
        if (Array.isArray(arr)) {
            for (const it of arr) {
                if (typeof it === 'string') {
                    if (candidateKeySet.has(it)) keys.push(it);
                } else if (it && typeof it === 'object') {
                    if (typeof it.key === 'string') {
                        if (candidateKeySet.has(it.key)) keys.push(it.key);
                    } else {
                        for (const v of Object.values(it)) {
                            if (typeof v === 'string' && candidateKeySet.has(v)) {
                                keys.push(v);
                                break;
                            }
                        }
                    }
                }
            }
        }
        keys = [...new Set(keys)].filter(k => candidateKeySet.has(k));
        return keys;
    }

    // ===== SEARCH UI STATE MANAGEMENT =====
    function toggleSearchButtonState(isLoading) {
        const btn = $('#schSearchBtn');
        if (isLoading) {
            // STOP STATE: Transparent background, red border, matches absolute positioning
            btn.innerHTML = '<i data-lucide="square" class="w-3.5 h-3.5"></i> Stop';
            btn.className = "absolute right-1 top-1.5 px-3 py-1.5 rounded-lg border-2 border-red-500 text-red-600 bg-transparent hover:bg-red-50 text-sm font-bold transition-all flex items-center gap-1.5";
        } else {
            // SEARCH STATE: Reset to your original btn-primary look
            btn.innerHTML = 'Search';
            btn.className = "absolute right-1 top-1.5 px-3 py-1.5 rounded-lg text-white text-sm font-semibold btn-primary transition-all";
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    // ===== DEBOUNCED SEARCH =====
    function handleSearchInput(e) {
        clearTimeout(searchDebounceTimer);
        const query = e.target.value.trim();
        aiRestrictKeys = null;

        if (query === '') {
            resetSearchState();
            return;
        }
        renderTable();
        searchDebounceTimer = setTimeout(() => {
            if (aiAbortController) {
                aiAbortController.abort();
                aiAbortController = null;
            }
            executeSearch();
        }, 600);
    }
    async function executeSearch() {
        const q = $('#schSearch').value.trim();
        if (!q) return;

        page = 1;
        aiAbortController = new AbortController();
        toggleSearchButtonState(true);

        try {
            await runAISchedulesSearch(q, aiAbortController.signal);
            renderTable();
        } catch (err) {
            if (err.name !== 'AbortError') {
                console.error('Search failed:', err);
                aiRestrictKeys = [];
                renderTable();
            }
        } finally {
            toggleSearchButtonState(false);
            aiAbortController = null;
        }
    }

    function resetSearchState() {
        if (aiAbortController) aiAbortController.abort();
        $('#schSearch').value = '';
        aiRestrictKeys = null;
        page = 1;
        renderTable();
        hideAIBox();
    }
    async function runAISchedulesSearch(naturalQuery, signal) {

        const requestId = ++currentSearchRequestId;

        const q = $('#schSearch').value.trim();

        if (!q) {
            aiRestrictKeys = null;
            page = 1;
            renderTable();
            return;
        }

        try {
            const qObj = parseQuery(q);
            const drFrom = $('#schFrom').value ? fmtDate($('#schFrom').value) : null;
            const drTo = $('#schTo').value ? fmtDate($('#schTo').value) : null;

            let candidates = SCHEDULES.map(r => ({
                key: makeKey(r),
                date: r.date,
                time: r.time,
                title: r.title,
                type: r.type,
                chamber: r.chamber,
                committee: r.committee || '',
                location: r.location,
                city: r.city,
                line: `${toHuman(r.date)} — ${r.time} | ${r.title} | ${r.chamber} | ${r.city} | ${r.type}`
            }));

            if (!candidates.length) {
                console.error("No items in SCHEDULES array at all.");
                return;
            }

            const prompt = `
User query: ${JSON.stringify(naturalQuery)}

You are given an array "items" (already filtered by the UI). Each item has:
{ key, date, time, title, type, chamber, committee, location, city, line }

RULES (very important):
- Only return keys that COME FROM the provided "items".
- Do NOT invent or infer any keys or dates not present in "items".
- Output RAW JSON only (no markdown/code fences, no commentary).
- If you would output a key not found in "items", DROP IT.

TASK:
1) Pick the best-matching rows for the user's query (fuzzy allowed).
2) OUTPUT JSON ARRAY ONLY: [{"key":"..."}], best-first.
3) If no strong match, return the 3–8 closest, ONLY from "items".
`.trim();

            const context = {
                title: document.title,
                url: location.href,
                metaDesc: 'Filtered schedule rows for AI ranking.',
                items: candidates
            };

            const API_BASE =
                "https://open-ai-hosting-free-production.up.railway.app/api/assistant/gemini";
            const res = await fetch(`${API_BASE}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                signal: signal,
                body: JSON.stringify({
                    message: prompt,
                    context
                })
            });

            let keys = [];

            if (res.ok && res.body) {
                const reader = res.body.getReader();
                const decoder = new TextDecoder('utf-8');
                let buf = '',
                    done = false;

                while (!done) {
                    const {
                        value,
                        done: d
                    } = await reader.read();
                    done = d;
                    if (value) buf += decoder.decode(value, {
                        stream: true
                    });
                }

                const text = buf.trim();
                console.log('🧾 Raw AI Response:', text);

                const candidateKeySet = new Set(candidates.map(i => i.key));
                keys = extractAIKeys(text, candidateKeySet);
            } else {}

            if (!keys.length) {
                keys = localFuzzyKeys(naturalQuery, candidates);
            }
            const itemKeySet = new Set(candidates.map(i => i.key));
            let validKeys = keys.filter(k => itemKeySet.has(k));

            if (!validKeys.length) {
                const fuzzy = localFuzzyKeys(naturalQuery, candidates);
                validKeys = fuzzy.filter(k => itemKeySet.has(k));
                if (!validKeys.length) validKeys = candidates.map(i => i.key);
            }

            if (requestId !== currentSearchRequestId) return;
            if (!$('#schSearch').value.trim()) return;

            aiRestrictKeys = (validKeys && validKeys.length) ? validKeys : null;
            page = 1;
            renderTable();


        } catch (err) {
            console.error('💥 AI schedules search error:', err);
            aiRestrictKeys = null;
            page = 1;
            renderTable();
        } finally {
            console.groupEnd();
        }
    }

    function localFuzzyKeys(q, items) {
        q = (q || '').toLowerCase();
        if (!q) return items.map(i => i.key);

        const tokens = q.split(/\s+/).filter(Boolean);
        const scoreRow = it => {
            const hay = `${it.title} ${it.committee} ${it.city} ${it.line}`.toLowerCase();
            let score = 0;
            tokens.forEach(t => {
                if (t.length <= 2) return;
                if (hay.includes(t)) score += 2;
                if (/energy/.test(t) && /energy/.test(hay)) score += 3;
                if (/pasay/.test(t) && /pasay/.test(hay)) score += 3;
                if (/hearing|committee/.test(t) && /committee|hearing/.test(hay)) score += 2;
            });
            return score;
        };

        return items
            .map(it => ({
                key: it.key,
                score: scoreRow(it)
            }))
            .sort((a, b) => b.score - a.score)
            .filter(x => x.score > 0)
            .map(x => x.key);
    }

    function localFuzzyKeys(q, items) {
        q = (q || '').toLowerCase();
        if (!q) return items.map(i => i.key);

        const tokens = q.split(/\s+/).filter(Boolean);
        const scoreRow = (it) => {
            const hay = `${it.title} ${it.committee} ${it.city} ${it.line}`.toLowerCase();
            let score = 0;
            tokens.forEach(t => {
                if (t.length <= 2) return;
                if (hay.includes(t)) score += 2;
                // small boosts for obvious intent words
                if (/energy/.test(t) && /energy/.test(hay)) score += 3;
                if (/pasay/.test(t) && /pasay/.test(hay)) score += 3;
                if (/hearing|committee/.test(t) && /committee|hearing/.test(hay)) score += 2;
            });
            return score;
        };

        return items
            .map(it => ({
                key: it.key,
                score: scoreRow(it)
            }))
            .sort((a, b) => b.score - a.score)
            .filter(x => x.score > 0) // keep only weak+ matches
            .map(x => x.key);
    }

    function renderTable() {
        const q = $('#schSearch').value;
        const qObj = parseQuery(q);
        const drFrom = $('#schFrom').value ? fmtDate($('#schFrom').value) : null;
        const drTo = $('#schTo').value ? fmtDate($('#schTo').value) : null;

        let filtered;
        if (Array.isArray(aiRestrictKeys)) {
            const order = new Map(aiRestrictKeys.map((k, i) => [k, i]));
            filtered = SCHEDULES.filter(r => order.has(makeKey(r)))
                .sort((a, b) => order.get(makeKey(a)) - order.get(makeKey(b)));
        } else {
            filtered = SCHEDULES.filter(r => matchesFilters(r, qObj, drFrom, drTo))
                .sort((a, b) => (a.date + a.time).localeCompare(b.date + b.time));
        }

        const total = filtered.length;
        const pages = Math.max(1, Math.ceil(total / pageSize));
        if (page > pages) page = pages;
        const start = (page - 1) * pageSize;
        const slice = filtered.slice(start, start + pageSize);
        if (total === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">
            <i data-lucide="search-x" class="w-8 h-8 mx-auto mb-2 opacity-20"></i>
            No schedules found matching "${q}"
        </td></tr>`;
        } else {
            tbody.innerHTML = slice.map(r => `
            <tr class="hover:bg-gray-50" data-key="${makeKey(r).replace(/"/g,'&quot;')}">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="font-semibold text-gray-900">${toHuman(r.date)}</div>
                <div class="text-[11px] text-gray-500">${pill(r.type)}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2 text-gray-800">
                  <i data-lucide="clock" class="w-4 h-4"></i>${r.time}
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="font-semibold text-gray-900">${r.title}</div>
                ${r.committee ? `<div class="text-xs text-gray-600 mt-0.5">Committee: ${r.committee}</div>` : ''}
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">${chamberBadge(r.chamber)}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-gray-900">${r.location}</div>
                <div class="text-xs text-gray-600">${r.city}</div>
              </td>
            </tr>
        `).join('');
        }

        $('#schCount').textContent = `${total} result${total===1?'':'s'}`;
        $('#schPage').textContent = `Page ${page} of ${pages}`;

        if (window.lucide) setTimeout(() => window.lucide.createIcons(), 0);
    }
    // ===== EVENTS =====
    $('#schSearchBtn').addEventListener('click', executeSearch);
    $('#schSearch').addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            page = 1;
            const q = $('#schSearch').value.trim();
            runAISchedulesSearch(q || 'show relevant events here');
        }
    });

    $('#schSearch').addEventListener('input', handleSearchInput);

    document.getElementById('schAIClear')?.addEventListener('click', () => {
        clearAIResults();
        hideAIBox();
    });

    $('#schFrom').addEventListener('change', () => {
        page = 1;
        renderTable();
        const q = $('#schSearch').value.trim();
        runAISchedulesSearch(q || 'show relevant events here');
    });
    $('#schTo').addEventListener('change', () => {
        page = 1;
        renderTable();
        const q = $('#schSearch').value.trim();
        runAISchedulesSearch(q || 'show relevant events here');
    });

    $$('input[data-type]').forEach(cb => cb.addEventListener('change', () => {
        page = 1;
        renderTable();
    }));
    $$('input[data-chamber]').forEach(cb => cb.addEventListener('change', () => {
        page = 1;
        renderTable();
    }));

    $('#schPrev').addEventListener('click', () => {
        if (page > 1) {
            page--;
            renderTable();
        }
    });
    $('#schNext').addEventListener('click', () => {
        page++;
        renderTable();
    });

    $('#schReset').addEventListener('click', () => {
        $('#schSearch').value = '';
        $('#schFrom').value = '';
        $('#schTo').value = '';
        $$('input[data-type],input[data-chamber]').forEach(cb => cb.checked = true);
        aiRestrictKeys = null;
        page = 1;
        renderTable();
        resetSearchState();
    });

    $('#schExport').addEventListener('click', () => {
        const rows = [
            ['Date', 'Time', 'Title', 'Type', 'Chamber', 'Committee', 'Location', 'City']
        ];
        const q = $('#schSearch').value;
        const qObj = parseQuery(q);
        const drFrom = $('#schFrom').value ? fmtDate($('#schFrom').value) : null;
        const drTo = $('#schTo').value ? fmtDate($('#schTo').value) : null;

        const data = SCHEDULES.filter(r => matchesFilters(r, qObj, drFrom, drTo))
            .sort((a, b) => (a.date + b.time).localeCompare(b.date + b.time));

        data.forEach(r => rows.push([r.date, r.time, r.title, r.type, r.chamber, r.committee || '', r.location, r.city]));
        const csv = rows.map(cols => cols.map(v => `"${String(v).replace(/"/g,'""')}"`).join(',')).join('\n');
        const blob = new Blob([csv], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `schedules_export_${Date.now()}.csv`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === '/' && !/input|textarea/i.test(document.activeElement.tagName)) {
            e.preventDefault();
            $('#schSearch').focus();
        }
    });
</script>