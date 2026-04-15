
function resolveAttachmentUrl(record, filename) {
    if (!filename) return "";
    const trimmed = filename.trim();
    return trimmed;
}

function loadFileForRecord(recordId) {
    const record = recordState[recordId];
    if (!record) return;

    const input = document.getElementById("recordFileInput");
    if (!input) return;

    input.onchange = () => {
        const files = Array.from(input.files || []);
        if (!files.length) return;

        const formData = new FormData();
        formData.append("trackingNo", record.trackingNo);
        files.forEach((f) => formData.append("attachments[]", f));

        fetch("/../../../api/upload_attachment.php", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (!data.ok) {
                    alert("Upload failed: " + data.message);
                    return;
                }

                const savedFiles = data.savedFiles || [];
                if (!Array.isArray(record.attachments)) {
                    record.attachments = [];
                }
                record.attachments = record.attachments.concat(savedFiles);
                persistRecordStateToStorage();
                if (currentRecordId === recordId) {
                    openRecordDialog(recordId);

                    const startIdx = record.attachments.length - savedFiles.length;
                    if (startIdx >= 0) {
                        previewAttachment(recordId, startIdx);
                    }
                }
            })
            .catch((err) => {
                console.error(err);
                alert("Upload error.");
            });

        input.value = "";
    };

    input.click();
}

function getAttachmentDisplayName(path) {
    if (!path) return "";
    const trimmed = String(path).trim();
    const parts = trimmed.split("/");
    return parts[parts.length - 1];
}

function previewAttachment(recordId, index) {
    const record =
        (typeof recordState !== "undefined" && recordState[recordId]) ||
        assignedItems.find((r) => r.trackingNo === recordId);

    if (!record || !record.attachments || !record.attachments[index]) return;

    const filename = record.attachments[index];
    const url = resolveAttachmentUrl(record, filename);
    const previewEl = document.getElementById("recordAttachmentPreview");

    if (!previewEl) {
        if (url) window.open(url, "_blank");
        return;
    }

    const lower = filename.toLowerCase();

    if (/\.(png|jpe?g|gif|webp|bmp|svg)$/.test(lower)) {
        previewEl.innerHTML = `
            <div class="record-attachment-preview-inner">
                <div class="record-attachment-preview-image-wrapper">
                    <img src="${url}" alt="${filename}" />
                </div>
            </div>
        `;
    } else if (lower.endsWith(".pdf")) {
        previewEl.innerHTML = `
        <div class="record-attachment-preview-inner pdf-preview">
            <iframe
                    src="${url}"
                    class="record-attachment-preview-frame"
                    title="${filename}"
                ></iframe>
        </div>
    `;
    } else {
        previewEl.innerHTML = `
            <div class="record-attachment-preview-inner">
                <p>
                    Preview not available for this file type.
                    <a href="${url}" target="_blank" rel="noopener">Open in new tab</a>
                </p>
            </div>
        `;
    }
}


