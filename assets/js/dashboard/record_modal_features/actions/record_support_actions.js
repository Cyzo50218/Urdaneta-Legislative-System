function openSetHyperlink(recordId) {
    const record = recordState[recordId];
    if (!record) return;
    closeRecordDialogAndOpenOtherDialog();


    const modal = document.getElementById("setHyperlinkModal");
    const input = document.getElementById("setHyperlinkInput");
    window.activeSetHyperlinkRecord = recordId;
    input.value = record.hyperlink || "";

    modal.classList.add("show");
}

function readPrintRecord(recordId) {
    const record =
        (typeof recordState !== "undefined" && recordState[recordId]) ||
        assignedItems.find((r) => r.trackingNo === recordId);

    if (!record) return;

    const layout = optionsState?.printLayout || "full";
    const w = window.open("", "_blank");

    const baseHtml = `
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>${record.trackingNo} – ${record.title}</title>

<style>
    body { font-family: Arial, sans-serif; padding: 24px; color:#111; }
    h1 { font-size: 22px; margin-bottom: 4px; }
    h2 { font-size: 16px; margin-top: 18px; margin-bottom: 6px; }
    p  { margin: 4px 0; font-size: 14px; }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px 24px;
        font-size: 13px;
    }
    .label { font-weight: bold; }

    .card {
        border: 1px solid #d1d5db;
        padding: 12px 16px;
        border-radius: 8px;
        margin-top: 10px;
        font-size: 14px;
    }
</style>

</head>
<body>
`;

    let content = "";
    if (layout === "card") {
        content = `
<div class="card">
    <div><strong>${record.trackingNo}</strong></div>
    <div>${record.title}</div>
    <div>${record.type} – ${record.status}</div>
    <div>Author: ${record.author}</div>
    <div>Deadline: ${record.deadline}</div>
</div>
        `;
    } else if (layout === "summary") {
        content = `
<h1>${record.trackingNo}</h1>
<p>${record.title}</p>

<p><span class="label">Type:</span> ${record.type}</p>
<p><span class="label">Status:</span> ${record.status}</p>
<p><span class="label">Author:</span> ${record.author}</p>
<p><span class="label">Deadline:</span> ${record.deadline}</p>
        `;
    } else {
        content = `
<h1>${record.trackingNo}</h1>
<p>${record.title}</p>

<h2>Details</h2>

<div class="meta-grid">
    <div><span class="label">Type:</span> ${record.type}</div>
    <div><span class="label">Status:</span> ${record.status}</div>
    <div><span class="label">Author:</span> ${record.author}</div>
    <div><span class="label">Deadline:</span> ${record.deadline}</div>
</div>
        `;
    }

    const endHtml =
        `
<scr` +
        `ipt>
    window.onload = () => window.print();
</scr` +
        `ipt>
</body>
</html>
`;

    w.document.write(baseHtml + content + endHtml);
    w.document.close();
}

function lockRecord(recordId) {
    const record = recordState[recordId];
    if (!record) return;

    record.locked = !record.locked;
    if (currentRecordId === recordId) openRecordDialog(recordId);
    renderAssignedTable();
}

document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("setHyperlinkModal");
    const btnSave = document.getElementById("setHyperlinkSaveBtn");
    const btnCancel = document.getElementById("setHyperlinkCancelBtn");
    const btnClose = document.getElementById("setHyperlinkCloseBtn");
    const input = document.getElementById("setHyperlinkInput");

    const closeHL = () => {
        openRecordDialog(currentRecordId);

        modal.classList.remove("show");
    };

    btnCancel.addEventListener("click", closeHL);
    btnClose.addEventListener("click", closeHL);
    modal.addEventListener("click", (e) => {
        if (e.target === modal) closeHL();
    });
    btnSave.addEventListener("click", () => {
        const recordId = window.activeSetHyperlinkRecord;
        const record = recordState[recordId];
        if (!record) return;

        const url = input.value.trim();
        record.hyperlink = url;

        persistRecordStateToStorage();

        if (currentRecordId === recordId) {
            openRecordDialog(recordId);
        }

        closeHL();
    });
});
