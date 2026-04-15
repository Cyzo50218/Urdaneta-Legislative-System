<div id="localgovcode" class="hash-section">
    <div class="content-main">
        <div class="section-card pl-4 pr-4 mb-6">
            <div class="mt-3 mb-3 mr-3">
                <div id="magazine">
                    <div class="cover-page" style="background:#2e3f5a; color:white; display:flex; align-items:center; justify-content:center; font-size:30px; font-weight:bold;">
                        <div style="padding: 40px;">LOCAL GOVERNMENT CODE<br>OF THE PHILIPPINES</div>
                    </div>
                    <div class="back-cover hard" style="background:#2e3f5a; color:white; display:flex; align-items:center; justify-content:center; font-size:30px; font-weight:bold;">
                        <div style="padding: 40px;">END OF DOCUMENT</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card p-6">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">Code Management</h3>
                    <p class="text-sm text-slate-500">Add or manage chapters of the Local Government Code</p>
                </div>
                <div class="flex gap-3">
                    <button id="saveAllBtn" onclick="saveAllNewSections()" class="record-btn-primary hidden flex items-center gap-2" style="background: #10b981;">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Save All to E-Library
                    </button>

                    <button onclick="addNewSectionRow()" class="record-btn-primary flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        Add New Section
                    </button>
                </div>
            </div>

            <div id="dynamicFormContainer" class="space-y-4 mb-6"></div>

            <div id="addSectionForm" class="bg-slate-50 p-6 rounded-xl border border-dashed border-slate-300 mb-6 hidden">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Section/Chapter Title</label>
                        <input type="text" id="newSectionTitle" placeholder="e.g., Chapter 1: General Provisions"
                            class="w-full p-2 border rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Content Body</label>
                        <textarea id="newSectionContent" rows="6" placeholder="Paste the legal text here..."
                            class="w-full p-3 border rounded-md font-serif"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button onclick="toggleAddSectionForm()" class="record-btn-secondary">Cancel</button>
                        <button onclick="saveNewGovSection()" class="record-btn-primary">Save to E-Library</button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600">
                            <th class="p-3 border-b">Section Title</th>
                            <th class="p-3 border-b text-center">Status</th>
                            <th class="p-3 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sectionsTableBody">
                        <tr>
                            <td colspan="3" class="p-4 text-center text-slate-400">Loading current sections...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>