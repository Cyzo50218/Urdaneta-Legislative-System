<div class="record-modal-backdrop" id="editEventModal">
    <div class="record-modal-dialog add-record-modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="record-modal-header">
            <div>
                <div class="record-modal-label">Calendar Management</div>
                <h2 class="record-modal-title">Edit Event Details</h2>
            </div>
            <div class="record-modal-right">
                <button type="button" class="record-modal-close" onclick="closeEditModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
        </div>

        <form id="editEventForm">
            <input type="hidden" name="event_id" id="editEventId">
            <div class="record-modal-body">
                <div class="form-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" id="editEventTitle" class="form-input-add-dialog" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Event Type</label>
                        <select name="type" id="editEventType" class="form-input-add-dialog">
                            <option value="session">Plenary Session</option>
                            <option value="committee">Committee Hearing</option>
                            <option value="public">Public Consultation</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" id="editEventDate" class="form-input-add-dialog" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Time</label>
                        <input type="time" name="time" id="editEventTime" class="form-input-add-dialog" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Location/Venue</label>
                        <input type="text" name="location" id="editEventLocation" class="form-input-add-dialog" placeholder="e.g. Session Hall">
                    </div>

                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" id="editEventCity" class="form-input-add-dialog" placeholder="e.g. Quezon City">
                    </div>
                </div>
            </div>

            <div class="record-modal-footer" style="display:flex; justify-content:flex-end; gap:12px; padding: 20px;">
                <button type="button" class="hyperlink-btn ghost" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="record-btn-primary" style="padding: 10px 24px;">Update Event</button>
            </div>
        </form>
    </div>
</div>

<div class="record-modal-backdrop" id="registerEventModal">
    <div class="record-modal-dialog add-record-modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="record-modal-header">
            <div>
                <div class="record-modal-label">Calendar Management</div>
                <h2 class="record-modal-title">Register New Event</h2>
            </div>
            <div class="record-modal-right">
                <button type="button" class="record-modal-close" onclick="closeRegisterModal()">
                    <i data-lucide="x"></i>
                </button>
            </div>
        </div>

        <form id="registerEventForm">
            <div class="record-modal-body">
                <div class="form-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" class="form-input-add-dialog" placeholder="e.g., First Regular Session" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Event Type</label>
                        <select name="type" class="form-input-add-dialog">
                            <option value="session">Plenary Session</option>
                            <option value="committee">Committee Hearing</option>
                            <option value="public">Public Consultation</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" id="registerEventDate" class="form-input-add-dialog" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Time</label>
                        <input type="time" name="time" class="form-input-add-dialog" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Location/Venue</label>
                        <input type="text" name="location" class="form-input-add-dialog" placeholder="e.g., Session Hall">
                    </div>

                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-input-add-dialog" placeholder="e.g., Urdaneta City">
                    </div>
                </div>
            </div>

            <div class="record-modal-footer" style="display:flex; justify-content:flex-end; gap:12px; padding: 20px;">
                <button type="button" class="hyperlink-btn ghost" onclick="closeRegisterModal()">Cancel</button>
                <button type="submit" class="record-btn-primary" style="padding: 10px 24px;">Save Event</button>
            </div>
        </form>
    </div>
</div>