(() => {
  let generationController = null;

  // ==========================
  // Utilities / Icons / Toast
  // ==========================
  function safeIcons() {
    if (window.lucide && typeof window.lucide.createIcons === "function") {
      window.lucide.createIcons();
    }
  }

  window.toggleMobileMenu = function toggleMobileMenu() {
    const menu = document.getElementById("mobileMenu");
    if (!menu) return;
    const current = getComputedStyle(menu).display;
    menu.style.display = current === "none" ? "block" : "none";
  };

  // ==========================
  // AI Bill Generator (Modal)
  // ==========================
  const saveAIState = (text, meta) => {
    localStorage.setItem("ai_last_generated_text", text);
    localStorage.setItem("ai_last_generated_meta", JSON.stringify(meta || {}));
  };
  const clearAIState = () => {
    localStorage.removeItem("ai_last_generated_text");
    localStorage.removeItem("ai_last_generated_meta");
  };

  window.openAIModal = function () {
    const modal = document.getElementById("aiModal");
    if (!modal) return;
    modal.classList.add("show");
    document.body.style.overflow = "hidden";
    setTimeout(safeIcons, 100);

    // --- RECOVERY LOGIC ---
    const savedText = localStorage.getItem("ai_last_generated_text");
    const savedMeta = localStorage.getItem("ai_last_generated_meta");

    if (savedText) {
      const output = document.getElementById("generatedBill");
      const container = document.getElementById("generatedBillContainer");
      const actionsContainer = document.getElementById("billGeneratedActionsContainer");

      if (output && container) {
        output.textContent = savedText;
        container.style.display = "block";

        if (savedMeta) {
          window.lastGeneratedMeta = JSON.parse(savedMeta);
          if (actionsContainer) actionsContainer.style.setProperty('display', 'flex', 'important');
        }
      }
    }
  };

  window.closeAIModal = function () {
    const modal = document.getElementById("aiModal");
    if (!modal) return;
    modal.classList.remove("show");
    document.body.style.overflow = "auto";
  };

  window.stopGenerating = function () {
    if (generationController) {
      generationController.abort();
      generationController = null;
      showToast("Generation stopped", "info");
    }
  };

  window.generateBill = async function generateBill() {
    const topicEl = document.getElementById("topic");
    const billTypeEl = document.getElementById("billType");
    const generateBtn = document.getElementById("generateBtn");
    const stopContainer = document.getElementById("stopContainer");
    const actionsContainer = document.getElementById("billGeneratedActionsContainer");
    const output = document.getElementById("generatedBill");
    const container = document.getElementById("generatedBillContainer");

    if (!topicEl || !billTypeEl || !generateBtn || !output || !container) return;

    const topic = (topicEl.value || "").trim();
    const billType = billTypeEl.value;
    if (!topic) {
      showToast("Please enter a topic", "error");
      return;
    }
    generateBtn.style.display = "none";
    stopContainer.style.display = "block";
    container.style.display = "block";
    if (actionsContainer) {
      actionsContainer.style.setProperty('display', 'none', 'important');
    }
    output.textContent = "Thinking...";
    generationController = new AbortController();

    try {
      const res = await fetch("/../../api/assistant/generateDraftAi.php", {
        method: "POST",
        signal: generationController.signal,
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          topic: topic,
          billType: billType
        }),
      });

      if (!res.ok) throw new Error("Server Error");

      output.textContent = "";
      const reader = res.body.getReader();
      const decoder = new TextDecoder("utf-8");
      let fullResponse = "";
      let done = false;

      while (!done) {
        const { value, done: d } = await reader.read();
        done = d;
        if (value) {
          const chunk = decoder.decode(value, { stream: true });
          fullResponse += chunk;
          const currentText = fullResponse.split("---METADATA---")[0];
          if (fullResponse.includes("---METADATA---")) {
            output.textContent = currentText;
          } else {
            output.textContent = fullResponse;
          }
          output.scrollTop = output.scrollHeight;

          saveAIState(currentText, window.lastGeneratedMeta);
        }
      }
      const parts = fullResponse.split("---METADATA---");
      const cleanText = parts[0].trim();
      const metadataRaw = parts[1] || "";
      output.textContent = cleanText;

      if (metadataRaw) {
        const metaObj = {};
        const lines = metadataRaw.split('\n');

        lines.forEach(line => {
          const colonIndex = line.indexOf(':');

          if (colonIndex !== -1) {
            let key = line.substring(0, colonIndex).replace(/^\d+\.\s*/, "").trim();
            let val = line.substring(colonIndex + 1).trim();

            if (key) {
              metaObj[key] = val;
            }
          }
        });
        console.log("%c>>> EXTRACTED METADATA", "color: #00ff00; font-weight: bold;", metaObj);

        window.lastGeneratedMeta = metaObj;
      }

      saveAIState(cleanText, window.lastGeneratedMeta);
      showToast("Draft generated and parsed!", "success");
      actionsContainer.style.setProperty('display', 'flex', 'important');

    } catch (err) {
      if (err.name === 'AbortError') {
        output.textContent += "\n\n[Generation stopped by user]";
      } else {
        console.error("Error:", err);
        showToast("Generation failed", "error");
      }
    } finally {
      generateBtn.style.display = "flex";
      stopContainer.style.display = "none";
      generationController = null;
    }
  };
  window.importAIGeneratedToRecord = async function (event) {
    const output = document.getElementById("generatedBill");
    const generateBtn = event.currentTarget;
    const visibleContent = output?.textContent || "";
    const meta = window.lastGeneratedMeta || {};
    if (Object.keys(meta).length === 0) {
      showToast("Error: Metadata missing. Please regenerate.", "error");
      return;
    }
    const docType = document.getElementById("billType")?.value || "resolution";

    try {
      generateBtn.disabled = true;
      const originalContent = generateBtn.innerHTML;
      generateBtn.innerHTML = `
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Uploading...`;

      const response = await fetch("/../../api/account/addDraftToDatabase.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          type: docType,
          metadata: meta,
          content: visibleContent
        })
      });

      const result = await response.json();

      if (!result.success) {
        if (result.error === "Session Expired, Log in again") {
          showToast("Session Expired. Redirecting...", "error");
          setTimeout(() => window.location.href = "/pages/loginportal", 2000);
          return;
        }
        throw new Error(result.error);
      }

      showToast(`Successfully Saved!`, "success");
      generateBtn.style.opacity = '0';
      generateBtn.style.pointerEvents = 'none';
      setTimeout(() => generateBtn.classList.add('hidden'), 300);

    } catch (err) {
      console.error("Database Save Error:", err);
      showToast(err.message || "Failed to save", "error");
      generateBtn.disabled = false;
      generateBtn.innerHTML = originalContent;
    }
  };

  window.downloadBill = function downloadBill() {
    const meta = window.lastGeneratedMeta;
    const out = document.getElementById("generatedBill");
    const rawText = out ? out.textContent : "";

    const title = meta?.Title || "LEGISLATIVE DRAFT";
    const whereasText = meta?.WhereasDescription || "";
    const trackingNo = meta?.TrackingNo || "Draft-2026";

    const sections = [];
    for (let i = 1; i <= 17; i++) {
      if (meta[`Sec${i}_Desc`]) {
        sections.push(meta[`Sec${i}_Desc`]);
      }
    }

    const htmlContent = `
<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="utf-8">
<style>
@page Section1 {
    size: 8.5in 11in;
    margin: 1in;
    mso-page-orientation: portrait;
}
div.Section1 {
    page: Section1;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 11pt;
    line-height: 1.5;
    text-align: left; 
}
.header-box {
    text-align: center;
    margin-bottom: 20px;
}
.title {
    font-weight: bold;
    text-transform: uppercase;
    text-align: left;
    margin: 20px 0;
    font-size: 13pt;
}

.whereas {
    margin-bottom: 15px;
    text-align: left;
}

.section-content {
    margin-bottom: 10px;
    text-align: left;
}

.draft-footer {
    border: 2px dashed #999;
    padding: 15px;
    margin-top: 40px;
    text-align: center;
    background-color: #f9f9f9;
}

.sig-table { width: 100%; margin-top: 40px; }
.sig-cell { width: 50%; vertical-align: top; padding-top: 30px; }

</style>
</head>
<body>
<div class="Section1">
<div class="header-box">
  <p>Republic of the Philippines</p>
  <p><b>PROVINCIAL/MUNICIPAL GOVERNMENT</b></p>
  <p>OFFICE OF THE SANGGUNIANG BAYAN/PANLALAWIGAN</p>
</div>

<div style="font-size:9pt;">Tracking No: ${trackingNo}</div>

<div class="title">${title}</div>

${whereasText ? `<div class="whereas">${whereasText.replace(/WHEREAS,/g, '<b>WHEREAS,</b>')}</div>` : ''}

${sections.length > 0
        ? sections.map(sec => {
          const boldedSec = sec.replace(/^(SECTION \d+\.)/i, '<b>$1</b>');
          return `<div class="section-content">${boldedSec}</div>`;
        }).join('')
        : `<div style="white-space: pre-wrap;">${rawText}</div>`
      }

<table class="sig-table">
<tr>
<td class="sig-cell">
<p>Authored by:</p><br><br>
<p><b>${meta?.PrincipalAuthor || "____________________"}</b></p>
<p>Member, Sangguniang Bayan</p>
</td>
<td class="sig-cell">
<p>Attested by:</p><br><br>
<p>__________________________</p>
<p>Secretary to the Sanggunian</p>
</td>
</tr>
</table>

<div class="draft-footer">
<p style="color:#d93025;font-weight:bold;font-size:14pt;margin:0;">PROVISIONAL DRAFT - NOT FOR LEGAL USE</p>
<p style="margin-top:5px;">This document is AI-generated for legislative review. It has not been enacted as of ${new Date().toLocaleDateString()}.</p>
</div>
</div>
</body>
</html>`;

    const blob = new Blob(['\ufeff', htmlContent], {
      type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    });

    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `${trackingNo}-DRAFT.docx`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);

    showToast("DOCX draft downloaded (portrait)!", "success");
  };


  // Close AI Bill modal on outside click
  window.addEventListener("click", (event) => {
    const modal = document.getElementById("aiModal");
    if (modal && event.target === modal) {
      window.closeAIModal();
    }
  });


  // ==========================
  // Free AI Assistant (Live)
  // ==========================

  // ---- Modal controls ----
  window.showAISummary = function (trackingNo) {
    const rec = recordState[trackingNo];
    if (!rec) {
      console.warn("No record found for trackingNo:", trackingNo);
      return;
    }
    const detailsLines = [
      `Tracking No: ${rec.trackingNo}`,
      `Title: ${rec.title}`,
      `Type: ${rec.type}`,
      `Principal Author: ${rec.author}`,
      `Current Status: ${rec.status}`,
      `Deadline / Hearing Date: ${rec.deadline}`,
      "", // Spacer
      `Assigned To: ${rec.assignedName || "Not yet assigned"}`,
      `Category: ${rec.category || "N/A"}`,
      `Session Type: ${rec.sessionType || "N/A"}`,
      `Year Taken: ${rec.yearTaken || "N/A"}`,
      `Agenda No: ${rec.agendaNo || "N/A"}`,
      `Committee: ${rec.committee || "N/A"}`,
    ];

    if (rec.hyperlink) {
      detailsLines.push("", `Main File: ${rec.hyperlink}`);
    }

    if (Array.isArray(rec.attachments) && rec.attachments.length) {
      detailsLines.push(
        "",
        "Attachments:",
        ...rec.attachments.map((a) => `- ${a}`)
      );
    }

    const detailsText = detailsLines.join("\n");
    const prompt = `
Summarize the following legislative item in clear, non-technical language.

Goal:
- 4–6 sentences
- Explain what the resolution/ordinance is about
- Highlight its main purpose and policy focus
- Mention important committee, session type, and current status
- Phrase it so a city staff member or councilor can quickly understand the item

Legislative record details:
${detailsText}
`.trim();
    const ctx = buildSiteContext();
    ctx.mode = "assigned-record-summary";
    ctx.record = {
      trackingNo: rec.trackingNo,
      title: rec.title,
      type: rec.type,
      author: rec.author,
      status: rec.status,
      deadline: rec.deadline,
      category: rec.category,
      sessionType: rec.sessionType,
      yearTaken: rec.yearTaken,
      agendaNo: rec.agendaNo,
      committee: rec.committee,
      hyperlink: rec.hyperlink,
      attachments: Array.isArray(rec.attachments) ? [...rec.attachments] : [],
    };
    openAssistantModal();
    askAssistant(prompt, ctx);
  };

  window.openAssistantModal = function () {
    const modal = document.getElementById("assistantModal");
    if (!modal) return;
    modal.classList.add("show");
    document.body.style.overflow = "hidden";
    setTimeout(safeIcons, 50);
    setTimeout(() => document.getElementById("assistantInput")?.focus(), 120);
  };
  window.closeAssistantModal = function () {
    const modal = document.getElementById("assistantModal");
    if (!modal) return;
    modal.classList.remove("show");
    document.body.style.overflow = "";
  };
  window.addEventListener("click", (e) => {
    const modal = document.getElementById("assistantModal");
    if (modal && e.target === modal) closeAssistantModal();
  });
  document.addEventListener("keydown", (e) => {
    const modal = document.getElementById("assistantModal");
    if (!modal) return;
    if (modal.classList.contains("show") && e.key === "Escape")
      closeAssistantModal();
  });

  // ---- Chat elements ----
  const chatEl = document.getElementById("assistantChat");
  const formEl = document.getElementById("assistantForm");
  const inputEl = document.getElementById("assistantInput");

  function bubble(content, side = "ai") {
    const wrap = document.createElement("div");
    wrap.className =
      side === "user"
        ? "ml-auto max-w-[85%] rounded-2xl px-4 py-3 text-sm bg-white border shadow-sm"
        : "max-w-[85%] rounded-2xl px-4 py-3 text-sm bg-blue-50 border border-blue-200 text-blue-900";
    wrap.innerHTML = content;
    chatEl.appendChild(wrap);
    chatEl.scrollTop = chatEl.scrollHeight;
    safeIcons();
    return wrap;
  }

  // Thinking bubble (with spinner)
  function startThinkingBubble() {
    const el = bubble(
      `
    <div class="flex items-center gap-3">
      <div class="w-5 h-5 rounded-full border-2 border-blue-300 border-t-transparent animate-spin"></div>
      <span class="text-sm text-blue-900/80">Thinking…</span>
      <span class="ai-dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span>
    </div>
    `,
      "ai"
    );
    let first = true;
    return {
      write(chunk) {
        if (first) {
          el.textContent = "";
          el.classList.add("ai-fade-in");
          first = false;
        }
        el.textContent += chunk;
        chatEl.scrollTop = chatEl.scrollHeight;
      },
      error(msg) {
        el.innerHTML = `<span class="text-red-700">${msg}</span>`;
      },
    };
  }

  // --- Build a concise site context from the current DOM ---
  function buildSiteContext() {
    const take = (str, n = 400) =>
      (str || "").replace(/\s+/g, " ").trim().slice(0, n);

    const title = document.title || "";
    const metaDesc =
      document.querySelector('meta[name="description"]')?.content || "";
    const url = location.href;

    const nav = [...document.querySelectorAll("nav a[href]")]
      .map((a) => a.textContent.trim())
      .filter(Boolean)
      .slice(0, 12);

    const sections = [...document.querySelectorAll("section")]
      .map((s) => {
        const heading = s.querySelector("h1, h2, h3");
        const id = s.id || "";
        const name = heading ? heading.textContent.trim() : id || "(section)";
        // grab a short readable snippet from each section
        const text = take(s.innerText, 500);
        return { id, name, text };
      })
      .slice(0, 8); // keep it compact

    return {
      title,
      url,
      metaDesc: take(metaDesc, 300),
      nav,
      sections,
    };
  }

  document.querySelectorAll("[data-ai-explain-site]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const ctx = buildSiteContext();
      askAssistant(
        "Explain this website to me in plain language. Summarize each major section and how citizens can use it.",
        ctx
      );
    });
  });

  // Send a question to backend and stream the answer
  async function askAssistant(message, context = null) {
    // user bubble
    bubble(message, "user");

    // show thinking animation
    const thinker = startThinkingBubble();

    // disable Send while streaming (optional)
    const sendBtn = document.querySelector(
      '#assistantForm button[type="submit"]'
    );
    const prevDisabled = !!sendBtn?.disabled;
    if (sendBtn) {
      sendBtn.disabled = true;
      sendBtn.innerHTML =
        '<span class="w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>';
    }

    try {
      const API_BASE = "https://open-ai-hosting-free-production.up.railway.app/api/assistant/gemini";
      const res = await fetch(`${API_BASE}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          message,
          context: context || buildSiteContext(),
        }),
      });

      if (!res.ok || !res.body) {
        thinker.error("Sorry—couldn’t connect to the assistant server.");
        console.error(
          "Assistant fetch failed:",
          res.status,
          await res.text().catch(() => "")
        );
        return;
      }

      // stream chunks into that same bubble
      const reader = res.body.getReader();
      const decoder = new TextDecoder("utf-8");
      let done = false;
      while (!done) {
        const { value, done: d } = await reader.read();
        done = d;
        if (value) thinker.write(decoder.decode(value, { stream: true }));
      }
    } catch (err) {
      thinker.error("There was a network error talking to the assistant.");
      console.error("Assistant error:", err);
    } finally {
      // restore Send button
      if (sendBtn) {
        sendBtn.disabled = prevDisabled;
        sendBtn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> Send';
        safeIcons();
      }
    }
  }

  // Suggestions (chips)
  document.querySelectorAll("[data-ai-suggest]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const q = btn.textContent.trim();
      askAssistant(q);
    });
  });

  // Form submit
  if (formEl) {
    formEl.addEventListener("submit", (e) => {
      e.preventDefault();
      const q = (inputEl?.value || "").trim();
      if (!q) return;
      inputEl.value = "";
      askAssistant(q);
    });
  }

  // Enter to send (Shift+Enter for newline)
  inputEl?.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      formEl?.dispatchEvent(new Event("submit", { cancelable: true }));
    }
  });

  // ==========================
  // Global Search
  // ==========================
  const SEARCH_ENTRIES = [
    {
      title: "Bill Search",
      desc: "Filter by author, status, chamber, committee, and dates.",
      href: "/pages/landingpage/legislation",
      tags: ["legislation", "bills", "search"],
    },
    {
      title: "Laws & Resolutions",
      desc: "Browse enacted laws, SBN/HBN, and resolutions.",
      href: "/pages/landingpage/legislation",
      tags: ["laws", "resolutions", "legislation"],
    },
    {
      title: "AI Drafting",
      desc: "Generate formatted drafts with the AI assistant.",
      href: "/pages/landingpage/legislation",
      tags: ["ai", "draft", "generator"],
    },
    {
      title: "Member Profiles",
      desc: "Authored measures, committees, and votes.",
      href: "/pages/landingpage/aboutus",
      tags: ["profiles", "members"],
    },
    {
      title: "Engage",
      desc: "Contact details and office hours.",
      href: "/pages/landingpage/resources",
      tags: ["contact"],
    },
    {
      title: "Committees Directory",
      desc: "Standing, special, and oversight committees.",
      href: "/pages/landingpage/aboutus",
      tags: ["committees", "directory"],
    },
    {
      title: "Committee Schedules",
      desc: "Hearings, agendas, and minutes.",
      href: "/pages/landingpage/schedules",
      tags: ["calendar", "schedules", "committee"],
    },
    {
      title: "Session Calendar",
      desc: "Plenary sessions and legislative days.",
      href: "/pages/landingpage/calendar",
      tags: ["calendar", "plenary"],
    },
    {
      title: "Public Consultations",
      desc: "Town halls and stakeholder meetings.",
      href: "/pages/landingpage/calendar",
      tags: ["consultations", "events"],
    },
    {
      title: "FAQs",
      desc: "Common questions about bills, votes, and data.",
      href: "/pages/landingpage/resources",
      tags: ["faq", "help"],
    },
    {
      title: "Legislative Gov Code Book",
      desc: "From filing to enactment — step by step.",
      href: "/pages/landingpage/localgovcode",
      tags: ["process", "how"],
    },
  ];

  function renderResults(list, items) {
    if (!list) return;
    list.innerHTML = "";
    if (!items.length) {
      list.innerHTML =
        '<div class="p-4 text-sm text-gray-600">No results. Try different keywords.</div>';
      return;
    }

    items.forEach((it) => {
      let targetHref = it.href;
      if (
        targetHref &&
        targetHref.startsWith("#") &&
        it.tags &&
        it.tags.includes("legislation")
      ) {
        targetHref = "/pages/landingpage/legislation";
      }

      const row = document.createElement("a");
      row.href = targetHref;
      row.className = "block p-4 hover:bg-gray-50";
      row.innerHTML = `
      <div class="font-semibold text-gray-900">${it.title}</div>
      <div class="text-sm text-gray-600">${it.desc}</div>
    `;

      if (it.title === "AI Drafting") {
        row.addEventListener("click", (e) => {
          e.preventDefault();
          e.stopPropagation();
          openAIModal();
        });
      } else {
        row.addEventListener("click", (e) => {
          if (targetHref.startsWith("#")) {
            e.preventDefault();
            const target = document.querySelector(targetHref);
            if (target) {
              target.scrollIntoView({ behavior: "smooth", block: "start" });
            } else {
              window.location.hash = targetHref.slice(1);
            }
          }
        });
      }

      list.appendChild(row);
    });
  }

  function filterEntries(q, scope) {
    q = (q || "").trim().toLowerCase();
    let items = SEARCH_ENTRIES;
    if (scope && scope !== "all") {
      items = items.filter((it) => it.tags.includes(scope));
    }
    if (!q) return items.slice(0, 6);
    return items
      .filter(
        (it) =>
          it.title.toLowerCase().includes(q) ||
          it.desc.toLowerCase().includes(q) ||
          it.tags.some((t) => t.includes(q))
      )
      .slice(0, 8);
  }

  function initGlobalSearch() {
    const form = document.getElementById("globalSearchForm");
    const input = document.getElementById("globalSearchInput");
    const scope = document.getElementById("globalSearchScope");
    const resultsWrap = document.getElementById("globalSearchResults");
    const resultsList = document.getElementById("globalSearchResultsList");
    if (!form || !input || !scope || !resultsWrap || !resultsList) return;

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const items = filterEntries(input.value, scope.value);
      renderResults(resultsList, items);
      resultsWrap.classList.remove("hidden");
    });

    let t;
    input.addEventListener("input", () => {
      clearTimeout(t);
      t = setTimeout(() => {
        const items = filterEntries(input.value, scope.value);
        renderResults(resultsList, items);
        resultsWrap.classList.remove("hidden");
      }, 250);
    });

    document.addEventListener("click", (e) => {
      if (!resultsWrap.contains(e.target) && !form.contains(e.target)) {
        resultsWrap.classList.add("hidden");
      }
    });

    // "/" to focus search
    window.addEventListener("keydown", (e) => {
      const tag =
        (document.activeElement && document.activeElement.tagName) || "";
      if (e.key === "/" && !/input|textarea/i.test(tag)) {
        e.preventDefault();
        input.focus();
      }
    });
  }

  // Init
  document.addEventListener("DOMContentLoaded", () => {
    safeIcons();
    initGlobalSearch();

    const summarizeBtn = document.getElementById("summarizeGovBtn");
    if (summarizeBtn) {
      console.log("Found summarize button");
      summarizeBtn.addEventListener("click", () => {
        const rawText = pageSections
          .map((sec) => `${sec.title}\n${sec.text}`)
          .join("\n\n")
          .replace(/\s+/g, " ")
          .trim();
        const maxLen = 5000;
        const clippedText = rawText.slice(0, maxLen);

        openAssistantModal();

        const prompt = `
Summarize the following excerpts from the Local Government Code of the Philippines.

Make it:
- clear and easy to understand for ordinary citizens of Urdaneta City
- grouped by section/title
- focused on: (1) powers of LGUs, (2) barangay responsibilities, and (3) key rights and protections for citizens.

Text:
${clippedText}
        `.trim();

        askAssistant(prompt);
      });
    }
  });
})();
