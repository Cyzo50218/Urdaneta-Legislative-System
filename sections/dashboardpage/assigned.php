<?php
?>

<div class=" assigned-layout">
    <div class="content-main">
        <div class="section-card">
            <div class="section-header section-header-with-action">
                <div>
                    <h3>My Assigned Resolutions &amp; Ordinances</h3>
                    <p class="section-subtitle">
                        All legislative items currently routed to you. Use the search and date filters
                        to quickly narrow down what you need to work on today.
                    </p>
                </div>

                <div class="header-actions">
                    <!-- AI Draft Button -->
                    <button class="btn-ai-draft" type="button" onclick="generateAIDraft()">
                        <i data-lucide="sparkles" class="icon-sm"></i>
                        Generate Draft AI
                    </button>

                    <!-- Create Draft Button -->
                    <button class="btn-upload-draft" type="button" onclick="addRecord()">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon-sm"
                            style="margin-right: 8px; vertical-align: middle;">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Create a Draft
                    </button>

                    <br>

                    <!-- Download -->
                    <button class="btn-download-all" type="button" onclick="downloadAllPDF()">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Download All PDF
                    </button>
                </div>
            </div>


            <div class="table-section">
                <div class="table-toolbar">
                    <!-- Search -->
                    <div class="toolbar-group">
                        <label class="toolbar-label" for="assignedSearch">Search Name / Title</label>
                        <div class="toolbar-input-wrapper">
                            <span class="toolbar-icon">
                                <i data-lucide="search" class="icon-sm"></i>
                            </span>

                            <input type="text" id="assignedSearch" class="toolbar-input"
                                placeholder="Type author or title..." />
                        </div>
                    </div>

                    <!-- Date filter -->
                    <div class="toolbar-group">
                        <label class="toolbar-label">Filter by Deadline / Hearing Date</label>
                        <div class="date-filter">
                            <input type="date" id="assignedDateFrom" class="toolbar-input date-input" />
                            <span class="date-separator">to</span>
                            <input type="date" id="assignedDateTo" class="toolbar-input date-input" />
                        </div>
                    </div>

                    <!-- Row count -->
                    <div class="toolbar-group toolbar-group-right">
                        <span class="row-count-badge">
                            <span id="meAssignedRowCount">0 of 0</span>
                            <span id="meAssignedTotalCount" class="row-total">Total: 0</span>
                            <span id="meAssignedRowPercent" class="row-percent">(0%)</span>
                        </span>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="data-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th>Code No.</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Author / Proponents</th>
                                <th>Current Status</th>
                                <th id="assigned-name-header" style="display: none;">Assigned Name</th>
                                <th>Deadline / Hearing Date</th>

                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>