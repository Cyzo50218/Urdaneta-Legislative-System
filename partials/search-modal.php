<div class="record-modal-backdrop" id="searchRecordModal" style="display:none;">
    <div class="record-modal-dialog small-modal">
        <div class="record-modal-header">
            <div>
                <div class="record-modal-label">Legislative Record</div>
                <h2 class="record-modal-title">Search Records</h2>
            </div>
            <div class="record-modal-right">
                <button
                    type="button"
                    class="record-modal-close"
                    onclick="closeSearchRecordModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
        </div>

        <form id="searchRecordForm">
            <div class="record-modal-body">
                <div class="form-group">
                    <label class="form-label">Search term</label>
                    <input
                        type="text"
                        id="searchRecordInput"
                        name="searchTerm"
                        class="form-input-search"
                        placeholder="Tracking No., title, or author">
                    <div class="form-help">
                        Example: <code>RES-2025-010</code> or <code>Approving Budget</code> or <code>Maria Santos</code>
                    </div>
                </div>

                <div id="searchRecordError"
                    style="margin-top:8px; font-size:11px; color:#b91c1c; display:none;">
                </div>

                <!-- Search results -->
                <div id="searchRecordResults"
                    class="search-results-list"
                    style="margin-top:10px; max-height:260px; overflow-y:auto; border-top:1px solid #e5e7eb; padding-top:8px; display:none;">
                    <!-- filled by JS -->
                </div>
            </div>

            <div class="record-modal-footer" style="display:flex; justify-content:flex-end; gap:8px;">
                <button
                    type="button"
                    class="hyperlink-btn ghost"
                    onclick="closeSearchRecordModal()">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="record-btn-primary">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>