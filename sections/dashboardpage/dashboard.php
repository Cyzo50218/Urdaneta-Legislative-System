<section>
    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card card-blue card-resolutions">
            <div class="card-icon">
                <svg class="icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-number">0</div>
                <div class="card-label" id="resolution-label">Assigned Resolutions</div>
            </div>
        </div>

        <div class="summary-card card-green card-ordinances">
            <div class="card-icon">
                <svg class="icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-number">0</div>
                <div class="card-label" id="ordinance-label">Assigned Ordinances</div>
            </div>
        </div>

        <div class="summary-card card-gold card-tasks">
            <div class="card-icon">
                <svg class="icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-number">0</div>
                <div class="card-label">Tasks for Today</div>
            </div>
        </div>

        <div class="summary-card card-purple card-hearings">
            <div class="card-icon">
                <svg class="icon-lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="card-content">
                <div class="card-number">0</div>
                <div class="card-label">Upcoming Sessions / Hearings</div>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="content-grid">
        <!-- Left Column - Table -->
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
                        <button class="btn-ai-draft" type="button" onclick="openAIModal()">
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

            <!-- Recent Activity Timeline -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Recent Activity</h3>
                </div>

                <div class="timeline">
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Tasks and Calendar -->
        <div class="content-sidebar">
            <!-- Task List -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Today's Tasks</h3>
                </div>

                <div class="task-list">
                </div>
            </div>

            <!-- Mini Calendar -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Legislative Calendar</h3>
                </div>

                <div class="mini-calendar">
                    <div class="calendar-header">
                        <button class="calendar-nav" id="calendarPrevBtn" type="button">
                            <i data-lucide="chevron-left" class="calendar-nav-icon"></i>
                        </button>

                        <div class="calendar-month" id="calendarMonthLabel">
                            <!-- Filled by JS -->
                        </div>

                        <button class="calendar-nav" id="calendarNextBtn" type="button">
                            <i data-lucide="chevron-right" class="calendar-nav-icon"></i>
                        </button>
                    </div>

                    <div class="calendar-weekdays">
                        <div class="weekday">Su</div>
                        <div class="weekday">Mo</div>
                        <div class="weekday">Tu</div>
                        <div class="weekday">We</div>
                        <div class="weekday">Th</div>
                        <div class="weekday">Fr</div>
                        <div class="weekday">Sa</div>
                    </div>

                    <div class="calendar-days" id="calendarDays">
                        <!-- Days will be rendered by JS -->
                    </div>
                </div>


                <div class="calendar-events" id="calendarEventsList"></div>

            </div>
        </div>
    </div>
</section>