async function loadDashboardData() {
    try {
        const response = await fetch('/api/fetchDashboardCount.php', {
            method: 'GET',
            credentials: 'include'
        });
        const data = await response.json();

        if (data.success) {
            document.querySelector('.card-resolutions .card-number').textContent = data.data.assigned_resolutions;
            document.querySelector('.card-ordinances .card-number').textContent = data.data.assigned_ordinances;
            document.querySelector('.card-tasks .card-number').textContent = data.data.tasks_pending;
            document.querySelector('.card-hearings .card-number').textContent = data.data.upcoming_hearings;
        } else {
        }
    } catch (err) {
    }
}
