<?php 
$active = 'home'; 
ini_set('session.cookie_path', '/');
session_start(); 

define('APP_ENV', 'dev');
require('api/connections/connection.php');

function render_mysql_error(mysqli_sql_exception $e, ?string $sql = null): never
{
  $sqlstate = method_exists($e, 'getSqlState') ? $e->getSqlState() : '';
  $payload = [
    'errno'     => $e->getCode(),
    'sqlstate'  => $sqlstate,
    'message'   => $e->getMessage(),
    'query'     => $sql,
    'trace_id'  => bin2hex(random_bytes(6)),
    'when'      => date('c'),
  ];
  $log = $payload;
  error_log("[MYSQL_ERROR] " . json_encode($log, JSON_UNESCAPED_SLASHES));
  $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
  if ($isAjax) {
    http_response_code(500);
    header('Content-Type: application/json');
    $client = (APP_ENV === 'dev')
      ? $payload
      : ['error' => 'Database error. Please try again later.', 'trace_id' => $payload['trace_id']];
    echo json_encode($client);
    exit;
  }

  $dev = (APP_ENV === 'dev');
?>
  <!doctype html>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Database Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  </head>

  <body class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
    <div class="w-full max-w-2xl">
      <div class="rounded-2xl border border-red-200 bg-white shadow-sm">
        <div class="p-5 flex items-start gap-3">
          <div class="flex-1">
            <h2 class="text-lg font-semibold text-gray-900">Database error</h2>
            <p class="mt-1 text-sm text-gray-600">
              Something went wrong while talking to the database. Please try again later.
              <span class="text-gray-500">Trace ID: <code class="bg-gray-100 px-1 py-0.5 rounded"><?php echo htmlspecialchars($payload['trace_id']); ?></code></span>
            </p>

            <?php if ($dev): ?>
              <div class="mt-4 grid gap-2 text-sm">
                <div class="rounded-lg bg-red-50 p-3 text-red-800">
                  <div><span class="font-mono font-semibold">errno</span>: <?php echo (int)$payload['errno']; ?></div>
                  <?php if ($payload['sqlstate']): ?>
                    <div><span class="font-mono font-semibold">sqlstate</span>: <?php echo htmlspecialchars($payload['sqlstate']); ?></div>
                  <?php endif; ?>
                  <div class="break-words"><span class="font-mono font-semibold">message</span>: <?php echo htmlspecialchars($payload['message']); ?></div>
                  <?php if (!empty($payload['query'])): ?>
                    <details class="mt-2">
                      <summary class="cursor-pointer select-none">Show SQL</summary>
                      <pre class="mt-2 overflow-auto rounded bg-white p-3 text-xs text-gray-800 border"><?php echo htmlspecialchars($payload['query']); ?></pre>
                    </details>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="mt-5 flex gap-2">
              <button onclick="location.reload()"
                class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Retry
              </button>
              <button onclick="history.back()"
                class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-black">
                Go back
              </button>
            </div>
          </div>
        </div>
      </div>
      <p class="mt-3 text-center text-xs text-gray-500">Environment: <?php echo APP_ENV; ?></p>
    </div>
  </body>

  </html>
<?php
  exit;
}


function q(mysqli $db, string $sql): mysqli_result|bool
{
  try {
    $res = $db->query($sql);
    return $res;
  } catch (mysqli_sql_exception $e) {
    render_mysql_error($e, $sql);
  }
}

?>
<?php
try {
    if (!isset($_SESSION['systemName']) || !isset($_SESSION['systemLocation'])) {
    
        $qcs = q($mysqli, "CALL checkSystem();");
        if ($qcs instanceof mysqli_result) {
            $count = $qcs->num_rows;

            if ($count > 0) {
                while ($rcs = $qcs->fetch_assoc()) {
                    $systemName          = $rcs['systemName'] ?? '';
                    $systemLocation      = $rcs['systemLocation'] ?? '';
                    $systemAdministrator = (int)($rcs['systemAdministrator'] ?? 0);

                    if (($systemName === '') || ($systemLocation === '') || ($systemAdministrator === 0)) {
                        exit;
                    } else {
                        $_SESSION['systemName']          = $systemName;
                        $_SESSION['systemLocation']      = $systemLocation;
                        $_SESSION['systemAdministrator'] = $systemAdministrator;
                    }
                }
            } else {
                header('Location: systemMessage.php');
                exit;
            }
            $qcs->free();
        }
        while ($mysqli->more_results()) {
            $mysqli->next_result();
            if ($tmp = $mysqli->store_result()) {
                $tmp->free();
            }
        }
    }
} catch (mysqli_sql_exception $e) {
    render_mysql_error($e, "CALL checkSystem();");
}
?>
<?php include __DIR__ . '/partials/_page_head.php'; ?>

<body class="bg-gray-50">
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
  <div class="page-wrapper">

    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="content">
      <?php include __DIR__ . '/sections/landingpage/home.php'; ?>
      <?php include __DIR__ . '/sections/landingpage/features.php'; ?>
    </div>


    <?php include __DIR__ . '/partials/footer.php'; ?>
    <?php include __DIR__ . '/partials/ai-modal.php'; ?>
    <?php include __DIR__ . '/partials/toast.php'; ?>
  </div>
  

</body>

</html>