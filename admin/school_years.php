<?php
$page_title = "School Years — Payments Hub";
require_once __DIR__ . '/../includes/config.php';
paymentsRequireAdmin();

$error = '';
$success = '';
$csrf = olp_csrf_token();
$currentLabel = schoolYearCurrentLabel();

if (!$con) {
    $error = 'Payments database is not available.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $con) {
    if (!olp_verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Security token invalid. Please refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            $schoolYear = trim($_POST['school_year'] ?? '');
            // Accept a start year only (e.g. 2027) and normalize it to 2027-2028.
            if (preg_match('/^\d{4}$/', $schoolYear)) {
                $schoolYear = $schoolYear . '-' . ((int)$schoolYear + 1);
            }

            if (!isValidSchoolYearLabel($schoolYear)) {
                $error = 'Enter a valid school year in YYYY-YYYY format (e.g. 2027-2028).';
            } else {
                $esc = mysqli_real_escape_string($con, $schoolYear);
                $dup = @mysqli_query($con, "SELECT id FROM school_years WHERE school_year='{$esc}' LIMIT 1");
                if ($dup && mysqli_fetch_assoc($dup)) {
                    $error = 'School year ' . $schoolYear . ' already exists.';
                } elseif (@mysqli_query($con, "INSERT INTO school_years (school_year, is_active) VALUES ('{$esc}', 1)")) {
                    $success = 'School year ' . $schoolYear . ' added.';
                } else {
                    $error = 'Failed to add the school year.';
                }
            }
        } elseif ($action === 'generate') {
            $count = max(1, min(20, (int)($_POST['count'] ?? 3)));
            $maxStart = schoolYearCurrentStart();

            $r = @mysqli_query($con, "SELECT school_year FROM school_years ORDER BY school_year DESC LIMIT 1");
            if ($r && $row = mysqli_fetch_assoc($r)) {
                $parts = explode('-', $row['school_year']);
                if (count($parts) === 2 && ctype_digit($parts[0])) {
                    $maxStart = max($maxStart, (int)$parts[0]);
                }
            }

            $added = 0;
            for ($i = 1; $i <= $count; $i++) {
                $start = $maxStart + $i;
                $label = $start . '-' . ($start + 1);
                $esc = mysqli_real_escape_string($con, $label);
                if (@mysqli_query($con, "INSERT IGNORE INTO school_years (school_year, is_active) VALUES ('{$esc}', 1)")) {
                    if (mysqli_affected_rows($con) > 0) $added++;
                }
            }
            $success = $added > 0
                ? $added . ' future school year(s) added.'
                : 'No new future school years were needed.';
        } elseif ($action === 'toggle') {
            $id = (int)($_POST['id'] ?? 0);
            $r = @mysqli_query($con, "SELECT school_year, is_active FROM school_years WHERE id={$id} LIMIT 1");
            $row = $r ? mysqli_fetch_assoc($r) : null;

            if (!$row) {
                $error = 'School year not found.';
            } elseif ($row['school_year'] === $currentLabel) {
                $error = 'The present school year is always included and cannot be disabled.';
            } else {
                $newActive = ((int)$row['is_active']) ? 0 : 1;
                if (@mysqli_query($con, "UPDATE school_years SET is_active={$newActive} WHERE id={$id}")) {
                    $success = 'School year ' . $row['school_year'] . ' ' . ($newActive ? 'enabled' : 'disabled') . '.';
                } else {
                    $error = 'Failed to update the school year.';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $r = @mysqli_query($con, "SELECT school_year FROM school_years WHERE id={$id} LIMIT 1");
            $row = $r ? mysqli_fetch_assoc($r) : null;

            if (!$row) {
                $error = 'School year not found.';
            } elseif ($row['school_year'] === $currentLabel) {
                $error = 'The present school year cannot be deleted.';
            } elseif (@mysqli_query($con, "DELETE FROM school_years WHERE id={$id}")) {
                $success = 'School year ' . $row['school_year'] . ' deleted.';
            } else {
                $error = 'Failed to delete the school year.';
            }
        }
    }
}

$rows = getSchoolYearRows($con, false);
$groups = ['present' => [], 'future' => [], 'past' => []];
foreach ($rows as $row) {
    $groups[schoolYearStatus($row['school_year'])][] = $row;
}
usort($groups['future'], function ($a, $b) { return strcmp($b['school_year'], $a['school_year']); });
usort($groups['past'], function ($a, $b) { return strcmp($b['school_year'], $a['school_year']); });

require_once __DIR__ . '/../includes/header.php';
?>
<nav class="admin-tabs" aria-label="Admin sections">
  <a href="<?= $payments_base ?>admin/">Dashboard</a>
  <a href="<?= $payments_base ?>admin/users">Users</a>
  <a href="<?= $payments_base ?>admin/students">Student Management</a>
  <a href="<?= $payments_base ?>admin/monitoring">Payment Monitoring</a>
  <a href="<?= $payments_base ?>admin/school_years" class="active">School Years</a>
  <a href="<?= $payments_base ?>admin/qr">QR Codes</a>
  <a href="<?= $payments_base ?>admin/backup">Backup Portal</a>
  <a href="<?= $payments_base ?>admin/logout" class="admin-tab-logout">Logout</a>
</nav>
<div class="admin-content">
  <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:6px">
    <h2 style="margin:0;font-size:22px"><i class="fa-solid fa-calendar-days" style="color:var(--blue)"></i> School Years</h2>
    <span style="margin-left:auto;background:var(--bg);border:1px solid var(--line);padding:6px 10px;border-radius:999px;font-size:11px;font-weight:800;color:var(--muted)">Present: <?= htmlspecialchars($currentLabel) ?></span>
  </div>
  <p style="color:var(--muted);margin:0 0 14px;font-size:13px">
    Manage the school year options shown on the payment forms. Enabled options include past, present, and future school years.
    The present school year is always available; add future school years here instead of editing the payment files.
  </p>

  <?php if ($error): ?><div class="alert err"><i class="fa-solid fa-triangle-exclamation"></i><div><?= htmlspecialchars($error) ?></div></div><?php endif; ?>
  <?php if ($success): ?><div class="alert ok"><i class="fa-solid fa-circle-check"></i><div><?= htmlspecialchars($success) ?></div></div><?php endif; ?>

  <div style="display:grid;grid-template-columns:1.15fr .85fr;gap:14px;margin:16px 0 20px" class="backup-grid">
    <div style="background:#f8fafc;border:1px solid var(--line);border-radius:14px;padding:16px">
      <div style="font-weight:800;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:10px">Add a school year</div>
      <form method="post" style="display:flex;gap:8px;flex-wrap:wrap;align-items:flex-end">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="action" value="add">
        <div style="flex:1;min-width:180px">
          <label style="display:block;font-size:11px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:#475569;margin-bottom:5px">School Year</label>
          <input type="text" name="school_year" required placeholder="2027-2028" pattern="\d{4}(-\d{4})?" title="Use YYYY-YYYY (e.g. 2027-2028) or just the start year (e.g. 2027)" style="width:100%;padding:10px 12px;border:1px solid #dfe6f5;border-radius:10px;font:600 13px Inter;background:#fff">
        </div>
        <button type="submit" class="btn" style="background:var(--blue);color:#fff;padding:10px 16px"><i class="fa-solid fa-plus"></i> Add</button>
      </form>
      <div style="font-size:11px;color:var(--muted);margin-top:8px">Tip: you may type just <strong>2027</strong> and it will be saved as <strong>2027-2028</strong>.</div>
    </div>

    <div style="background:#fff;border:1px solid var(--line);border-radius:14px;padding:16px">
      <div style="font-weight:800;font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:var(--muted);margin-bottom:10px">Generate future years</div>
      <form method="post" style="display:flex;gap:8px;flex-wrap:wrap;align-items:flex-end">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <input type="hidden" name="action" value="generate">
        <div style="flex:1;min-width:120px">
          <label style="display:block;font-size:11px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:#475569;margin-bottom:5px">How many years?</label>
          <input type="number" name="count" min="1" max="20" value="3" style="width:100%;padding:10px 12px;border:1px solid #dfe6f5;border-radius:10px;font:600 13px Inter;background:#fff">
        </div>
        <button type="submit" class="btn" style="background:#fff;border:1px solid var(--line);padding:10px 16px"><i class="fa-solid fa-forward"></i> Generate</button>
      </form>
      <div style="font-size:11px;color:var(--muted);margin-top:8px">Adds the next school years after the latest option.</div>
    </div>
  </div>

  <?php if (empty($rows)): ?>
    <div class="alert err"><i class="fa-solid fa-triangle-exclamation"></i><div>No school years found. Add one above, or check that the payments database is available.</div></div>
  <?php endif; ?>

  <?php foreach (['future' => ['Future', 'warn'], 'present' => ['Present', 'ok'], 'past' => ['Past', '']] as $key => $meta): ?>
    <?php if (!empty($groups[$key])): ?>
      <h3 style="margin:18px 0 8px;font-size:14px;display:flex;align-items:center;gap:8px">
        <?= $meta[0] ?> School Years
        <span class="badge <?= $meta[1] ?>"><?= count($groups[$key]) ?></span>
      </h3>
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr><th>School Year</th><th>Status</th><th>In Payment Options</th><th>Added</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($groups[$key] as $row): ?>
              <?php $isPresent = ($row['school_year'] === $currentLabel); ?>
              <tr>
                <td><strong><?= htmlspecialchars($row['school_year']) ?></strong></td>
                <td><span class="badge <?= $meta[1] ?>"><?= $meta[0] ?></span></td>
                <td>
                  <?php if ((int)$row['is_active'] === 1): ?>
                    <span class="badge ok">Enabled</span>
                  <?php else: ?>
                    <span class="badge err">Disabled</span>
                  <?php endif; ?>
                </td>
                <td style="font-size:12px;color:var(--muted)"><?= htmlspecialchars($row['created_at'] ?? '—') ?></td>
                <td style="display:flex;gap:6px;flex-wrap:wrap">
                  <?php if ($isPresent): ?>
                    <span style="font-size:11px;color:var(--muted);font-weight:700">Always included</span>
                  <?php else: ?>
                    <form method="post" style="display:inline">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                      <input type="hidden" name="action" value="toggle">
                      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                      <button type="submit" class="btn btn-secondary" style="padding:6px 10px;font-size:12px"><?= ((int)$row['is_active'] === 1) ? 'Disable' : 'Enable' ?></button>
                    </form>
                    <form method="post" style="display:inline" onsubmit="return confirm('Delete school year <?= htmlspecialchars($row['school_year'], ENT_QUOTES) ?>?')">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                      <button type="submit" class="btn btn-danger" style="padding:6px 10px;font-size:12px">Delete</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
