async function downloadAllPDF() {
    if (typeof html2pdf === "undefined") {
        alert("html2pdf.js library is not loaded.");
        return;
    }

    const itemsToProcess = assignedItems.length > 0 ? assignedItems : Object.values(recordState);
    if (itemsToProcess.length === 0) {
        alert("No records to download.");
        return;
    }

    const printDiv = document.getElementById("printContainer");
    printDiv.style.display = "block";

    for (const item of itemsToProcess) {
        const record = recordState[item.trackingNo] || item;
        printDiv.innerHTML = generateRecordHtml(record);
        const opt = {
            margin: 10,
            filename: `${record.trackingNo}_record.pdf`,
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: "mm", format: "a4", orientation: "portrait" }
        };
        await html2pdf().set(opt).from(printDiv).save();
    }

    printDiv.style.display = "none";
    alert(`Finished downloading ${itemsToProcess.length} records.`);
}
function generateRecordHtml(record) {
    const safe = (v) => (v == null || v === "" ? "—" : v);

    const cleanCommittee = (str) => {
        if (!str) return "—";
        const items = str.replace(/[\[\]]/g, "")
            .split(";")
            .map(v => v.trim())
            .filter(v => v !== "");

        if (items.length === 0) return "—";
        return items.map(item => {
            let cleanItem = item;
            if (cleanItem.endsWith(";")) cleanItem = cleanItem.slice(0, -1);
            return `<div style="margin-bottom: 4px;">• ${cleanItem}.</div>`;
        }).join("");
    };

    let customFieldsHtml = "";
    if (Array.isArray(record.additional_fields) && record.additional_fields.length > 0) {
        const rows = record.additional_fields.map(field => `
            <tr>
                <td style="width:30%; padding:6px 10px; font-size:11px; color:#6b7280; border-bottom:1px solid #f3f4f6;">${safe(field.name)}</td>
                <td style="width:70%; padding:6px 10px; font-size:12px; font-weight:500; border-bottom:1px solid #f3f4f6;">${safe(field.value)}</td>
            </tr>
        `).join("");
        customFieldsHtml = `<table style="width:100%; border-collapse:collapse; margin-bottom:16px; border:1px solid #e5e7eb;">
            <thead><tr style="background:#f9fafb;"><th colspan="2" style="text-align:left; padding:6px 10px; font-size:11px; text-transform:uppercase; letter-spacing:0.08em; color:#4b5563;">Additional Details</th></tr></thead>
            <tbody>${rows}</tbody>
        </table>`;
    }

    let attachmentsHtml = record.attachments?.length > 0
        ? record.attachments.map((fullPath, idx) => {
            const filename = fullPath.split("/").pop() || fullPath;
            const isPdf = fullPath.toLowerCase().endsWith(".pdf");
            return `<tr>
                <td style="padding:6px 8px; font-size:11px;">${idx + 1}</td>
                <td style="padding:6px 8px; font-size:11px;">${filename}</td>
                <td style="padding:6px 8px; font-size:11px;">${isPdf ? 'PDF document' : `<img src="${fullPath}" style="max-width:100%; max-height:220px; border:1px solid #d1d5db; border-radius:4px;">`}</td>
            </tr>`;
        }).join("")
        : `<tr><td colspan="3" style="padding:6px 8px; font-size:11px; color:#6b7280;">No attachments.</td></tr>`;

    return `
        <div style="padding:24px 28px; font-family: system-ui, -apple-system, sans-serif; color:#111827; font-size:12px;">
            <table style="width:100%; border-collapse:collapse; margin-bottom:12px;">
                <tr>
                    <td style="width:64px; vertical-align:top;">
                        <div style="width:56px; height:56px; border-radius:999px; border:2px solid #22c55e; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:bold; color:#16a34a;">CITY</div>
                    </td>
                    <td style="vertical-align:top; padding-left:8px;">
                        <div style="font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:#6b7280;">Government of ${window.LIS_SYSTEM?.name || "City Government"}</div>
                        <div style="font-size:16px; font-weight:700; color:#111827;">Sangguniang Panlungsod</div>
                        <div style="font-size:11px; color:#4b5563; margin-top:2px;">Legislative Information System - Official Record Extract</div>
                    </td>
                    <td style="text-align:right; vertical-align:top; font-size:10px; color:#6b7280;">
                        Generated: ${new Date().toLocaleString()}
                    </td>
                </tr>
            </table>

            <hr style="border:none; border-top:1px solid #e5e7eb; margin:8px 0 16px;">

            <div style="margin-bottom:16px;">
                <div style="font-size:11px; letter-spacing:0.12em; text-transform:uppercase; color:#6b7280; margin-bottom:4px;">Legislative Record</div>
                <div style="font-size:15px; font-weight:700; color:#111827; margin-bottom:4px;">${safe(record.title)}</div>
                <div style="font-size:11px; color:#374151;">
                    Tracking No.: <strong>${safe(record.trackingNo)}</strong> &nbsp;•&nbsp; 
                    Status: <strong>${safe(record.status)}</strong> &nbsp;•&nbsp; 
                    Assigned To: <strong>${safe(record.display_assigned_to)}</strong>
                    ${record.display_role ? ` &nbsp;•&nbsp; Role: <strong>${record.display_role}</strong>` : ''}
                </div>
            </div>

            <table style="width:100%; border-collapse:collapse; margin-bottom:14px; border:1px solid #e5e7eb;">
                <thead><tr style="background:#f9fafb;"><th colspan="4" style="text-align:left; padding:6px 10px; font-size:11px; text-transform:uppercase; color:#4b5563;">Basic Information</th></tr></thead>
                <tbody>
                    <tr>
                        <td style="width:20%; padding:6px 10px; color:#6b7280;">Author</td>
                        <td style="width:30%; font-weight:500;">${safe(record.author)}</td>
                        <td style="width:20%; padding:6px 10px; color:#6b7280;">Deadline</td>
                        <td style="width:30%; font-weight:500;">${safe(record.deadline)}</td>
                    </tr>
                </tbody>
            </table>

            <table style="width:100%; border-collapse:collapse; margin-bottom:16px; border:1px solid #e5e7eb;">
                <thead><tr style="background:#f9fafb;"><th colspan="4" style="text-align:left; padding:6px 10px; font-size:11px; text-transform:uppercase; color:#4b5563;">Legislation Details</th></tr></thead>
                <tbody>
                    <tr>
                        <td style="width:20%; padding:6px 10px; color:#6b7280; vertical-align: top;">Committee</td>
                        <td colspan="3" style="padding:10px; font-weight:500; line-height:1.4;">
                            ${cleanCommittee(record.committee)}
                        </td>
                    </tr>
                </tbody>
            </table>

            ${customFieldsHtml}

            <table style="width:100%; border-collapse:collapse; border:1px solid #e5e7eb;">
                <thead>
                    <tr style="background:#f9fafb;"><th colspan="3" style="text-align:left; padding:6px 10px; font-size:11px; text-transform:uppercase; color:#4b5563;">Attachments</th></tr>
                </thead>
                <tbody>${attachmentsHtml}</tbody>
            </table>

            <div style="margin-top:18px; font-size:10px; color:#9ca3af; text-align:right;">Urdaneta LIS • Official Extract</div>
        </div>
    `;
}