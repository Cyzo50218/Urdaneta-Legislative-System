function toggleSidebar(forceClose = false) {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');
    const toggleIcon = document.getElementById('toggleIcon');
    const pinIcon = document.querySelector('.sidebar-pin .pin-icon');
    const isMobile = window.innerWidth <= 768;

    let isOpen;

    if (isMobile) {
        if (forceClose) {
            sidebar.classList.add('closed');
            sidebar.classList.remove('open');
            isOpen = false;
        } else {
            sidebar.classList.toggle('open');
            sidebar.classList.toggle('closed');
            isOpen = sidebar.classList.contains('open');
        }

        if (isOpen) {
            overlay.classList.add('show');
        } else {
            overlay.classList.remove('show');
        }
    } else {
        overlay.classList.remove('show');

        if (forceClose) {
            sidebar.classList.add('collapsed');
            isOpen = false;
        } else {
            sidebar.classList.toggle('collapsed');
            isOpen = !sidebar.classList.contains('collapsed');
        }
    }

    if (pinIcon) {
        pinIcon.style.transform = isOpen ? 'scaleX(1)' : 'scaleX(-1)';
    }

    if (!isOpen) {
        // burger
        toggleIcon.innerHTML = `
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6"  x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        `;
    } else {
        // X
        toggleIcon.innerHTML = `
            <line x1="18" y1="6"  x2="6"  y2="18"></line>
            <line x1="6"  y1="6"  x2="18" y2="18"></line>
        `;
    }
}


function setActiveMenu(element, event) {
    event.preventDefault();

    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
        if (window.innerWidth <= 768) {
            const sidebar = document.getElementById('sidebar');

        sidebar.classList.add('closed');
            sidebar.classList.remove('open');
        toggleSidebar(true);
    }
    });
    element.classList.add('active');

    
}

window.addEventListener('load', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');

    if (window.innerWidth <= 768) {
        sidebar.classList.add('closed');
        sidebar.classList.remove('open', 'collapsed');
        overlay.classList.remove('show');
    } else {
        sidebar.classList.remove('closed');
        sidebar.classList.remove('collapsed');
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }
});

window.addEventListener('resize', function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');

    if (window.innerWidth <= 768) {
        // switch to mobile mode
        sidebar.classList.add('closed');
        sidebar.classList.remove('open');
        sidebar.classList.remove('collapsed');
        overlay.classList.remove('show');
    } else {
        // switch to desktop mode
        sidebar.classList.remove('closed');
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }
});