(() => {
  function ensureToastDom() {
    let toast = document.getElementById("toast");
    if (!toast) {
      toast = document.createElement("div");
      toast.id = "toast";
      toast.className = "toast";
      toast.innerHTML = `
        <div id="toastTitle" class="toast-title" style="display:none;"></div>
        <div id="toastMessage" class="toast-message"></div>
      `;
      document.body.appendChild(toast);
    }
    return toast;
  }

  window.showToast = function showToast(message, type = "success", title = "") {
    const toast = ensureToastDom();
    const toastMessage = toast.querySelector("#toastMessage");
    const toastTitle = toast.querySelector("#toastTitle");

    toastMessage.textContent = message || "";
    if (toastTitle) {
      toastTitle.textContent = title || "";
      toastTitle.style.display = title ? "block" : "none";
    }

    toast.className = `toast show ${type}`;
    if (typeof window.safeIcons === "function") {
      setTimeout(window.safeIcons, 100);
    }
    setTimeout(() => toast.classList.remove("show"), 3000);
  };
})();
