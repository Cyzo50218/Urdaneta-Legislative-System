<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Urdaneta City - Official Legislative Information System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/testIndex.css">

  <!-- GLOBAL CSS BODY + ROOT THEME COLORS -->
  <link rel="stylesheet" href="/assets/css/systemLogo.css">
  <link rel="stylesheet" href="/assets/css/globalforms.css">
  <link rel="stylesheet" href="/assets/css/globalcss.css">
  <link rel="stylesheet" href="/assets/css/globalroot.css">

  <script src="/assets/js/mainPageScript.js" defer></script>
  <script src="/assets/js/toastAction.js" defer></script>

  <!-- LUICIDE ICONS CREATE ICON SAFELY -->
  <script src="https://unpkg.com/lucide@0.464.0/dist/umd/lucide.min.js" defer></script>
  <script defer>
    window.addEventListener('DOMContentLoaded', () => {
      if (!window.lucide) {
        console.error('Lucide not loaded');
        return;
      }
      lucide.createIcons();
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const loader = document.querySelector(".gradient-border::before");
      const progressBar = document.createElement('div');
      progressBar.classList.add('nav-loading-bar');
      document.querySelector('.gradient-border').appendChild(progressBar);

      let width = 0;
      const interval = setInterval(() => {
        width += Math.random() * 10;
        if (width >= 95) width = 95;
        progressBar.style.width = width + "%";
      }, 100);

      window.addEventListener("load", () => {
        progressBar.style.width = "100%";
        setTimeout(() => {
          progressBar.style.opacity = 0;
        }, 300);
        clearInterval(interval);
      });
    });
  </script>

<body class="overflow-y-scroll overflow-x-hidden">
  <div id="nav-loading-bar" style="
  position: fixed;
  top: 0;
  left: 0;
  height: 4px;
  width: 0%;
  background: linear-gradient(90deg, #059703 0%, #07641d 50%, #fcd116 100%);
  z-index: 9999;
  transition: width 0.2s ease, opacity 0.5s ease;
"></div>
  <script>
    (function() {
      const bar = document.getElementById('nav-loading-bar');
      let width = 0;
      const timer = setInterval(() => {
        width += Math.random() * 5;
        if (width > 90) width = 90;
        bar.style.width = width + "%";
      }, 100);
      window.addEventListener("load", () => {
        clearInterval(timer);
        bar.style.width = "100%";

        setTimeout(() => {
          bar.style.opacity = 0;
        }, 300);
      });
    })();
  </script>

  </head>