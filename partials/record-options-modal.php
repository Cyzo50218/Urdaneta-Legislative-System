<div class="record-options-backdrop" id="recordOptionsModal">
  <div class="record-options-dialog">
    <div class="record-options-header">
      <div>
        <div class="record-options-title">Record View & Print Settings</div>
        <div class="record-options-subtitle">
          Configure how this legislative record is displayed and printed.
        </div>
      </div>
      <button class="record-options-close" id="recordOptionsCloseBtn" type="button">
        <i data-lucide="x"></i>
      </button>
    </div>

    <div class="record-options-body">
      <div class="record-options-field">
        <label for="recordPrintLayoutSelect" class="record-options-label">
          Print layout
        </label>
        <select id="recordPrintLayoutSelect" class="record-options-select">
          <option value="full">Full (detailed record)</option>
          <option value="summary">Summary</option>
          <option value="card">Card-style</option>
        </select>
        <p class="record-options-help">
          Controls how this record will look in print previews and PDF exports.
        </p>
      </div>

      <div class="record-options-field">
        <label for="recordLegisLayoutSelect" class="record-options-label">
          Legislation layout
        </label>
        <select id="recordLegisLayoutSelect" class="record-options-select">
          <option value="detailed">Detailed grid (all fields)</option>
          <option value="compact">Compact layout</option>
        </select>
        <p class="record-options-help">
          Switch between a full legislation grid or a tighter compact layout.
        </p>
      </div>

      <div class="record-options-field record-options-inline">
        <label class="record-options-label">
          <input type="checkbox" id="recordLegisSubtitleToggle">
          <span>Show legislation section subtitle</span>
        </label>
        <p class="record-options-help">
          Hide the explanatory subtitle if you want a cleaner, tighter view.
        </p>
      </div>
    </div>

    <div class="record-options-footer">
      <button type="button" class="record-options-btn ghost" id="recordOptionsCancelBtn">
        Cancel
      </button>
      <button type="button" class="record-options-btn primary" id="recordOptionsSaveBtn">
        Save
      </button>
    </div>
  </div>
</div>
