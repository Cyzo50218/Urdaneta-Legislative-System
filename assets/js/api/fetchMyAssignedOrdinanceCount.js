async function loadAssignedOrdinances() {
    try {
        const response = await fetch('/api/ordinances/fetchMyAssignedOrdinances.php', {
            method: 'GET',
            credentials: 'include'
        });
        const data = await response.json();

        if (data.success && data.data.length > 0) {
            const firstOrdinance = data.data[0];
            const totalAssigned = data.data.length;
            document.querySelector('.card-ordinances .card-number').textContent = totalAssigned;
            document.querySelector('.card-ordinances .card-label').textContent = 'Assigned Ordinances';
        } else {
            document.querySelector('.card-ordinances .card-number').textContent = 0;
        }
    } catch (err) {
        console.error('Error fetching assigned ordinances:', err);
        fetch('/api/helpers/logConsoleError.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                error: err.message || err,
                script: 'loadAssignedOrdinances.js'
            }),
            credentials: 'include'
        }).catch(e => console.error('Failed to log error to server:', e));
    }
}


