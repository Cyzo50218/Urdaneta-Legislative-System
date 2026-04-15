const userDropdown = document.getElementById('userDropdown');
const userBtn = document.querySelector('.user-btn');
let dropdownOpen = false;

function toggleUserDropdown(e) {
    e.stopPropagation();
    dropdownOpen = !dropdownOpen;
    userDropdown.style.display = dropdownOpen ? 'block' : 'none';
    toggleBodyScroll(dropdownOpen);
}

function toggleBodyScroll(disable) {
    if (disable) {
        document.body.style.overflow = 'hidden';
        document.querySelectorAll('.main-content').forEach(el => el.style.overflowY = 'hidden');
    } else {
        document.body.style.overflow = '';
        document.querySelectorAll('.main-content').forEach(el => el.style.overflowY = 'auto');
    }
}
document.addEventListener('click', () => {
    if (dropdownOpen) {
        dropdownOpen = false;
        userDropdown.style.display = 'none';
        toggleBodyScroll(false);
    }
});

userBtn.addEventListener('click', toggleUserDropdown);
const logoutLink = userDropdown.querySelector('a[href="#logout"]');
logoutLink.addEventListener('click', async (e) => {
    e.preventDefault();
    try {
        const response = await fetch('/api/loginportal/logout.php', {
            method: 'GET',
            credentials: 'include'
        });
        window.location.href = '/pages/loginportal.php?message=logged_out';
    } catch (err) {
        console.error('Logout failed', err);
        alert('Failed to logout. Please try again.');
    }
});
