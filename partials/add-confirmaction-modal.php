
<div class="record-modal-backdrop" id="confirmActionModal" style="display:flex;">
    <div class="record-modal-dialog" style="max-width: 400px; margin-top: 15vh;">
        <div class="record-modal-header">
            <h2 class="record-modal-title">Finalize Action</h2>
        </div>
        <div class="record-modal-body">
            <div class="form-group">
                <label class="form-label">Save Edit Action as?</label>
                <input type="text" id="lastActionInput" class="form-input-add-dialog" placeholder="e.g., First Reading, Committee Referral...">
                <div class="form-help">This will be recorded as the latest activity for this legislation.</div>
            </div>
        </div>
        <div class="record-modal-footer" style="display:flex; justify-content:flex-end; gap:8px;">
            <button type="button" class="hyperlink-btn ghost" onclick="closeConfirmModal()">Cancel</button>
            <button type="button" class="record-btn-primary" id="finalSubmitBtn">Confirm & Save</button>
        </div>
    </div>
</div>