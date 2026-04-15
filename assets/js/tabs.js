(() => {
  function ensureLoginTypeField() {
    let field = document.getElementById("loginType");
    if (!field) {
      const form = document.getElementById("loginForm");
      if (form) {
        field = document.createElement("input");
        field.type = "hidden";
        field.name = "loginType";
        field.id = "loginType";
        field.value = "staff";
        form.appendChild(field);
      }
    }
    return field;
  }

  function setRoleActive(role) {
    const group = document.getElementById("roleGroup");
    if (!group) return;
    group
      .querySelectorAll(".tab-button")
      .forEach((b) => b.classList.remove("active"));
    const btn = group.querySelector(`.tab-button[data-role="${role}"]`);
    btn?.classList.add("active");
    const field = ensureLoginTypeField();
    if (field) field.value = role;
    const form = document.getElementById("loginForm");
    if (form) form.dataset.loginType = role;
  }

  function ensureDefaultRole() {
    const group = document.getElementById("roleGroup");
    if (!group) return;
    const anyActive = group.querySelector(".tab-button.active");
    if (!anyActive) setRoleActive("staff");
  }

  function switchTab(tabName, btn) {
    const root = btn?.closest("[data-tabs]") || document;
    root
      .querySelectorAll(".tab-content")
      .forEach((el) => el.classList.remove("active"));
    const panel =
      root.querySelector(`[data-tab-panel="${tabName}"]`) ||
      root.querySelector(`#${CSS.escape(tabName)}Tab`);
    panel?.classList.add("active");

    const tabList =
      btn?.closest(".tab-list") || root.querySelector(".tab-list");
    if (tabList) {
      tabList
        .querySelectorAll(".tab-button")
        .forEach((el) => el.classList.remove("active"));
      const toActivate =
        btn || tabList.querySelector(`.tab-button[data-tab="${tabName}"]`);
      toActivate?.classList.add("active");
    }

    if (tabName === "login") {
      ensureDefaultRole();
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".tab-button").forEach((b) => {
      if (!b.hasAttribute("type")) b.setAttribute("type", "button");
      const tab = b.dataset.tab;
      const role = b.dataset.role;
      if (tab) b.addEventListener("click", () => switchTab(tab, b));
      if (role) b.addEventListener("click", () => switchTab(role, b));
    });

    const loginVisible = document
      .getElementById("loginTab")
      ?.classList.contains("active");
    if (loginVisible) ensureDefaultRole();
  });

  window.switchTab = switchTab;
})();
