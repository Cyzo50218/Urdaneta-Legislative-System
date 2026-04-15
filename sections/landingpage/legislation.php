<section id="legislation" class="hash-section py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="icon-wrapper w-12 h-12 rounded-xl flex items-center justify-center">
          <i data-lucide="file-text" class="text-white w-6 h-6"></i>
        </div>
        <div>
          <h2 class="text-3xl lg:text-4xl font-black text-gray-900">Legislation</h2>
          <p class="text-sm text-gray-600">Search bills, resolutions, and track their status across chambers</p>
        </div>
      </div>
      <a href="#" onclick="openAIModal();return false;" class="hidden md:inline-flex items-center gap-2 btn-primary text-white px-4 py-2 rounded-lg">
        <i data-lucide="wand-2" class="w-4 h-4"></i> Open AI Drafting
      </a>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4 md:p-5 mb-6">
      <div class="grid lg:grid-cols-5 md:grid-cols-3 grid-cols-1 gap-3">
        <div class="lg:col-span-2">
          <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-emerald-600"></i>
            <input id="leg-search" type="search" placeholder="Search title, number, author, keywords… (/ to focus)"
              class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
          </div>
        </div>
        <select id="leg-chamber" class="px-3 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="">Chamber (All)</option>
          <option value="senate">Senate</option>
          <option value="house">House</option>
          <option value="joint">Joint</option>
        </select>
        <select id="leg-type" class="px-3 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="">Type (All)</option>
          <option value="bill">Bill</option>
          <option value="resolution">Resolution</option>
        </select>
        <select id="leg-status" class="px-3 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <option value="">Status (All)</option>
          <option value="filed">Filed</option>
          <option value="in-committee">In Committee</option>
          <option value="approved">Approved</option>
          <option value="enacted">Enacted</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-left">
          <thead class="bg-gray-50/70 text-[12px] uppercase tracking-wide text-gray-600">
            <tr>
              <th class="px-5 py-3">Number</th>
              <th class="px-5 py-3">Title</th>
              <th class="px-5 py-3">Author(s)</th>
              <th class="px-5 py-3">Chamber</th>
              <th class="px-5 py-3">Status</th>
              <th class="px-5 py-3">Last Action</th>
              <th class="px-5 py-3"></th>
            </tr>
          </thead>
          <tbody id="leg-tbody" class="divide-y text-sm"></tbody>
        </table>
      </div>

      <!-- Footer / results -->
      <div class="flex items-center justify-between px-4 py-3 bg-gray-50">
        <div class="text-xs text-gray-600">
          <span id="leg-count">0</span> result(s)
        </div>
        <div class="flex items-center gap-2 text-xs">
          <button id="leg-prev" class="px-2.5 py-1.5 rounded-lg border hover:bg-white disabled:opacity-40">Prev</button>
          <span id="leg-page" class="text-gray-600">1</span>
          <button id="leg-next" class="px-2.5 py-1.5 rounded-lg border hover:bg-white disabled:opacity-40">Next</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Drawer BACKDROP (for click-outside) -->
  <div id="leg-drawer-backdrop"
    class="fixed inset-0 bg-black/40 backdrop-blur-[1px] opacity-0 pointer-events-none transition-opacity z-40"></div>

  <!-- Preview Drawer -->
  <div id="leg-drawer"
    class="fixed top-0 right-0 h-full w-full sm:w-[520px] bg-white border-l border-gray-200 shadow-2xl translate-x-full transition-transform z-50 overscroll-contain"
    role="dialog" aria-modal="true" aria-labelledby="d-title">
    <!-- Sticky header -->
    <div class="sticky top-0 z-10 flex items-center justify-between px-5 py-4 border-b"
      style="background: linear-gradient(90deg, var(--ph-blue-dark) 0%, var(--ph-blue) 50%, var(--ph-blue-dark) 100%);">
      <div>
        <div id="d-num" class="text-white/90 text-xs tracking-wide uppercase"></div>
        <div id="d-title" class="text-white font-bold text-lg leading-tight">—</div>
      </div>
      <button id="d-close" class="text-white/90 hover:text-white" aria-label="Close preview">
        <i data-lucide="x" class="w-6 h-6"></i>
      </button>
    </div>

    <!-- Scrollable content area -->
    <div id="leg-drawer-body" class="h-[calc(100%-64px)] overflow-y-auto p-5 space-y-5">
      <div class="grid grid-cols-2 gap-3 text-sm">
        <div class="p-3 rounded-xl border">
          <div class="text-[11px] text-gray-500">Chamber</div>
          <div id="d-chamber" class="font-semibold"></div>
        </div>
        <div class="p-3 rounded-xl border">
          <div class="text-[11px] text-gray-500">Type</div>
          <div id="d-type" class="font-semibold"></div>
        </div>
        <div class="p-3 rounded-xl border">
          <div class="text-[11px] text-gray-500">Status</div>
          <div id="d-status" class="font-semibold"></div>
        </div>
        <div class="p-3 rounded-xl border">
          <div class="text-[11px] text-gray-500">Filed</div>
          <div id="d-filed" class="font-semibold"></div>
        </div>
      </div>

      <div class="p-4 rounded-xl border">
        <div class="text-[11px] text-gray-500 mb-1">Author(s)</div>
        <div id="d-authors" class="text-sm font-medium text-gray-900"></div>
      </div>

      <div class="p-4 rounded-xl border">
        <div class="text-[11px] text-gray-500 mb-2">Summary</div>
        <div id="d-summary" class="text-sm text-gray-800 leading-relaxed">—</div>
      </div>

      <!-- Ask AI (demo) -->
      <div class="rounded-2xl border overflow-hidden">
        <div class="px-4 py-2.5 text-sm font-semibold text-white"
          style="background: linear-gradient(90deg, var(--ph-blue-dark) 0%, var(--ph-blue) 60%, var(--ph-blue-dark) 100%);">
          Ask to Understand (Demo)
        </div>
        <div class="p-4 space-y-3">
          <div class="flex flex-wrap gap-2">
            <button class="ai-chip px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">Explain like I’m 12</button>
            <button class="ai-chip px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">Key impacts for LGUs</button>
            <button class="ai-chip px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">Pros & cons</button>
            <button class="ai-chip px-3 py-1.5 rounded-full border text-xs hover:bg-gray-50">Budget & timelines</button>
          </div>

          <textarea id="ai-q" rows="2"
            class="w-full border rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-emerald-600"
            placeholder="e.g., What does this bill change for LGUs?"></textarea>

          <div class="flex items-center gap-2">
            <button id="ai-ask" class="btn-primary text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
              <i data-lucide="message-circle" class="w-4 h-4"></i> Ask
            </button>
            <button id="ai-stop" class="px-3 py-2 rounded-lg border text-sm hidden">Stop</button>
          </div>

          <div id="ai-a" class="hidden rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-900 whitespace-pre-line"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // =================== Demo Data (free, local) ===================
  let aiController = null;
  let LEGISLATION = [];

  async function loadLegislation() {
    try {
      const response = await fetch('/api/schedules/fetchLegislationDetails.php');
      const result = await response.json();

      if (result.success) {
        LEGISLATION = result.data;
        applyFilters();
      }
    } catch (err) {
      console.error("Legislation fetch error:", err);
    }
  }

  loadLegislation();

  // =================== Helpers ===================
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const chamberLabel = c => c === 'senate' ? 'Senate' : c === 'house' ? 'House' : 'Joint';
  const typeLabel = t => t === 'bill' ? 'Bill' : 'Resolution';
  const fmtDate = iso => new Date(iso).toLocaleDateString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
  const badge = (status) => {
    const map = {
      filed: {
        bg: 'bg-gray-100',
        fg: 'text-gray-800',
        dot: 'bg-gray-500',
        label: 'Filed'
      },
      'in-committee': {
        bg: 'bg-amber-100',
        fg: 'text-amber-900',
        dot: 'bg-amber-500',
        label: 'In Committee'
      },
      approved: {
        bg: 'bg-green-100',
        fg: 'text-green-900',
        dot: 'bg-green-600',
        label: 'Approved'
      },
      enacted: {
        bg: 'bg-emerald-100',
        fg: 'text-emerald-900',
        dot: 'bg-emerald-600',
        label: 'Enacted'
      },
    };
    const v = map[status] || map.filed;
    return `<span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full ${v.bg} ${v.fg}">
            <span class="w-1.5 h-1.5 rounded-full ${v.dot}"></span>${v.label}
          </span>`;
  };

  // =================== State ===================
  let state = {
    q: '',
    chamber: '',
    type: '',
    status: '',
    page: 1,
    perPage: 8,
    data: [...LEGISLATION]
  };

  // =================== Render Table ===================
  function applyFilters() {
    const ql = state.q.trim().toLowerCase();
    let rows = [...LEGISLATION];
    if (ql) {
      rows = rows.filter(r =>
        r.number.toLowerCase().includes(ql) ||
        r.title.toLowerCase().includes(ql) ||
        r.authors.join(' ').toLowerCase().includes(ql) ||
        r.summary.toLowerCase().includes(ql)
      );
    }
    if (state.chamber) rows = rows.filter(r => r.chamber === state.chamber);
    if (state.type) rows = rows.filter(r => r.type === state.type);
    if (state.status) rows = rows.filter(r => r.status === state.status);
    state.data = rows;
    state.page = 1;
    renderTable();
  }

  function renderTable() {
    const tbody = $('#leg-tbody');
    tbody.innerHTML = '';
    const start = (state.page - 1) * state.perPage;
    const slice = state.data.slice(start, start + state.perPage);

    slice.forEach(r => {
      const tr = document.createElement('tr');
      tr.className = 'hover:bg-gray-50';
      tr.innerHTML = `
      <td class="px-5 py-3 font-medium text-gray-900">${r.number}</td>
      <td class="px-5 py-3">
        <div class="font-semibold text-gray-900 line-clamp-2">${r.title}</div>
        <div class="text-xs text-gray-500 mt-0.5">${typeLabel(r.type)}</div>
      </td>
      <td class="px-5 py-3 text-gray-700">${r.authors.join(', ')}</td>
      <td class="px-5 py-3">${chamberLabel(r.chamber)}</td>
      <td class="px-5 py-3">${badge(r.status)}</td>
      <td class="px-5 py-3">
        <div class="text-gray-900">${r.last_action}</div>
        <div class="text-xs text-gray-500">${fmtDate(r.filed)}</div>
      </td>
      <td class="px-5 py-3">
        <div class="flex items-center gap-2">
          <button class="px-2.5 py-1.5 rounded-lg border hover:bg-white text-xs flex items-center gap-1" data-action="preview">
            <i data-lucide="eye" class="w-4 h-4"></i> Preview
          </button>
          <button class="px-2.5 py-1.5 rounded-lg border hover:bg-white text-xs flex items-center gap-1" data-action="explain">
            <i data-lucide="message-circle" class="w-4 h-4"></i> Explain
          </button>
        </div>
      </td>`;
      tr.querySelector('[data-action="preview"]').addEventListener('click', () => openDrawer(r));
      tr.querySelector('[data-action="explain"]').addEventListener('click', () => {
        openDrawer(r);
        startExplain(r, 'What does this mean?');
      });
      tbody.appendChild(tr);
    });

    $('#leg-count').textContent = state.data.length;
    const totalPages = Math.max(1, Math.ceil(state.data.length / state.perPage));
    $('#leg-page').textContent = `${state.page} / ${totalPages}`;
    $('#leg-prev').disabled = state.page <= 1;
    $('#leg-next').disabled = state.page >= totalPages;
    if (window.lucide) setTimeout(() => window.lucide.createIcons(), 0);
  }

  // =================== Drawer + Backdrop ===================
  const drawer = $('#leg-drawer');
  let backdrop = $('#leg-drawer-backdrop');
  if (!backdrop) {
    backdrop = document.createElement('div');
    backdrop.id = 'leg-drawer-backdrop';
    backdrop.className = 'fixed inset-0 bg-black/30 opacity-0 pointer-events-none transition-opacity z-40';
    document.body.appendChild(backdrop);
  }

  function openDrawer(r) {
    $('#d-num').textContent = r.number;
    $('#d-title').textContent = r.title;
    $('#d-chamber').textContent = chamberLabel(r.chamber);
    $('#d-type').textContent = typeLabel(r.type);
    $('#d-status').innerHTML = badge(r.status);
    $('#d-filed').textContent = fmtDate(r.filed);
    $('#d-authors').textContent = r.authors.join(', ');
    $('#d-summary').textContent = r.summary;

    const aibox = $('#ai-a');
    aibox.textContent = '';
    aibox.classList.add('hidden');
    aibox.style.whiteSpace = 'pre-line';
    $('#ai-q').value = '';
    $('#ai-stop').classList.add('hidden');

    drawer.classList.remove('translate-x-full');
    backdrop.classList.remove('pointer-events-none');
    backdrop.classList.add('opacity-100');
    document.body.style.overflow = 'hidden';
    if (window.lucide) setTimeout(() => window.lucide.createIcons(), 0);
  }

  function closeDrawer() {
    drawer.classList.add('translate-x-full');
    backdrop.classList.add('pointer-events-none');
    backdrop.classList.remove('opacity-100');
    document.body.style.overflow = '';
    stopAIStream();
  }

  $('#d-close').addEventListener('click', closeDrawer);
  backdrop.addEventListener('click', closeDrawer);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !drawer.classList.contains('translate-x-full')) closeDrawer();
  });

  // =================== “AI” Human Explanation ===================
  let aiTimer = null,
    aiQueue = [];

  function stopAIStream() {
    if (aiController) {
      aiController.abort();
      aiController = null;
    }
    $('#ai-stop').classList.add('hidden');
    $('#ai-ask').disabled = false;
  }

  function makeHumanExplanation(q, bill) {
    const low = (q || '').toLowerCase();
    const wantsSimple = /(explain.*12|simple|layman|basic|kid)/.test(low);
    const caresLGU = /(lgu|local|barangay|city|municip|province)/.test(low);
    const caresBudget = /(budget|fund|appropriation|money|cost)/.test(low);
    const caresTime = /(timeline|when|effect|irr|implement|deadline)/.test(low);
    const pros = /(pros|benefit|advantage|good)/.test(low);
    const cons = /(cons|risk|concern|problem|downside)/.test(low);

    const isBill = bill.type === 'bill';
    const chamber = bill.chamber === 'senate' ? 'Senate' : bill.chamber === 'house' ? 'House of Representatives' : 'both chambers';

    const lines = [];
    lines.push(`TL;DR: ${isBill
    ? 'This bill proposes binding changes to law and assigns agencies to carry them out.'
    : 'This resolution expresses the chamber’s position and can prompt hearings or oversight.'}`);

    lines.push(`What it’s about: ${bill.title}`);
    lines.push(`Current status: ${bill.status.replace('-', ' ')} — ${bill.last_action}.`);
    lines.push(`Chamber: ${chamber}. Filed: ${fmtDate(bill.filed)}.`);

    lines.push('');
    lines.push('What it likely does:');
    lines.push(`• ${bill.summary}`);
    lines.push(`• Government actions: sets rules, assigns responsible agencies, and defines how progress is monitored.`);
    lines.push('• Stakeholders: national agencies, LGUs, and affected sectors/citizens.');

    if (caresLGU) {
      lines.push('');
      lines.push('LGU Angle:');
      lines.push('• Coordination with LGUs for permits, implementation, and reporting.');
      lines.push('• Local prioritization where impact or need is highest.');
    }
    if (caresBudget) {
      lines.push('');
      lines.push('Budget & Funding:');
      lines.push('• Initial costs may be sourced from current agency appropriations.');
      lines.push('• Future funding typically included in the General Appropriations Act (GAA).');
    }
    if (caresTime) {
      lines.push('');
      lines.push('Timeline:');
      lines.push('• Usual practice: Implementing Rules and Regulations (IRR) within ~90 days of effectivity.');
      lines.push('• Milestones depend on the responsible agencies and committees.');
    }
    if (pros) {
      lines.push('');
      lines.push('Possible Benefits:');
      lines.push('• Clarifies responsibilities and targets a public need.');
      lines.push('• Can unlock funding or streamline approvals.');
      lines.push('• Improves accountability via reporting and metrics.');
    }
    if (cons) {
      lines.push('');
      lines.push('Potential Risks/Concerns:');
      lines.push('• Capacity and coordination challenges across agencies/LGUs.');
      lines.push('• Possible compliance overhead or duplication.');
      lines.push('• Funding adequacy and timely implementation.');
    }

    if (wantsSimple) {
      const simpleTitle = bill.title.replace(/Act|Resolution|An\s+Act|A\s+Resolution/gi, '').trim();
      lines.push('');
      lines.push('Explain like I’m 12:');
      lines.push(`• In simple terms, “${simpleTitle}” means the government wants to fix this problem by setting clear rules,`);
      lines.push('  choosing which offices must do the work, and making sure there’s time and money to get it done.');
    }

    lines.push('');
    lines.push('How to follow this:');
    lines.push('• Watch committee schedules and the measure’s “last action” for movement.');
    lines.push('• Read the final IRR (if a bill) for exact rules and deadlines.');

    return lines.join('\n');
  }

  function streamAnswer(text) {
    const box = $('#ai-a');
    box.classList.remove('hidden');
    box.textContent = '';
    box.style.whiteSpace = 'pre-line';
    aiQueue = text.split('');
    $('#ai-stop').classList.remove('hidden');
    aiTimer = setInterval(() => {
      if (!aiQueue.length) {
        stopAIStream();
        return;
      }
      box.textContent += aiQueue.splice(0, 3).join('');
      box.scrollTop = box.scrollHeight;
    }, 16);
  }

  async function startExplain(bill, q) {
    const box = $('#ai-a');
    const stopBtn = $('#ai-stop');
    const askBtn = $('#ai-ask');

    // UI Reset
    stopAIStream();
    box.classList.remove('hidden');
    box.innerHTML = `<div class="flex items-center gap-2"><span class="animate-spin h-4 w-4 border-2 border-emerald-600 border-t-transparent rounded-full"></span> Thinking...</div>`;
    stopBtn.classList.remove('hidden');
    askBtn.disabled = true;

    aiController = new AbortController();
    const prompt = `User Question: ${q}\n\nLegislation Details:\nNumber: ${bill.number}\nTitle: ${bill.title}\nSummary: ${bill.summary}\nStatus: ${bill.status}\nLast Action: ${bill.last_action}`;

    try {
      const API_BASE = "https://open-ai-hosting-free-production.up.railway.app/api/assistant/gemini";
      const res = await fetch(`${API_BASE}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        signal: aiController.signal,
        body: JSON.stringify({
          message: prompt,
          context: {
            type: "legislation_analysis",
            bill_number: bill.number,
            site_context: document.title
          },
        }),
      });

      if (!res.ok || !res.body) throw new Error("API Connection Failed");
      box.textContent = "";
      const reader = res.body.getReader();
      const decoder = new TextDecoder("utf-8");

      while (true) {
        const {
          value,
          done
        } = await reader.read();
        if (done) break;

        const chunk = decoder.decode(value, {
          stream: true
        });
        box.textContent += chunk;
        const body = $('#leg-drawer-body');
        body.scrollTop = body.scrollHeight;
      }

    } catch (err) {
      if (err.name === 'AbortError') {
        box.textContent += "\n[Generation Stopped]";
      } else {
        console.error("AI Error:", err);
        box.textContent = "Error connecting to AI. Please try again.";
      }
    } finally {
      stopBtn.classList.add('hidden');
      askBtn.disabled = false;
      aiController = null;
    }
  }

  // Ask button
  $('#ai-ask').addEventListener('click', () => {
    const q = $('#ai-q').value.trim();
    if (!q) {
      const box = $('#ai-a');
      box.textContent = 'Please enter a question.';
      box.classList.remove('hidden');
      return;
    }
    const current = $('#d-num').textContent;
    const bill = LEGISLATION.find(x => x.number === current);
    if (!bill) return;
    startExplain(bill, q);
  });
  $('#ai-stop').addEventListener('click', stopAIStream);

  // Quick chips
  document.querySelectorAll('.ai-chip').forEach(btn => {
    btn.addEventListener('click', () => {
      $('#ai-q').value = btn.textContent.trim();
      $('#ai-ask').click();
    });
  });

  // =================== Filters & Paging ===================
  $('#leg-search').addEventListener('input', e => {
    state.q = e.target.value;
    applyFilters();
  });
  $('#leg-chamber').addEventListener('change', e => {
    state.chamber = e.target.value;
    applyFilters();
  });
  $('#leg-type').addEventListener('change', e => {
    state.type = e.target.value;
    applyFilters();
  });
  $('#leg-status').addEventListener('change', e => {
    state.status = e.target.value;
    applyFilters();
  });
  $('#leg-prev').addEventListener('click', () => {
    state.page = Math.max(1, state.page - 1);
    renderTable();
  });
  $('#leg-next').addEventListener('click', () => {
    const t = Math.max(1, Math.ceil(state.data.length / state.perPage));
    state.page = Math.min(t, state.page + 1);
    renderTable();
  });

  // Slash to focus search
  window.addEventListener('keydown', (e) => {
    if (e.key === '/' && !/input|textarea/i.test(document.activeElement.tagName)) {
      e.preventDefault();
      $('#leg-search').focus();
    }
  });
</script>