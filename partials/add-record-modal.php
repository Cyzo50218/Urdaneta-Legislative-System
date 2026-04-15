<div class="record-modal-backdrop" id="addRecordModal">
    <div class="record-modal-dialog add-record-modal-dialog">

        <div class="record-modal-header">
            <div>
                <div class="record-modal-label">Legislative Record</div>
                <h2 class="record-modal-title" id="addRecordModalTitle">Add New Record</h2>
            </div>
            <div class="record-modal-right">
                <button
                    type="button"
                    class="record-modal-close"
                    onclick="closeAddRecordModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
        </div>

        <form id="addRecordForm">
            <div class="record-modal-body">
                <div class="form-grid">
                    <!-- Tracking No -->
                    <div class="form-group">
                        <label class="form-label">Tracking No.</label>
                        <input
                            type="text"
                            name="trackingNo"
                            class="form-input-add-dialog form-input-readonly"
                            placeholder="e.g., RES-2025-013"
                            readonly
                            required>
                        <div class="form-help">Tracking numbers are permanent and cannot be modified.</div>
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input
                            type="text"
                            name="title"
                            class="form-input-add-dialog"
                            required>
                    </div>

                    <!-- Type -->
                    <div class="form-group">
                        <label class="form-label">Record Type</label>
                        <select name="type" id="recordType" class="form-input-add-dialog">
                            <option value="ordinance">Ordinance</option>
                            <option value="resolution">Resolution</option>
                        </select>
                    </div>

                    <!-- Author -->
                    <div class="form-group">
                        <label class="form-label">Principal Author</label>
                        <input
                            type="text"
                            name="author"
                            class="form-input-add-dialog"
                            placeholder="Hon. "
                            value="Hon. ">
                    </div>

                    <!-- Deadline -->
                    <div class="form-group">
                        <label class="form-label">Deadline / Hearing Date</label>
                        <input
                            type="text"
                            name="deadline"
                            class="form-input-add-dialog"
                            placeholder="Dec 31, 2025">
                    </div>

                    <!-- Section / Status -->
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="section" class="form-input-add-dialog">
                            <option value="assigned">Assigned</option>
                            <option value="in-progress">In-Progress</option>
                            <option value="for-review">For Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="archived">Archived</option>
                        </select>
                        <div class="form-help">
                            Current workflow stage for this record.
                        </div>
                    </div>

                    <div id="injectedFieldsContainer" style="grid-column: 1 / -1; display: contents;">
                    </div>

                    <div class="form-group" style="grid-column:1 / -1;">
                        <hr style="border:none;border-top:1px solid #e5e7eb;margin:12px 0 8px;">
                        <label class="form-label">Add more fields</label>

                        <div id="customHeadersListBasicFields" style="display: flex; flex-direction: column; gap: 10px;">
                        </div>

                        <div id="headerControls" style="margin-top: 10px; display: flex; gap: 8px; align-items: center;">
                            <input type="text" id="newHeaderName" class="form-input-add-dialog" style="flex: 1;" placeholder="Enter Header Name (e.g. Subject Matter, Co-Authors, etc.)">

                            <button type="button" class="record-btn-primary" style="padding: 8px 16px; font-size: 12px;"
                                onclick="saveNewHeader('newHeaderName', 'customHeadersListBasicFields')">
                                Save Header
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="grid-column:1 / -1;">
                    <hr style="border:none;border-top:1px solid #e5e7eb;margin:12px 0 8px;">
                    <div style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;">
                        Legislation Details
                    </div>
                </div>


                <!-- Session Type -->
                <div class="form-group">
                    <label class="form-label">Session Type</label>
                    <input
                        type="text"
                        name="sessionType"
                        class="form-input-add-dialog"
                        placeholder="e.g., Regular Session">
                </div>

                <!-- Year Taken Up -->
                <div class="form-group">
                    <label class="form-label">Year Taken Up</label>
                    <input
                        type="text"
                        name="yearTaken"
                        class="form-input-add-dialog"
                        placeholder="2025">
                </div>

                <div class="form-group">
                    <label class="form-label">Summary</label>
                    <input
                        type="text"
                        name="summary"
                        class="form-input-add-dialog"
                        placeholder="Brief summary of the legislation...">
                </div>

                <div class="form-group">
                    <label class="form-label">Agenda / Item No.</label>

                    <div id="agendaList">
                    </div>

                </div>

                <div class="form-group" style="grid-column:1 / -1;">
                    <label class="form-label">Committee Handling</label>

                    <div id="committeeList">
                        <div class="dynamic-item">
                            <input type="text" name="committee[]" class="form-input-add-dialog" placeholder="Committee...">
                        </div>
                    </div>

                    <button type="button" class="btn-add" onclick="addDynamicField('committeeList', 'committee[]')">
                        <i data-lucide="plus"></i> Add Committee
                    </button>
                </div>


                <div class="form-group" style="grid-column:1 / -1;">
                    <hr style="border:none;border-top:1px solid #e5e7eb;margin:12px 0 8px;">
                    <div style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;">
                        Legislation Attachments
                    </div>
                </div>

                <!-- Hyperlink -->
                <div class="form-group" style="grid-column:1 / -1;">
                    <label class="form-label">Hyperlink</label>
                    <input
                        type="text"
                        name="hyperlink"
                        class="form-input-add-dialog"
                        placeholder="/uploads/resolutions/RES-2025-010.pdf">
                </div>

                <!-- Attachments -->
                <div class="form-group" style="grid-column:1 / -1;">
                    <label class="form-label">Attachments</label>
                    <input
                        type="file"
                        id="addRecordFiles"
                        class="form-input-add-dialog"
                        multiple
                        accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                    <div id="addRecordFilePreview" class="attachment-preview-grid"></div>
                    <div class="form-help">
                        Images will show a thumbnail preview. Other files will be listed by filename.
                    </div>
                </div>


                <div id="addRecordError"
                    style="margin-top:8px; font-size:11px; color:#b91c1c; display:none;">
                </div>
            </div>

            <div class="record-modal-footer" style="display:flex; justify-content:flex-end; gap:8px;">

                <button
                    type="button"
                    class="hyperlink-btn ghost"
                    onclick="closeAddRecordModal()">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="record-btn-primary">
                    Save Record
                </button>
            </div>
        </form>
    </div>
</div>