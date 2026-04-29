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
     FROM Person p
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
    body { font-family: "MS Sans Serif", "Microsoft Sans Serif", Tahoma, sans-serif; font-size: 11px; background-color: #008080; min-height: 100vh; display: flex; flex-direction: column; align-items: center; padding: 16px 16px 40px; color: #000; }
    .ie-window { width: 960px; max-width: 100%; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #404040; border-bottom: 2px solid #404040; box-shadow: 2px 2px 0 #000; }
    .title-bar { background: linear-gradient(90deg, #000080, #1084d0); padding: 3px 4px; display: flex; align-items: center; justify-content: space-between; }
    .title-bar-text { color: #fff; font-weight: bold; font-size: 11px; display: flex; align-items: center; gap: 6px; }
    .title-bar-controls { display: flex; gap: 2px; }
    .win-btn { width: 16px; height: 14px; background: #c0c0c0; border-top: 1px solid #fff; border-left: 1px solid #fff; border-right: 1px solid #808080; border-bottom: 1px solid #808080; font-size: 9px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold; color: #000; text-decoration: none; }
    .ie-content { background: #fff; border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; margin: 4px; min-height: 480px; padding: 16px; overflow-y: auto; }
    .page-header { background: linear-gradient(90deg, #000080, #1084d0); color: #fff; padding: 10px 16px; margin: -16px -16px 16px -16px; display: flex; align-items: center; justify-content: space-between; }
    .toolbar-action { padding: 3px 10px; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #808080; border-bottom: 2px solid #808080; font-family: inherit; font-size: 11px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; color: #000; margin-bottom: 10px; }
    .status-badge { padding: 1px 6px; font-size: 10px; border: 1px solid; }
    .status-active { background: #ccffcc; border-color: #009900; color: #006600; }
    .status-completed { background: #ccccff; border-color: #000080; color: #000080; }
    .status-pending { background: #ffffcc; border-color: #999900; color: #666600; }
    .status-cancelled { background: #ffcccc; border-color: #990000; color: #660000; }
    .main-panel { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; margin-bottom: 12px; }
    .main-panel-header { background: linear-gradient(90deg, #000080, #1084d0); color: #fff; padding: 4px 10px; font-weight: bold; font-size: 12px; }
    .details-table td { padding: 4px 10px; vertical-align: top; font-size: 12px; }
    .label { font-weight: bold; color: #000080; }
    .progress-bar-outer { background: #fff; border: 2px inset #808080; height: 12px; width: 120px; display: inline-block; }
    .progress-bar-inner { background: #000080; height: 100%; }
    .subtable-header { background: #404080; color: #fff; padding: 3px 8px; font-weight: bold; font-size: 11px; }
  </style>
</head>
<body>

  <div class="ie-window">
    <div class="title-bar">
      <div class="title-bar-text">✅ <?php echo htmlspecialchars($task['title']); ?> — Research Group Database</div>
      <div class="title-bar-controls">
        <div class="win-btn">_</div><div class="win-btn">□</div><a href="logout.php" class="win-btn">✕</a>
      </div>
    </div>

    <div class="ie-content">
      <div class="page-header">
        <h1>✅ <?php echo htmlspecialchars($task['title']); ?></h1>
        <div style="font-size:11px; text-align:right;">
          Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong><br>
          Role: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>
        </div>
      </div>

      <div style="display:flex;gap:8px;margin-bottom:12px;">
        <a class="toolbar-action" href="tasks.php">◀ Back to Tasks</a>
        
        <?php /* Fix: Standardized to person_id */ ?>
        <?php if (can_edit_task($_SESSION['role'], $_SESSION['person_id'], $id, $pdo)): ?>
          <a class="toolbar-action" href="task_edit.php?id=<?php echo $id; ?>">✏️ Edit Task</a>
        <?php endif; ?>

        <?php if (can_delete($_SESSION['role'])): ?>
          <a class="toolbar-action" href="task_delete.php?id=<?php echo $id; ?>" onclick="return confirm('Delete this task?')" style="color:#990000;">🗑️ Delete</a>
        <?php endif; ?>
      </div>

      <div class="main-panel">
        <div class="main-panel-header">✅ Task Details</div>
        <div style="background:#c0c0c0; padding:12px;">
          <?php
            $sc = match(strtolower($task['status'] ?? '')) {
              'completed'   => 'status-completed',
              'in progress' => 'status-active',
              'not started' => 'status-pending',
              default       => 'status-cancelled'
            };
            $prog = (int)($task['progress'] ?? 0);
          ?>
          <table style="width:100%; border-collapse:collapse;">
            <tr>
              <td class="label">Status:</td>
              <td><span class="status-badge <?php echo $sc; ?>"><?php echo htmlspecialchars($task['status'] ?? '—'); ?></span></td>
              <td class="label">Due Date:</td>
              <td><?php echo htmlspecialchars($task['due_date'] ?? '—'); ?></td>
            </tr>
            <tr>
              <td class="label">Progress:</td>
              <td colspan="3">
                <div class="progress-bar-outer"><div class="progress-bar-inner" style="width:<?php echo $prog; ?>%"></div></div>
                <span style="margin-left:5px;"><?php echo $prog; ?>%</span>
              </td>
            </tr>
          </table>
        </div>

        <?php if ($project): ?>
          <div class="subtable-header">📁 Parent Project</div>
          <div style="padding:8px; background:#d4d4d4;">
            <a href="project_view.php?id=<?php echo $project['project_id']; ?>" style="color:#000080; font-weight:bold;">
              <?php echo htmlspecialchars($project['title']); ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>