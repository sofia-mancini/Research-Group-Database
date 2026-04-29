<?php
require_once "includes/session.php";
require_once "includes/database-connection.php";
require_once "includes/auth.php";

require_login($logged_in);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header('Location: tasks.php'); exit; }

$task = pdo($pdo, "SELECT * FROM task WHERE task_id = :id", ['id' => $id])->fetch();
if (!$task) { header('Location: tasks.php'); exit; }

$assignedPeople = pdo($pdo,
    "SELECT p.ID, p.name, p.email, p.role, ta.assigned_date
     FROM person p
     JOIN task_assignment ta ON p.ID = ta.person_id
     WHERE ta.task_id = :id
     ORDER BY p.name",
    ['id' => $id]
)->fetchAll();

$project = pdo($pdo,
    "SELECT p.project_id, p.title, p.status
     FROM project p
     JOIN project_tasks pt ON p.project_id = pt.project_id
     WHERE pt.task_id = :id",
    ['id' => $id]
)->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($task['title']); ?> — Research Group DB</title>
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
    .quick-links { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
    .quick-link-btn { padding: 3px 12px; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #808080; border-bottom: 2px solid #808080; font-family: inherit; font-size: 11px; cursor: pointer; text-decoration: none; color: #000; display: flex; align-items: center; gap: 4px; }
    .quick-link-btn:active { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; }
    .divider { border: none; border-top: 1px solid #808080; border-bottom: 1px solid #fff; margin: 10px 0; }
    .toolbar-action { padding: 3px 10px; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #808080; border-bottom: 2px solid #808080; font-family: inherit; font-size: 11px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; color: #000; margin-bottom: 10px; }
    .status-badge { padding: 1px 6px; font-size: 10px; border: 1px solid; }
    .status-active { background: #ccffcc; border-color: #009900; color: #006600; }
    .status-completed { background: #ccccff; border-color: #000080; color: #000080; }
    .status-pending { background: #ffffcc; border-color: #999900; color: #666600; }
    .status-cancelled { background: #ffcccc; border-color: #990000; color: #660000; }
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
    .main-panel { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; margin-bottom: 12px; }
    .main-panel-header { background: linear-gradient(90deg, #000080, #1084d0); color: #fff; padding: 4px 10px; font-weight: bold; font-size: 12px; display: flex; align-items: center; gap: 6px; }
    .details-section { background: #c0c0c0; padding: 10px 12px; border-bottom: 2px solid #808080; }
    .details-table { border-collapse: collapse; width: 100%; }
    .details-table td { padding: 4px 10px; vertical-align: top; font-size: 12px; }
    .details-table td.label { font-weight: bold; color: #000080; white-space: nowrap; width: 110px; }
    .subtables-row { display: flex; background: #c0c0c0; }
    .subtable-col { flex: 1; border-right: 2px solid #808080; display: flex; flex-direction: column; }
    .subtable-col:last-child { border-right: none; }
    .subtable-header { background: #404080; color: #fff; padding: 3px 8px; font-weight: bold; font-size: 11px; border-bottom: 1px solid #222260; }
    .subtable-body { overflow-y: auto; max-height: 220px; }
    .subtable-body table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .subtable-body th { background: #5a5a8a; color: #fff; padding: 3px 6px; text-align: left; font-size: 11px; position: sticky; top: 0; }
    .subtable-body td { padding: 4px 6px; border-bottom: 1px solid #b0b0b0; vertical-align: middle; }
    .subtable-body tr:nth-child(even) td { background: #d8d8e8; }
    .subtable-body tr:hover td { background: #c8d0e8; }
    .subtable-body a { color: #000080; text-decoration: underline; }
    .subtable-body a:hover { color: #ff0000; }
    .empty-notice { color: #555; font-style: italic; padding: 8px 6px; font-size: 11px; }
    .progress-bar-outer { background: #fff; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; height: 12px; width: 80px; display: inline-block; vertical-align: middle; }
    .progress-bar-inner { background: linear-gradient(90deg, #fff 0%, #1084d0 60%, #000080 100%); height: 100%; }
    /* Single-column project panel */
    .project-panel { background: #c0c0c0; padding: 10px 12px; }
    .project-card { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; padding: 8px 12px; background: #d4d4d4; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .project-card a { color: #000080; text-decoration: underline; font-size: 12px; font-weight: bold; }
    .project-card a:hover { color: #ff0000; }
  </style>
</head>
<body>

  <div class="ie-window">
    <div class="title-bar">
      <div class="title-bar-text">✅ <?php echo htmlspecialchars($task['title']); ?> — Research Group Database — Microsoft Internet Explorer</div>
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
      <input class="address-input" type="text" value="http://localhost/researchdb/task_view.php?id=<?php echo $id; ?>" readonly>
      <button class="go-btn">Go</button>
    </div>

    <div class="ie-content">
      <div class="page-header">
        <h1>✅ <?php echo htmlspecialchars($task['title']); ?></h1>
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
        <a class="quick-link-btn" href="tasks.php" style="border-top:2px solid #808080;border-left:2px solid #808080;border-right:2px solid #fff;border-bottom:2px solid #fff;">✅ Tasks</a>
        <a class="quick-link-btn" href="departments.php">🏛️ Departments</a>
      </div>

      <hr class="divider">

      <div style="display:flex;gap:8px;margin-bottom:12px;">
        <a class="toolbar-action" href="tasks.php">◀ Back to Tasks</a>
        <?php if (can_edit_task($_SESSION['role'], $_SESSION['personID'], $id, $pdo)): ?>
          <a class="toolbar-action" href="task_edit.php?id=<?php echo $id; ?>">✏️ Edit Task</a>
        <?php endif; ?>
        <?php if (can_delete($_SESSION['role'])): ?>
          <a class="toolbar-action" href="task_delete.php?id=<?php echo $id; ?>"
             onclick="return confirm('Delete this task?')" style="color:#990000;">🗑️ Delete</a>
        <?php endif; ?>
      </div>

      <div class="main-panel">
        <div class="main-panel-header">✅ Task Details</div>

        <div class="details-section">
          <?php
            $sc = match(strtolower($task['status'] ?? '')) {
              'completed'   => 'status-completed',
              'in progress' => 'status-active',
              'not started' => 'status-pending',
              'cancelled'   => 'status-cancelled',
              default       => ''
            };
            $prog = (int)($task['progress'] ?? 0);
          ?>
          <table class="details-table">
            <tr>
              <td class="label">Status:</td>
              <td><span class="status-badge <?php echo $sc; ?>"><?php echo htmlspecialchars($task['status'] ?? '—'); ?></span></td>
              <td class="label">Due Date:</td>
              <td><?php echo htmlspecialchars($task['due_date'] ?? '—'); ?></td>
              <td class="label">Completed:</td>
              <td><?php echo htmlspecialchars($task['completed_date'] ?? '—'); ?></td>
            </tr>
            <tr>
              <td class="label">Progress:</td>
              <td colspan="5">
                <div class="progress-bar-outer" style="width:120px;">
                  <div class="progress-bar-inner" style="width:<?php echo $prog; ?>%"></div>
                </div>
                <span style="margin-left:5px;font-size:11px;"><?php echo $prog; ?>%</span>
              </td>
            </tr>
            <?php if (!empty($task['description'])): ?>
            <tr>
              <td class="label">Description:</td>
              <td colspan="5"><?php echo htmlspecialchars($task['description']); ?></td>
            </tr>
            <?php endif; ?>
          </table>
        </div>

        <!-- Project strip (single record) -->
        <?php if ($project): ?>
        <div class="subtable-header" style="background:#404080;color:#fff;padding:3px 8px;font-weight:bold;font-size:11px;border-bottom:1px solid #222260;">
          📁 Parent Project
        </div>
        <div class="project-panel">
          <div class="project-card">
            <?php
              $ppsc = match(strtolower($project['status'])) {
                'active'    => 'status-active',
                'completed' => 'status-completed',
                'pending'   => 'status-pending',
                'cancelled' => 'status-cancelled',
                default     => ''
              };
            ?>
            <a href="project_view.php?id=<?php echo $project['project_id']; ?>">📁 <?php echo htmlspecialchars($project['title']); ?></a>
            <span class="status-badge <?php echo $ppsc; ?>"><?php echo htmlspecialchars($project['status']); ?></span>
          </div>
        </div>
        <?php endif; ?>

        <!-- Assigned People -->
        <div class="subtables-row" style="border-top:2px solid #808080;">
          <div class="subtable-col" style="border-right:none;">
            <div class="subtable-header">👥 Assigned People</div>
            <div class="subtable-body">
              <?php if (empty($assignedPeople)): ?>
                <p class="empty-notice">No people assigned.</p>
              <?php else: ?>
              <table>
                <thead><tr><th>Name</th><th>Role</th><th>Assigned Date</th></tr></thead>
                <tbody>
                  <?php foreach ($assignedPeople as $p): ?>
                  <tr>
                    <td>
                      <?php if (can_edit_person($_SESSION['role'])): ?>
                        <a href="member_edit.php?id=<?php echo $p['ID']; ?>"><?php echo htmlspecialchars($p['name']); ?></a>
                      <?php else: ?>
                        <a href="member_view.php?id=<?php echo $p['ID']; ?>"><?php echo htmlspecialchars($p['name']); ?></a>
                      <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($p['role']); ?></td>
                    <td><?php echo htmlspecialchars($p['assigned_date'] ?? '—'); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <?php endif; ?>
            </div>
          </div>
        </div><!-- /subtables-row -->

      </div><!-- /main-panel -->

    </div><!-- /ie-content -->

    <div class="ie-status">
      <div class="status-panel">✔ Done</div>
      <div class="status-panel">research_group_db @ localhost</div>
      <div class="status-panel" style="margin-left:auto;">🌐 Local intranet</div>
    </div>
  </div>

  <div class="taskbar">
    <button class="start-btn"><div class="start-logo"><span></span><span></span><span></span><span></span></div>Start</button>
    <div class="taskbar-active">✅ <?php echo htmlspecialchars($task['title']); ?> — Research Group Database</div>
    <div class="taskbar-clock" id="clock">12:00 PM</div>
  </div>

  <script>
    function updateClock() { const now = new Date(); let h = now.getHours(), m = now.getMinutes(); const ampm = h >= 12 ? 'PM' : 'AM'; h = h % 12 || 12; document.getElementById('clock').textContent = h + ':' + String(m).padStart(2, '0') + ' ' + ampm; }
    updateClock(); setInterval(updateClock, 1000);
  </script>
</body>
</html>
