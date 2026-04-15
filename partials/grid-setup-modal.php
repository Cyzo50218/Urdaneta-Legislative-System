<div class="grid-setup-backdrop" id="gridSetupModal">
  <div class="grid-setup-dialog">
    <div class="grid-setup-header">
      <div>
        <div class="grid-setup-label">LEGISLATIVE RECORD VIEW SETUP</div>
        <div class="grid-setup-subtitle">(Toggle which sections & fields to show)</div>
      </div>
      <button type="button" class="grid-setup-close" id="gridSetupCloseBtn">
        <i data-lucide="x"></i>
      </button>
    </div>

    <div class="grid-setup-body">
      <!-- Sections -->
      <div class="grid-setup-section">
        <div class="grid-setup-section-title">Sections</div>
        <div class="grid-setup-grid">
          <label class="grid-setup-toggle">
            <input type="checkbox" name="sections.basic" />
            <span>Basic</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="sections.legislation" />
            <span>Legislation</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="sections.support" />
            <span>Support</span>
          </label>
        </div>
      </div>

      <!-- Basic Fields -->
      <div class="grid-setup-section">
        <div class="grid-setup-section-title">Basic Fields</div>
        <div class="grid-setup-grid">
          <label class="grid-setup-toggle">
            <input type="checkbox" name="basic.trackingNo" />
            <span>Tracking No</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="basic.type" />
            <span>Type</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="basic.author" />
            <span>Author</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="basic.status" />
            <span>Status</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="basic.deadline" />
            <span>Deadline</span>
          </label>
        </div>
      </div>

      <!-- Legislation Fields -->
      <div class="grid-setup-section">
        <div class="grid-setup-section-title">Legislation Fields</div>
        <div class="grid-setup-grid">
          <label class="grid-setup-toggle">
            <input type="checkbox" name="legis.category" />
            <span>Category</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="legis.sessionType" />
            <span>Session Type</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="legis.yearTaken" />
            <span>Year Taken</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="legis.agendaNo" />
            <span>Agenda No</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="legis.committee" />
            <span>Committee</span>
          </label>
        </div>
      </div>

      <!-- Support Fields -->
      <div class="grid-setup-section">
        <div class="grid-setup-section-title">Support Fields</div>
        <div class="grid-setup-grid">
          <label class="grid-setup-toggle">
            <input type="checkbox" name="support.hyperlink" />
            <span>Hyperlink</span>
          </label>
          <label class="grid-setup-toggle">
            <input type="checkbox" name="support.attachments" />
            <span>Attachments</span>
          </label>
        </div>
      </div>
    </div>

    <div class="grid-setup-footer">
      <button type="button" class="grid-setup-btn ghost" id="gridSetupCancelBtn">
        Cancel
      </button>
      <button type="button" class="grid-setup-btn primary" id="gridSetupSaveBtn">
        Save changes
      </button>
    </div>
  </div>
</div>
