<div id="adminTaskSystem" class="bg-[#f8fafc] p-3 m-0 border border-gray-200 rounded-lg font-sans">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
        <div class="flex items-center gap-2 w-full md:w-auto">
            <div class="relative flex-1 md:flex-none">
                <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-[11px] text-gray-400"></i>
                <input type="text"
                    id="taskSearchInput"
                    placeholder="Search..."
                    class="w-full md:w-48 pl-9 pr-3 py-[10px] border border-gray-300 rounded-[6px] text-xs focus:ring-1 focus:ring-emerald-500 outline-none bg-white shadow-sm transition-all">
            </div>

            <button class="btn-new-task shrink-0" onclick="TaskManager.addNewTask()">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span class="hidden sm:inline">New Task</span>
                <span class="sm:hidden">New</span>
            </button>
        </div>
    </div>

    <div class="relative flex items-center gap-1 border-b border-gray-200 mb-6 overflow-x-auto whitespace-nowrap scrollbar-hide" id="filterTabContainer">
        <div id="tab-indicator" class="absolute bottom-0 h-0.5 bg-emerald-700 transition-all duration-300 ease-in-out"></div>

        <button class="filter-tab px-4 py-2 text-xs font-bold text-emerald-700 flex items-center gap-2" data-filter="All Tasks">
            All Tasks <span class="count-badge text-[10px] px-1.5 py-0.5 bg-emerald-100 rounded-full font-mono">(0)</span>
        </button>
        <button class="filter-tab px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition flex items-center gap-2" data-filter="Pending">
            Pending <span class="count-badge text-[10px] px-1.5 py-0.5 bg-gray-100 rounded-full font-mono text-gray-400">(0)</span>
        </button>
        <button class="filter-tab px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition flex items-center gap-2" data-filter="In Progress">
            In Progress <span class="count-badge text-[10px] px-1.5 py-0.5 bg-gray-100 rounded-full font-mono text-gray-400">(0)</span>
        </button>
        <button class="filter-tab px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition flex items-center gap-2" data-filter="Completed">
            Completed <span class="count-badge text-[10px] px-1.5 py-0.5 bg-gray-100 rounded-full font-mono text-gray-400">(0)</span>
        </button>
        <button class="filter-tab px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition flex items-center gap-2 text-red-600" data-filter="Blocked">
            Blocked <span class="count-badge text-[10px] px-1.5 py-0.5 bg-red-50 rounded-full font-mono text-red-400">(0)</span>
        </button>
    </div>

    <div id="taskListContainer" class="space-y-2">
        <div class="flex flex-col items-center justify-center py-12 px-4 border-2 border-dashed border-gray-200 rounded-lg bg-white">
            <div class="bg-gray-50 p-4 rounded-full mb-4">
                <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-300"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-900">No Task Created</h3>
            <p class="text-xs text-gray-500 mt-1 text-center">There are currently no tasks assigned to your queue.</p>
        </div>
    </div>

    <div id="taskListFooter"
        class="mt-8 border-t border-gray-200 pt-4 flex justify-between items-center text-[11px] text-gray-400 font-bold uppercase tracking-widest">

        <div class="flex items-center gap-4">
            <span id="taskPaginationText">Showing 0 of 0</span>
        </div>

    </div>

</div>