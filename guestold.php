<?php
$page_title = "Other Payments — General";
require_once __DIR__ . '/includes/config.php';

$selected_campus = isset($_GET['campus']) ? strtoupper(trim($_GET['campus'])) : '';

// Preserve the legacy flow: choose a campus, then continue to paymentold.php.
if (isset($_POST['btnsubmit'])) {
  $campid = strtoupper(trim($_POST['campid'] ?? ''));
  $allowedCampuses = ['UPHB', 'UPHMU', 'UPHG', 'UPHM', 'PHCP', 'UPHI', 'UPHR'];
  if (!in_array($campid, $allowedCampuses, true)) {
    $error_msg = 'Please select a valid campus.';
  } else {
    date_default_timezone_set('Asia/Manila');
    $transid = $campid . '_' . date('HismdY');
    header('Location: ' . $payments_base . 'paymentold.php?payee=&transid=' . urlencode($transid) . '&type=other&campus=' . urlencode($campid));
    exit;
  }
}
require_once __DIR__ . '/includes/header.php';
?>
<div style="max-width:780px;margin:0 auto">
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
    <a href="<?= $payments_base ?>" class="btn" style="background:#fff;border:1px solid var(--line); padding:7px 12px; font-size:12px">Back</a>
    <span style="color:var(--muted);font-weight:600; font-size:12px">Other Payments</span>
  </div>

  <div class="form-card">
    <div class="form-head" style="background:linear-gradient(135deg,#b45309,#f59e0b)">
      <i class="fa-solid fa-file-invoice"></i>
      <div>
        <div style="font-weight:800;font-size:18px">General / Other Payment</div>
        <div style="opacity:.9;font-size:13px">No locator or student number required — for alumni, parents, guests.</div>
      </div>
    </div>
    <div class="form-body">
      <?php if (!empty($error_msg)): ?><div class="alert err"><i class="fa-solid fa-triangle-exclamation"></i><div><?= htmlspecialchars($error_msg) ?></div></div><?php endif; ?>
      <div class="alert warn"><i class="fa-solid fa-circle-info"></i><div><strong>Flexible:</strong> No student verification is required for this payment flow.</div></div>

      <form method="post" id="otherForm">
        <div class="field">
          <label for="campid">Select Your Campus <span style="color:var(--err)">*</span></label>
          <select name="campid" id="campid" required>
            <option value="">Choose your campus...</option>
            <option value="UPHB" <?= $selected_campus === 'UPHB' ? 'selected' : '' ?>>Binan Campus</option>
            <option value="UPHMU" <?= $selected_campus === 'UPHMU' ? 'selected' : '' ?>>Medical University</option>
            <option value="UPHG" <?= $selected_campus === 'UPHG' ? 'selected' : '' ?>>GMA Campus</option>
            <option value="UPHM" <?= $selected_campus === 'UPHM' ? 'selected' : '' ?>>Manila Campus</option>
            <option value="PHCP" <?= $selected_campus === 'PHCP' ? 'selected' : '' ?>>Pangasinan Campus</option>
            <option value="UPHI" <?= $selected_campus === 'UPHI' ? 'selected' : '' ?>>Isabela Campus</option>
            <option value="UPHR" <?= $selected_campus === 'UPHR' ? 'selected' : '' ?>>Roxas Campus</option>
          </select>
          <small style="color:var(--muted)">Choose the campus where you are requesting your payment.</small>
        </div>
        <button type="submit" name="btnsubmit" class="btn btn-primary" style="width:100%;padding:16px;background:linear-gradient(135deg,#b45309,#f59e0b)"><i class="fa-solid fa-arrow-right"></i> Proceed to Payment</button>
      </form>

      <div style="border-top:1px solid var(--line);padding-top:14px;margin-top:4px">
        <h4 style="margin:0 0 8px"><i class="fa-solid fa-list"></i> What you can pay</h4>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px;color:var(--muted)">
          <span><i class="fa-solid fa-check" style="color:var(--ok)"></i> Activity / Alumni / Certificate</span>
          <span><i class="fa-solid fa-check" style="color:var(--ok)"></i> Good Moral / Transcript / CAV</span>
          <span><i class="fa-solid fa-check" style="color:var(--ok)"></i> Uniform / Books / ID</span>
          <span><i class="fa-solid fa-check" style="color:var(--ok)"></i> Any custom description</span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
