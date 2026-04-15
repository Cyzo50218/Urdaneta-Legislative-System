<section id="resources" class="hash-section py-20 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Government-style header -->
    <div class="mb-8 rounded-2xl overflow-hidden border border-green-100 shadow-sm relative">
      <div class="absolute inset-0"
           style="background: linear-gradient(90deg, var(--ph-blue-dark) 0%, var(--ph-blue) 50%, var(--ph-blue-light) 100%);"></div>
      <div class="relative grid lg:grid-cols-2 gap-6 p-6 lg:p-10">
        <div class="text-white">
          <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/15 border border-white/25 mb-3">
            <i data-lucide="book-open" class="w-4 h-4"></i>
            <span class="text-xs font-semibold tracking-wide">RESOURCE HUB</span>
          </div>
          <h2 class="text-3xl lg:text-4xl font-black leading-tight">Legislative Resource Center</h2>
          <p class="text-green-100 mt-2 max-w-2xl">
            Guides, datasets, and explainer materials to help citizens, researchers, and public servants
            understand and navigate the Philippine legislative process.
          </p>
        </div>

        <!-- Quick Search & Filters -->
        <div class="bg-white/95 rounded-xl p-4 lg:p-5 border border-white/30 backdrop-blur-sm">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
              <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-emerald-600"></i>
              <input id="res-search" type="search" placeholder="Search guides, datasets, FAQs… (/ to focus)"
                     class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <select id="res-category"
              class="sm:w-48 px-3 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
              <option value="">All categories</option>
              <option value="guides">Guides</option>
              <option value="data">Data</option>
              <option value="faq">FAQs</option>
              <option value="process">Process</option>
              <option value="civic">Civic</option>
              <option value="dev">Developers</option>
            </select>
          </div>

          <!-- Quick chips -->
          <div class="mt-3 flex flex-wrap gap-2">
            <button data-chip value="guides" class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">Guides</button>
            <button data-chip value="data" class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">Datasets</button>
            <button data-chip value="process" class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">Process 101</button>
            <button data-chip value="faq" class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">FAQs</button>
            <button data-chip value="dev" class="px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">API / Developers</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Featured callouts -->
    <div class="grid md:grid-cols-3 gap-6 mb-10">
      <a href="#res-downloads" class="group p-6 rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center icon-wrapper mb-4">
          <i data-lucide="download" class="text-white w-6 h-6"></i>
        </div>
        <h3 class="font-bold">Download Center</h3>
        <p class="text-gray-600 text-sm mt-1">Get machine-readable datasets, sample CSVs, and templates.</p>
        <div class="mt-3 text-emerald-700 font-semibold inline-flex items-center gap-1">
          Browse files <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </div>
      </a>

      <button onclick="openAIModal()" class="text-left group p-6 rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center icon-wrapper mb-4">
          <i data-lucide="sparkles" class="text-white w-6 h-6"></i>
        </div>
        <h3 class="font-bold">Ask the Assistant</h3>
        <p class="text-gray-600 text-sm mt-1">Type a question about bills or the process. Get plain-language answers.</p>
        <div class="mt-3 text-emerald-700 font-semibold inline-flex items-center gap-1">
          Open AI Assistant <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </div>
      </button>

      <button onclick="openAssistantModal()" class="text-left group p-6 rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center icon-wrapper mb-4">
          <i data-lucide="messages-square" class="text-white w-6 h-6"></i>
        </div>
        <h3 class="font-bold">Free AI Legislative Assistant</h3>
        <p class="text-gray-600 text-sm mt-1">Ask about bills, the legislative process, committees, schedules, and civic info in the Philippines.</p>
        <div class="mt-3 text-emerald-700 font-semibold inline-flex items-center gap-1">
          Chat with AI <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </div>
      </button>

      <a href="#res-help" class="group p-6 rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center icon-wrapper mb-4">
          <i data-lucide="life-buoy" class="text-white w-6 h-6"></i>
        </div>
        <h3 class="font-bold">Help & Support</h3>
        <p class="text-gray-600 text-sm mt-1">Contact the Help Desk or see troubleshooting steps.</p>
        <div class="mt-3 text-emerald-700 font-semibold inline-flex items-center gap-1">
          Get help <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </div>
      </a>
    </div>

    <!-- Resource Cards -->
    <div id="res-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Each card carries data-category & data-tags for filtering -->
      <article class="p-6 rounded-2xl border border-gray-200 bg-white shadow-sm" data-category="guides" data-tags="guide,bill,search,filters">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="font-bold">Bill Tracker Guide</h3>
            <p class="text-gray-600 text-sm mt-1">Learn advanced search (by author, status, chamber, committee, dates).</p>
          </div>
          <span class="text-[11px] px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">Guide</span>
        </div>
        <div class="mt-4 flex flex-wrap gap-2">
          <span class="text-xs px-2 py-1 rounded bg-gray-100">Search</span>
          <span class="text-xs px-2 py-1 rounded bg-gray-100">Filters</span>
          <span class="text-xs px-2 py-1 rounded bg-gray-100">Bills</span>
        </div>
        <div class="mt-4 flex items-center gap-2">
          <a href="#legislation" class="btn-primary text-white px-3 py-2 rounded-lg text-sm inline-flex items-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i>Open tracker
          </a>
          <button class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50 inline-flex items-center gap-2" data-copy="#legislation">
            <i data-lucide="link" class="w-4 h-4"></i>Copy link
          </button>
        </div>
      </article>

      <article class="p-6 rounded-2xl border border-gray-200 bg-white shadow-sm" data-category="process" data-tags="process,how a bill becomes law,flow,timeline">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="font-bold">How a Bill Becomes Law (PH)</h3>
            <p class="text-gray-600 text-sm mt-1">Step-by-step flow from filing to enactment, including bicam & IRR.</p>
          </div>
          <span class="text-[11px] px-2 py-1 rounded-full bg-amber-50 text-amber-700">Process</span>
        </div>
        <details class="mt-4 group">
          <summary class="cursor-pointer list-none flex items-center justify-between gap-2 p-3 border rounded-lg text-sm hover:bg-gray-50">
            <span class="font-medium">See timeline</span>
            <i data-lucide="chevron-down" class="w-4 h-4 transition group-open:rotate-180"></i>
          </summary>
          <div class="p-3 text-sm text-gray-700 space-y-1">
            <div>• Filing → First Reading → Committee Work → Second Reading → Third Reading</div>
            <div>• Other Chamber repeats → Bicameral Conference (if needed)</div>
            <div>• President’s Action → RA Publication → IRR (~90 days typical)</div>
          </div>
        </details>
        <div class="mt-4">
          <a href="#resources" class="inline-flex items-center gap-2 text-emerald-700 font-semibold text-sm">
            <i data-lucide="workflow" class="w-4 h-4"></i> Read full explainer
          </a>
        </div>
      </article>

      <article class="p-6 rounded-2xl border border-gray-200 bg-white shadow-sm" data-category="data" data-tags="data,csv,api,download,schemas">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="font-bold">Data Dictionary & Schemas</h3>
            <p class="text-gray-600 text-sm mt-1">Field names, types, and relationships for legislative datasets.</p>
          </div>
          <span class="text-[11px] px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">Data</span>
        </div>
        <div class="mt-4 flex items-center gap-2">
          <a href="#res-downloads" class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50 inline-flex items-center gap-2">
            <i data-lucide="table" class="w-4 h-4"></i>View schema
          </a>
          <a href="#res-downloads" class="btn-primary text-white px-3 py-2 rounded-lg text-sm inline-flex items-center gap-2">
            <i data-lucide="download" class="w-4 h-4"></i>Download CSV
          </a>
        </div>
      </article>

      <article class="p-6 rounded-2xl border border-gray-200 bg-white shadow-sm" data-category="faq" data-tags="faq,help,questions,answers">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="font-bold">Frequently Asked Questions</h3>
            <p class="text-gray-600 text-sm mt-1">Common questions about bill statuses, voting records, and datasets.</p>
          </div>
          <span class="text-[11px] px-2 py-1 rounded-full bg-gray-100 text-gray-800">FAQ</span>
        </div>
        <details class="mt-4 group">
          <summary class="cursor-pointer list-none flex items-center justify-between gap-2 p-3 border rounded-lg text-sm hover:bg-gray-50">
            <span class="font-medium">What does “In Committee” mean?</span>
            <i data-lucide="chevron-down" class="w-4 h-4 transition group-open:rotate-180"></i>
          </summary>
          <div class="p-3 text-sm text-gray-700">
            The measure is being studied, amended, or scheduled for hearing by the relevant committee.
          </div>
        </details>
        <details class="mt-2 group">
          <summary class="cursor-pointer list-none flex items-center justify-between gap-2 p-3 border rounded-lg text-sm hover:bg-gray-50">
            <span class="font-medium">Where can I find roll-call votes?</span>
            <i data-lucide="chevron-down" class="w-4 h-4 transition group-open:rotate-180"></i>
          </summary>
          <div class="p-3 text-sm text-gray-700">
            Votes are attached to session journals or measure pages when available.
          </div>
        </details>
      </article>

      <article class="p-6 rounded-2xl border border-gray-200 bg-white shadow-sm" data-category="civic" data-tags="civic,participation,contact,representatives">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="font-bold">Engage Your Representatives</h3>
            <p class="text-gray-600 text-sm mt-1">Find district/party-list offices and contact details.</p>
          </div>
          <span class="text-[11px] px-2 py-1 rounded-full bg-yellow-50 text-yellow-700">Civic</span>
        </div>
        <div class="mt-4 flex items-center gap-2">
          <a href="#representatives" class="btn-primary text-white px-3 py-2 rounded-lg text-sm inline-flex items-center gap-2">
            <i data-lucide="users" class="w-4 h-4"></i>Find representatives
          </a>
          <a href="#resources" class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50 inline-flex items-center gap-2">
            <i data-lucide="map-pin" class="w-4 h-4"></i>Office hours
          </a>
        </div>
      </article>

      <!--
      <article class="p-6 rounded-2xl border border-gray-200 bg-white shadow-sm" data-category="dev" data-tags="api,developers,sdk,examples">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h3 class="font-bold">Developers: API & Examples</h3>
            <p class="text-gray-600 text-sm mt-1">How to query measures, members, and committees programmatically.</p>
          </div>
          <span class="text-[11px] px-2 py-1 rounded-full bg-indigo-50 text-indigo-700">API</span>
        </div>
        <div class="mt-4 flex items-center gap-2">
          <a href="#res-downloads" class="px-3 py-2 rounded-lg border text-sm hover:bg-gray-50 inline-flex items-center gap-2">
            <i data-lucide="code" class="w-4 h-4"></i>SDK samples
          </a>
          <a href="#res-downloads" class="btn-primary text-white px-3 py-2 rounded-lg text-sm inline-flex items-center gap-2">
            <i data-lucide="link" class="w-4 h-4"></i>Get API key (demo)
          </a>
        </div>
      </article>
      -->
    </div>

    <!-- Download Center -->
    <div id="res-downloads" class="mt-12">
      <div class="mb-4 flex items-center gap-3">
        <div class="icon-wrapper w-10 h-10 rounded-xl flex items-center justify-center">
          <i data-lucide="hard-drive-download" class="text-white w-5 h-5"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900">Download Center</h3>
      </div>

      <div class="overflow-x-auto bg-white rounded-2xl border border-gray-200 shadow-sm">
        <table class="min-w-full text-left">
          <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
            <tr>
              <th class="px-5 py-3">File</th>
              <th class="px-5 py-3">Description</th>
              <th class="px-5 py-3">Updated</th>
              <th class="px-5 py-3">Size</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y text-sm">
            <tr>
              <td class="px-5 py-3 font-medium text-gray-900">measures_sample.csv</td>
              <td class="px-5 py-3 text-gray-700">Sample measures (SBN/HBN) with status & authors</td>
              <td class="px-5 py-3">Oct 10, 2025</td>
              <td class="px-5 py-3">128 KB</td>
              <td class="px-5 py-3">
                <button class="btn-primary text-white px-3 py-2 rounded-lg inline-flex items-center gap-2">
                  <i data-lucide="download" class="w-4 h-4"></i>Download
                </button>
              </td>
            </tr>
            <tr>
              <td class="px-5 py-3 font-medium text-gray-900">committees.json</td>
              <td class="px-5 py-3 text-gray-700">Committees directory + jurisdiction</td>
              <td class="px-5 py-3">Oct 9, 2025</td>
              <td class="px-5 py-3">64 KB</td>
              <td class="px-5 py-3">
                <button class="btn-primary text-white px-3 py-2 rounded-lg inline-flex items-center gap-2">
                  <i data-lucide="download" class="w-4 h-4"></i>Download
                </button>
              </td>
            </tr>
            <tr>
              <td class="px-5 py-3 font-medium text-gray-900">data_dictionary.pdf</td>
              <td class="px-5 py-3 text-gray-700">Field definitions & relationships</td>
              <td class="px-5 py-3">Sep 30, 2025</td>
              <td class="px-5 py-3">392 KB</td>
              <td class="px-5 py-3">
                <button class="btn-primary text-white px-3 py-2 rounded-lg inline-flex items-center gap-2">
                  <i data-lucide="download" class="w-4 h-4"></i>Download
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Glossary -->
    <div class="mt-12">
      <div class="mb-4 flex items-center gap-3">
        <div class="icon-wrapper w-10 h-10 rounded-xl flex items-center justify-center">
          <i data-lucide="book" class="text-white w-5 h-5"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900">Legislative Glossary</h3>
      </div>
      <div class="grid md:grid-cols-2 gap-4">
        <details class="group bg-white rounded-xl border p-4">
          <summary class="cursor-pointer list-none flex items-center justify-between gap-2">
            <span class="font-semibold">In Committee</span>
            <i data-lucide="chevron-down" class="w-4 h-4 transition group-open:rotate-180"></i>
          </summary>
          <p class="text-sm text-gray-700 mt-2">The measure is under consideration by the relevant committee.</p>
        </details>
        <details class="group bg-white rounded-xl border p-4">
          <summary class="cursor-pointer list-none flex items-center justify-between gap-2">
            <span class="font-semibold">Bicameral Conference</span>
            <i data-lucide="chevron-down" class="w-4 h-4 transition group-open:rotate-180"></i>
          </summary>
          <p class="text-sm text-gray-700 mt-2">A panel that reconciles differences between Senate and House versions.</p>
        </details>
        <details class="group bg-white rounded-xl border p-4">
          <summary class="cursor-pointer list-none flex items-center justify-between gap-2">
            <span class="font-semibold">IRR</span>
            <i data-lucide="chevron-down" class="w-4 h-4 transition group-open:rotate-180"></i>
          </summary>
          <p class="text-sm text-gray-700 mt-2">Implementing Rules and Regulations that detail how a law is carried out.</p>
        </details>
        <details class="group bg-white rounded-xl border p-4">
          <summary class="cursor-pointer list-none flex items-center justify-between gap-2">
            <span class="font-semibold">Enacted</span>
            <i data-lucide="chevron-down" class="w-4 h-4 transition group-open:rotate-180"></i>
          </summary>
          <p class="text-sm text-gray-700 mt-2">Signed into law and published; ready for implementation.</p>
        </details>
      </div>
    </div>

    <!-- Help & Accessibility -->
    <div id="res-help" class="mt-12 grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-3">
          <div class="icon-wrapper w-10 h-10 rounded-xl flex items-center justify-center">
            <i data-lucide="headset" class="text-white w-5 h-5"></i>
          </div>
          <h3 class="text-2xl font-black text-gray-900">Help Desk</h3>
        </div>
        <p class="text-gray-700 mb-4">Questions about data, schedules, or status terms? We’re here to help.</p>
        <div class="grid sm:grid-cols-2 gap-3">
          <a href="mailto:info@example.gov.ph" class="px-4 py-3 rounded-lg border hover:bg-gray-50 inline-flex items-center gap-2">
            <i data-lucide="mail" class="w-4 h-4"></i> info@example.gov.ph
          </a>
          <a href="#resources" class="px-4 py-3 rounded-lg border hover:bg-gray-50 inline-flex items-center gap-2">
            <i data-lucide="file-question" class="w-4 h-4"></i> Submit a question
          </a>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-3">
          <div class="icon-wrapper w-10 h-10 rounded-xl flex items-center justify-center">
            <i data-lucide="accessibility" class="text-white w-5 h-5"></i>
          </div>
          <h3 class="text-xl font-black text-gray-900">Accessibility</h3>
        </div>
        <ul class="text-sm text-gray-700 space-y-2">
          <li>High contrast and keyboard navigation supported</li>
          <li>Search shortcut: <span class="font-mono">/</span></li>
          <li>ARIA labels on interactive controls</li>
        </ul>
      </div>
    </div>

  </div>
</section>

<script>
  (() => {
    // Helpers
    const $ = (s, r = document) => r.querySelector(s);
    const $$ = (s, r = document) => [...r.querySelectorAll(s)];

    // Filter/search state
    let q = '';
    let category = '';

    const grid = $('#res-grid');
    const search = $('#res-search');
    const select = $('#res-category');
    const cards = $$('[data-category]', grid);

    function normalize(str) { return (str || '').toLowerCase().trim(); }

    function applyFilters() {
      const ql = normalize(q);
      const cat = normalize(category);
      cards.forEach(card => {
        const ccat = normalize(card.dataset.category);
        const tags = normalize(card.dataset.tags);
        const text = normalize(card.innerText);
        const matchCategory = !cat || ccat === cat;
        const matchSearch = !ql || text.includes(ql) || tags.includes(ql);
        card.style.display = (matchCategory && matchSearch) ? '' : 'none';
      });
    }

    // Search box
    search?.addEventListener('input', (e) => { q = e.target.value; applyFilters(); });

    // Category dropdown
    select?.addEventListener('change', (e) => { category = e.target.value; applyFilters(); });

    // Chips
    $$('[data-chip]').forEach(chip => {
      chip.addEventListener('click', () => {
        category = chip.value || '';
        if (select) select.value = category;
        applyFilters();
      });
    });

    // Copy link buttons
    $$('[data-copy]').forEach(btn => {
      btn.addEventListener('click', () => {
        const href = btn.getAttribute('data-copy') || '#resources';
        const url = location.origin + location.pathname + href;
        navigator.clipboard.writeText(url).then(() => {
          btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i>Copied';
          setTimeout(() => {
            btn.innerHTML = '<i data-lucide="link" class="w-4 h-4"></i>Copy link';
            if (window.lucide) window.lucide.createIcons();
          }, 1500);
          if (window.lucide) window.lucide.createIcons();
        });
      });
    });

    // Slash to focus search (accessibility shortcut)
    window.addEventListener('keydown', (e) => {
      if (e.key === '/' && !/input|textarea/i.test(document.activeElement.tagName)) {
        e.preventDefault();
        search?.focus();
      }
    });

    // Initialize icons
    if (window.lucide) window.lucide.createIcons();
  })();
</script>
