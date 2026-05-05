<?php
require_once "includes/session.php";
require_once "includes/database-connection.php";
require_once "includes/auth.php";
require_login($logged_in);

$q        = trim($_GET['q'] ?? '');
$searched = array_key_exists('q', $_GET);

$projects = $tasks = $members = $experiments = $literature = [];

if ($searched && $q !== '') {
    $term = '%' . $q . '%';
    $projects    = pdo($pdo, "SELECT * FROM project    WHERE title LIKE :t1 OR description LIKE :t2", ['t1' => $term, 't2' => $term])->fetchAll();
    $tasks       = pdo($pdo, "SELECT * FROM task       WHERE title LIKE :t1 OR description LIKE :t2", ['t1' => $term, 't2' => $term])->fetchAll();
    $members     = pdo($pdo, "SELECT * FROM Person     WHERE name  LIKE :t1 OR email       LIKE :t2", ['t1' => $term, 't2' => $term])->fetchAll();
    $experiments = pdo($pdo, "SELECT * FROM experiment WHERE title LIKE :t1 OR objective  LIKE :t2", ['t1' => $term, 't2' => $term])->fetchAll();
    $literature  = pdo($pdo, "SELECT * FROM literature WHERE title LIKE :t1 OR journal    LIKE :t2", ['t1' => $term, 't2' => $term])->fetchAll();
}

$urlQ       = ($searched && $q !== '') ? '?q=' . urlencode($q) : '';
$addressUrl = 'http://localhost/researchdb/search.php' . $urlQ;

function sc(string $status): string {
    return match(strtolower($status)) {
        'active', 'in progress'              => 'status-active',
        'completed'                          => 'status-completed',
        'pending', 'not started', 'on hold'  => 'status-pending',
        'cancelled'                          => 'status-cancelled',
        default                              => '',
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search — Research Group DB</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: "MS Sans Serif", "Microsoft Sans Serif", Tahoma, sans-serif; font-size: 11px; background-color: #008080; background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.03) 0px, rgba(0,0,0,0.03) 1px, transparent 1px, transparent 4px); min-height: 100vh; display: flex; flex-direction: column; align-items: center; padding: 16px 16px 40px; color: #000; }
    .ie-window { width: 960px; max-width: 100%; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #404040; border-bottom: 2px solid #404040; box-shadow: 2px 2px 0 #000; }
    .title-bar { background: linear-gradient(90deg, #000080, #1084d0); padding: 3px 4px; display: flex; align-items: center; justify-content: space-between; }
    .title-bar-text { color: #fff; font-weight: bold; font-size: 11px; display: flex; align-items: center; gap: 6px; }
    .title-bar-controls { display: flex; gap: 2px; }
    .win-btn { width: 16px; height: 14px; background: #c0c0c0; border-top: 1px solid #fff; border-left: 1px solid #fff; border-right: 1px solid #808080; border-bottom: 1px solid #808080; font-size: 9px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold; color: #000; text-decoration: none; }
    .menu-bar { background: #c0c0c0; padding: 2px 4px; display: flex; gap: 2px; border-bottom: 1px solid #808080; }
    .menu-item { padding: 2px 6px; font-size: 11px; cursor: pointer; }
    .menu-item:hover { background: #000080; color: #fff; }
    .toolbar { background: #c0c0c0; padding: 3px 4px; display: flex; align-items: center; gap: 4px; border-bottom: 1px solid #808080; }
    .toolbar-btn { display: flex; flex-direction: column; align-items: center; gap: 1px; padding: 2px 6px; font-size: 10px; cursor: pointer; border: 1px solid transparent; background: none; font-family: inherit; min-width: 40px; }
    .toolbar-btn:hover { border-top: 1px solid #fff; border-left: 1px solid #fff; border-right: 1px solid #808080; border-bottom: 1px solid #808080; }
    .toolbar-icon { font-size: 16px; line-height: 1; }
    .toolbar-sep { width: 1px; height: 32px; background: #808080; border-right: 1px solid #fff; margin: 0 2px; }
    .address-bar { background: #c0c0c0; padding: 2px 4px; display: flex; align-items: center; gap: 4px; border-bottom: 2px solid #808080; }
    .address-label { font-size: 11px; white-space: nowrap; }
    .address-input { flex: 1; height: 20px; background: #fff; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; padding: 0 4px; font-family: inherit; font-size: 11px; color: #000080; }
    .go-btn { padding: 1px 8px; height: 20px; background: #c0c0c0; border-top: 1px solid #fff; border-left: 1px solid #fff; border-right: 1px solid #808080; border-bottom: 1px solid #808080; font-family: inherit; font-size: 11px; cursor: pointer; }
    .ie-content { background: #fff; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; margin: 4px; min-height: 480px; padding: 16px; overflow-y: auto; }
    .page-header { background: linear-gradient(90deg, #000080, #1084d0); color: #fff; padding: 10px 16px; margin: -16px -16px 16px -16px; display: flex; align-items: center; justify-content: space-between; }
    .page-header h1 { font-size: 16px; font-weight: bold; font-family: "Times New Roman", serif; }
    .page-header-right { font-size: 11px; text-align: right; line-height: 1.6; }
    .quick-links { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; align-items: center; }
    .quick-link-btn { padding: 3px 12px; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #808080; border-bottom: 2px solid #808080; font-family: inherit; font-size: 11px; cursor: pointer; text-decoration: none; color: #000; display: flex; align-items: center; gap: 4px; }
    .quick-link-btn:active { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; }
    .divider { border: none; border-top: 1px solid #808080; border-bottom: 1px solid #fff; margin: 10px 0; }
    .toolbar-action { padding: 3px 10px; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #808080; border-bottom: 2px solid #808080; font-family: inherit; font-size: 11px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; color: #000; border-style: solid; }
    .status-badge { padding: 1px 6px; font-size: 10px; border: 1px solid; }
    .status-active { background: #ccffcc; border-color: #009900; color: #006600; }
    .status-completed { background: #ccccff; border-color: #000080; color: #000080; }
    .status-pending { background: #ffffcc; border-color: #999900; color: #666600; }
    .status-cancelled { background: #ffcccc; border-color: #990000; color: #660000; }
    .data-table { width: 100%; border-collapse: collapse; font-size: 11px; }
    .data-table th { background: #000080; color: #fff; padding: 4px 8px; text-align: left; border: 1px solid #000060; }
    .data-table td { padding: 3px 8px; border: 1px solid #c0c0c0; }
    .data-table tr:nth-child(even) td { background: #f0f0f0; }
    .data-table tr:hover td { background: #d0d8f0; }
    .data-table a { color: #000080; text-decoration: underline; cursor: pointer; }
    .data-table a:hover { color: #ff0000; }
    .progress-bar-outer { width: 80px; height: 12px; background: #fff; border-top: 1px solid #808080; border-left: 1px solid #808080; border-right: 1px solid #fff; border-bottom: 1px solid #fff; display: inline-block; vertical-align: middle; }
    .progress-bar-inner { height: 100%; background: #000080; }
    .result-section { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; margin-bottom: 12px; }
    .section-hdr { background: linear-gradient(90deg, #000080, #1084d0); color: #fff; padding: 4px 10px; display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none; font-weight: bold; font-size: 12px; }
    .section-hdr:hover { background: linear-gradient(90deg, #0000a0, #2094e0); }
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
      <div class="title-bar-text">🔍 Search — Research Group Database — Microsoft Internet Explorer</div>
      <div class="title-bar-controls">
        <div class="win-btn">_</div>
        <div class="win-btn">□</div>
        <a href="logout.php" class="win-btn" title="Log Out">✕</a>
      </div>
    </div>

    <div class="menu-bar">
      <div class="menu-item">File</div><div class="menu-item">Edit</div><div class="menu-item">View</div><div class="menu-item">Favorites</div><div class="menu-item">Tools</div><div class="menu-item">Help</div>
    </div>

    <div class="toolbar">
      <button class="toolbar-btn" onclick="history.back()"><span class="toolbar-icon">◀</span>Back</button>
      <button class="toolbar-btn" onclick="history.forward()"><span class="toolbar-icon">▶</span>Forward</button>
      <button class="toolbar-btn" onclick="location.reload()"><span class="toolbar-icon">🔄</span>Refresh</button>
      <button class="toolbar-btn" onclick="window.location='profile.php'"><span class="toolbar-icon">🏠</span>Home</button>
      <div class="toolbar-sep"></div>
      <a href="logout.php" class="toolbar-btn" style="text-decoration:none;color:#000;"><span class="toolbar-icon">🔒</span>Log Out</a>
    </div>

    <div class="address-bar">
      <span class="address-label">Address</span>
      <input class="address-input" type="text" value="<?php echo htmlspecialchars($addressUrl); ?>" readonly>
      <button class="go-btn">Go</button>
    </div>

    <div class="ie-content">
      <div class="page-header">
        <h1>🔍 Search</h1>
        <div class="page-header-right">
          Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong><br>
          Role: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>
        </div>
      </div>

      <div class="quick-links">
        <a class="quick-link-btn" href="experiments.php">🧪 Experiments</a>
        <a class="quick-link-btn" href="projects.php">📁 Projects</a>
        <a class="quick-link-btn" href="members.php">👥 Members</a>
        <a class="quick-link-btn" href="literature.php">📚 Literature</a>
        <a class="quick-link-btn" href="tasks.php">✅ Tasks</a>
        <a class="quick-link-btn" href="departments.php">🏛️ Departments</a>
        <a class="quick-link-btn" href="search.php" style="border-top:2px solid #808080;border-left:2px solid #808080;border-right:2px solid #fff;border-bottom:2px solid #fff;">🔍 Search</a>
        <form action="search.php" method="GET" style="margin-left:auto;display:flex;align-items:center;gap:4px;">
          <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search..."
                 style="height:20px;background:#fff;border-top:2px solid #808080;border-left:2px solid #808080;border-right:2px solid #fff;border-bottom:2px solid #fff;padding:0 4px;font-family:inherit;font-size:11px;width:130px;">
          <button type="submit" style="padding:1px 6px;height:20px;background:#c0c0c0;border-top:1px solid #fff;border-left:1px solid #fff;border-right:1px solid #808080;border-bottom:1px solid #808080;font-family:inherit;font-size:11px;cursor:pointer;">🔍</button>
        </form>
      </div>

      <hr class="divider">

      <div style="margin-bottom:16px;">
        <form action="search.php" method="GET" style="display:flex;gap:6px;align-items:center;">
          <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>"
                 placeholder="Search projects, tasks, experiments, literature..."
                 style="flex:1;height:24px;background:#fff;border-top:2px solid #808080;border-left:2px solid #808080;border-right:2px solid #fff;border-bottom:2px solid #fff;padding:0 8px;font-family:inherit;font-size:12px;">
          <button type="submit" class="toolbar-action">🔍 Search</button>
        </form>
      </div>

      <?php if (!$searched || $q === ''): ?>

        <div style="text-align:center;padding:40px 0;color:#555;font-size:14px;">
          🔍 Enter a search term above to search across all research data.
        </div>

      <?php elseif (empty($projects) && empty($tasks) && empty($members) && empty($experiments) && empty($literature)): ?>

        <div style="text-align:center;padding:40px 0;color:#555;font-size:13px;">
          No results found for &ldquo;<?php echo htmlspecialchars($q); ?>&rdquo;.
        </div>

      <?php else: ?>

        <?php
          $pCnt = count($projects);    $pOpen = $pCnt > 0;
          $tCnt = count($tasks);       $tOpen = $tCnt > 0;
          $mCnt = count($members);     $mOpen = $mCnt > 0;
          $eCnt = count($experiments); $eOpen = $eCnt > 0;
          $lCnt = count($literature);  $lOpen = $lCnt > 0;
        ?>

        <!-- Projects -->
        <div class="result-section">
          <div class="section-hdr" onclick="toggleSection('projects')">
            <span>📁 Projects (<?php echo $pCnt; ?> <?php echo $pCnt === 1 ? 'result' : 'results'; ?>)</span>
            <span id="arrow-projects"><?php echo $pOpen ? '▲' : '▼'; ?></span>
          </div>
          <div id="body-projects"<?php echo $pOpen ? '' : ' style="display:none"'; ?>>
            <?php if ($pCnt > 0): ?>
            <table class="data-table">
              <thead><tr><th>Title</th><th>Status</th><th>Start Date</th></tr></thead>
              <tbody>
                <?php foreach ($projects as $p): ?>
                <tr>
                  <td><a href="project_view.php?id=<?php echo $p['project_id']; ?>"><?php echo htmlspecialchars($p['title']); ?></a></td>
                  <td><span class="status-badge <?php echo sc($p['status']); ?>"><?php echo htmlspecialchars($p['status']); ?></span></td>
                  <td><?php echo htmlspecialchars($p['start_date'] ?? '—'); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </div>
        </div>

        <!-- Tasks -->
        <div class="result-section">
          <div class="section-hdr" onclick="toggleSection('tasks')">
            <span>✅ Tasks (<?php echo $tCnt; ?> <?php echo $tCnt === 1 ? 'result' : 'results'; ?>)</span>
            <span id="arrow-tasks"><?php echo $tOpen ? '▲' : '▼'; ?></span>
          </div>
          <div id="body-tasks"<?php echo $tOpen ? '' : ' style="display:none"'; ?>>
            <?php if ($tCnt > 0): ?>
            <table class="data-table">
              <thead><tr><th>Title</th><th>Status</th><th>Progress</th></tr></thead>
              <tbody>
                <?php foreach ($tasks as $t):
                  $prog = (int)($t['progress'] ?? 0); ?>
                <tr>
                  <td><a href="task_view.php?id=<?php echo $t['task_id']; ?>"><?php echo htmlspecialchars($t['title']); ?></a></td>
                  <td><span class="status-badge <?php echo sc($t['status']); ?>"><?php echo htmlspecialchars($t['status']); ?></span></td>
                  <td>
                    <div class="progress-bar-outer"><div class="progress-bar-inner" style="width:<?php echo $prog; ?>%"></div></div>
                    <span style="margin-left:3px;font-size:10px;"><?php echo $prog; ?>%</span>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </div>
        </div>

        <!-- Members -->
        <div class="result-section">
          <div class="section-hdr" onclick="toggleSection('members')">
            <span>👤 Members (<?php echo $mCnt; ?> <?php echo $mCnt === 1 ? 'result' : 'results'; ?>)</span>
            <span id="arrow-members"><?php echo $mOpen ? '▲' : '▼'; ?></span>
          </div>
          <div id="body-members"<?php echo $mOpen ? '' : ' style="display:none"'; ?>>
            <?php if ($mCnt > 0): ?>
            <table class="data-table">
              <thead><tr><th>Name</th><th>Email</th><th>Role</th></tr></thead>
              <tbody>
                <?php foreach ($members as $m): ?>
                <tr>
                  <td><a href="member_view.php?id=<?php echo $m['ID']; ?>"><?php echo htmlspecialchars($m['name']); ?></a></td>
                  <td><?php echo htmlspecialchars($m['email'] ?? '—'); ?></td>
                  <td><?php echo htmlspecialchars($m['role'] ?? '—'); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </div>
        </div>

        <!-- Experiments -->
        <div class="result-section">
          <div class="section-hdr" onclick="toggleSection('experiments')">
            <span>🧪 Experiments (<?php echo $eCnt; ?> <?php echo $eCnt === 1 ? 'result' : 'results'; ?>)</span>
            <span id="arrow-experiments"><?php echo $eOpen ? '▲' : '▼'; ?></span>
          </div>
          <div id="body-experiments"<?php echo $eOpen ? '' : ' style="display:none"'; ?>>
            <?php if ($eCnt > 0): ?>
            <table class="data-table">
              <thead><tr><th>Title</th><th>Status</th><th>Start Date</th></tr></thead>
              <tbody>
                <?php foreach ($experiments as $e): ?>
                <tr>
                  <td><a href="experiment_view.php?id=<?php echo $e['experiment_id']; ?>"><?php echo htmlspecialchars($e['title']); ?></a></td>
                  <td><span class="status-badge <?php echo sc($e['status']); ?>"><?php echo htmlspecialchars($e['status']); ?></span></td>
                  <td><?php echo htmlspecialchars($e['start_date'] ?? '—'); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </div>
        </div>

        <!-- Literature -->
        <div class="result-section">
          <div class="section-hdr" onclick="toggleSection('literature')">
            <span>📚 Literature (<?php echo $lCnt; ?> <?php echo $lCnt === 1 ? 'result' : 'results'; ?>)</span>
            <span id="arrow-literature"><?php echo $lOpen ? '▲' : '▼'; ?></span>
          </div>
          <div id="body-literature"<?php echo $lOpen ? '' : ' style="display:none"'; ?>>
            <?php if ($lCnt > 0): ?>
            <table class="data-table">
              <thead><tr><th>Title</th><th>Journal</th><th>Year</th></tr></thead>
              <tbody>
                <?php foreach ($literature as $l): ?>
                <tr>
                  <td><a href="literature_view.php?id=<?php echo $l['lit_id']; ?>"><?php echo htmlspecialchars($l['title']); ?></a></td>
                  <td><?php echo htmlspecialchars($l['journal'] ?? '—'); ?></td>
                  <td><?php echo htmlspecialchars($l['year'] ?? '—'); ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php endif; ?>
          </div>
        </div>

      <?php endif; ?>

    </div><!-- /ie-content -->

    <div class="ie-status">
      <div class="status-panel">✔ Done</div>
      <div class="status-panel">research_group_db @ localhost</div>
      <div class="status-panel" style="margin-left:auto;">🌐 Local intranet</div>
    </div>
  </div>

  <div class="taskbar">
    <button class="start-btn"><div class="start-logo"><span></span><span></span><span></span><span></span></div>Start</button>
    <div class="taskbar-active">🔍 Search — Research Group Database</div>
    <div class="taskbar-clock" id="clock">12:00 PM</div>
  </div>

  <script>
    function toggleSection(id) {
      const body  = document.getElementById('body-'  + id);
      const arrow = document.getElementById('arrow-' + id);
      if (body.style.display === 'none') {
        body.style.display = '';
        arrow.textContent  = '▲';
      } else {
        body.style.display = 'none';
        arrow.textContent  = '▼';
      }
    }
    function updateClock() { const now = new Date(); let h = now.getHours(), m = now.getMinutes(); const ampm = h >= 12 ? 'PM' : 'AM'; h = h % 12 || 12; document.getElementById('clock').textContent = h + ':' + String(m).padStart(2, '0') + ' ' + ampm; }
    updateClock(); setInterval(updateClock, 1000);
  </script>
</body>
</html>
