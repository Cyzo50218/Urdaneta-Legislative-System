async function openMainDataEditor(recordId) {
    const record = recordState[recordId];
    if (!record) return;

    if (record.assignedTo && record.assignedTo.toLowerCase() === "me") {
        record.display_assigned_to = window.USER_SESSION?.full_name || "Me";
        record.display_role = window.USER_SESSION?.role || "";
    } else {
        record.display_assigned_to = record.assignedTo;
        record.display_role = "";
    }
    const printDiv = document.getElementById("printContainer");
    const htmlContent = generateRecordHtml(record);
    printDiv.innerHTML = htmlContent;
    printDiv.style.display = "block";

    const opt = {
        margin: 10,
        filename: `${record.trackingNo}_record.pdf`,
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait" }
    };

    await html2pdf().set(opt).from(printDiv).save();
    printDiv.style.display = "none";

    const win = window.open("", "_blank");
    if (win) {
        win.document.write(`<html><head><title>Print</title></head><body>${htmlContent}</body></html>`);
        win.document.close();
        win.onload = () => { win.focus(); win.print(); win.close(); };
    }
}
function closeAddRecordModal() {
    const modal = document.getElementById("addRecordModal");
    modal.classList.remove("show");
}

function createDynamicInput(container, name, value = "") {
    if (!container) return;

    const wrapper = document.createElement("div");
    wrapper.className = "dynamic-item";
    wrapper.style.marginTop = "6px";

    wrapper.innerHTML = `
        <div style="display:flex; gap:6px; align-items:center;">
            <input type="text" name="${name}" class="form-input-add-dialog" value="${value || ""}" placeholder="Enter value...">
            <button type="button" class="btn-remove" onclick="this.closest('.dynamic-item').remove()">
                <i data-lucide="x"></i>
            </button>
        </div>
    `;

    container.appendChild(wrapper);

    if (window.lucide) {
        lucide.createIcons();
    }
}

function addDynamicField(containerId, inputName) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const div = document.createElement("div");
    div.className = "dynamic-item";
    div.style.marginTop = "6px";

    div.innerHTML = `
        <div style="display:flex;">
            <input type="text" name="${inputName}" class="form-input-add-dialog" placeholder="Enter value...">
            <button type="button" class="btn-remove" onclick="this.parentElement.parentElement.remove()">
                <i data-lucide="x"></i>
            </button>
        </div>
    `;

    container.appendChild(div);

    if (window.lucide) lucide.createIcons();
}
function saveNewHeader(inputId, containerId) {
    const headerInput = document.getElementById(inputId);
    const container = document.getElementById(containerId);
    if (!headerInput || !container) return;
    const headerName = headerInput.value.trim();
    if (!headerName) {
        alert("Please enter a header name.");
        return;
    }
    const headerId = 'header_' + Date.now() + Math.floor(Math.random() * 1000);
    const wrapper = document.createElement("div");

    wrapper.className = "dynamic-header-block";
    wrapper.style = "border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; background: #f9fafb;";
    wrapper.id = headerId;
    wrapper.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
            <strong style="font-size: 12px; color: #374151; text-transform: uppercase;">${headerName}</strong>
            <input type="hidden" name="customHeaderNames[]" value="${headerName}">
            <button type="button" onclick="this.closest('.dynamic-header-block').remove()" 
                    style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 11px; display: flex; align-items: center; gap: 4px;">
                <i data-lucide="trash-2" style="width:12px; height:12px;"></i> Remove
            </button>
        </div>
        <textarea name="customHeaderValues[]" class="form-input-add-dialog" 
                  style="width: 100%; min-height: 60px; resize: vertical;" 
                  placeholder="Enter details for ${headerName}..."></textarea>
    `;
    container.appendChild(wrapper);
    headerInput.value = "";
    if (window.lucide) lucide.createIcons();
}
function addRecord() {
    const modal = document.getElementById("addRecordModal");
    const form = document.getElementById("addRecordForm");
    const errBox = document.getElementById("addRecordError");
    const categoryList = document.getElementById("categoryList");
    const agendaList = document.getElementById("agendaList");
    const committeeList = document.getElementById("committeeList");
    const fileInput = document.getElementById("addRecordFiles");
    const previewBox = document.getElementById("addRecordFilePreview");

    if (typeof closeRecordDialogAndOpenOtherDialog === "function") {
        closeRecordDialogAndOpenOtherDialog();
    }

    if (!modal || !form) {
        console.error("Add Record modal elements not found");
        return;
    }

    form.reset();
    if (errBox) {
        errBox.textContent = "";
        errBox.style.display = "none";
    }

    const f = form.elements;

    if (f.trackingNo) {
        f.trackingNo.value = "";
        f.trackingNo.readOnly = true;
        f.trackingNo.classList.add("form-input-readonly");
    }
    if (f.title) f.title.value = "";
    if (f.type) {
        f.type.value = "resolution";
        f.type.disabled = false;
        f.type.style.cursor = "default";
    }
    if (f.author) f.author.value = "";
    if (f.status) f.status.value = "Assigned";
    if (f.deadline) f.deadline.value = "";
    if (f.sessionType) f.sessionType.value = "";
    if (f.yearTaken) f.yearTaken.value = "";
    if (f.hyperlink) f.hyperlink.value = "";
    if (f.section) f.section.value = "draft";
    if (categoryList) categoryList.innerHTML = "";

    if (agendaList) agendaList.innerHTML = "";
    if (committeeList) committeeList.innerHTML = "";
    if (committeeList) {
        createDynamicInput(committeeList, "committee[]", "");
    }

    // ==== Attachments: pending files + preview ====
    let pendingFiles = [];

    if (previewBox) {
        previewBox.innerHTML = "";
    }

    if (fileInput) {
        fileInput.value = "";
        fileInput.onchange = () => {
            const newFiles = Array.from(fileInput.files || []);
            if (!newFiles.length) return;

            pendingFiles = pendingFiles.concat(newFiles);

            if (!previewBox) return;
            previewBox.innerHTML = "";
            pendingFiles.forEach((file) => {
                const item = document.createElement("div");
                item.className = "attachment-preview-item";

                const isImage = file.type.startsWith("image/");

                if (isImage) {
                    const img = document.createElement("img");
                    img.src = URL.createObjectURL(file);
                    img.alt = file.name;
                    item.appendChild(img);
                }

                const nameDiv = document.createElement("div");
                nameDiv.className = "attachment-preview-item-name";
                nameDiv.textContent = file.name;
                item.appendChild(nameDiv);

                previewBox.appendChild(item);
            });
            fileInput.value = "";
        };
    }

    // Show modal
    modal.style.display = "flex";
    modal.classList.add("show");

    form.onsubmit = async function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const title = (formData.get("title") || "").trim();
        const docType = (formData.get("type") || "Resolution").toLowerCase().trim();
        const author = (formData.get("author") || "").trim();
        const deadline = (formData.get("deadline") || "").trim();
        const committee = formData.getAll("committee[]").map(v => v.trim()).filter(v => v !== "").join("; ");

        if (!title) {
            showAddRecordError("Title is required.");
            return;
        }

        try {
            let savedFiles = [];
            if (pendingFiles.length > 0) {
                const uploadForm = new FormData();
                uploadForm.append("tempId", Date.now());
                pendingFiles.forEach((f) => uploadForm.append("attachments[]", f));

                const uploadRes = await fetch("/../../../api/upload_attachment.php", {
                    method: "POST",
                    body: uploadForm,
                });
                const uploadData = await uploadRes.json();
                if (uploadData.ok) {
                    savedFiles = Array.isArray(uploadData.savedFiles) ? uploadData.savedFiles : [];
                }
            }
            const meta = {
                "Title": title,
                "PrincipalAuthor": author,
                "Committee": committee,
                "DeadlineOrHearingDate": deadline,
                "Attachments": savedFiles
            };
            const customNames = formData.getAll("customHeaderNames[]");
            const customValues = formData.getAll("customHeaderValues[]");
            customNames.forEach((name, index) => {
                const trimmedName = name.trim();
                if (trimmedName) {
                    meta[trimmedName] = customValues[index] || "";
                }
            });
            const response = await fetch("/../../api/account/addDraftToDatabase.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    type: docType,
                    metadata: meta,
                    content: ""
                })
            });

            const result = await response.json();

            if (result.success) {
                const newTrackingNo = result.trackingNo;
                if (typeof renderAssignedTable === "function") await renderAssignedTable();
                if (typeof openRecordDialog === "function") openRecordDialog(newTrackingNo);
                closeAddRecordModal();
            } else {
                showAddRecordError("Database Error: " + result.error);
            }

        } catch (err) {
            console.error("Submission Error:", err);
            showAddRecordError("Connection error. Please try again.");
        }
    };

    function showAddRecordError(msg) {
        if (!errBox) return;
        errBox.textContent = msg;
        errBox.style.display = "block";
    }
}
function closeConfirmModal() {
    const confirmModal = document.getElementById('confirmActionModal');
    confirmModal.style.display = 'none';
    confirmModal.classList.remove("show");

    const mainModal = document.getElementById("addRecordModal");
    mainModal.style.display = "flex";
    mainModal.classList.add("show");
}
function injectExistingHeader(name, value, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const wrapper = document.createElement("div");
    wrapper.className = "form-group dynamic-header-field";
    wrapper.style = "position: relative;";
    wrapper.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <label class="form-label" style="margin-bottom: 0;">${name}</label>
            <button type="button" onclick="this.closest('.dynamic-header-field').remove()" 
                    style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 10px; display: flex; align-items: center; gap: 3px;">
                <i data-lucide="x-circle" style="width:12px; height:12px;"></i> Remove
            </button>
        </div>
        <input type="hidden" name="customHeaderNames[]" value="${name}">
        <textarea 
            name="customHeaderValues[]" 
            class="form-input-add-dialog" 
            style="width: 100%; min-height: 40px; resize: vertical;" 
            placeholder="Enter details for ${name}...">${value}</textarea>
    `;

    container.appendChild(wrapper);
    if (window.lucide) lucide.createIcons();
}
function editRecord() {
    const modal = document.getElementById("addRecordModal");
    const form = document.getElementById("addRecordForm");
    const errBox = document.getElementById("addRecordError");
    if (typeof closeRecordDialogAndOpenOtherDialog === "function") {
        closeRecordDialogAndOpenOtherDialog();
    }

    if (!modal || !form) {
        console.error("Edit Record modal elements not found");
        return;
    }

    if (!currentRecordId || !recordState[currentRecordId]) {
        alert("No record selected to edit.");
        return;
    }

    const baseRec = recordState[currentRecordId];
    const titleEl = document.getElementById("addRecordModalTitle");

    form.reset();
    const rec = recordState[currentRecordId];

    titleEl.textContent = "Edit Record - " + rec.trackingNo;
    form.reset();
    if (errBox) {
        errBox.textContent = "";
        errBox.style.display = "none";
    }

    const f = form.elements;
    if (f.trackingNo) {
        f.trackingNo.value = baseRec.trackingNo || "";
        f.trackingNo.readOnly = true;
        f.trackingNo.classList.add("form-input-readonly");
    }
    if (f.title) f.title.value = baseRec.title || "";
    if (f.type) {
        const normalizedType = baseRec.type.toLowerCase();
        f.type.value = normalizedType;
        f.type.disabled = true;
        f.type.style.cursor = "not-allowed";
        f.type.classList.add("form-input-readonly");
    }
    if (f.author) f.author.value = baseRec.author || "";
    if (f.deadline) f.deadline.value = baseRec.deadline || "";
    if (f.sessionType) f.sessionType.value = baseRec.sessionType || "";
    if (f.yearTaken) f.yearTaken.value = baseRec.yearTaken || "";
    if (f.hyperlink) f.hyperlink.value = baseRec.hyperlink || "";
    if (f.summary) f.summary.value = baseRec.summary || "";
    const customContainer = document.getElementById("customHeadersListBasicFields");
    if (customContainer) {
        customContainer.innerHTML = "";
        if (Array.isArray(baseRec.additional_fields)) {
            baseRec.additional_fields.forEach(field => {
                injectExistingHeader(field.name, field.value, "customHeadersListBasicFields");
            });
        }
    }
    if (f && f.section) {
        let sectionValue = "assigned";
        const st = (baseRec.status || "").toLowerCase().trim();

        if (st === "assigned") {
            sectionValue = "assigned";
        } else if (st === "in-progress" || st.includes("progress")) {
            sectionValue = "in-progress";
        } else if (st === "for-review" || st.includes("review")) {
            sectionValue = "for-review";
        } else if (st === "approved" || st === "enacted") {
            sectionValue = "approved";
        } else if (st === "rejected") {
            sectionValue = "rejected";
        } else if (st === "archived") {
            sectionValue = "archived";
        } else {
            if (st.includes("committee")) sectionValue = "in-progress";
            else if (st.includes("session")) sectionValue = "for-review";
            else sectionValue = "assigned";
        }

        f.section.value = sectionValue;
    }

    const categoryList = document.getElementById("categoryList");
    const agendaList = document.getElementById("agendaList");
    const committeeList = document.getElementById("committeeList");

    if (categoryList) categoryList.innerHTML = "";
    if (agendaList) agendaList.innerHTML = "";
    if (committeeList) committeeList.innerHTML = "";
    const baseCategories = (baseRec.category || "").split(";").map(v => v.trim()).filter(v => v !== "");
    const baseAgendaNos = (baseRec.agendaNo || "").split(";").map(v => v.trim()).filter(v => v !== "");
    const cats = baseCategories.length ? baseCategories : [""];
    const agds = baseAgendaNos.length ? baseAgendaNos : [""];

    if (categoryList) {
        cats.forEach(val => {
            createDynamicInput(categoryList, "category[]", val);
        });
    }

    if (agendaList) {
        agds.forEach(val => {
            createDynamicInput(agendaList, "agendaNo[]", val);
        });
    }
    if (committeeList) {
        committeeList.innerHTML = "";
        const coms = (baseRec.committee || "").split(";").map(v => v.trim()).filter(v => v !== "");

        if (coms.length === 0) {
            addDynamicField('committeeList', 'committee[]');
        } else {
            coms.forEach(val => {
                createDynamicInput(committeeList, "committee[]", val);
            });
        }
    }
    modal.style.display = "flex";
    modal.classList.add("show");
    form.onsubmit = function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        const trackingNo = baseRec.trackingNo;
        const title = (formData.get("title") || "").trim();
        if (!title) {
            showEditRecordError("Title is required.");
            return;
        }
        document.getElementById('confirmActionModal').classList.add("show");
        document.getElementById('lastActionInput').value = ""; // Clear previous
        document.getElementById('lastActionInput').focus();
        document.getElementById('finalSubmitBtn').onclick = async function () {
            const lastAction = document.getElementById('lastActionInput').value.trim();

            if (!lastAction) {
                alert("Please enter an action description.");
                return;
            }
            const committeeArray = formData.getAll("committee[]");
            const mergedCommittee = committeeArray
                .map(v => v.trim())
                .filter(v => v !== "")
                .join("; ");

            formData.delete("committee[]");
            formData.append("committee", mergedCommittee);
            formData.append("section", formData.get("section"));
            formData.append("last_action", lastAction);
            formData.append("trackingNo", baseRec.trackingNo);
            const customNames = formData.getAll("customHeaderNames[]");
            const customValues = formData.getAll("customHeaderValues[]");
            const customHeadersData = customNames.map((name, index) => ({
                name: name,
                value: customValues[index]
            }));
            formData.append("custom_metadata", JSON.stringify(customHeadersData));

            try {
                const saveRes = await fetch("/../../../api/account/saveLegislationDetails.php", {
                    method: "POST",
                    body: formData
                });
                const saveResult = await saveRes.json();

                if (saveResult.success) {
                    closeConfirmModal();
                    renderAssignedTable();
                    openRecordDialog(trackingNo);
                }
            } catch (err) {
                console.error(err);
                alert("Failed to save record.");
            }
        };
    };

    function showEditRecordError(msg) {
        if (!errBox) return;
        errBox.textContent = msg;
        errBox.style.display = "block";
    }
}

function deleteRecord(recordId) {
    const record = recordState[recordId];
    if (!record) return;
    closeRecordDialogAndOpenOtherDialog();

    pendingDeleteRecordId = recordId;

    const modal = document.getElementById("deleteRecordModal");
    const labelEl = document.getElementById("deleteRecordLabel");

    if (labelEl) {
        labelEl.textContent = `${record.trackingNo} – ${record.title}`;
    }

    if (modal) {
        modal.style.display = "flex";
        modal.classList.add("show");
    }

    if (window.lucide) {
        lucide.createIcons();
    }
}

function closeDeleteRecordModal() {
    const modal = document.getElementById("deleteRecordModal");
    if (!modal) return;
    modal.style.display = "none";
    modal.classList.remove("show");
    openRecordDialog(pendingDeleteRecordId);
    pendingDeleteRecordId = null;
}
async function confirmDeleteRecord() {
    if (!pendingDeleteRecordId) {
        closeDeleteRecordModal();
        return;
    }

    const recordId = pendingDeleteRecordId;
    const record = recordState[recordId];

    if (!record) {
        closeDeleteRecordModal();
        return;
    }
    const deleteBtn = document.querySelector("#deleteRecordModal .record-btn-danger");
    const cancelBtn = document.querySelector("#deleteRecordModal .hyperlink-btn");
    const closeIcon = document.querySelector("#deleteRecordModal .record-modal-close");
    const modalBackdrop = document.getElementById("deleteRecordModal");

    if (deleteBtn) {
        deleteBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Deleting...`;
        deleteBtn.disabled = true;
    }
    [cancelBtn, closeIcon].forEach(el => {
        if (el) {
            el.style.opacity = "0.4";
            el.style.pointerEvents = "none";
        }
    });
    modalBackdrop.onclick = null;

    try {
        const response = await fetch('/../../../api/account/deleteRecord.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ trackingNo: record.trackingNo })
        });

        const result = await response.json();

        if (result.success) {
            delete recordState[recordId];

            if (typeof persistRecordStateToStorage === "function") {
                persistRecordStateToStorage();
            }
            closeRecordDialog();
            renderAssignedTable();
            closeDeleteRecordModal();

            if (typeof showToast === 'function') {
                showToast("Record deleted successfully", "success");
            }
        } else {
            throw new Error(result.error || "Failed to delete record");
        }
    } catch (err) {
        console.error("Delete Error:", err);
        if (typeof showToast === 'function') {
            showToast(err.message, "error");
        }
        if (deleteBtn) {
            deleteBtn.innerHTML = "Delete";
            deleteBtn.disabled = false;
        }
        [cancelBtn, closeIcon].forEach(el => {
            if (el) {
                el.style.opacity = "1";
                el.style.pointerEvents = "auto";
            }
        });
    }
}

function transferRecord(recordId) {
    const record = recordState[recordId];
    if (!record) return;

    const newStatus = prompt(
        "Transfer record to new status (e.g., Approved, For Committee, For 2nd Reading):",
        record.status
    );
    if (!newStatus) return;

    record.status = newStatus.trim();
    record.statusClass = "status-review";

    if (currentRecordId === recordId) openRecordDialog(recordId);
    renderAssignedTable();
}

function resetRecordChanges(recordId) {
    const record = recordState[recordId];
    if (!record || !record.original) return;

    if (!confirm("Reset this record to its original state?")) return;

    recordState[recordId] = {
        ...record.original,
        original: { ...record.original },
    };

    if (currentRecordId === recordId) openRecordDialog(recordId);
    renderAssignedTable();
}
