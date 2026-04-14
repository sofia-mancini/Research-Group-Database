<?php
// =============================================================
// auth.php — Central permission functions for Research Group DB
// Include this file on any page that requires access control
// =============================================================

// -------------------------------------------------------------
// Role classification
// -------------------------------------------------------------

function is_admin(string $role): bool
{
    return $role === 'Principal Investigator';
}

function is_member(string $role): bool
{
    return in_array($role, [
        'Principal Investigator',
        'Senior Researcher',
        'Researcher',
        'Lead Data Scientist',
        'Lead Engineer',
        'Postdoctoral Fellow',
        'Research Associate',
    ]);
}

function is_viewer(string $role): bool
{
    return !is_member($role);   // Anyone not in the member list
}

// -------------------------------------------------------------
// Generic permission checks
// -------------------------------------------------------------

function can_delete(string $role): bool
{
    return is_admin($role);     // Admin only across all tables
}

function can_edit_person(string $role): bool
{
    return is_admin($role);     // Admin only
}

function can_edit_department(string $role): bool
{
    return is_admin($role);     // Admin only
}

function can_add_department(string $role): bool
{
    return is_admin($role);     // Admin only
}

function can_edit_literature(string $role): bool
{
    return is_member($role);    // Admin + Member
}

function can_add_literature(string $role): bool
{
    return is_member($role);    // Admin + Member (not viewer)
}

function can_edit_experiment(string $role): bool
{
    return is_member($role);    // Admin + Member
}

function can_edit_project(string $role): bool
{
    return is_member($role);    // Admin + Member
}

function can_edit_research_group(string $role): bool
{
    return is_member($role);    // Admin + Member
}

// -------------------------------------------------------------
// Task edit — project membership check
// A user can edit a task if they are:
//   (a) an admin, OR
//   (b) a member of the project that encompasses that task
// -------------------------------------------------------------

function can_edit_task(string $role, int $person_id, int $task_id, PDO $pdo): bool
{
    if (is_admin($role)) {
        return true;
    }

    if (!is_member($role)) {
        return false;
    }

    // Check if person is a member of the project this task belongs to
    $sql = "SELECT COUNT(*) FROM project_tasks pt
            JOIN project_member pm ON pt.project_id = pm.project_id
            WHERE pt.task_id = :task_id
            AND pm.person_id = :person_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'task_id'   => $task_id,
        'person_id' => $person_id,
    ]);

    return $stmt->fetchColumn() > 0;
}

// -------------------------------------------------------------
// Access denied response
// Shows an error dialog message
// Call this when a viewer clicks edit/delete
// -------------------------------------------------------------

function access_denied_message(): string
{
    return '
    <div style="
        display:flex;
        align-items:flex-start;
        gap:12px;
        background:#c0c0c0;
        border-top:2px solid #808080;
        border-left:2px solid #808080;
        border-right:2px solid #fff;
        border-bottom:2px solid #fff;
        padding:12px;
        margin-bottom:12px;
        font-family: MS Sans Serif, Tahoma, sans-serif;
        font-size:11px;
    ">
        <div style="
            width:32px;height:32px;flex-shrink:0;
            background:#c0c0c0;border:2px solid #000;border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            font-size:18px;font-weight:bold;color:#ff0000;
            font-family:Times New Roman,serif;
        ">!</div>
        <div>
            <strong>Access Denied</strong><br>
            You do not have permission to perform this action.<br>
            Please contact a Principal Investigator for access.
        </div>
    </div>';
}

// -------------------------------------------------------------
// Require a minimum role or redirect to access_denied.php
// if the session role does not meet the requirement.
// Usage: require_role('admin') or require_role('member')
// -------------------------------------------------------------

function require_role(string $minimum_role): void
{
    $role = $_SESSION['role'] ?? '';

    if ($minimum_role === 'admin' && !is_admin($role)) {
        header('Location: access_denied.php');
        exit;
    }

    if ($minimum_role === 'member' && !is_member($role)) {
        header('Location: access_denied.php');
        exit;
    }
}
// End of auth.php – do NOT add any whitespace or closing tag after this line