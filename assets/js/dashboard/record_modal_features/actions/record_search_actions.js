function openSearchMode() {
    const modal = document.getElementById("searchRecordModal");
    const input = document.getElementById("searchRecordInput");
    const errBox = document.getElementById("searchRecordError");
    const form = document.getElementById("searchRecordForm");
    const resultsBox = document.getElementById("searchRecordResults");

    if (typeof closeRecordDialogAndOpenOtherDialog === "function") {
        closeRecordDialogAndOpenOtherDialog();
    }

    if (!modal || !input || !form) {
        console.error("Search modal elements not found");
        return;
    }

    if (errBox) {
        errBox.textContent = "";
        errBox.style.display = "none";
    }
    if (resultsBox) {
        resultsBox.innerHTML = "";
        resultsBox.style.display = "none";
    }

    input.value = currentSearchTerm || "";
    modal.style.display = "flex";
    modal.classList.add("show");
    setTimeout(() => input.focus(), 0);

    let debounceId = null;

    function runSearchFromInput() {
        const term = input.value.trim();
        if (!term) {
            currentSearchTerm = "";
            if (resultsBox) {
                resultsBox.innerHTML = "";
                resultsBox.style.display = "none";
            }
            if (errBox) {
                errBox.textContent = "";
                errBox.style.display = "none";
            }
            return;
        }

        currentSearchTerm = term.toLowerCase();
        const matches = findRecord({ fromModal: true, term });

        if (!matches || !matches.length) {
            if (errBox) {
                errBox.textContent = "No record found matching that search.";
                errBox.style.display = "block";
            }
        } else if (errBox) {
            errBox.textContent = "";
            errBox.style.display = "none";
        }
    }

    input.oninput = function () {
        if (errBox) {
            errBox.textContent = "";
            errBox.style.display = "none";
        }
        if (debounceId) clearTimeout(debounceId);
        debounceId = setTimeout(runSearchFromInput, 300);
    };

    form.onsubmit = function (e) {
        e.preventDefault();
        runSearchFromInput();
    };

    if (window.lucide) {
        lucide.createIcons();
    }
}

function closeSearchRecordModal() {
    const modal = document.getElementById("searchRecordModal");
    if (!modal) return;
    modal.style.display = "none";
    modal.classList.remove("show");
}

function findRecord(opts) {
    const fromModal = opts && opts.fromModal;
    const termRaw = opts && typeof opts.term === "string" ? opts.term : currentSearchTerm;

    if (!termRaw) {
        if (!fromModal) {
            alert("No search term set.");
        }
        return null;
    }

    const term = termRaw.toLowerCase();
    const all = Object.values(recordState);
    const matches = all.filter((r) =>
        (r.trackingNo || "").toLowerCase().includes(term) ||
        (r.title || "").toLowerCase().includes(term) ||
        (r.author || "").toLowerCase().includes(term)
    );

    if (!matches.length) {
        if (!fromModal) {
            alert("No record found.");
        }
        return null;
    }

    if (fromModal) {
        const resultsBox = document.getElementById("searchRecordResults");
        if (!resultsBox) return matches;

        resultsBox.innerHTML = "";

        const header = document.createElement("div");
        header.style.fontSize = "11px";
        header.style.color = "#6b7280";
        header.style.marginBottom = "4px";
        header.textContent = `${matches.length} record(s) found for “${termRaw}”`;
        resultsBox.appendChild(header);

        matches.forEach((rec) => {
            const item = document.createElement("div");
            item.className = "search-result-item";
            item.setAttribute("data-tracking", rec.trackingNo);

            item.innerHTML = `
                <div class="search-result-main">
                    ${rec.trackingNo} – ${rec.title}
                </div>
                <div class="search-result-sub">
                    ${rec.type || ""} • ${rec.author || ""} • ${rec.status || ""}
                </div>
            `;

            resultsBox.appendChild(item);
        });

        resultsBox.style.display = "block";

        resultsBox.onclick = function (e) {
            const row = e.target.closest(".search-result-item");
            if (!row) return;

            const trackingNo = row.getAttribute("data-tracking");
            if (!trackingNo) return;

            openRecordDialog(trackingNo);
            highlightRecordModal(termRaw);
            closeSearchRecordModal();
        };

        return matches;
    }
    const match = matches[0];

    openRecordDialog(match.trackingNo);
    highlightRecordModal(termRaw);

    return match;
}

function escapeRegExp(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function highlightRecordModal(termRaw) {
    if (!termRaw) return;
    const term = termRaw.trim();
    if (!term || term.length < 2) return; // Prevent highlighting single letters

    if (highlightTimer) {
        clearTimeout(highlightTimer);
        highlightTimer = null;
    }

    const modalEl = document.getElementById("recordModal");
    if (!modalEl) return;
    const targetSelectors = ".record-detail-value, #recordModalTitle, #recordModalCode, .record-attachments-wrapper";
    const elements = modalEl.querySelectorAll(targetSelectors);
    const regex = new RegExp(`(${escapeRegExp(term)})`, 'gi');

    elements.forEach(el => {
        el.innerHTML = el.innerHTML.replace(/<span class="search-term-highlight">(.*?)<\/span>/g, '$1');
        if (el.children.length > 0 && !el.classList.contains('record-attachments-wrapper')) return;
        const originalHTML = el.innerHTML;
        if (originalHTML.toLowerCase().includes(term.toLowerCase())) {
            el.innerHTML = originalHTML.replace(regex, '<span class="search-term-highlight">$1</span>');
            const container = el.closest(".record-detail") || el.closest(".record-modal-header");
            if (container) {
                container.classList.add("search-box-highlight");
            }
        }
    });
    highlightTimer = setTimeout(() => {
        elements.forEach(el => {
            el.innerHTML = el.innerHTML.replace(/<span class="search-term-highlight">(.*?)<\/span>/g, '$1');
            const container = el.closest(".record-detail") || el.closest(".record-modal-header");
            if (container) container.classList.remove("search-box-highlight");
        });
    }, 5000);
}

function endRecordModule() {
    closeRecordDialog();
}
