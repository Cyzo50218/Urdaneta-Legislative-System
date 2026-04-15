function formatDueDate(dueDate, today = new Date()) {
    const startToday = new Date(today);
    startToday.setHours(0, 0, 0, 0);

    const startDue = new Date(dueDate);
    startDue.setHours(0, 0, 0, 0);

    const diffMs = startDue - startToday;
    const diffDays = Math.round(diffMs / (1000 * 60 * 60 * 24));

    // Past dates
    if (diffDays < 0) {
        const pastDays = Math.abs(diffDays);

        if (pastDays <= 7) {
            return `${pastDays} day${pastDays > 1 ? 's' : ''} ago`;
        }

        if (pastDays <= 14) {
            return `Last week`;
        }

        return startDue.toLocaleDateString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric'
        });
    }

    // Future dates
    if (diffDays === 0) return 'Today';
    if (diffDays === 1) return 'Tomorrow';
    if (diffDays <= 7) return `In ${diffDays} days`;

    // Far future
    return startDue.toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
}
window.formatDueDate = formatDueDate;