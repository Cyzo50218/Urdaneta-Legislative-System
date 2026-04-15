function updateDashboardLabels() {
    const resLabel = document.getElementById('resolution-label');
    const ordLabel = document.getElementById('ordinance-label');
    const nameHeader = document.getElementById('assigned-name-header');

    if (!window.USER_SESSION) return;

    const userRole = window.USER_SESSION.role.toLowerCase();

    if (userRole === 'staff') {
        // Staff View
        if (resLabel) resLabel.textContent = 'Assigned Resolutions';
        if (ordLabel) ordLabel.textContent = 'Assigned Ordinances';
        if (nameHeader) nameHeader.style.display = 'none'; // Hide for staff
    } else {
        // Councilor / Mayor View
        if (resLabel) resLabel.textContent = 'Total Assigned Resolutions for Staff';
        if (ordLabel) ordLabel.textContent = 'Total Assigned Ordinances for Staff';
        if (nameHeader) nameHeader.style.display = 'table-cell'; // Show for non-staff
    }
}