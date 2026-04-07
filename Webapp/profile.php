<?php
require_once 'includes/session.php';
require_once 'includes/database-connection.php';
require_login($logged_in);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Research Group DB — Dashboard</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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
      padding: 16px 16px 40px;
      color: #000;
    }

    /* ── IE Window ── */
    .ie-window {
      width: 900px;
      max-width: 100%;
      background: #c0c0c0;
      border-top: 2px solid #fff;
      border-left: 2px solid #fff;
      border-right: 2px solid #404040;
      border-bottom: 2px solid #404040;
      box-shadow: 2px 2px 0 #000;
    }

    /* ── Title bar ── */
    .title-bar {
      background: linear-gradient(90deg, #000080, #1084d0);
      padding: 3px 4px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .title-bar-text {
      color: #fff;
      font-weight: bold;
      font-size: 11px;
      display: flex;
      align-items: center;
      gap: 6px;
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
      font-weight: bold;
      color: #000;
      text-decoration: none;
    }
    .win-btn:active {
      border-top: 1px solid #808080;
      border-left: 1px solid #808080;
      border-right: 1px solid #fff;
      border-bottom: 1px solid #fff;
    }

    /* ── IE Menu bar ── */
    .menu-bar {
      background: #c0c0c0;
      padding: 2px 4px;
      display: flex;
      gap: 2px;
      border-bottom: 1px solid #808080;
    }

    .menu-item {
      padding: 2px 6px;
      font-size: 11px;
      cursor: pointer;
    }
    .menu-item:hover {
      background: #000080;
      color: #fff;
    }

    /* ── IE Toolbar ── */
    .toolbar {
      background: #c0c0c0;
      padding: 3px 4px;
      display: flex;
      align-items: center;
      gap: 4px;
      border-bottom: 1px solid #808080;
    }

    .toolbar-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1px;
      padding: 2px 6px;
      font-size: 10px;
      cursor: pointer;
      border: 1px solid transparent;
      background: none;
      font-family: inherit;
      min-width: 40px;
    }
    .toolbar-btn:hover {
      border-top: 1px solid #fff;
      border-left: 1px solid #fff;
      border-right: 1px solid #808080;
      border-bottom: 1px solid #808080;
    }
    .toolbar-btn:active {
      border-top: 1px solid #808080;
      border-left: 1px solid #808080;
      border-right: 1px solid #fff;
      border-bottom: 1px solid #fff;
    }

    .toolbar-icon { font-size: 16px; line-height: 1; }

    .toolbar-sep {
      width: 1px;
      height: 32px;
      background: #808080;
      border-right: 1px solid #fff;
      margin: 0 2px;
    }

    /* ── Address bar ── */
    .address-bar {
      background: #c0c0c0;
      padding: 2px 4px;
      display: flex;
      align-items: center;
      gap: 4px;
      border-bottom: 2px solid #808080;
    }

    .address-label {
      font-size: 11px;
      white-space: nowrap;
    }

    .address-input {
      flex: 1;
      height: 20px;
      background: #fff;
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
      padding: 0 4px;
      font-family: inherit;
      font-size: 11px;
      color: #000080;
    }

    .go-btn {
      padding: 1px 8px;
      height: 20px;
      background: #c0c0c0;
      border-top: 1px solid #fff;
      border-left: 1px solid #fff;
      border-right: 1px solid #808080;
      border-bottom: 1px solid #808080;
      font-family: inherit;
      font-size: 11px;
      cursor: pointer;
    }

    /* ── IE Content area ── */
    .ie-content {
      background: #fff;
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
      margin: 4px;
      min-height: 480px;
      padding: 16px;
      overflow-y: auto;
    }

    /* ── Page header inside IE ── */
    .page-header {
      background: linear-gradient(90deg, #000080, #1084d0);
      color: #fff;
      padding: 10px 16px;
      margin: -16px -16px 16px -16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .page-header h1 {
      font-size: 16px;
      font-weight: bold;
      font-family: "Times New Roman", serif;
    }

    .page-header-right {
      font-size: 11px;
      text-align: right;
      line-height: 1.6;
    }

    /* ── Welcome banner ── */
    .welcome-banner {
      border: 2px solid #000080;
      padding: 8px 12px;
      margin-bottom: 16px;
      background: #ffffcc;
      font-size: 11px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* ── Section panels ── */
    .panel-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-bottom: 16px;
    }

    .panel {
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
    }

    .panel-header {
      background: #000080;
      color: #fff;
      padding: 3px 8px;
      font-weight: bold;
      font-size: 11px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .panel-body {
      padding: 8px;
      background: #c0c0c0;
    }

    .panel-row {
      display: flex;
      justify-content: space-between;
      padding: 2px 0;
      border-bottom: 1px dotted #808080;
      font-size: 11px;
    }
    .panel-row:last-child { border-bottom: none; }

    .panel-link {
      color: #000080;
      text-decoration: underline;
      cursor: pointer;
      font-size: 11px;
    }
    .panel-link:hover { color: #ff0000; }

    /* ── Quick links ── */
    .quick-links {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 16px;
    }

    .quick-link-btn {
      padding: 3px 12px;
      background: #c0c0c0;
      border-top: 2px solid #fff;
      border-left: 2px solid #fff;
      border-right: 2px solid #808080;
      border-bottom: 2px solid #808080;
      font-family: inherit;
      font-size: 11px;
      cursor: pointer;
      text-decoration: none;
      color: #000;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .quick-link-btn:active {
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
    }

    /* ── Status bar ── */
    .ie-status {
      border-top: 1px solid #808080;
      padding: 2px 6px;
      display: flex;
      align-items: center;
      gap: 4px;
      background: #c0c0c0;
    }

    .status-panel {
      border-top: 1px solid #808080;
      border-left: 1px solid #808080;
      border-right: 1px solid #fff;
      border-bottom: 1px solid #fff;
      padding: 1px 6px;
      font-size: 10px;
    }

    .status-globe {
      margin-left: auto;
      font-size: 16px;
    }

    /* ── Taskbar ── */
    .taskbar {
      position: fixed;
      bottom: 0; left: 0; right: 0;
      height: 28px;
      background: #c0c0c0;
      border-top: 2px solid #fff;
      display: flex;
      align-items: center;
      padding: 0 4px;
      gap: 4px;
      z-index: 100;
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

    .start-logo {
      width: 16px; height: 16px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1px;
    }
    .start-logo span { display: block; }
    .start-logo span:nth-child(1) { background: #ff0000; }
    .start-logo span:nth-child(2) { background: #00aa00; }
    .start-logo span:nth-child(3) { background: #0000ff; }
    .start-logo span:nth-child(4) { background: #ffaa00; }

    .taskbar-active {
      background: #c0c0c0;
      border-top: 2px solid #808080;
      border-left: 2px solid #808080;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
      padding: 2px 10px;
      font-size: 11px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

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

    /* ── Desktop icons ── */
    .desktop-icons {
      position: fixed;
      top: 10px; left: 10px;
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
      width: 32px; height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
    }
    .icon-label {
      font-size: 10px;
      color: #fff;
      text-align: center;
      text-shadow: 1px 1px 1px #000;
      padding: 1px 2px;
      line-height: 1.2;
    }

    .divider {
      border: none;
      border-top: 1px solid #808080;
      border-bottom: 1px solid #fff;
      margin: 10px 0;
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

  <!-- IE Window -->
  <div class="ie-window">

    <!-- Title bar -->
    <div class="title-bar">
      <div class="title-bar-text">
        🔬 Research Group Database — Microsoft Internet Explorer
      </div>
      <div class="title-bar-controls">
        <div class="win-btn">_</div>
        <div class="win-btn">□</div>
        <a href="logout.php" class="win-btn" title="Log Out">✕</a>
      </div>
    </div>

    <!-- Menu bar -->
    <div class="menu-bar">
      <div class="menu-item">File</div>
      <div class="menu-item">Edit</div>
      <div class="menu-item">View</div>
      <div class="menu-item">Favorites</div>
      <div class="menu-item">Tools</div>
      <div class="menu-item">Help</div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
      <button class="toolbar-btn" onclick="history.back()">
        <span class="toolbar-icon">◀</span>Back
      </button>
      <button class="toolbar-btn" onclick="history.forward()">
        <span class="toolbar-icon">▶</span>Forward
      </button>
      <button class="toolbar-btn" onclick="location.reload()">
        <span class="toolbar-icon">🔄</span>Refresh
      </button>
      <button class="toolbar-btn" onclick="window.location='profile.php'">
        <span class="toolbar-icon">🏠</span>Home
      </button>
      <div class="toolbar-sep"></div>
      <button class="toolbar-btn">
        <span class="toolbar-icon">⭐</span>Favorites
      </button>
      <button class="toolbar-btn">
        <span class="toolbar-icon">🖨️</span>Print
      </button>
      <div class="toolbar-sep"></div>
      <a href="logout.php" class="toolbar-btn" style="text-decoration:none;color:#000;">
        <span class="toolbar-icon">🔒</span>Log Out
      </a>
    </div>

    <!-- Address bar -->
    <div class="address-bar">
      <span class="address-label">Address</span>
      <input class="address-input" type="text" 
             value="http://localhost/researchdb/profile.php" readonly>
      <button class="go-btn">Go</button>
    </div>

    <!-- Page content -->
    <div class="ie-content">

      <!-- Page header -->
      <div class="page-header">
        <h1>🔬 Research Group Database</h1>
        <div class="page-header-right">
          Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong><br>
          Role: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>
        </div>
      </div>

      <!-- Welcome banner -->
      <div class="welcome-banner">
        💡 <span>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>. 
        You are logged in as <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>.</span>
      </div>

      <!-- Quick links -->
      <div class="quick-links">
        <a class="quick-link-btn" href="#">🧪 Experiments</a>
        <a class="quick-link-btn" href="#">📁 Projects</a>
        <a class="quick-link-btn" href="#">👥 Members</a>
        <a class="quick-link-btn" href="#">📚 Literature</a>
        <a class="quick-link-btn" href="#">✅ Tasks</a>
        <a class="quick-link-btn" href="#">🏛️ Departments</a>
      </div>

      <hr class="divider">

      <!-- Panel grid -->
      <div class="panel-grid">

        <div class="panel">
          <div class="panel-header">📁 Active Projects</div>
          <div class="panel-body">
            <div class="panel-row">
              <span class="panel-link">CRISPR Gene Editing Study</span>
              <span>Active</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Quantum Error Correction</span>
              <span>Active</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Amyloid Fibril Structure</span>
              <span>Active</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">CAR-T Cell Optimization</span>
              <span>Active</span>
            </div>
            <div class="panel-row" style="padding-top:6px;">
              <a class="panel-link" href="#">View all projects »</a>
            </div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header">Recent Tasks</div>
          <div class="panel-body">
            <div class="panel-row">
              <span class="panel-link">Design sgRNA sequences</span>
              <span>✔ Done</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Train CNN baseline model</span>
              <span>✔ Done</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Prepare neuron staining</span>
              <span>⏳ 60%</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Align optical components</span>
              <span>⏳ 45%</span>
            </div>
            <div class="panel-row" style="padding-top:6px;">
              <a class="panel-link" href="#">View all tasks »</a>
            </div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header">Recent Experiments</div>
          <div class="panel-body">
            <div class="panel-row">
              <span class="panel-link">CRISPR Transfection Trial 1</span>
              <span>Completed</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Qubit Coherence Time Test</span>
              <span>Completed</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Fibril Cryo-EM Prep</span>
              <span>Completed</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">EEG Language Switch</span>
              <span>Completed</span>
            </div>
            <div class="panel-row" style="padding-top:6px;">
              <a class="panel-link" href="#">View all experiments »</a>
            </div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header">Recent Literature</div>
          <div class="panel-body">
            <div class="panel-row">
              <span class="panel-link">CRISPR-Cas9 Genome Editing</span>
              <span>2012</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Quantum Supremacy</span>
              <span>2019</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">Cryo-EM Amyloid Fibrils</span>
              <span>2017</span>
            </div>
            <div class="panel-row">
              <span class="panel-link">CAR-T Cell Remissions</span>
              <span>2014</span>
            </div>
            <div class="panel-row" style="padding-top:6px;">
              <a class="panel-link" href="#">View all literature »</a>
            </div>
          </div>
        </div>

      </div>

    </div>

    <!-- IE Status bar -->
    <div class="ie-status">
      <div class="status-panel">✔ Done</div>
      <div class="status-panel">research_group_db @ localhost</div>
      <div class="status-panel" style="margin-left:auto;">🌐 Local intranet</div>
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
    <div class="taskbar-active">
      🔬 Research Group Database — Microsoft Internet Explorer
    </div>
    <div class="taskbar-clock" id="clock">12:00 PM</div>
  </div>

  <script>
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