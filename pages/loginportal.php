<?php
define('APP_ENV', 'dev');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
require('../api/connections/connection.php');

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
    $qcs = q($mysqli, "CALL checkSystem();");
    if ($qcs instanceof mysqli_result) {
        $count = $qcs->num_rows;

        if ($count > 0) {
            while ($rcs = $qcs->fetch_assoc()) {
                $systemName          = $rcs['systemName'] ?? '';
                $systemLocation      = $rcs['systemLocation'] ?? '';
                $systemAdministrator = (int)($rcs['systemAdministrator'] ?? 0);

                if (($systemName === '') || ($systemLocation === '') || ($systemAdministrator === 0)) {
                    header('Location: systemMessage.php');
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
} catch (mysqli_sql_exception $e) {
    render_mysql_error($e, "CALL checkSystem();");
}
?>
<?php include __DIR__ . '../../partials/_page_head_loginportal.php'; ?>

<body>
    <div class="page-wrapper">

        <!-- ============================================================ -->
        <!-- NAVIGATION BAR -->
        <!-- ============================================================ -->
        <nav class="navbar ">
            <div class="container">
                <div class="navbar-content">
                    <div class="flex items-center gap-4">
                        <div class="ph-seal bg-white/20">
                            <i data-lucide="landmark" class="text-white w-7 h-7"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-white">Urdaneta City</span>
                            <span class="text-xs text-white/80">Legislative Information System</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="content">
            <!-- ============================================================ -->
            <!-- LOGIN/REGISTER PORTAL -->
            <!-- ============================================================ -->
            <div class="hero">
                <div class="absolute top-20 right-10 w-96 h-96 rounded-full opacity-10 bg-ph-blue"></div>

                <div class="absolute bottom-10 left-10 w-72 h-72 rounded-full opacity-10 bg-ph-red"></div>

                <div class="absolute top-1/3 right-1/3 w-48 h-48 border-8 rounded-2xl bounce-blue-border"
                    style="border-color: color-mix(in srgb, var(--ph-blue-light) 60%, white); opacity: .2;"></div>

                <div class="absolute bottom-1/3 left-1/4 w-32 h-32 border-8 rounded-full bounce-red-border"
                    style="border-color: color-mix(in srgb, var(--ph-red) 50%, white); opacity: .2;"></div>

                <div class="floating-shape">

                    <div class="floating-shape shape1"></div>
                    <div class="floating-shape shape2"></div>

                </div>
                <div class="container">
                    <div class="hero-content">
                        <div class="hero-icon">
                            <svg width="40" height="40" viewBox="0 0 24 26" fill="none" stroke="white" stroke-width="2">
                                <path d="M12 2L2 7v6c0 5.5 3.8 10.7 10 12 6.2-1.3 10-6.5 10-12V7l-10-5z" />
                            </svg>
                        </div>
                        <h1>Admin Portal</h1>
                        <p>Access legislative information, track bills, and participate in the democratic process</p>
                    </div>
                </div>
            </div>

            <div class="section-spacing">
                <div class="container">
                    <div class="grid grid-main">
                        <!-- Login/Register Forms -->
                        <div class="card">
                            <div class="tabs">
                                <div class="tab-list">
                                    <button class="tab-button active" type="button" data-tab="login" onclick="switchTab('login')">Sign In</button>
                                    <button class="tab-button" type="button" data-tab="register" onclick="switchTab('register')">Register</button>
                                </div>
                            </div>

                            <!-- Login Form -->
                            <div id="loginTab" class="tab-content active">
                                <!-- Header -->
                                <div class="card-header">
                                    <h2 class="card-title">Welcome Back</h2>
                                    <p class="card-description">
                                        Sign in to access your legislative portal account
                                    </p>
                                </div>

                                <h2 class="card-title">Sign In as</h2>

                                <br>

                                <form id="loginForm" onsubmit="handleLogin(event)">
                                    <input type="hidden" name="loginType" id="loginType" value="staff" />
                                    <div class="form-group">
                                        <label class="form-label">Email Address</label>
                                        <div class="input-wrapper">
                                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                                <polyline points="22,6 12,13 2,6" />
                                            </svg>
                                            <input type="email" name="email" class="form-input" placeholder="citizen@example.com" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <div class="input-wrapper">
                                            <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                            </svg>
                                            <input type="password" name="password" id="loginPassword" class="form-input" placeholder="Enter your password" required>

                                            <button type="button" class="toggle-password" data-target="loginPassword" tabindex="-1">
                                                <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="alert alert-info mb-3">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" y1="16" x2="12" y2="12" />
                                            <line x1="12" y1="8" x2="12.01" y2="8" />
                                        </svg>
                                        <p>Your session will be secured with government-grade encryption. Only authorized personnel can access your information.</p>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-full btn-large">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                            <polyline points="10 17 15 12 10 7" />
                                            <line x1="15" y1="12" x2="3" y2="12" />
                                        </svg>
                                        Sign In to Portal
                                    </button>
                                </form>
                            </div>

                            <!-- Register Form -->
                            <div id="registerTab" class="tab-content">
                                <div class="card-header">
                                    <h2 class="card-title">Create Account</h2>
                                    <p class="card-description">Register to access Philippine legislative information and services</p>
                                </div>

                                <form id="registerForm" onsubmit="handleRegister(event)">
                                    <!-- backend reads this -->
                                    <input type="hidden" name="registerType" id="registerType" value="staff" />

                                    <!-- Step 1: Basic Information -->
                                    <div class="form-step">
                                        <div class="step-title">Step 1 · Basic Information</div>

                                        <div class="grid grid-2">
                                            <div class="form-group">
                                                <label class="form-label">First Name</label>
                                                <div class="input-wrapper">
                                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                        <circle cx="12" cy="7" r="4" />
                                                    </svg>
                                                    <input type="text" name="firstName" class="form-input" placeholder="Juan" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Last Name</label>
                                                <div class="input-wrapper">
                                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                        <circle cx="12" cy="7" r="4" />
                                                    </svg>
                                                    <input type="text" name="lastName" class="form-input" placeholder="Dela Cruz" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Email Address</label>
                                            <div class="input-wrapper">
                                                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                                    <polyline points="22,6 12,13 2,6" />
                                                </svg>
                                                <input type="email" name="email" id="regEmail" class="form-input" placeholder="citizen@example.com" required>
                                            </div>
                                            <small id="emailFeedback" class="validation-message"></small>
                                        </div>
                                    </div>

                                    <!-- Step 2: Region -->
                                    <div class="form-step">
                                        <div class="step-title">Step 2 · Location</div>

                                        <div class="form-group">
                                            <label class="form-label">Region</label>
                                            <select name="region" id="regionSelect" class="form-select" required>
                                                <option value="">Select region</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Province</label>
                                            <select name="province" id="provinceSelect" class="form-select" required>
                                                <option value="">Select province</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">City / Municipality</label>
                                            <select name="city" id="citySelect" class="form-select" required>
                                                <option value="">Select city/municipality</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">Barangay</label>
                                            <select name="barangay" id="barangaySelect" class="form-select" required>
                                                <option value="">Select barangay</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Step 3: Security -->
                                    <div class="form-step">
                                        <div class="step-title">Step 3 · Security</div>
                                        <div class="grid grid-2">
                                            <div class="form-group">
                                                <label class="form-label">Password</label>
                                                <div class="input-wrapper">
                                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                                    </svg>
                                                    <input type="password" name="password" id="regPassword" class="form-input" placeholder="Create password" required minlength="8">
                                                    <button type="button" class="toggle-password" data-target="regPassword" tabindex="-1">
                                                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="strength-meter">
                                                    <div id="strengthBar" class="strength-bar"></div>
                                                </div>
                                                <small id="strengthText" class="strength-text">Password strength</small>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Confirm Password</label>
                                                <div class="input-wrapper">
                                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                                    </svg>
                                                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-input" placeholder="Confirm password" required minlength="8">
                                                    <button type="button" class="toggle-password" data-target="confirmPassword" tabindex="-1">
                                                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <small id="matchFeedback" class="validation-message"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 4: Government Role -->
                                    <div class="form-step">
                                        <div class="step-title">Step 4 · Government Role</div>

                                        <div id="govRoleBox" class="gov-role-box">
                                            <fieldset class="role-radio-group" id="govRoleGroup">
                                                <legend class="form-label">Select Government Role</legend>

                                                <label class="role-radio">
                                                    <input type="radio" name="registerType" value="staff" checked />
                                                    <span>Staff</span>
                                                </label>

                                                <label class="role-radio">
                                                    <input type="radio" name="registerType" value="councilor" />
                                                    <span>Councilor</span>
                                                </label>

                                                <label class="role-radio">
                                                    <input type="radio" name="registerType" value="mayor" />
                                                    <span>Mayor</span>
                                                </label>
                                            </fieldset>

                                            <label class="checkbox-row mt-2">
                                                <input type="checkbox" id="govAttestation" />
                                                <span>I certify I’m authorized to access government systems under my chosen role.</span>
                                            </label>
                                        </div>

                                    </div>

                                    <br>

                                    <div class="alert alert-warning mb-3">
                                        <p><strong>Data Privacy Notice:</strong> Your information is protected under the Philippine Data Privacy Act of 2012. We will only use your data for official legislative services.</p>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-full btn-large" disabled>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="8.5" cy="7" r="4" />
                                            <line x1="20" y1="8" x2="20" y2="14" />
                                            <line x1="23" y1="11" x2="17" y2="11" />
                                        </svg>
                                        Create Account
                                    </button>
                                </form>
                            </div>

                        </div>

                        <!-- Benefits Sidebar -->
                        <?php include __DIR__ . '../../pages/benefitPortalSidebar.php'; ?>

                    </div>
                </div>
            </div>

        </div>

        <div class="mt-auto">
            <?php include __DIR__ . '../../partials/footer.php'; ?>
        </div>

    </div>

</body>

</html>