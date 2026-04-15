/**
 * Real-time validation for Email and Password strength
 */
export function initSecurityValidation() {
    const preventSpaces = (el) => {
        if (!el) return;
        el.addEventListener('keydown', (e) => {
            if (e.key === " " || e.keyCode === 32) {
                e.preventDefault();
            }
        });
        el.addEventListener('input', () => {
            if (el.value.includes(" ")) {
                el.value = el.value.replace(/\s/g, "");
            }
        });
    };
    const emailInput = document.getElementById('regEmail');
    const emailFeedback = document.getElementById('emailFeedback');
    const passInput = document.getElementById('regPassword');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const confirmInput = document.getElementById('confirmPassword');
    const matchFeedback = document.getElementById('matchFeedback');

    // --- FOR LOGIN ---
    const loginEmail = document.querySelector('#loginForm input[name="email"]');
    // --- END LOGIN ---

    const registerForm = document.getElementById('registerForm');
    const submitBtn = registerForm?.querySelector('button[type="submit"]');
    const attestation = document.getElementById('govAttestation');
    const textInputs = [
        registerForm?.querySelector('input[name="firstName"]'),
        registerForm?.querySelector('input[name="lastName"]')
    ];
    const selectInputs = [
        document.getElementById('regionSelect'),
        document.getElementById('provinceSelect'),
        document.getElementById('citySelect'),
        document.getElementById('barangaySelect')
    ];

    const validateForm = () => {
        let allValid = true;
        textInputs.forEach(field => {
            if (!field) return;
            const val = field.value.trim();
            if (val === "") {
                field.classList.add('input-error');
                allValid = false;
            } else {
                field.classList.remove('input-error');
            }
        });
        selectInputs.forEach(select => {
            if (!select) return;
            const isDefault = select.value === "" || select.value.toLowerCase().includes("select");

            if (isDefault) {
                select.classList.add('input-error');
                select.style.border = "2px solid #ff4d4d"; // Force the color
                allValid = false;
            } else {
                select.classList.remove('input-error');

                select.style.border = "2px solid var(--ph-blue)";
                select.classList.add('input-success');
            }
        });
        const isCertified = attestation?.checked;
        if (!isCertified) {
            attestation?.closest('.checkbox-row')?.style.setProperty('color', '#ff4d4d');
            allValid = false;
        } else {
            attestation?.closest('.checkbox-row')?.style.setProperty('color', 'inherit');
        }
        const isEmailValid = document.getElementById('regEmail')?.classList.contains('input-success');
        const isPassMatch = document.getElementById('confirmPassword')?.classList.contains('input-success');
        if (submitBtn) {
            submitBtn.disabled = !(allValid && isCertified && isEmailValid && isPassMatch);
        }
    };
    [...textInputs, ...selectInputs].forEach(field => {
        field?.addEventListener('input', validateForm);
        field?.addEventListener('change', validateForm);
    });

    attestation?.addEventListener('change', validateForm);
    validateForm();

    // Real-time Email Validation
    emailInput?.addEventListener('input', () => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const val = emailInput.value.trim();
        const icon = emailInput.closest('.input-wrapper')?.querySelector('.input-icon');

        if (val === "") {
            emailFeedback.textContent = "";
            emailInput.classList.remove('input-error', 'input-success');
            if (icon) icon.style.color = "var(--ph-blue-dark)";
        } else if (emailRegex.test(val)) {
            emailFeedback.textContent = "Valid email address";
            emailFeedback.className = "validation-message text-success";
            emailInput.classList.add('input-success');
            emailInput.classList.remove('input-error');
            if (icon) icon.style.color = "var(--ph-success)"; // Success Green
        } else {
            emailFeedback.textContent = "Please enter a valid email (e.g., name@domain.com)";
            emailFeedback.className = "validation-message text-error";
            emailInput.classList.add('input-error');
            emailInput.classList.remove('input-success');
            if (icon) icon.style.color = "#ff4d4d"; // Error Red
        }
    });
    // Real-time Password Strength
    passInput?.addEventListener('input', () => {
        const val = passInput.value;
        let score = 0;
        if (val.length > 7) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val) || /[^A-Za-z0-9]/.test(val)) score++;

        strengthBar.className = "strength-bar";

        if (val.length === 0) {
            strengthText.textContent = "Password strength";
        } else if (score === 1) {
            strengthBar.classList.add('weak');
            strengthText.textContent = "Weak (Add uppercase/numbers)";
        } else if (score === 2) {
            strengthBar.classList.add('medium');
            strengthText.textContent = "Medium (Add symbols)";
        } else if (score === 3) {
            strengthBar.classList.add('strong');
            strengthText.textContent = "Strong password";
        }
    });

    // Real-time Match Validation
    const checkMatch = () => {
        const p1 = passInput.value;
        const p2 = confirmInput.value;

        const icon1 = passInput.closest('.input-wrapper')?.querySelector('.input-icon');
        const icon2 = confirmInput.closest('.input-wrapper')?.querySelector('.input-icon');
        const icons = [icon1, icon2];

        if (p2 === "") {
            matchFeedback.textContent = "";
            [passInput, confirmInput].forEach(el => el.classList.remove('input-error', 'input-success'));
            icons.forEach(icon => { if (icon) icon.style.color = ""; });
            return;
        }

        if (p1 === p2) {
            matchFeedback.textContent = "Passwords match";
            matchFeedback.className = "validation-message text-success";
            [passInput, confirmInput].forEach(el => {
                el.classList.add('input-success');
                el.classList.remove('input-error');
            });
            icons.forEach(icon => { if (icon) icon.style.color = "var(--ph-success)"; });
        } else {
            matchFeedback.textContent = "Passwords do not match";
            matchFeedback.className = "validation-message text-error";
            [passInput, confirmInput].forEach(el => {
                el.classList.add('input-error');
                el.classList.remove('input-success');
            });
            icons.forEach(icon => { if (icon) icon.style.color = "#ff4d4d"; });
        }
    };

    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('svg');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
                this.style.color = "var(--ph-blue-dark)"; // Highlight active eye
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
                this.style.color = ""; // Reset color
            }
        });
    });
    passInput?.addEventListener('input', checkMatch);
    confirmInput?.addEventListener('input', checkMatch);

    // --- FOR LOGIN PAGE: Prevent spaces in email field ---
    preventSpaces(loginEmail);

}