<?php $active = 'localgovcode'; ?>
<?php include __DIR__ . '/../../partials/_page_head.php'; ?>
<link rel="stylesheet" href="/../../assets/css/localGovCode.css">
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="/api/js/turn.min.js"></script>

<body class="bg-gray-50 min-h-[100svh] flex flex-col">
  <?php include __DIR__ . '/../../partials/navbar.php'; ?>

  <main class="flex-1">
    <?php include __DIR__ . '/../../sections/landingpage/localgovcode.php'; ?>
  </main>

  <div class="mt-auto">
    <?php include __DIR__ . '/../../partials/footer.php'; ?>
  </div>

  <?php include __DIR__ . '/../../partials/ai-modal.php'; ?>
  <?php include __DIR__ . '/../../partials/toast.php'; ?>
</body>
<script src="/../../assets/js/localGovCode.js"></script>

</html>

</html>