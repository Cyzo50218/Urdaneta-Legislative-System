import { GeoService } from './regions/fetchPhilippinesLocationInfo.js';
import { populateOptions, resetDropdowns } from './helpers/uiHelpers.js';
import { initSecurityValidation } from './helpers/securityHelpers.js';

(() => {
  function setLoginType(role, btn) {
    const group = document.getElementById("roleGroup");
    group
      .querySelectorAll(".tab-button")
      .forEach((b) => b.classList.remove("active"));
    btn?.classList.add("active");
    const field = document.getElementById("loginType");
    if (field) field.value = role;

    const form = document.getElementById("loginForm");
    if (form) form.dataset.loginType = role;
  }

  async function handleLogin(ev) {
    ev.preventDefault();
    const form = ev.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Authenticating...';

    const fd = new FormData(form);

    try {
      const data = await ApiClient.postTo("/api/loginportal/saveAccountDetails.php", "login", fd);

      if (data?.success) {
        showToast("Welcome back!", "success", "Authorized");
        setTimeout(() => (location.href = "/pages/adminpage/dashboard"), 1000);
      } else {
        btn.disabled = false;
        btn.innerHTML = originalContent;
        showToast(data?.message || "Login failed.", "error", "Access Denied");
      }
    } catch (err) {

      btn.disabled = false;
      btn.innerHTML = originalContent;
      showToast("Connection error.", "error", "Error");
    }
  }

  async function initLocationSelectors() {
    const selects = {
      region: document.querySelector('select[name="region"]'),
      province: document.querySelector('select[name="province"]'),
      city: document.querySelector('select[name="city"]'),
      barangay: document.querySelector('select[name="barangay"]')
    };
    const toggleVisibility = (name, show) => {
      const container = selects[name]?.closest('.form-group');
      if (!container) return;
      if (show) {
        container.style.display = 'block';
        setTimeout(() => {
          container.classList.add('fade-in');
          container.classList.remove('fade-out');
        }, 10);
      } else {
        container.classList.remove('fade-in');
        container.classList.add('fade-out');
        setTimeout(() => {
          if (!container.classList.contains('fade-in')) {
            container.style.display = 'none';
          }
        }, 400);
      }
    };
    ['province', 'city', 'barangay'].forEach(key => toggleVisibility(key, false));
    try {
      const regions = await GeoService.getRegions();
      populateOptions(selects.region, regions, "Select Region");
    } catch (err) {
      showToast("Failed to load regions", "error");
    }
    selects.region.addEventListener('change', async (e) => {
      const code = e.target.value;
      resetDropdowns(['province', 'city', 'barangay'], selects);
      ['province', 'city', 'barangay'].forEach(key => toggleVisibility(key, false));
      if (!code) return;

      try {
        const provinces = await GeoService.getProvincesByRegion(code);

        if (provinces.length === 0) {
          // NCR - Keep province hidden, show city
          toggleVisibility('province', false);
          toggleVisibility('city', true);

          const cities = await GeoService.getCitiesByRegion(code);
          populateOptions(selects.city, cities, "Select City/Municipality");
          selects.province.innerHTML = '<option value="ncr" selected>N/A (NCR)</option>';
        } else {
          // Show province, keep others hidden
          toggleVisibility('province', true);
          populateOptions(selects.province, provinces, "Select Province");
        }
      } catch (err) { showToast("Error loading provinces", "error"); }
    });

    // PROVINCE CHANGE
    selects.province.addEventListener('change', async (e) => {
      const code = e.target.value;
      resetDropdowns(['city', 'barangay'], selects);

      toggleVisibility('city', !!code);
      toggleVisibility('barangay', false);

      if (!code || code === 'ncr') return;

      try {
        const cities = await GeoService.getCitiesByProvince(code);
        populateOptions(selects.city, cities, "Select City/Municipality");
      } catch (err) { showToast("Error loading cities", "error"); }
    });

    // CITY CHANGE
    selects.city.addEventListener('change', async (e) => {
      const code = e.target.value;
      resetDropdowns(['barangay'], selects);

      toggleVisibility('barangay', !!code);

      if (!code) return;

      try {
        const barangays = await GeoService.getBarangaysByCity(code);
        populateOptions(selects.barangay, barangays, "Select Barangay");
      } catch (err) { showToast("Error loading barangays", "error"); }
    });
  }

  async function handleRegister(ev) {
    ev.preventDefault();
    const form = ev.target;
    const fd = new FormData(form);

    // Password Match Validation
    if (fd.get("password") !== fd.get("confirmPassword")) {
      showToast("Passwords do not match", "error", "Error");
      return;
    }

    // Location Validation
    if (!fd.get("region")) {
      showToast("Please select your region", "error", "Missing Information");
      return;
    }

    // Privacy & Authorization Validation
    const attestation = document.getElementById("govAttestation");
    if (!attestation || !attestation.checked) {
      showToast("You must certify the authorization statement", "error", "Authorization Required");
      return;
    }

    // Role Mapping
    const selectedRole = form.querySelector('input[name="registerType"]:checked')?.value;
    fd.set("role", selectedRole || "staff");

    try {
      const data = await ApiClient.postTo("/api/loginportal/saveAccountDetails.php", "register", fd);

      if (data && data.success) {
        showToast("Account created successfully!", "success", "Success");
        window.location.href = "/pages/adminpage/dashboard#dashboard";
      } else {
        showToast(data?.message || "Registration failed", "error", "Error");
      }
    } catch (err) {
      showToast("Server error. Please try again later.", "error", "Connection Error");
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("#roleGroup .tab-button").forEach((btn) => {
      if (!btn.hasAttribute("onclick")) {
        btn.addEventListener("click", () =>
          setLoginType(btn.dataset.role, btn)
        );
      }
    });
    initLocationSelectors();
    initSecurityValidation();
    const activeBtn = document.querySelector("#roleGroup .tab-button.active");
    if (activeBtn) setLoginType(activeBtn.dataset.role, activeBtn);

    document
      .getElementById("loginForm")
      ?.addEventListener("submit", handleLogin);
    document
      .getElementById("registerForm")
      ?.addEventListener("submit", handleRegister);
  });
  window.setLoginType = setLoginType;
  window.handleLogin = handleLogin;
  window.handleRegister = handleRegister;
})();
