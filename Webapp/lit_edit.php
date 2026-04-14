<?php
require_once 'includes/session.php';
require_once 'includes/database-connection.php';
require_once 'includes/auth.php';
require_login($logged_in);

// Check edit permission
if (!can_edit_literature($_SESSION['role'])) {
    header('Location: access_denied.php');
    exit;
}

// Validate ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
   ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: literature.php');
    exit;
}

// Fetch existing record
$lit = pdo($pdo, "SELECT * FROM literature WHERE lit_id = :id", ['id' => $id])->fetch();

if (!$lit) {
    header('Location: literature.php');
    exit;
}

$error = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title'] ?? '');
    $year    = trim($_POST['year'] ?? '');
    $journal = trim($_POST['journal'] ?? '') ?: null;
    $doi     = trim($_POST['doi'] ?? '') ?: null;
    $url     = trim($_POST['url'] ?? '') ?: null;
    $theory  = trim($_POST['theory'] ?? '') ?: null;

    if (empty($title)) {
        $error = 'Title is required.';
    } elseif (!preg_match('/^\d{4}$/', $year) || $year < 1900 || $year > 2100) {
        $error = 'Year must be a valid 4-digit year between 1900 and 2100.';
    } elseif ($doi && !str_starts_with($doi, '10.')) {
        $error = 'DOI must start with "10."';
    } elseif ($url && !str_starts_with($url, 'http')) {
        $error = 'URL must start with "http".';
    } else {
        pdo($pdo,
            "UPDATE literature SET title=:title, year=:year, journal=:journal,
             doi=:doi, url=:url, theory=:theory
             WHERE lit_id=:id",
            [
                'title'   => $title,
                'year'    => $year,
                'journal' => $journal,
                'doi'     => $doi,
                'url'     => $url,
                'theory'  => $theory,
                'id'      => $id,
            ]
        );
        header('Location: literature.php?updated=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Literature — Research Group DB</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: "MS Sans Serif", "Microsoft Sans Serif", Tahoma, sans-serif;
      font-size: 11px; background-color: #008080;
      background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.03) 0px, rgba(0,0,0,0.03) 1px, transparent 1px, transparent 4px);
      min-height: 100vh; display: flex; flex-direction: column;
      align-items: center; padding: 16px 16px 40px; color: #000;
    }
    .ie-window { width: 700px; max-width: 100%; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #404040; border-bottom: 2px solid #404040; box-shadow: 2px 2px 0 #000; }
    .title-bar { background: linear-gradient(90deg, #000080, #1084d0); padding: 3px 4px; display: flex; align-items: center; justify-content: space-between; }
    .title-bar-text { color: #fff; font-weight: bold; font-size: 11px; }
    .title-bar-controls { display: flex; gap: 2px; }
    .win-btn { width: 16px; height: 14px; background: #c0c0c0; border-top: 1px solid #fff; border-left: 1px solid #fff; border-right: 1px solid #808080; border-bottom: 1px solid #808080; font-size: 9px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold; color: #000; text-decoration: none; }
    .toolbar { background: #c0c0c0; padding: 3px 4px; display: flex; align-items: center; gap: 4px; border-bottom: 1px solid #808080; }
    .toolbar-btn { display: flex; flex-direction: column; align-items: center; gap: 1px; padding: 2px 6px; font-size: 10px; cursor: pointer; border: 1px solid transparent; background: none; font-family: inherit; min-width: 40px; text-decoration: none; color: #000; }
    .toolbar-btn:hover { border-top: 1px solid #fff; border-left: 1px solid #fff; border-right: 1px solid #808080; border-bottom: 1px solid #808080; }
    .toolbar-icon { font-size: 16px; line-height: 1; }
    .address-bar { background: #c0c0c0; padding: 2px 4px; display: flex; align-items: center; gap: 4px; border-bottom: 2px solid #808080; }
    .address-label { font-size: 11px; white-space: nowrap; }
    .address-input { flex: 1; height: 20px; background: #fff; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; padding: 0 4px; font-family: inherit; font-size: 11px; color: #000080; }
    .go-btn { padding: 1px 8px; height: 20px; background: #c0c0c0; border-top: 1px solid #fff; border-left: 1px solid #fff; border-right: 1px solid #808080; border-bottom: 1px solid #808080; font-family: inherit; font-size: 11px; cursor: pointer; }
    .ie-content { background: #fff; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; margin: 4px; padding: 16px; }
    .page-header { background: linear-gradient(90deg, #000080, #1084d0); color: #fff; padding: 10px 16px; margin: -16px -16px 16px -16px; display: flex; align-items: center; justify-content: space-between; }
    .page-header h1 { font-size: 16px; font-weight: bold; font-family: "Times New Roman", serif; }
    .page-header-right { font-size: 11px; text-align: right; line-height: 1.6; }
    .divider { border: none; border-top: 1px solid #808080; border-bottom: 1px solid #fff; margin: 10px 0; }
    .form-panel { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; padding: 12px; margin-bottom: 12px; background: #c0c0c0; }
    .form-panel-title { font-weight: bold; margin-bottom: 10px; padding-bottom: 4px; border-bottom: 1px solid #808080; }
    .form-row { display: flex; align-items: flex-start; margin-bottom: 8px; gap: 8px; }
    .form-row label { width: 100px; flex-shrink: 0; padding-top: 2px; font-size: 11px; }
    .form-row input[type="text"],
    .form-row input[type="number"],
    .form-row textarea {
      flex: 1; background: #fff;
      border-top: 2px solid #808080; border-left: 2px solid #808080;
      border-right: 2px solid #fff; border-bottom: 2px solid #fff;
      padding: 2px 4px; font-family: inherit; font-size: 11px; outline: none;
    }
    .form-row textarea { height: 60px; resize: vertical; }
    .form-row input:focus, .form-row textarea:focus { outline: 1px dotted #000; outline-offset: -2px; }
    .field-hint { font-size: 10px; color: #808080; margin-top: 2px; }
    .btn-row { display: flex; gap: 6px; }
    .win98-btn {
      min-width: 80px; height: 23px; background: #c0c0c0;
      border-top: 2px solid #fff; border-left: 2px solid #fff;
      border-right: 2px solid #808080; border-bottom: 2px solid #808080;
      font-family: inherit; font-size: 11px; cursor: pointer;
      text-decoration: none; color: #000;
      display: flex; align-items: center; justify-content: center;
    }
    .win98-btn:active { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; }
    .win98-btn.default { outline: 1px solid #000; outline-offset: -4px; }
    .banner { display: flex; align-items: flex-start; gap: 10px; padding: 8px 10px; margin-bottom: 12px; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; font-size: 11px; }
    .banner-icon { font-size: 18px; flex-shrink: 0; }
    .banner.error { background: #ffcccc; }
    .ie-status { border-top: 1px solid #808080; padding: 2px 6px; display: flex; align-items: center; gap: 4px; background: #c0c0c0; }
    .status-panel { border-top: 1px solid #808080; border-left: 1px solid #808080; border-right: 1px solid #fff; border-bottom: 1px solid #fff; padding: 1px 6px; font-size: 10px; }
    .taskbar { position: fixed; bottom: 0; left: 0; right: 0; height: 28px; background: #c0c0c0; border-top: 2px solid #fff; display: flex; align-items: center; padding: 0 4px; gap: 4px; z-index: 100; }
    .start-btn { display: flex; align-items: center; gap: 4px; padding: 2px 6px; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #808080; border-bottom: 2px solid #808080; font-weight: bold; font-size: 11px; cursor: pointer; font-family: inherit; }
    .start-logo { width: 16px; height: 16px; display: grid; grid-template-columns: 1fr 1fr; gap: 1px; }
    .start-logo span { display: block; }
    .start-logo span:nth-child(1) { background: #ff0000; }
    .start-logo span:nth-child(2) { background: #00aa00; }
    .start-logo span:nth-child(3) { background: #0000ff; }
    .start-logo span:nth-child(4) { background: #ffaa00; }
    .taskbar-active { background: #c0c0c0; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; padding: 2px 10px; font-size: 11px; display: flex; align-items: center; gap: 4px; }
    .taskbar-clock { margin-left: auto; padding: 2px 8px; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; font-size: 11px; min-width: 60px; text-align: center; }
  </style>
</head>
<body>

  <div class="ie-window">
    <div class="title-bar">
      <div class="title-bar-text">✏️ Edit Literature — Research Group Database — Microsoft Internet Explorer</div>
      <div class="title-bar-controls">
        <div class="win-btn">_</div>
        <div class="win-btn">□</div>
        <a href="literature.php" class="win-btn">✕</a>
      </div>
    </div>

    <div class="toolbar">
      <button class="toolbar-btn" onclick="history.back()"><span class="toolbar-icon">◀</span>Back</button>
      <button class="toolbar-btn" onclick="location.reload()"><span class="toolbar-icon">🔄</span>Refresh</button>
      <button class="toolbar-btn" onclick="window.location='profile.php'"><span class="toolbar-icon">🏠</span>Home</button>
      <a href="logout.php" class="toolbar-btn"><span class="toolbar-icon">🔒</span>Log Out</a>
    </div>

    <div class="address-bar">
      <span class="address-label">Address</span>
      <input class="address-input" type="text" value="http://localhost/researchdb/literature_edit.php?id=<?php echo $id; ?>" readonly>
      <button class="go-btn">Go</button>
    </div>

    <div class="ie-content">
      <div class="page-header">
        <h1>✏️ Edit Literature #<?php echo $id; ?></h1>
        <div class="page-header-right">
          Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong><br>
          Role: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>
        </div>
      </div>

      <?php if ($error): ?>
      <div class="banner error">
        <span class="banner-icon">!</span>
        <div><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></div>
      </div>
      <?php endif; ?>

      <form method="POST" action="lit_edit.php?id=<?php echo $id; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-panel">
          <div class="form-panel-title">Literature Details</div>

          <div class="form-row">
            <label for="title">Title: *</label>
            <input type="text" id="title" name="title" required
                   value="<?php echo htmlspecialchars($lit['title']); ?>">
          </div>

          <div class="form-row">
            <label for="year">Year: *</label>
            <input type="number" id="year" name="year" min="1900" max="2100" required
                   value="<?php echo htmlspecialchars($lit['year']); ?>">
          </div>

          <div class="form-row">
            <label for="journal">Journal:</label>
            <input type="text" id="journal" name="journal"
                   value="<?php echo htmlspecialchars($lit['journal'] ?? ''); ?>">
          </div>

          <div class="form-row">
            <label for="doi">DOI:</label>
            <div style="flex:1;">
              <input type="text" id="doi" name="doi" style="width:100%;"
                     value="<?php echo htmlspecialchars($lit['doi'] ?? ''); ?>">
              <div class="field-hint">Must start with "10." e.g. 10.1126/science.1225829</div>
            </div>
          </div>

          <div class="form-row">
            <label for="url">URL:</label>
            <div style="flex:1;">
              <input type="text" id="url" name="url" style="width:100%;"
                     value="<?php echo htmlspecialchars($lit['url'] ?? ''); ?>">
              <div class="field-hint">Must start with "http"</div>
            </div>
          </div>

          <div class="form-row">
            <label for="theory">Theory:</label>
            <textarea id="theory" name="theory"><?php echo htmlspecialchars($lit['theory'] ?? ''); ?></textarea>
          </div>

        </div>

        <hr class="divider">

        <div class="btn-row">
          <button type="submit" class="win98-btn default">💾 Save</button>
          <a href="literature.php" class="win98-btn">Cancel</a>
        </div>

      </form>
    </div>

    <div class="ie-status">
      <div class="status-panel">Done</div>
      <div class="status-panel">research_group_db @ localhost</div>
      <div class="status-panel" style="margin-left:auto;">🌐 Local intranet</div>
    </div>
  </div>

  <div class="taskbar">
    <button class="start-btn">
      <div class="start-logo"><span></span><span></span><span></span><span></span></div>
      Start
    </button>
    <div class="taskbar-active">✏️ Edit Literature: Research Group Database</div>
    <div class="taskbar-clock" id="clock">12:00 PM</div>
  </div>

  <script>
    function updateClock() {
      const now = new Date();
      let h = now.getHours(), m = now.getMinutes();
      const ampm = h >= 12 ? 'PM' : 'AM';
      h = h % 12 || 12;
      document.getElementById('clock').textContent = h + ':' + String(m).padStart(2, '0') + ' ' + ampm;
    }
    updateClock();
    setInterval(updateClock, 1000);
  </script>
</body>
</html>