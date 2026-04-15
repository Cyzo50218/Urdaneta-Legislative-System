import { GeoService } from './../../regions/fetchPhilippinesLocationInfo.js';

async function loadUserProfile() {
    const emailEl = document.getElementById('profileEmail');
    if (!emailEl) return;

    try {
        const profileRes = await fetch('/api/account/getProfile.php');
        const profileResult = await profileRes.json();

        if (profileResult.success) {
            const d = profileResult.data;

            // --- REAL-TIME NAME & AVATAR UPDATE ---
            const fullName = `${d.first_name} ${d.last_name}`;
            updateElementText('profileFullName', fullName); // Add this ID to your H1

            // Update Avatar Initial
            const avatarEl = document.querySelector('.rounded-xl.bg-gray-100.flex.items-center');
            if (avatarEl && d.first_name) {
                avatarEl.textContent = d.first_name.charAt(0).toUpperCase();
            }

            // --- OTHER FIELDS ---
            if (emailEl) emailEl.textContent = d.email;
            updateElementText('profContact', d.phone_number || 'None provided');

            const isNCR = d.region === '130000000';
            const [reg, prov, city, brgy] = await Promise.allSettled([
                d.region ? GeoService.getRegionName(d.region) : Promise.reject(),
                (d.province && !isNCR) ? GeoService.getProvinceName(d.province) : Promise.reject(),
                d.city ? GeoService.getCityName(d.city) : Promise.reject(),
                d.barangay ? GeoService.getBarangayName(d.barangay) : Promise.reject()
            ]);

            updateElementText('profRegion', reg.status === 'fulfilled' ? reg.value.name : d.region);

            let provinceName = isNCR ? "NCR" : "N/A";
            if (!isNCR && prov.status === 'fulfilled') provinceName = prov.value.name;
            updateElementText('profProvince', provinceName);

            updateElementText('profCity', city.status === 'fulfilled' ? city.value.name : d.city);
            updateElementText('profBarangay', brgy.status === 'fulfilled' ? brgy.value.name : d.barangay);
        }

        // Fetch Count
        const assignedRes = await fetch('/api/fetchMyAssignedOrdinancesCount.php');
        const assignedResult = await assignedRes.json();
        if (assignedResult.success) {
            updateElementText('countResolutions', assignedResult.count);
        }

        if (window.lucide) lucide.createIcons();

    } catch (error) {
        console.error("Error loading profile details:", error);
        window.showToast(
            "Failed to load profile data. Some information may be missing.",
            "error",
            "Profile Load Error"
        );
    }
}

function updateElementText(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
}

window.toggleEditProfile = function (show) {
    const overlay = document.getElementById('editProfileOverlay');
    if (show) {
        overlay.classList.remove('hidden');
        const fullName = document.getElementById('profileFullName').textContent;
        const nameParts = fullName.split(' ');
        document.getElementById('editFirstName').value = nameParts[0] || '';
        document.getElementById('editLastName').value = nameParts.slice(1).join(' ') || '';
        document.getElementById('editEmail').value = document.getElementById('profileEmail').textContent;
        document.getElementById('editContact').value = document.getElementById('profContact').textContent === 'None provided' ? '' : document.getElementById('profContact').textContent;
    } else {
        overlay.classList.add('hidden');
    }
};
document.getElementById('editProfileForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.textContent;

    btn.disabled = true;
    btn.textContent = 'Saving...';

    const formData = new FormData(e.target);

    try {
        const response = await fetch('/api/account/updateProfile.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.success) {
            const newFirstName = formData.get('first_name');
            const newLastName = formData.get('last_name');
            const newPhone = formData.get('phone_number');
            const newFullName = `${newFirstName} ${newLastName}`;
            const initial = newFirstName.charAt(0).toUpperCase();
            updateElementText('profileFullName', newFullName);
            updateElementText('profContact', newPhone || 'None provided');
            const profileAvatar = document.querySelector('#profileSection .user-avatar-container, .bg-gray-100.flex.items-center');
            if (profileAvatar) profileAvatar.textContent = initial;
            updateElementText('headerUserName', newFullName);
            updateElementText('headerAvatar', initial);
            if (window.USER_SESSION) {
                window.USER_SESSION.full_name = newFullName;
            }
            loadUserProfile();
            if (typeof updateSidebarName === 'function') updateSidebarName(newFullName);

            toggleEditProfile(false);

            window.showToast(
                "Your profile information has been updated successfully.",
                "success",
                "Profile Updated"
            );
        } else {
            window.showToast(
                result.error || "Unable to update profile.",
                "error",
                "Update Failed"
            );
        }
    } catch (error) {
        console.error("Save error:", error);
        window.showToast("A connection error occurred.", "error", "System Error");
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
});
window.loadUserProfile = loadUserProfile;