(function () {
    window.openEditModal = function (eventData) {
        const modal = document.getElementById('editEventModal');

        document.getElementById('editEventTitle').value = eventData.title;
        document.getElementById('editEventId').value = eventData.id;
        document.getElementById('editEventCity').value = eventData.city || '';
        document.getElementById('editEventType').value = eventData.type;
        document.getElementById('editEventDate').value = eventData.date;
        document.getElementById('editEventTime').value = eventData.time;
        document.getElementById('editEventLocation').value = eventData.location;
        modal.classList.add('show');

        if (window.lucide) window.lucide.createIcons();
    };

    window.closeEditModal = function () {
        document.getElementById('editEventModal').classList.remove('show');
    };

    window.openRegisterModal = function () {
        const modal = document.getElementById('registerEventModal');
        const form = document.getElementById('registerEventForm');

        form.reset();
        const dateInput = document.getElementById('registerEventDate');

        if (dateInput && window.getSelectedDate) {
            const selectedDate = window.getSelectedDate();
            const year = selectedDate.getFullYear();
            const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
            const day = String(selectedDate.getDate()).padStart(2, '0');
            dateInput.value = `${year}-${month}-${day}`;
        }

        modal.classList.add('show');
        if (window.lucide) window.lucide.createIcons();
    };

    window.closeRegisterModal = function () {
        document.getElementById('registerEventModal').classList.remove('show');
    };

})();