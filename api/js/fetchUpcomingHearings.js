async function loadMyUpcomingHearings() {
    try {
        const response = await fetch('/api/schedules/fetchUpcomingHearings.php', {
            method: 'GET',
            credentials: 'include'
        });
        const data = await response.json();

        if (data.success) {
            const totalAssigned = data.data;
            document.querySelector('.card-hearings .card-number').textContent = totalAssigned;
            document.querySelector('.card-hearings .card-label').textContent = 'Upcoming Sessions / Hearings';
        } else {
            document.querySelector('.card-hearings .card-number').textContent = 0;
        }
    } catch (err) {
        console.error('Error fetching assigned tasks:', err);
        fetch('/api/helpers/logConsoleError.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                error: err.message || err,
                script: 'fetchUpcomingHearings.js'
            }),
            credentials: 'include'
        }).catch(e => console.error('Failed to log error to server:', e));
    }
}