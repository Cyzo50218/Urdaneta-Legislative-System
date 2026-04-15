(() => {
  document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");
    const submitBtn = form?.querySelector('button[type="submit"]');

    if (!form || !submitBtn) return;

    // Helper to check validity
    function checkFormValidity() {
      const isValid = form.checkValidity();

      submitBtn.disabled = !isValid;
    }
    checkFormValidity();
    form.querySelectorAll("input, select, textarea").forEach((el) => {
      el.addEventListener("input", checkFormValidity);
      el.addEventListener("change", checkFormValidity);
    });
  });
})();
