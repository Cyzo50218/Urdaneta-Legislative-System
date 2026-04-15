<?php $active = 'calendar'; ?>
<?php include __DIR__ . '/../../partials/_page_head.php'; ?>

<body class="bg-gray-50 min-h-[100svh] flex flex-col">
  <?php include __DIR__ . '/../../partials/navbar.php'; ?>

  <main class="flex-1">
    <?php include __DIR__ . '/../../sections/landingpage/calendar.php'; ?>
  </main>

  <div class="mt-auto">
    <?php include __DIR__ . '/../../partials/footer.php'; ?>
</div>

  <?php include __DIR__ . '/../../partials/ai-modal.php'; ?>
  <?php include __DIR__ . '/../../partials/toast.php'; ?>
</body>

</html>

</html>