<?php
require_once 'includes/session.php';
require_once 'includes/database-connection.php';
require_once 'includes/auth.php';
require_login($logged_in);

// Admin only
if (!can_delete($_SESSION['role'])) {
    header('Location: access_denied.php');
    exit;
}

// Validate ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
   ?? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: members.php');
    exit;
}

// Prevent admin from deleting their own account
if ($id === (int)$_SESSION['personID']) {
    header('Location: members.php?error=selfdelete');
    exit;
}

// Fetch record
$member = pdo($pdo, "SELECT * FROM Person WHERE ID = :id", ['id' => $id])->fetch();

if (!$member) {
    header('Location: members.php');
    exit;
}

// Fetch department for display
$dept = pdo($pdo,
    "SELECT d.name FROM department d
     JOIN person_dept pd ON d.department_id = pd.department_id
     WHERE pd.person_id = :id",
    ['id' => $id]
)->fetch();

// Handle confirmed deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Remove all junction table rows first
    pdo($pdo, "DELETE FROM task_assignment WHERE person_id = :id", ['id' => $id]);
    pdo($pdo, "DELETE FROM project_member WHERE person_id = :id",  ['id' => $id]);
    pdo($pdo, "DELETE FROM person_group WHERE person_id = :id",    ['id' => $id]);
    pdo($pdo, "DELETE FROM person_dept WHERE person_id = :id",     ['id' => $id]);
    pdo($pdo, "DELETE FROM Person WHERE ID = :id",                 ['id' => $id]);
    header('Location: members.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Member — Research Group DB</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: "MS Sans Serif", "Microsoft Sans Serif", Tahoma, sans-serif;
      font-size: 11px; background-color: #008080;
      background-image: repeating-linear-gradient(45deg, rgba(0,0,0,0.03) 0px, rgba(0,0,0,0.03) 1px, transparent 1px, transparent 4px);
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
    }
    .window { width: 420px; background: #c0c0c0; border-top: 2px solid #fff; border-left: 2px solid #fff; border-right: 2px solid #404040; border-bottom: 2px solid #404040; box-shadow: 2px 2px 0 #000; }
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
      <div class="title-bar-text">🗑️ Confirm Deletion — Research Group DB</div>
      <div class="title-bar-controls">
        <a href="members.php" class="win-btn">✕</a>
      </div>
    </div>

    <div class="window-body">
      <div class="dialog-row">
        <div class="dialog-icon">!</div>
        <div class="dialog-text">
          Are you sure you want to permanently delete this member?
          <br><br>
          This will also remove all linked records in
          <strong>task_assignment</strong>, <strong>project_member</strong>,
          <strong>person_group</strong>, and <strong>person_dept</strong>.
          <br>
          <strong>This action cannot be undone.</strong>
        </div>
      </div>

      <div class="record-preview">
        <div><span class="label">ID: </span><?php echo htmlspecialchars($member['ID']); ?></div>
        <div><span class="label">Name: </span><strong><?php echo htmlspecialchars($member['name']); ?></strong></div>
        <div><span class="label">Email: </span><?php echo htmlspecialchars($member['email']); ?></div>
        <div><span class="label">Role: </span><?php echo htmlspecialchars($member['role']); ?></div>
        <div><span class="label">Department: </span><?php echo htmlspecialchars($dept['name'] ?? '—'); ?></div>
      </div>

      <hr class="divider">

      <form method="POST" action="member_delete.php?id=<?php echo $id; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="btn-row">
          <button type="submit" name="confirm_delete" value="1" class="win98-btn danger">🗑️ Delete</button>
          <a href="members.php" class="win98-btn">Cancel</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>