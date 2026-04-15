function formatList(value) {
    if (!value) return "—";
    if (Array.isArray(value)) {
        return value.map(v => `<div class="detail-item">• ${v}</div>`).join("");
    }
    const parts = value.split(";").map(v => v.trim()).filter(v => v !== "");
    return parts.map(v => `<div class="detail-item">• ${v}</div>`).join("");
}

function openRecordDialog(trackingNo) {
    const baseRecord =
        (typeof recordState !== "undefined" && recordState[trackingNo]) ||
        assignedItems.find((i) => i.trackingNo === trackingNo);
    if (!baseRecord) return;

    const record = {
        hyperlink: "",
        attachments: [],
        locked: false,
        ...baseRecord,
    };

    currentRecordId = trackingNo;



    const modal = document.getElementById("recordModal");
    const titleEl = document.getElementById("recordModalTitle");
    const codeEl = document.getElementById("recordModalCode");
    const bodyEl = document.getElementById("recordModalBody");
    const category =
        record.category ||
        (record.type === "Ordinance" ? "Regulatory Ordinance" : "Resolution");
    const sessionType = record.sessionType || "Regular Session";
    const yearTaken = record.yearTaken || "2025";
    const agendaNo = record.agendaNo || "—";
    const committee = record.committee || "For Committee / Referral";

    titleEl.textContent = record.title;
    codeEl.textContent = record.trackingNo + (record.locked ? " • LOCKED" : "");
    const cfg = getGridConfigForRecord(trackingNo);
    const basicFieldsCfg = cfg.basicFields || {};

    const basicDetails = [
        { key: "trackingNo", label: "Tracking No.", value: record.trackingNo },
        { key: "type", label: "Type", value: record.type },
        { key: "author", label: "Principal Author", value: record.author },
        { key: "status", label: "Current Status", value: record.status },
        {
            key: "deadline",
            label: "Deadline / Hearing Date",
            value: record.deadline,
        },
    ].filter((f) => basicFieldsCfg[f.key] !== false);

    const hyperlinkHtml = record.hyperlink
        ? `<a href="${record.hyperlink}" target="_blank">${record.hyperlink}</a>`
        : `<span class="text-muted">No hyperlink set</span>`;

    const attachmentsHtml =
        record.attachments && record.attachments.length
            ? `
        <div class="record-attachments-wrapper">
            <ul class="record-attachments-list">
                ${record.attachments
                .map((fullPath, idx) => {
                    const displayName = getAttachmentDisplayName(fullPath);

                    return `
                            <li class="record-attachment-item"
                                data-idx="${idx}"
                                data-record-id="${record.trackingNo}">
                                <button
                                    type="button"
                                    class="record-attachment-link"
                                    title="${fullPath}"
                                >
                                    <i data-lucide="paperclip" class="icon-sm"></i>
                                    <span class="record-attachment-name">
                                        ${displayName}
                                    </span>
                                </button>
                            </li>
                        `;
                })
                .join("")}
            </ul>
            <div class="record-attachment-preview" id="recordAttachmentPreview">
                <div class="record-attachment-preview-placeholder">
                    Select an attachment to preview. Images and PDFs will show here.
                </div>
            </div>
        </div>
        `
            : `<span class="text-muted">No files attached</span>`;

    const sectionsCfg = cfg.sections || {};
    const legislationCfg = cfg.legislationFields || {};
    const supportFieldsCfg = cfg.supportFields || {};

    const legisLayout = optionsState.legisLayout || "detailed"; // "detailed" | "compact"
    const showLegisSubtitle =
        optionsState.legisShowSubtitle !== false;

    const legisGridClass =
        legisLayout === "compact"
            ? "record-legislation-grid record-legislation-grid--compact"
            : "";

    const basicGridClass =
        legisLayout === "compact"
            ? "record-details-grid"
            : "";

    let html = "";

    // BASIC
    if (sectionsCfg.basic !== false) {
        html += `
        <div class="${basicGridClass}">
            <div class="record-detail record-detail--full">
                <div class="record-detail-label">Subject / Title</div>
                <div class="record-detail-value">${record.title}</div>
            </div>

            ${basicDetails
                .map(
                    (d) => `
                <div class="record-detail">
                    <div class="record-detail-label">${d.label}</div>
                    <div class="record-detail-value">${d.value}</div>
                </div>
            `
                )
                .join("")}
        </div>
        `;
    }

    // LEGISLATION
    if (sectionsCfg.legislation !== false) {
        html += `
    <div class="record-section">
        <div class="record-section-header">
            <div>
                <div class="record-section-title">Legislation Record</div>
                ${showLegisSubtitle
                ? `<div class="record-section-subtitle">
                            Classification, session details, and handling information for this item.
                           </div>`
                : ""
            }
            </div>
        </div>

        <div class="${legisGridClass}">
            ${legislationCfg.category !== false
                ? `
            <div class="record-detail">
                <div class="record-detail-label">Category of Record</div>
                <div class="record-detail-value">${formatList(category)}</div>
            </div>`
                : ""
            }

            ${legislationCfg.sessionType !== false
                ? `
            <div class="record-detail">
                <div class="record-detail-label">Session Type</div>
                <div class="record-detail-value">${sessionType}</div>
            </div>`
                : ""
            }

            ${legislationCfg.yearTaken !== false
                ? `
            <div class="record-detail">
                <div class="record-detail-label">Year Taken Up</div>
                <div class="record-detail-value">${yearTaken}</div>
            </div>`
                : ""
            }

            ${legislationCfg.agendaNo !== false
                ? `
            <div class="record-detail">
                <div class="record-detail-label">Agenda / Item No.</div>
                <div class="record-detail-value">${agendaNo}</div>
            </div>`
                : ""
            }

            ${legislationCfg.committee !== false
                ? `
            <div class="record-detail record-detail--full">
                <div class="record-detail-label">Committee Handling</div>
                <div class="record-detail-value">${formatList(committee)}</div>
            </div>`
                : ""
            }
        </div>
    </div>
    `;
    }

    // SUPPORT
    if (sectionsCfg.support !== false) {
        html += `
        <div class="record-section">
            <div class="record-section-header">
                <div class="record-section-title">Supporting Files & Links</div>
                <div class="record-section-subtitle">
                    Hyperlinks and attached documents for this record.
                </div>
            </div>

            <div class="record-support-grid">
                ${supportFieldsCfg.hyperlink !== false
                ? `
                <div class="record-detail record-detail--full">
                    <div class="record-detail-label">Hyperlink</div>
                    <div class="record-detail-value">${hyperlinkHtml}</div>
                </div>`
                : ""
            }

                ${supportFieldsCfg.attachments !== false
                ? `
                <div class="record-detail record-detail--full">
                    <div class="record-detail-label">Attachments</div>
                    <div class="record-detail-value">${attachmentsHtml}</div>
                </div>`
                : ""
            }
            </div>
        </div>
        `;
    }

    bodyEl.innerHTML = html;

    const attachmentButtons = bodyEl.querySelectorAll(
        ".record-attachment-item .record-attachment-link"
    );

    attachmentButtons.forEach((btn) => {
        btn.addEventListener("click", (evt) => {
            evt.preventDefault();
            evt.stopPropagation();

            const li = btn.closest(".record-attachment-item");
            if (!li) return;

            const idx = parseInt(li.dataset.idx, 10);
            const recId = li.dataset.recordId;

            previewAttachment(recId, idx);
        });
    });

    if (record.attachments && record.attachments.length > 0) {
        previewAttachment(record.trackingNo, 0);
    }

    modal.classList.add("show");
    document.body.classList.add("modal-open");

    renderRecordFooterButtons();

    if (window.lucide) {
        lucide.createIcons();
    }
}

function renderRecordFooterButtons() {
    const container = document.getElementById("recordFooterActions");
    if (!container) return;

    const role = (window.currentUserRole || ROLE.STAFF).toLowerCase();
    container.innerHTML = "";

    const rec = currentRecordId ? recordState[currentRecordId] : null;
    const lockedEditingIds = [
        "setupGrid",
        "setHyperlink",
        "mainData",
        "addRecord",
        "editRecord",
        "deleteRecord",
        "transferRecord",
        "resetChanges",
    ];

    recordFeatureButtons
        .filter((btn) => btn.roles.includes(role))
        .forEach((btn) => {
            const button = document.createElement("button");
            button.type = "button";
            button.className = "record-btn-tool";
            button.setAttribute("data-action", btn.id);
            let iconName = btn.icon;
            let labelText = btn.label;

            if (btn.id === "lockRecord" && rec) {
                if (rec.locked) {
                    iconName = "unlock";
                    labelText = "Unlock";
                } else {
                    iconName = "lock";
                    labelText = "Lock";
                }
            }

            button.innerHTML = `
                <i data-lucide="${iconName}"></i>
                <span>${labelText}</span>
            `;

            button.addEventListener("click", (e) => {
                e.stopPropagation();
                if (typeof window[btn.handler] === "function") {
                    window[btn.handler](currentRecordId);
                } else {
                    console.warn("Handler not implemented:", btn.handler);
                    alert(btn.label + " clicked for " + currentRecordId);
                }
            });

            if (rec && rec.locked && lockedEditingIds.includes(btn.id)) {
                button.disabled = true;
                button.classList.add("record-btn-disabled");
            }

            container.appendChild(button);
        });

    if (window.lucide) {
        lucide.createIcons();
    }
}

function closeRecordDialog() {
    const modal = document.getElementById("recordModal");
    if (!modal) return;
    modal.classList.remove("show");
    document.body.classList.remove("modal-open");
    const selected = document.querySelector(".data-table tbody tr.selected-row");
    if (selected) selected.classList.remove("selected-row");

    currentRecordId = null;
}

function closeRecordDialogAndOpenOtherDialog() {
    const modal = document.getElementById("recordModal");
    if (!modal) return;
    modal.classList.remove("show");
    document.body.classList.remove("modal-open");
    const selected = document.querySelector(".data-table tbody tr.selected-row");
    if (selected) selected.classList.remove("selected-row");
}

// ESC key
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        closeRecordDialog();
    }
});

// click backdrop to close
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("recordModal");
    if (!modal) return;
    modal.addEventListener("click", (e) => {
        if (e.target.id === "recordModal") {
            closeRecordDialog();
        }
    });
});

function onAISummaryClicked() {
    if (!currentRecordId) return;
    showAISummary(currentRecordId);
    closeRecordDialog();
}
