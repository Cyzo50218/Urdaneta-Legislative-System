<?php
$APP_ENDPOINT = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="app-endpoint" content="">

    <title>Login Portal • Legislative Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/adminGlobal.css">
    <link rel="stylesheet" href="/assets/css/testIndex.css">
    
    <link rel="stylesheet" href="/assets/css/loginportal.css">

    <!-- GLOBAL CSS BODY + ROOT THEME COLORS -->
    <link rel="stylesheet" href="/assets/css/systemLogo.css">
    <link rel="stylesheet" href="/assets/css/globalcss.css">
    <link rel="stylesheet" href="/assets/css/globalforms.css">
    <link rel="stylesheet" href="/assets/css/globalbuttons.css">
    <link rel="stylesheet" href="/assets/css/globaltabs.css">
    <link rel="stylesheet" href="/assets/css/globalroot.css">

    <script src="/assets/js/apiClient.js" defer></script>
    <script src="/assets/js/registerForm.js" defer></script>
    <script src="/assets/js/tabs.js" defer></script>
    <script type="module" src="/assets/js/regions/fetchPhilippinesLocationInfo.js"></script>
    <script type="module" src="/assets/js/auth.js" defer></script>
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
        let devtoolsOpen = false;

        setInterval(() => {
            const widthDiff = window.outerWidth - window.innerWidth;
            const heightDiff = window.outerHeight - window.innerHeight;

            if (widthDiff > 160 || heightDiff > 160) {
                if (!devtoolsOpen) {
                    devtoolsOpen = true;
                    document.body.innerHTML = `
        <div style="
          display:flex;
          align-items:center;
          justify-content:center;
          height:100vh;
          background:#0f172a;
          color:#fff;
          font-family:sans-serif;
          text-align:center;
        ">
          <div>
            <h1>⚠️ Developer Tools Detected!</h1>
            <p>Unauthorized inspection is not allowed.</p>
          </div>
        </div>
      `;
                }
            }
        }, 500);
    </script>
</head>