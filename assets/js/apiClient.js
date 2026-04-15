(() => {
  const ENDPOINT_META = document.querySelector('meta[name="app-endpoint"]');
  const ROOT = (ENDPOINT_META?.content ?? "").trim();

  async function postTo(pathOrFile, action, fd = new FormData()) {
    fd.append("action", action);
    const url =
      ROOT && !pathOrFile.startsWith("http")
        ? ROOT.replace(/\/+$/, "") + "/" + pathOrFile.replace(/^\/+/, "")
        : pathOrFile;

    const res = await fetch(url, {
      method: "POST",
      body: fd,
      credentials: "same-origin",
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  }

  window.ApiClient = {
    postTo,
  };
})();
