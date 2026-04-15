// --- ACTIONS STATE ---
const toolbarState = {};
const manualToggles = {};
let manualModalToggles = {
    bold: false,
    italic: false,
    underline: false,
    insertUnorderedList: false,
    insertOrderedList: false
};
const toolbarEditModalState = {
    bold: false,
    italic: false,
    underline: false,
    insertUnorderedList: false,
    insertOrderedList: false
};

function initializeRowState(rowId) {
    if (!toolbarState[rowId]) {
        toolbarState[rowId] = {
            bold: false, italic: false, underline: false,
            insertUnorderedList: false, insertOrderedList: false
        };
    }
    if (!manualToggles[rowId]) {
        manualToggles[rowId] = {
            bold: false, italic: false, underline: false
        };
    }
}

window.addNewSectionRow = function () {
    const container = document.getElementById('dynamicFormContainer');
    const saveAllBtn = document.getElementById('saveAllBtn');
    saveAllBtn.classList.remove('hidden');

    const rowId = 'row_' + Date.now();
    const rowHtml = `
        <div id="${rowId}" class="mb-6 bg-white border border-slate-300 rounded-md shadow-sm overflow-hidden">
            
            <div class="flex items-center justify-between bg-slate-50 border-b border-slate-300 px-3 py-1.5">
                <div class="toolbar flex items-center gap-0.5">
                    <button type="button" onmousedown="event.preventDefault()" onclick="formatDoc('undo')" class="p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Undo">
                        <i data-lucide="undo-2" class="w-4 h-4"></i>
                    </button>
                    <button type="button" onmousedown="event.preventDefault()" onclick="formatDoc('redo')" class="p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Redo">
                        <i data-lucide="redo-2" class="w-4 h-4"></i>
                    </button>

                    <div class="w-px h-4 bg-slate-300 mx-1.5"></div>

                    <button type="button" data-cmd="bold" onmousedown="event.preventDefault()" onclick="formatDoc('bold', event)" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Bold">
                        <i data-lucide="bold" class="w-4 h-4"></i>
                    </button>
                    <button type="button" data-cmd="italic" onmousedown="event.preventDefault()" onclick="formatDoc('italic', event)" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Italic">
                        <i data-lucide="italic" class="w-4 h-4"></i>
                    </button>
                    <button type="button" data-cmd="underline" onmousedown="event.preventDefault()" onclick="formatDoc('underline', event)" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Underline">
                        <i data-lucide="underline" class="w-4 h-4"></i>
                    </button>

                    <div class="w-px h-4 bg-slate-300 mx-1.5"></div>

                    <button type="button" onmousedown="event.preventDefault()" onclick="formatDoc('insertText', '§ ')" class="p-1.5 rounded hover:bg-slate-200 text-slate-900 font-bold text-xs" title="Section Symbol">§</button>
                    <button type="button" onmousedown="event.preventDefault()" onclick="formatDoc('insertText', '¶ ')" class="p-1.5 rounded hover:bg-slate-200 text-slate-900 font-bold text-xs" title="Paragraph Symbol">¶</button>

                    <div class="w-px h-4 bg-slate-300 mx-1.5"></div>

                    <button type="button" data-cmd="insertUnorderedList" onmousedown="event.preventDefault()" onclick="formatDoc('insertUnorderedList')" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Bullet List">
                        <i data-lucide="list" class="w-4 h-4"></i>
                    </button>
                    <button type="button" data-cmd="insertOrderedList" onmousedown="event.preventDefault()" onclick="formatDoc('insertOrderedList')" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Numbered List">
                        <i data-lucide="list-ordered" class="w-4 h-4"></i>
                    </button>

                    <div class="w-px h-4 bg-slate-300 mx-1.5"></div>

                    <button type="button" onmousedown="event.preventDefault()" onclick="formatDoc('outdent')" class="p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Decrease Indent">
                        <i data-lucide="indent-decrease" class="w-4 h-4"></i>
                    </button>
                    <button type="button" onmousedown="event.preventDefault()" onclick="formatDoc('indent')" class="p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Increase Indent">
                        <i data-lucide="indent-increase" class="w-4 h-4"></i>
                    </button>
                </div>

                <button onclick="removeSectionRow('${rowId}')" class="text-slate-400 hover:text-red-600 p-1" title="Remove">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="p-5">
                <div id="title_${rowId}" 
                     contenteditable="true" 
                     onfocus="applyToolbarState(this)"
                     onkeyup="updateToolbar(this)" 
                     onmouseup="updateToolbar(this)"
                     class="section-title-editor w-full p-1 outline-none font-bold text-xl text-slate-900"
                     data-placeholder="Section Title..."></div>

                <hr class="border-slate-200 my-4">

                <div id="editor_${rowId}" 
                     contenteditable="true" 
                     onfocus="applyToolbarState(this)"
                     onkeydown="handleEditorKeydown(event, this)" 
                     onkeyup="updateToolbar(this)" 
                     onmouseup="updateToolbar(this)"
                     class="section-content-editor w-full p-1 min-h-[200px] outline-none text-slate-700 font-serif text-base leading-relaxed"
                     data-placeholder="Start writing your content here..."></div>
                </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', rowHtml);
    if (window.lucide) lucide.createIcons();
};
window.formatDoc = function (cmd, value = null) {
    const event = window.event;
    const btn = event ? event.currentTarget : null;
    if (!btn) return;

    const parentRow = btn.closest('[id^="row_"]');
    if (!parentRow) return;

    const rowId = parentRow.id;
    const titleEditor = parentRow.querySelector('.section-title-editor');
    const bodyEditor = parentRow.querySelector('.section-content-editor');
    initializeRowState(rowId);
    if (document.activeElement !== titleEditor && document.activeElement !== bodyEditor) {
        bodyEditor.focus();
    }
    const active = document.activeElement;
    if (active === titleEditor && (cmd === 'insertUnorderedList' || cmd === 'insertOrderedList')) {
        return;
    }
    if (manualToggles[rowId] && manualToggles[rowId].hasOwnProperty(cmd)) {
        manualToggles[rowId][cmd] = !toolbarState[rowId][cmd];
        toolbarState[rowId][cmd] = manualToggles[rowId][cmd];
    }
    if (cmd === 'insertText' || cmd === 'indent' || cmd === 'outdent') {
        document.execCommand(cmd, false, value);
    } else {
        document.execCommand(cmd, false, null);
    }

    if (cmd === 'undo' || cmd === 'redo') {
        setTimeout(() => window.updateToolbar(active), 10);
    } else {
        syncButtonUI(parentRow.querySelector('.toolbar'), rowId);
    }
};

window.updateToolbar = function (editor) {
    const parentRow = editor.closest('[id^="row_"]');
    if (!parentRow) return;

    const rowId = parentRow.id;
    initializeRowState(rowId);

    const cmds = ['bold', 'italic', 'underline', 'insertUnorderedList', 'insertOrderedList'];

    cmds.forEach(cmd => {
        const actualState = document.queryCommandState(cmd);
        if (cmd.includes('List')) {
            toolbarState[rowId][cmd] = actualState;
            return;
        }

        if (actualState === manualToggles[rowId][cmd]) {
            toolbarState[rowId][cmd] = actualState;
        } else if (document.activeElement === editor) {
            toolbarState[rowId][cmd] = actualState;
            manualToggles[rowId][cmd] = actualState;
        }
    });

    syncButtonUI(parentRow.querySelector('.toolbar'), rowId);
};
window.applyToolbarState = function (editor) {
    const parentRow = editor.closest('[id^="row_"]');
    const rowId = parentRow.id;
    const toolbar = parentRow.querySelector('.toolbar');

    if (!toolbarState[rowId]) {
        toolbarState[rowId] = {
            bold: false, italic: false, underline: false,
            insertUnorderedList: false, insertOrderedList: false
        };
    }
    setTimeout(() => {
        Object.keys(toolbarState[rowId]).forEach(cmd => {
            const isCurrentlyActive = document.queryCommandState(cmd);
            const shouldBeActive = toolbarState[rowId][cmd];

            if (shouldBeActive !== isCurrentlyActive) {
                document.execCommand(cmd, false, null);
            }
        });
        syncButtonUI(toolbar, rowId);
    }, 10);
};
window.handleEditorKeydown = function (event, editor) {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === 'z' || event.key === 'y') {
            setTimeout(() => window.updateToolbar(editor), 10);
        }
    }

    if (event.key === 'Backspace') {
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            const container = range.startContainer;
            const li = container.nodeType === 3 ? container.parentNode.closest('li') : container.closest('li');
            if (li && (li.innerText.trim() === '' || li.innerHTML === '<br>')) {
                const isUl = li.closest('ul');
                const cmd = isUl ? 'insertUnorderedList' : 'insertOrderedList';
                event.preventDefault();
                document.execCommand(cmd, false, null);
                return;
            }
        }
    }

    if (event.key === 'Enter') {
        setTimeout(() => window.updateToolbar(editor), 10);
    }
};
function syncButtonUI(toolbar, rowId) {
    const buttons = toolbar.querySelectorAll('.btn-format');
    buttons.forEach(btn => {
        const cmd = btn.getAttribute('data-cmd');
        if (!cmd || !toolbarState[rowId]) return;

        const isActive = toolbarState[rowId][cmd];
        if (isActive) {
            btn.classList.add('bg-blue-100', 'text-blue-700', 'ring-1', 'ring-blue-200');
            btn.classList.remove('text-slate-900');
        } else {
            btn.classList.remove('bg-blue-100', 'text-blue-700', 'ring-1', 'ring-blue-200');
            btn.classList.add('text-slate-900');
        }
    });
}
window.removeSectionRow = function (rowId) {
    const row = document.getElementById(rowId);
    row.classList.add('animate-out', 'fade-out', 'zoom-out-95', 'duration-200');
    setTimeout(() => {
        row.remove();
        const container = document.getElementById('dynamicFormContainer');
        if (container.children.length === 0) {
            document.getElementById('saveAllBtn').classList.add('hidden');
        }
    }, 200);
};
window.saveAllNewSections = async function () {
    const container = document.getElementById('dynamicFormContainer');
    const rows = container.querySelectorAll('[id^="row_"]');
    const saveBtn = document.getElementById('saveAllBtn');

    const sectionsData = [];
    let isValid = true;

    rows.forEach(row => {
        const titleEditor = row.querySelector('.section-title-editor');
        const contentEditor = row.querySelector('.section-content-editor');
        const titleHtml = titleEditor.innerHTML.trim();
        const contentHtml = contentEditor.innerHTML.trim();
        if (titleHtml === "" || contentHtml === "" || contentHtml === "<br>") {
            isValid = false;
            row.classList.add('border-red-400', 'bg-red-50', 'ring-1', 'ring-red-200');
        } else {
            row.classList.remove('border-red-400', 'bg-red-50', 'ring-1', 'ring-red-200');
            sectionsData.push({
                title: titleHtml,
                content: contentHtml
            });
        }
    });

    if (!isValid) {
        showToast("Please complete all sections. Highlighted rows are missing info.", "error");
        return;
    }

    if (sectionsData.length === 0) {
        showToast("No sections to save.", "info");
        return;
    }

    // --- LOADING STATE ---
    const originalBtnHtml = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Saving...`;
    if (window.lucide) lucide.createIcons();

    try {
        const response = await fetch('/../../api/govcodes/saveBulkSections.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ sections: sectionsData })
        });

        const result = await response.json();

        if (result.success) {
            showToast(`Success! Added ${sectionsData.length} entries.`, "success");
            container.innerHTML = '';
            saveBtn.classList.add('hidden');
            if (typeof loadPageSections === 'function') await loadPageSections();
        } else {
            showToast(result.error || "Failed to save", "error");
        }
    } catch (error) {
        console.error("Save error:", error);
        showToast("Connection error.", "error");
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalBtnHtml;
        if (window.lucide) lucide.createIcons();
    }
};
// --- MODAL CONTROLS ---
window.handleModalKeydown = function (event, editor) {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === 'z' || event.key === 'y') {
            setTimeout(() => window.updateModalToolbar(editor), 10);
        }
    }

    if (event.key === 'Backspace') {
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            const container = range.startContainer;
            const li = container.nodeType === 3 ? container.parentNode.closest('li') : container.closest('li');
            if (li && (li.innerText.trim() === '' || li.innerHTML === '<br>')) {
                const isUl = li.closest('ul');
                const cmd = isUl ? 'insertUnorderedList' : 'insertOrderedList';
                event.preventDefault();
                document.execCommand(cmd, false, null);
                return;
            }
        }
    }

    if (event.key === 'Enter') {
        setTimeout(() => window.updateModalToolbar(editor), 10);
    }
};
window.formatModalDoc = function (cmd, value = null) {
    const bodyEditor = document.getElementById('editSectionContent');
    const titleEditor = document.getElementById('editSectionTitle');
    let active = document.activeElement;
    if (active !== bodyEditor && active !== titleEditor) {
        bodyEditor.focus();
        active = bodyEditor;
    }
    if (active === titleEditor && (cmd === 'insertUnorderedList' || cmd === 'insertOrderedList')) {
        return;
    }
    if (toolbarEditModalState.hasOwnProperty(cmd)) {
        manualModalToggles[cmd] = !toolbarEditModalState[cmd];
        toolbarEditModalState[cmd] = manualModalToggles[cmd];
    }
    if (cmd === 'undo' || cmd === 'redo') {
        document.execCommand(cmd, false, null);
    } else if (cmd === 'insertText' || cmd === 'indent' || cmd === 'outdent') {
        document.execCommand(cmd, false, value);
    } else {
        document.execCommand(cmd, false, null);
    }
    window.updateModalToolbar(active);
};
window.updateModalToolbar = function (editor) {
    if (document.activeElement !== editor) return;

    const cmds = ['bold', 'italic', 'underline', 'insertUnorderedList', 'insertOrderedList'];

    cmds.forEach(cmd => {
        const actualState = document.queryCommandState(cmd);
        if (actualState !== toolbarEditModalState[cmd]) {
            toolbarEditModalState[cmd] = actualState;
        }
    });

    updateModalToolbarUI();
};
window.applyModalToolbarState = function (editor) {
    setTimeout(() => {
        Object.keys(toolbarEditModalState).forEach(cmd => {
            const isCurrentlyActive = document.queryCommandState(cmd);
            const shouldBeActive = toolbarEditModalState[cmd];

            if (shouldBeActive && !isCurrentlyActive) {
                document.execCommand(cmd, false, null);
            }
        });
        updateModalToolbarUI();
    }, 50);
};
function updateModalToolbarUI() {
    const modalContainer = document.getElementById('row_editModal');
    if (!modalContainer) return;

    const toolbar = modalContainer.querySelector('.toolbar');
    const buttons = toolbar.querySelectorAll('.btn-format');

    buttons.forEach(btn => {
        const cmd = btn.getAttribute('data-cmd');
        if (!cmd) return;

        const isActive = toolbarEditModalState[cmd];
        if (isActive) {
            btn.classList.add('bg-blue-100', 'text-blue-700', 'ring-1', 'ring-blue-200');
            btn.classList.remove('text-slate-900');
        } else {
            btn.classList.remove('bg-blue-100', 'text-blue-700', 'ring-1', 'ring-blue-200');
            btn.classList.add('text-slate-900');
        }
    });
}

window.editGovSection = function (key) {
    const section = rawDataStore.find(s => s.section_key === key);
    if (!section) return;
    document.getElementById('editSectionKey').value = section.section_key;
    document.getElementById('editSectionTitle').innerHTML = section.title || '';
    document.getElementById('editSectionContent').innerHTML = section.content || '';

    const contentEditor = document.getElementById('editSectionContent');
    contentEditor.innerHTML = section.content;

    Object.keys(manualModalToggles).forEach(k => {
        manualModalToggles[k] = false;
        toolbarEditModalState[k] = false;
    });

    const modal = document.getElementById('editSectionModal');
    modal.style.display = 'flex';

    setTimeout(() => {
        modal.classList.add('show');
        updateModalToolbarUI();
    }, 10);
};
document.getElementById('editSectionForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const titleEl = document.getElementById('editSectionTitle');
    const contentEl = document.getElementById('editSectionContent');
    const keyEl = document.getElementById('editSectionKey');

    const data = {
        section_key: keyEl.value,
        title: titleEl.innerHTML.trim(),
        content: contentEl.innerHTML.trim()
    };
    const titleText = titleEl.innerText.trim();
    const contentText = contentEl.innerText.trim();

    if (!titleText || !contentText) {
        showToast("Title and Content are required", "error");
        return;
    }

    try {
        const response = await fetch('/../../api/govcodes/updateSection.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        // Handle non-json responses safely
        const text = await response.text();
        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            throw new Error("Invalid Server Response");
        }

        if (result.success) {
            showToast("Section updated successfully", "success");
            closeEditSectionModal();
            if (typeof loadPageSections === "function") loadPageSections();
        } else {
            showToast(result.message || "Update failed", "error");
        }
    } catch (err) {
        console.error("Update failed", err);
        showToast("Update failed: " + err.message, "error");
    }
});
window.closeEditSectionModal = () => {
    const modal = document.getElementById('editSectionModal');
    modal.classList.remove('show');
    setTimeout(() => { if (!modal.classList.contains('show')) modal.style.display = 'none'; }, 300);
};
window.deleteGovSection = function (key, title) {
    sectionKeyToDelete = key;
    document.getElementById('deleteSectionLabel').textContent = title;

    const modal = document.getElementById('deleteSectionModal');
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
};
window.closeDeleteSectionModal = () => {
    const modal = document.getElementById('deleteSectionModal');
    modal.classList.remove('show');
    setTimeout(() => { if (!modal.classList.contains('show')) modal.style.display = 'none'; }, 300);
    sectionKeyToDelete = null;
};
document.getElementById('btnConfirmDelete').addEventListener('click', async () => {
    if (!sectionKeyToDelete) return;
    try {
        const response = await fetch('/../../api/govcodes/deleteSection.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ section_key: sectionKeyToDelete })
        });

        const result = await response.json();
        if (result.success) {
            showToast("Section removed", "success");
            closeDeleteSectionModal();
            loadPageSections();
        }
    } catch (err) {
        console.error("Delete failed", err);
    }
});

