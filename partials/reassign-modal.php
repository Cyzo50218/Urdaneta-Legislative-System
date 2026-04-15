<div class="record-modal-backdrop" id="reassignModal">
    <div class="record-modal-dialog" style="max-width: 400px;">
        <div class="record-modal-header">
            <div>
                <div class="record-modal-label">Management</div>
                <h2 class="record-modal-title">Reassign Document</h2>
            </div>
            <button type="button" class="record-modal-close" onclick="closeReassignModal()">
                <i data-lucide="x"></i>
            </button>
        </div>

        <form id="reassignForm">
            <div class="record-modal-body">
                <input type="hidden" name="trackingNo" id="reassignTrackingNo">
                
                <div class="form-group">
                    <label class="form-label">Select Staff Member</label>
                    <select name="userId" id="staffSelect" class="form-input-add-dialog" required>
                        <option value="">Loading staff...</option>
                    </select>
                    <div class="form-help">Select the staff member who will be responsible for this document.</div>
                </div>
            </div>

            <div class="record-modal-footer" style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="hyperlink-btn ghost" onclick="closeReassignModal()">Cancel</button>
                <button type="submit" class="record-btn-primary">Confirm Assignment</button>
            </div>
        </form>
    </div>
</div>