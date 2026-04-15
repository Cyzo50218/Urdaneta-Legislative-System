
function initDashboardPage() {

    initUsedAssigned();
    startRealTimeUpdates();
    calendarState.currentDate = new Date();

    initMiniCalendarNav();
    renderMiniCalendar();

    loadDashboardData();
    loadTaskList();
    fetchMyAssignedOrdinance();
    const searchInputAssigned = document.getElementById("assignedSearch");
    const dateFromInputAssigned = document.getElementById("assignedDateFrom");
    const dateToInputAssigned = document.getElementById("assignedDateTo");
    if (searchInputAssigned) {
        searchInputAssigned.addEventListener("input", applyAssignedFilters);
    }
    if (dateFromInputAssigned) {
        dateFromInputAssigned.addEventListener("change", applyAssignedFilters);
    }
    if (dateToInputAssigned) {
        dateToInputAssigned.addEventListener("change", applyAssignedFilters);
    }
    filteredAssignedItems = [...assignedItems];
    renderAssignedTable(filteredAssignedItems);

}


function initAssignedPage() {

    initUsedAssigned();
    startRealTimeUpdates();
    loadDashboardData();
    fetchMyAssignedOrdinance();
    const searchInputAssigned = document.getElementById("assignedSearch");
    const dateFromInputAssigned = document.getElementById("assignedDateFrom");
    const dateToInputAssigned = document.getElementById("assignedDateTo");
    if (searchInputAssigned) {
        searchInputAssigned.addEventListener("input", applyAssignedFilters);
    }
    if (dateFromInputAssigned) {
        dateFromInputAssigned.addEventListener("change", applyAssignedFilters);
    }
    if (dateToInputAssigned) {
        dateToInputAssigned.addEventListener("change", applyAssignedFilters);
    }
    filteredAssignedItems = [...assignedItems];
    renderAssignedTable(filteredAssignedItems);

}
