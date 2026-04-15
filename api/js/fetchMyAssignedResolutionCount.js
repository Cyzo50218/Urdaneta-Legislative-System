async function loadAssignedResolutions() {
    try {
        const response = await fetch('/api/resolutions/fetchMyAssignedResolutions.php', {
            method: 'GET',
            credentials: 'include'
        });
        const data = await response.json();

        if (data.success && data.data.length > 0) {
            const firstResolution = data.data[0];
            const totalAssigned = data.data.length;
            document.querySelector('.card-resolutions .card-number').textContent = totalAssigned;
            document.querySelector('.card-resolutions .card-label').textContent = 'Assigned Resolutions';
        } else {
            document.querySelector('.card-resolutions .card-number').textContent = 0;
        }
    } catch (err) {
        console.error('Error fetching assigned resolutions:', err);
        fetch('/api/helpers/logConsoleError.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                error: err.message || err,
                script: 'loadAssignedResolutions.js'
            }),
            credentials: 'include'
        }).catch(e => console.error('Failed to log error to server:', e));
    }
}


