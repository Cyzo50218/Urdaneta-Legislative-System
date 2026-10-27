<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legislative Information System - Staff Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/dashboard/dashboard.css">
    <link rel="stylesheet" href="/assets/css/dashboard/sidebar.css">
    <link rel="stylesheet" href="/assets/css/dashboard/attachments.css">
    <link rel="stylesheet" href="/assets/css/dashboard/gridSetup.css">
    <link rel="stylesheet" href="/assets/css/dashboard/hyperlinkModal.css">
    <link rel="stylesheet" href="/assets/css/dashboard/recordOptionModal.css">
    <link rel="stylesheet" href="/assets/css/dashboard/searchModal.css">
    <link rel="stylesheet" href="/assets/css/dashboard/taskList.css">
    <link rel="stylesheet" href="/assets/css/dashboard/calendarEventModal.css">
    <link rel="stylesheet" href="/assets/css/testIndex.css">

    <link rel="stylesheet" href="/assets/css/systemLogo.css">
    <link rel="stylesheet" href="/assets/css/globalcss.css">
    <link rel="stylesheet" href="/assets/css/globalforms.css">
    <link rel="stylesheet" href="/assets/css/globalbuttons.css">
    <link rel="stylesheet" href="/assets/css/globaltabs.css">
    <link rel="stylesheet" href="/assets/css/globalroot.css">

    <link rel="stylesheet" href="/assets/css/dashboard/viewModal.css">
    <link rel="stylesheet" href="/assets/css/dashboard/localGovCode.css">
    <link rel="stylesheet" href="/assets/css/dashboard/dashboardAssigned.css">

    <script src="/assets/js/mainPageScript.js" defer></script>
    <script src="/assets/js/toastAction.js" defer></script>

    <!-- jQuery and turn.js for the Local Gov Code section -->
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="/assets/js/api/turn.min.js"></script>

    <!-- LUICIDE ICONS CREATE ICON SAFELY -->
    <script src="https://unpkg.com/lucide@0.464.0/dist/umd/lucide.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script defer>
        window.addEventListener('DOMContentLoaded', () => {
            if (!window.lucide) {
                console.error('Lucide not loaded');
                return;
            }
            lucide.createIcons();
        });
    </script>

</head>