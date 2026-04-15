function renderAssignedTable(items) {
    if (items === undefined || items === null) {
        items = Object.values(recordState) || [];
    }
    const tbody = document.querySelector(".data-table tbody");
    if (!tbody) return;

    tbody.innerHTML = "";

    const cols = gridConfig.visibleColumns || {
        trackingNo: true,
        title: true,
        type: true,
        author: true,
        status: true,
        deadline: true,
        actions: true,
    };

    const userRole = (window.USER_SESSION?.role || "").toLowerCase();
    const isHighLevel = (userRole === 'councilor' || userRole === 'mayor');
    const columnOrder = [
        "trackingNo",
        "title",
        "type",
        "author",
        "status",
        "assignedTo",
        "deadline",
        "actions",
    ];
    const visibleKeys = columnOrder.filter((k) => cols[k]);
    const colSpan = visibleKeys.length || 1;

    // Empty state
    if (!items.length) {
        const tr = document.createElement("tr");
        const td = document.createElement("td");
        td.colSpan = colSpan;
        td.textContent = "No records found.";
        td.style.textAlign = "center";
        td.style.padding = "0.9rem";
        td.style.color = "#6b7280";
        tr.appendChild(td);
        tbody.appendChild(tr);

        if (window.lucide && typeof lucide.createIcons === "function") {
            lucide.createIcons();
        }
        return;
    }

    items.forEach((item) => {
        const record = recordState[item.trackingNo] || item;
        const lockedIcon = record.locked
            ? `<i data-lucide="lock" class="icon-sm lock-icon"></i> `
            : "";
        const isUsed = currentUsedAssignedIds.includes(record.trackingNo);

        const tr = document.createElement("tr");
        tr.dataset.id = record.trackingNo;
        tr.onclick = function () {
            if (typeof selectAssignedRow === "function") {
                selectAssignedRow(record.trackingNo);
            }
            openRecordDialog(record.trackingNo);
        };

        let html = "";

        if (cols.trackingNo) {
            html += `
                <td data-label="Tracking No.">
                    <span class="tracking-no">${record.trackingNo}</span>
                </td>`;
        }

        if (cols.title) {
            html += `
                <td data-label="Title">${lockedIcon}${record.title}</td>`;
        }

        if (cols.type) {
            html += `
                <td data-label="Type">
                    <span class="badge badge-${record.type.toLowerCase()}">${record.type}</span>
                </td>`;
        }

        if (cols.author) {
            html += `
                <td data-label="Principal Author">${record.author}</td>`;
        }

        if (cols.status) {
            html += `
                <td data-label="Current Status">
                    <span class="status ${record.statusClass}">${record.status}</span>
                </td>`;
        }

        if (isHighLevel) {
            html += `
                <td data-label="Assigned Staff">
                    <div class="assigned-chip">
                        <span>${record.assignedName || "Unassigned"}</span>
                    </div>
                </td>`;
        }

        if (gridConfig.visibleColumns.deadline) html += `<td data-label="Deadline">${record.deadline}</td>`;

        if (gridConfig.visibleColumns.actions) {
            const isUnassigned = !record.assignedName || record.assignedName.trim() === "" || record.assignedName === "Unassigned";

            html += `
        <td data-label="Actions">
            <div class="action-buttons">
                <button class="btn-ai" onclick="event.stopPropagation(); showAISummary('${record.trackingNo}')">
                    <i data-lucide="sparkles" class="icon-sm"></i> AI Summary
                </button>

                ${isHighLevel ? `
                    <button class="btn-assign" onclick="event.stopPropagation(); openAssignStaffModal('${record.trackingNo}')">
                        <i data-lucide="user-plus" class="icon-sm"></i> Reassign
                    </button>
                ` : `
                    ${(isUnassigned || isUsed) ? `
                        <button class="btn-use ${isUsed ? "btn-remove" : ""}"
                            onclick="event.stopPropagation(); useAssignedItem('${record.trackingNo}')">
                            <i data-lucide="${isUsed ? "minus-circle" : "check-circle-2"}" class="icon-sm"></i>
                            ${isUsed ? "Remove" : "Use"}
                        </button>
                    ` : `
                        <span class="text-muted small">Already Assigned</span>
                    `}
                `}
            </div>
        </td>`;
        }

        tr.innerHTML = html;
        tbody.appendChild(tr);
    });

    if (window.lucide && typeof lucide.createIcons === "function") {
        lucide.createIcons();
    }
}

async function openAssignStaffModal(trackingNo) {
    const modal = document.getElementById('reassignModal');
    const select = document.getElementById('staffSelect');
    const trackingInput = document.getElementById('reassignTrackingNo');
    trackingInput.value = trackingNo;
    modal.classList.add('show');

    try {
        const response = await fetch('/api/account/showUsersStaff.php');
        const result = await response.json();

        if (result.success) {
            select.innerHTML = '<option value="">-- Choose Staff --</option>';
            result.staff.forEach(person => {
                const option = document.createElement('option');
                option.value = person.user_id;
                option.textContent = person.full_name;
                select.appendChild(option);
            });
        } else {
            select.innerHTML = '<option value="">Error loading staff</option>';
        }
    } catch (error) {
        console.error("Staff fetch failed:", error);
    }

    if (window.lucide) lucide.createIcons();
}
function closeReassignModal() {
    document.getElementById('reassignModal').classList.remove('show');
    document.getElementById('reassignForm').reset();
}
document.getElementById('reassignForm').onsubmit = async function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const response = await fetch('/api/account/updateAssignment.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.success) {
            alert("Assigned successfully!");
            closeReassignModal();
            if (typeof fetchRecords === 'function') fetchRecords();
        } else {
            alert("Error: " + result.error);
        }
    } catch (error) {
        console.error("Assignment failed:", error);
    }
};

function initUsedAssigned() {
    try {
        const saved = localStorage.getItem("lis_activeAssignedArray");
        if (saved) {
            currentUsedAssignedIds = JSON.parse(saved);
        }
    } catch (e) {
        currentUsedAssignedIds = [];
    }
}

function useAssignedItem(trackingNo) {
    const record = recordState[trackingNo];
    if (!record) return;

    const idx = currentUsedAssignedIds.indexOf(trackingNo);

    if (idx === -1) {
        currentUsedAssignedIds.push(trackingNo);
    } else {
        currentUsedAssignedIds.splice(idx, 1);
    }
    localStorage.setItem(
        "lis_activeAssignedArray",
        JSON.stringify(currentUsedAssignedIds)
    );
    if (typeof renderAssignedTable === "function") {
        renderAssignedTable(Object.values(recordState));
    }
    highlightUsedAssignedRows();

    if (typeof updateAssignedLegend === "function") updateAssignedLegend(record);
    if (typeof updateQuickActionsPanel === "function")
        updateQuickActionsPanel(record);
    if (typeof selectAssignedRow === "function") selectAssignedRow(trackingNo);
}

function highlightUsedAssignedRows() {
    const tbody = document.querySelector(".data-table tbody");
    if (!tbody) return;

    tbody.querySelectorAll("tr").forEach((tr) => {
        const id = tr.dataset.id;

        if (currentUsedAssignedIds.includes(id)) {
            tr.classList.add("assigned-in-use");
        } else {
            tr.classList.remove("assigned-in-use");
        }
    });
}

function getUsedAssignedItems() {
    return currentUsedAssignedIds.map((id) => recordState[id]).filter(Boolean);
}

function renderMyAssignedOnly() {
    const list = getUsedAssignedItems();
    renderAssignedTable(list);
}

function selectAssignedRow(trackingNo) {
    const rows = document.querySelectorAll(".data-table tbody tr");
    rows.forEach((row) => row.classList.remove("selected-row"));

    const targetRow = document.querySelector(`tr[data-id="${trackingNo}"]`);
    if (targetRow) {
        targetRow.classList.add("selected-row");
    }
}

/* ---------- FILTER LOGIC ---------- */

function applyAssignedFilters() {
    const searchInput = document.getElementById("assignedSearch");
    const dateFromInput = document.getElementById("assignedDateFrom");
    const dateToInput = document.getElementById("assignedDateTo");

    const searchTerm = (searchInput?.value || "").trim().toLowerCase();
    const fromValue = dateFromInput?.value;
    const toValue = dateToInput?.value;
    filteredAssignedItems = assignedItems.filter((item) => {
        const record = (typeof recordState !== "undefined" && recordState[item.trackingNo]) || item;
        const attachmentText = Array.isArray(record.attachments) ? record.attachments.join(" ") : "";
        const haystack = [
            record.title || "",
            record.author || "",
            record.trackingNo || "",
            record.status || "",
            record.committee || "",
            record.category || "",
            attachmentText
        ].join(" ").toLowerCase();

        const matchesSearch = !searchTerm || haystack.includes(searchTerm);
        let matchesDate = true;
        if (fromValue || toValue) {
            if (!item.deadline) {
                matchesDate = false;
            } else {
                const itemDate = new Date(item.deadline);
                itemDate.setHours(0, 0, 0, 0);

                if (isNaN(itemDate.getTime())) {
                    matchesDate = false;
                } else {
                    if (fromValue) {
                        const fromDate = new Date(fromValue);
                        fromDate.setHours(0, 0, 0, 0);
                        if (itemDate < fromDate) matchesDate = false;
                    }
                    if (toValue) {
                        const toDate = new Date(toValue);
                        toDate.setHours(0, 0, 0, 0);
                        if (itemDate > toDate) matchesDate = false;
                    }
                }
            }
        }

        return matchesSearch && matchesDate;
    });
    renderAssignedTable(filteredAssignedItems);
    const myFiltered = filteredAssignedItems.filter(item => (item.assignedTo || "") === "Me");
    const myTotal = assignedItems.filter(item => (item.assignedTo || "") === "Me");
    updateMeAssignedCounts(myFiltered.length, myTotal.length);
}
function updateMeAssignedCounts(filteredCount, totalCount) {
    const rowCountEl = document.getElementById("meAssignedRowCount");
    const rowTotalEl = document.getElementById("meAssignedTotalCount");
    const rowPercentEl = document.getElementById("meAssignedRowPercent");

    if (!rowCountEl || !rowTotalEl || !rowPercentEl) return;

    rowCountEl.textContent = `${filteredCount} of ${totalCount}`;
    rowTotalEl.textContent = `Total: ${totalCount}`;

    const percent = totalCount === 0 ? 0 : Math.round((filteredCount / totalCount) * 100);
    rowPercentEl.textContent = `(${percent}%)`;
}