<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
  require_once 'includes/session.php';
  require_once 'includes/database-connection.php';
  if ($logged_in) {
    header('Location: profile.php');
    exit;
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = authenticate($pdo, $username, $password);
    if ($user) {
      login($user);
      header('Location: profile.php');
      exit;
    }
  }
  include 'includes/header.php';
?>

<!-- HTML framework to build the UI -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Research Group DB — Login</title>
  <style>
    /* Reset */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    /* Styling after Windows 98 */
    body {
      font-family: "MS Sans Serif", "Microsoft Sans Serif", Tahoma, sans-serif;
      font-size: 11px;
      background-color: #008080;
      background-image:
        repeating-linear-gradient(
          45deg,
          rgba(0,0,0,0.03) 0px,
          rgba(0,0,0,0.03) 1px,
          transparent 1px,
          transparent 4px
        );
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #000;
    }

    /* ── Taskbar ── */
    .taskbar {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 28px;
      background: #c0c0c0;
      border-top: 2px solid #fff;
      display: flex;
      align-items: center;
      padding: 0 4px;
      gap: 4px;
      z-index: 100;
      box-shadow: inset 0 1px 0 #dfdfdf;
    }

    .start-btn {
      display: flex;
      align-items: center;
      gap: 4px;
      padding: 2px 6px;
      background: #c0c0c0;
      border-top: 2px solid #fff;
      border-left: 2px solid #fff;
      border-right: 2px solid #808080;
      border-bottom: 2px solid #808080;
      font-weight: bold;
      font-size: 11px;
      cursor: pointer;
      font-family: inherit;
    }
    .start-btn:active {
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
    }

    /* Trademark issues? IDK */
    .start-logo {
      width: 16px;
      height: 16px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1px;
    }
    .start-logo span { display: block; }
    .start-logo span:nth-child(1) { background: #ff0000; }
    .start-logo span:nth-child(2) { background: #00aa00; }
    .start-logo span:nth-child(3) { background: #0000ff; }
    .start-logo span:nth-child(4) { background: #ffaa00; }

    /* Should actually be accurate */
    .taskbar-clock {
      margin-left: auto;
      padding: 2px 8px;
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
      font-size: 11px;
      min-width: 60px;
      text-align: center;
    }

    /* ── Window container ── */
    .window {
      width: 340px;
      background: #c0c0c0;
      border-top: 2px solid #fff;
      border-left: 2px solid #fff;
      border-right: 2px solid #404040;
      border-bottom: 2px solid #404040;
      box-shadow: 2px 2px 0 #000;
      margin-bottom: 36px;
    }

    /* ── Title bar ── */
    .title-bar {
      background: linear-gradient(90deg, #000080, #1084d0);
      padding: 3px 4px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 4px;
    }

    .title-bar-text {
      color: #fff;
      font-weight: bold;
      font-size: 11px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .title-icon {
      width: 14px;
      height: 14px;
      background: #ffdd00;
      border: 1px solid #aa8800;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 9px;
    }

    .title-bar-controls {
      display: flex;
      gap: 2px;
    }

    .win-btn {
      width: 16px;
      height: 14px;
      background: #c0c0c0;
      border-top: 1px solid #fff;
      border-left: 1px solid #fff;
      border-right: 1px solid #808080;
      border-bottom: 1px solid #808080;
      font-size: 9px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-family: "Marlett", "Wingdings", sans-serif;
      font-weight: bold;
      line-height: 1;
      color: #000;
    }
    .win-btn.close { color: #000; }
    .win-btn:active {
      border-top: 1px solid #808080;
      border-left: 1px solid #808080;
      border-right: 1px solid #fff;
      border-bottom: 1px solid #fff;
    }

    /* ── Window body ── */
    .window-body {
      padding: 12px;
    }

    /* ── Inset panel (sunken box) ── */
    .inset-panel {
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
      padding: 10px 12px;
      margin-bottom: 12px;
      background: #c0c0c0;
    }

    .panel-title {
      font-size: 11px;
      font-weight: bold;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .panel-title::before {
      content: '';
      display: inline-block;
      width: 16px;
      height: 16px;
      background: #000080;
      border: 1px solid #808080;
      flex-shrink: 0;
    }

    /* ── Form fields ── */
    .form-row {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
      gap: 6px;
    }

    .form-row label {
      width: 80px;
      font-size: 11px;
      flex-shrink: 0;
    }

    .form-row input[type="text"],
    .form-row input[type="password"] {
      flex: 1;
      height: 21px;
      background: #fff;
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #dfdfdf;
      border-bottom: 2px solid #dfdfdf;
      padding: 0 4px;
      font-family: inherit;
      font-size: 11px;
      outline: none;
    }
    .form-row input[type="text"]:focus,
    .form-row input[type="password"]:focus {
      outline: 1px dotted #000;
      outline-offset: -3px;
    }

    /* ── Buttons ── */
    .btn-row {
      display: flex;
      justify-content: flex-end;
      gap: 6px;
      margin-top: 4px;
    }

    .win98-btn {
      min-width: 75px;
      height: 23px;
      background: #c0c0c0;
      border-top: 2px solid #fff;
      border-left: 2px solid #fff;
      border-right: 2px solid #808080;
      border-bottom: 2px solid #808080;
      font-family: inherit;
      font-size: 11px;
      cursor: pointer;
      padding: 0 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .win98-btn:active {
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
      padding-top: 2px;
      padding-left: 10px;
    }
    .win98-btn.default {
      outline: 1px solid #000;
      outline-offset: -4px;
    }

    /* ── Status bar ── */
    .status-bar {
      border-top: 1px solid #808080;
      padding: 2px 6px;
      display: flex;
      gap: 4px;
    }

    .status-panel {
      flex: 1;
      border-top: 1px solid #808080;
      border-left: 1px solid #808080;
      border-right: 1px solid #fff;
      border-bottom: 1px solid #fff;
      padding: 1px 4px;
      font-size: 10px;
      color: #000;
    }

    /* Error Handling (hopefully) */
    .error-box {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      background: #c0c0c0;
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
      padding: 8px 10px;
      margin-bottom: 10px;
      font-size: 11px;
    }

    .error-icon {
      width: 32px;
      height: 32px;
      flex-shrink: 0;
      background: #c0c0c0;
      border: 2px solid #000;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      font-weight: bold;
      color: #ff0000;
      font-family: "Times New Roman", serif;
    }

    /* Desktop icons behind window */
    .desktop-icons {
      position: fixed;
      top: 10px;
      left: 10px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .desktop-icon {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2px;
      width: 64px;
      cursor: pointer;
      padding: 2px;
    }
    .desktop-icon:hover .icon-label {
      background: #000080;
      color: #fff;
    }

    .icon-img {
      width: 32px;
      height: 32px;
      background: #c0c0c0;
      border: 1px solid #808080;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    .icon-label {
      font-size: 10px;
      color: #fff;
      text-align: center;
      text-shadow: 1px 1px 1px #000;
      padding: 1px 2px;
      line-height: 1.2;
    }

    /* Divider */
    .divider {
      border: none;
      border-top: 1px solid #808080;
      border-bottom: 1px solid #fff;
      margin: 8px 0;
    }
  </style>
</head>
<body>

  <!-- Desktop icons -->
  <div class="desktop-icons">
    <div class="desktop-icon">
      <div class="icon-img">🖥️</div>
      <div class="icon-label">My Computer</div>
    </div>
    <div class="desktop-icon">
      <div class="icon-img">🗑️</div>
      <div class="icon-label">Recycle Bin</div>
    </div>
    <div class="desktop-icon">
      <div class="icon-img">🔬</div>
      <div class="icon-label">Research DB</div>
    </div>
  </div>

  <!-- Login Window -->
  <div class="window">

    <!-- Title bar -->
    <div class="title-bar">
      <div class="title-bar-text">
        <span class="title-icon">🔬</span>
        Research Group Database — Log In
      </div>
      <div class="title-bar-controls">
        <div class="win-btn">_</div>
        <div class="win-btn">□</div>
        <div class="win-btn close">✕</div>
      </div>
    </div>

    <!-- Window body -->
    <div class="window-body">

      <?php if (isset($_GET['registered'])): ?>
      <div class="error-box" style="background:#ccffcc;">
        <div class="error-icon" style="color:#006600;">✔</div>
        <div><strong>Account created.</strong><br>You can now log in with your credentials.</div>
      </div>
      <?php endif; ?>

      <?php if (isset($login_error)): ?>
      <div class="error-box">
        <div class="error-icon">!</div>
        <div>
          <strong>Login Failed</strong><br>
          The username or password you entered is incorrect. Please try again.
        </div>
      </div>
      <?php endif; ?>

      <div class="inset-panel">
        <div class="panel-title">Enter Network Credentials</div>

        <form method="POST" action="login.php">
          <div class="form-row">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required autocomplete="username">
          </div>
          <div class="form-row">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
          </div>

          <hr class="divider">

          <div style="font-size:10px;margin-bottom:8px;text-align:center;">
              New user? <a href="register.php" style="color:#000080;">Create an account</a>
          </div>

          <div class="btn-row">
              <button type="submit" class="win98-btn default">OK</button>
              <button type="reset" class="win98-btn">Cancel</button>
          </div>
        </form>
      </div>

    </div>

    <!-- Status bar -->
    <div class="status-bar">
      <div class="status-panel">research_group_db @ localhost</div>
      <div class="status-panel" style="flex:0;min-width:80px;text-align:center;">🔒 Secure</div>
    </div>

  </div>

  <!-- Taskbar -->
  <div class="taskbar">
    <button class="start-btn">
      <div class="start-logo">
        <span></span><span></span><span></span><span></span>
      </div>
      Start
    </button>
    <div style="
      background:#c0c0c0;
      border-top:2px solid #808080;
      border-left:2px solid #808080;
      border-right:2px solid #fff;
      border-bottom:2px solid #fff;
      padding:2px 10px;
      font-size:11px;
      display:flex;
      align-items:center;
      gap:4px;
    ">🔬 Research Group Database</div>
    <div class="taskbar-clock" id="clock">12:00 PM</div>
  </div>

  <script>
    // Live clock
    function updateClock() {
      const now = new Date();
      let h = now.getHours(), m = now.getMinutes();
      const ampm = h >= 12 ? 'PM' : 'AM';
      h = h % 12 || 12;
      document.getElementById('clock').textContent =
        h + ':' + String(m).padStart(2, '0') + ' ' + ampm;
    }
    updateClock();
    setInterval(updateClock, 1000);
  </script>

</body>
</html>