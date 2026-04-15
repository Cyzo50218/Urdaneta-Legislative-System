async function loadMyTasks() {
    try {
        const response = await fetch('/api/fetchMyTasks.php', {
            method: 'GET',
            credentials: 'include'
        });
        const data = await response.json();

        if (data.success) {
            const totalAssigned = data.data;
            document.querySelector('.card-tasks .card-number').textContent = totalAssigned;
            document.querySelector('.card-tasks .card-label').textContent = 'Tasks for Today';
        } else {
            document.querySelector('.card-tasks .card-number').textContent = 0;
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
                script: 'fetchMyTasks.js'
            }),
            credentials: 'include'
        }).catch(e => console.error('Failed to log error to server:', e));
    }
}

