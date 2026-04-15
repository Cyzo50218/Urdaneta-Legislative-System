<div class="record-modal-backdrop" id="editSectionModal" style="display:none; align-items: center; justify-content: center; padding: 20px;">
    <div class="record-modal-dialog add-record-modal-dialog" id="row_editModal"
        style="width: 95%; max-width: 900px; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; margin-bottom: 20px;">

        <div class="record-modal-header" style="flex-shrink: 0;">
            <div>
                <div class="record-modal-label">E-Library Management</div>
                <h2 class="record-modal-title">Edit Code Section</h2>
            </div>
            <div class="record-modal-right">
                <button type="button" class="record-modal-close" onclick="closeEditSectionModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
        </div>

        <form id="editSectionForm" style="display: flex; flex-direction: column; flex-grow: 1; overflow: hidden;">
            <input type="hidden" id="editSectionKey">

            <div class="flex items-center justify-between bg-slate-50 border-b border-slate-300 px-4 py-2" style="flex-shrink: 0;">
                <div class="toolbar flex items-center gap-0.5">
                    <button type="button" onmousedown="event.preventDefault()" onclick="formatModalDoc('undo')" class="p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Undo (Ctrl+Z)">
                        <i data-lucide="undo-2" class="w-4 h-4"></i>
                    </button>
                    <button type="button" onmousedown="event.preventDefault()" onclick="formatModalDoc('redo')" class="p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Redo (Ctrl+Y)">
                        <i data-lucide="redo-2" class="w-4 h-4"></i>
                    </button>
                    <div class="w-px h-4 bg-slate-300 mx-1.5"></div>
                    <button type="button" data-cmd="bold" onmousedown="event.preventDefault()" onclick="formatModalDoc('bold', event)" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Bold"><i data-lucide="bold" class="w-4 h-4"></i></button>
                    <button type="button" data-cmd="italic" onmousedown="event.preventDefault()" onclick="formatModalDoc('italic', event)" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Italic"><i data-lucide="italic" class="w-4 h-4"></i></button>
                    <button type="button" data-cmd="underline" onmousedown="event.preventDefault()" onclick="formatModalDoc('underline', event)" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Underline"><i data-lucide="underline" class="w-4 h-4"></i></button>
                    <div class="w-px h-4 bg-slate-300 mx-1.5"></div>
                    <button type="button" data-cmd="insertUnorderedList" onmousedown="event.preventDefault()" onclick="formatModalDoc('insertUnorderedList')" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Bullet List"><i data-lucide="list" class="w-4 h-4"></i></button>
                    <button type="button" data-cmd="insertOrderedList" onmousedown="event.preventDefault()" onclick="formatModalDoc('insertOrderedList')" class="btn-format p-1.5 rounded hover:bg-slate-200 text-slate-900 transition-colors" title="Numbered List"><i data-lucide="list-ordered" class="w-4 h-4"></i></button>
                </div>
            </div>

            <div class="record-modal-body bg-white" style="flex-grow: 1; overflow-y: auto; padding: 30px 40px;">

                <div id="editSectionTitle"
                    contenteditable="true"
                    class="w-full outline-none font-bold text-3xl text-slate-900 placeholder-slate-300"
                    data-placeholder="Section Title..."
                    style="padding: 0; min-height: 1.2em;"></div>

                <hr class="border-slate-200 my-6">

                <div id="editSectionContent"
                    contenteditable="true"
                    onfocus="applyModalToolbarState(this)"
                    onkeyup="updateModalToolbar(this)"
                    onkeydown="handleModalKeydown(event, this)"
                    onmouseup="updateModalToolbar(this)"
                    class="section-content-editor w-full outline-none text-slate-700 font-serif text-lg leading-relaxed bg-white"
                    style="min-height: 400px; padding: 0;"
                    data-placeholder="Start writing the legal text here..."></div>

            </div>

            <div class="record-modal-footer" style="flex-shrink: 0; display:flex; justify-content:flex-end; gap:8px; background: #f8fafc; padding: 16px 16px 32px 16px; border-top: 1px solid #e2e8f0;">
                <button type="button" class="hyperlink-btn ghost" onclick="closeEditSectionModal()">Cancel</button>
                <button type="submit" class="record-btn-primary">Update Section</button>
            </div>
        </form>
    </div>
</div>

<div class="record-modal-backdrop" id="deleteSectionModal" style="display:none; pointer-events: auto;">
    <div class="record-modal-dialog small-modal">
        <div class="record-modal-header">
            <div>
                <div class="record-modal-label">E-Library Management</div>
                <h2 class="record-modal-title">Remove Section</h2>
            </div>
            <div class="record-modal-right">
                <button type="button" class="record-modal-close" onclick="closeDeleteSectionModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
        </div>

        <div class="record-modal-body">
            <p style="font-size:13px; color:#374151; margin-bottom:8px;">Are you sure you want to delete this section?</p>
            <p style="font-size:12px; color:#6b7280; margin-bottom:12px;">
                <strong id="deleteSectionLabel" class="text-red-600"></strong>
            </p>
            <p style="font-size:11px; color:#9ca3af;">This will permanently remove the content from the digital Local Government Code book.</p>
        </div>

        <div class="record-modal-footer" style="display:flex; justify-content:flex-end; gap:8px;">
            <button type="button" class="hyperlink-btn ghost" onclick="closeDeleteSectionModal()">Cancel</button>
            <button type="button" class="record-btn-danger" id="btnConfirmDelete">Delete Permanently</button>
        </div>
    </div>
</div>