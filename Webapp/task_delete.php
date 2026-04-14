<?php
require_once 'includes/session.php';
require_once 'includes/database-connection.php';
require_once 'includes/auth.php';
require_login($logged_in);

// Only admins can delete
if (!can_delete($_SESSION['role'])) {
    header('Location: access_denied.php');
    exit;
}

// Validate ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
   ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: tasks.php');
    exit;
}

// Fetch record
$task = pdo($pdo, "SELECT * FROM task WHERE task_id = :id", ['id' => $id])->fetch();

if (!$task) {
    header('Location: tasks.php');
    exit;
}

// Fetch associated project for display
$project = pdo($pdo,
    "SELECT p.title FROM project p
     JOIN project_tasks pt ON p.project_id = pt.project_id
     WHERE pt.task_id = :id",
    ['id' => $id]
)->fetch();

// Handle confirmed deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Remove junction table rows first
    pdo($pdo, "DELETE FROM project_tasks WHERE task_id = :id",   ['id' => $id]);
    pdo($pdo, "DELETE FROM task_assignment WHERE task_id = :id", ['id' => $id]);
    pdo($pdo, "DELETE FROM task WHERE task_id = :id",            ['id' => $id]);
    header('Location: tasks.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Task: Research Group DB</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: "MS Sans Serif", "Microsoft Sans Serif", Tahoma, sans-serif;
      font-size: 11px; background-color: #008080;
      background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.03) 0px, rgba(0,0,0,0.03) 1px, transparent 1px, transparent 4px);
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
    }
    .window {
      width: 420px; background: #c0c0c0;
      border-top: 2px solid #fff; border-left: 2px solid #fff;
      border-right: 2px solid #404040; border-bottom: 2px solid #404040;
      box-shadow: 2px 2px 0 #000;
    }
    .title-bar { background: linear-gradient(90deg, #000080, #1084d0); padding: 3px 4px; display: flex; align-items: center; justify-content: space-between; }
    .title-bar-text { color: #fff; font-weight: bold; font-size: 11px; }
    .title-bar-controls { display: flex; gap: 2px; }
    .win-btn { width: 16px; height: 14px; background: #c0c0c0; border-top: 1px solid #fff; border-left: 1px solid #fff; border-right: 1px solid #808080; border-bottom: 1px solid #808080; font-size: 9px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold; color: #000; text-decoration: none; }
    .window-body { padding: 16px; }
    .dialog-row { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px; }
    .dialog-icon { width: 40px; height: 40px; flex-shrink: 0; border: 2px solid #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; color: #ff0000; font-family: "Times New Roman", serif; background: #c0c0c0; }
    .dialog-text { font-size: 11px; line-height: 1.7; }
    .dialog-text strong { color: #000080; }
    .record-preview { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; padding: 6px 10px; background: #fff; margin-bottom: 14px; font-size: 11px; line-height: 1.6; }
    .record-preview .label { color: #808080; }
    .progress-bar-bg { height: 10px; border-top: 1px solid #808080; border-left: 1px solid #808080; border-right: 1px solid #fff; border-bottom: 1px solid #fff; background: #fff; overflow: hidden; margin-top: 3px; }
    .progress-bar-fill { height: 100%; background: #000080; }
    .divider { border: none; border-top: 1px solid #808080; border-bottom: 1px solid #fff; margin: 10px 0; }
    .btn-row { display: flex; justify-content: flex-end; gap: 6px; }
    .win98-btn { min-width: 80px; height: 23px; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #808080; border-bottom: 2px solid #808080; font-family: inherit; font-size: 11px; cursor: pointer; text-decoration: none; color: #000; display: flex; align-items: center; justify-content: center; }
    .win98-btn:active { border-top: 2px solid #808080; border-left: 2px solid #808080; border-right: 2px solid #fff; border-bottom: 2px solid #fff; }
    .win98-btn.danger { outline: 1px solid #000; outline-offset: -4px; }
  </style>
</head>
<body>

  <div class="window">
    <div class="title-bar">
      <div class="title-bar-text">Confirm Deletion: Research Group DB</div>
      <div class="title-bar-controls">
        <a href="tasks.php" class="win-btn">✕</a>
      </div>
    </div>

    <div class="window-body">
      <div class="dialog-row">
        <div class="dialog-icon">!</div>
        <div class="dialog-text">
          Are you sure you want to permanently delete this task?
          <br><br>
          This will also remove all linked records in
          <strong>project_tasks</strong> and
          <strong>task_assignment</strong>.
          <br>
          <strong>This action cannot be undone.</strong>
        </div>
      </div>

      <div class="record-preview">
        <div><span class="label">ID: </span><?php echo htmlspecialchars($task['task_id']); ?></div>
        <div><span class="label">Title: </span><strong><?php echo htmlspecialchars($task['title']); ?></strong></div>
        <?php if ($project): ?>
        <div><span class="label">Project: </span><?php echo htmlspecialchars($project['title']); ?></div>
        <?php endif; ?>
        <div><span class="label">Status: </span><?php echo htmlspecialchars($task['status']); ?></div>
        <div><span class="label">Due: </span><?php echo htmlspecialchars($task['due_date'] ?? '—'); ?></div>
        <div>
          <span class="label">Progress: </span><?php echo (int)$task['progress']; ?>%
          <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width:<?php echo (int)$task['progress']; ?>%"></div>
          </div>
        </div>
      </div>

      <hr class="divider">

      <form method="POST" action="task_delete.php?id=<?php echo $id; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="btn-row">
          <button type="submit" name="confirm_delete" value="1" class="win98-btn danger">🗑️ Delete</button>
          <a href="tasks.php" class="win98-btn">Cancel</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>