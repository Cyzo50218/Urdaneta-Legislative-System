<div class="record-modal-backdrop" id="deleteTaskRecordModal" style="display:none; pointer-events: auto;">
    <div class="record-modal-dialog small-modal" onclick="event.stopPropagation()">
        <div class="record-modal-header">
            <div>
                <div class="record-modal-label">Legislative Record</div>
                <h2 class="record-modal-title">Delete Task</h2>
            </div>
            <div class="record-modal-right">
                <button
                    type="button"
                    class="record-modal-close"
                    onclick="window.TaskManager.closeDeleteModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
        </div>

        <div class="record-modal-body">
            <p style="font-size:13px; color:#374151; margin-bottom:8px;">
                Are you sure you want to delete this record?
            </p>
            <p style="font-size:12px; color:#6b7280; margin-bottom:12px;">
                <strong id="deleteRecordLabel"></strong>
            </p>
            <p style="font-size:11px; color:#9ca3af;">
                This action will remove the record from the current view. Make sure this
                record is not needed before proceeding.
            </p>
        </div>

        <div class="record-modal-footer" style="display:flex; justify-content:flex-end; gap:8px;">
            <button
                type="button"
                class="hyperlink-btn ghost"
                onclick="window.TaskManager.closeDeleteModal()"> Cancel
            </button>
            <button
                type="button"
                class="record-btn-danger"
                onclick="window.TaskManager.confirmDeleteTask()"> Delete
            </button>
        </div>
    </div>
</div>