<?php
$page_title = "How to Pay Online - Instructions";
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
?>
<section style="max-width:720px; margin:0 auto 14px">
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
    <a href="<?= $payments_base ?>" class="btn" style="background:#fff;border:1px solid var(--line); padding:7px 12px; font-size:12px"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
    <span style="color:var(--muted);font-weight:600; font-size:12px">How to Pay • Pay Folder</span>
  </div>
  <div style="text-align:center">
    <h1 style="margin:0; font-size:28px; font-family:'Plus Jakarta Sans',sans-serif">How to Pay Online</h1>
    <p style="color:var(--muted); margin:8px 0 0; font-size:14.5px">Choose your payment type. Each tab shows exactly what to do.</p>
    <div style="display:flex;gap:8px;justify-content:center;margin-top:12px" class="no-print">
      <button onclick="window.print()" class="btn" style="background:#fff; border:1px solid var(--line); padding:9px 16px; font-size:13px"><i class="fa-solid fa-print"></i> Print guide</button>
      <button onclick="exportAllVisible()" class="btn" style="background:var(--blue); color:#fff; border:1px solid var(--blue); padding:9px 16px; font-size:13px"><i class="fa-solid fa-images"></i> Export all steps</button>
    </div>
  </div>
</section>

<div class="tabs-navigation no-print" role="tablist" style="justify-content:center">
  <button class="tab-btn active" data-tab="new">New Enrollee</button>
  <button class="tab-btn" data-tab="enrolled">Enrolled</button>
  <button class="tab-btn" data-tab="other">Other Payment</button>
</div>
<div id="stepProgress" class="step-progress no-print" aria-live="polite" style="display:none"><span class="sp-dot"></span><span id="stepProgressText">Step 1 of 8</span></div>

<style>
.steps-fig{display:grid;gap:14px;margin:8px 0 0}
.step-row{border:1px solid var(--line);border-radius:16px;padding:16px;background:#fff;display:grid;gap:10px;position:relative;overflow:hidden;scroll-margin-top:140px;transition:border-color .2s,box-shadow .2s}
.step-row.is-active{border-color:var(--blue);box-shadow:0 8px 24px rgba(28,77,161,.12)}
.step-row-head{display:flex;align-items:flex-start;gap:10px;justify-content:space-between}
.step-text{display:flex;gap:10px;align-items:flex-start;font-size:13.5px;line-height:1.6;color:var(--text);flex:1}
.step-text .step-num{flex-shrink:0;width:28px;height:28px;border-radius:999px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12.5px;background:var(--blue);color:#fff}
.step-export-btn{flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;border:1px solid var(--line);background:#fff;color:var(--blue);font-weight:800;font-size:11.5px;cursor:pointer;transition:.18s;white-space:nowrap}
.step-export-btn:hover{background:var(--blue);color:#fff;border-color:var(--blue);box-shadow:0 4px 12px rgba(28,77,161,.18)}
.step-export-btn:disabled{opacity:.6;cursor:wait}
.fig{border:1px solid #e2e8f0;border-radius:14px;padding:14px;background:#fbfdff}
.fig .field{margin:0}
.fig .field label{font-size:11px}
.fig .field input,.fig .field select{pointer-events:none}
.fig-live{pointer-events:none}
.fig-caption{font-size:11px;color:#94a3b8;text-align:center;margin-top:2px;font-style:italic}
.export-toast{position:fixed;bottom:18px;left:50%;transform:translateX(-50%) translateY(20px);background:#0f2040;color:#fff;padding:10px 16px;border-radius:999px;font-size:13px;font-weight:700;box-shadow:0 10px 30px rgba(0,0,0,.25);opacity:0;pointer-events:none;transition:.25s;z-index:9999}
.export-toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
@media(max-width:640px){.step-row-head{flex-direction:column}.step-export-btn{align-self:flex-start}}
/* sticky tabs - follow along */
.tabs-navigation{position:sticky;top:84px;z-index:25;background:rgba(246,248,252,.92);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);margin:0 -20px;padding:10px 20px 12px;border-bottom:2px solid #e2e8f0;justify-content:center}
.tabs-navigation.is-stuck{box-shadow:0 6px 18px rgba(15,32,64,.08);border-bottom-color:var(--line)}
.step-progress{position:sticky;top:138px;z-index:24;background:#fff;border:1px solid var(--line);border-radius:999px;padding:6px 12px;display:inline-flex;align-items:center;gap:8px;font-size:12px;font-weight:700;color:var(--muted);box-shadow:0 4px 16px rgba(15,32,64,.06);margin:10px auto 0;left:50%;transform:translateX(-50%);max-width:calc(100% - 32px);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.step-progress strong{color:var(--blue)}
.step-progress .sp-dot{width:8px;height:8px;border-radius:999px;background:var(--blue);flex-shrink:0;animation:pulse 1.6s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
@media(max-width:860px){.tabs-navigation{top:68px}.step-progress{top:122px}}
@media(max-width:640px){.tabs-navigation{top:64px;padding:8px 14px 10px;margin:0 -14px}.step-progress{top:112px;font-size:11px;padding:5px 10px}}
@media(max-width:380px){.tabs-navigation{top:60px}.step-progress{top:106px}}
@media print{.tabs-navigation,.step-progress{display:none !important}}
</style>

<!-- NEW ENROLLEE -->
<div id="tab-new" class="tab-content active">
  <div class="print-only" style="text-align:center; margin-bottom:10px; padding:12px; background:#f1f5f9; border:1px solid #ddd; border-radius:12px">
    <div style="font-weight:800; font-size:14px; color:var(--blue); letter-spacing:.04em; text-transform:uppercase">New Enrollee Guide</div>
  </div>
  <div class="section" style="margin-top:0">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px"><span style="width:28px;height:28px;border-radius:999px;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px">1</span><strong>What you need</strong></div>
    <ul style="margin:0 0 14px 28px;color:var(--muted);font-size:14px;line-height:1.6">
      <li>Campus where you were advised</li>
      <li>Locator number — on your <strong style="color:var(--text)">white form</strong>, above your name</li>
    </ul>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px"><span style="width:28px;height:28px;border-radius:999px;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px">2</span><strong>Steps</strong></div>

    <div class="steps-fig">

      <div class="step-row" id="new-step-1" data-export-name="new-01-select-campus">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">1</span><span><strong>Select campus</strong> — pick where you were advised. The locator box appears after you choose.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div class="field">
            <label>Select Your Campus</label>
            <select disabled style="opacity:1;background:#fff">
              <option>Binan Campus — selected</option>
            </select>
            <small style="color:var(--muted)">Choose where you were advised.</small>
          </div>
          <div style="margin-top:10px;background:var(--blue);color:#fff;padding:10px 12px;border-radius:10px;font-weight:700;font-size:12px;display:inline-flex;align-items:center;gap:8px"><i class="fa-solid fa-check"></i> Binan Campus (UPHB)</div>
        </div>
        <div class="fig-caption">Fig. 1 — Campus dropdown. Choose one to unlock verification.</div>
      </div>

      <div class="step-row" id="new-step-2" data-export-name="new-02-enter-locator">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">2</span><span><strong>Enter locator</strong> — copy the number from the <strong>white form above your name</strong>, tap <em>Verify</em>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div style="background:var(--bg);border:1px solid var(--line);border-radius:14px;padding:14px">
            <h4 style="margin:0 0 8px;color:var(--blue)"><i class="fa-solid fa-magnifying-glass"></i> Verify Locator Number</h4>
            <div style="text-align:center;color:var(--muted);font-size:13px;margin-bottom:10px">Please verify your locator number before proceeding</div>
            <div class="verify-box">
              <div class="field" style="margin:0"><label>Locator Number</label><input type="text" value="26417244" readonly style="background:#fff;font-weight:700;color:var(--blue)"></div>
              <button type="button" class="btn-verify" style="opacity:1"><i class="fa-solid fa-magnifying-glass"></i> Verify</button>
            </div>
          </div>
        </div>
        <div class="fig-caption">Fig. 2 — Locator field + Verify button. Wait for the result card below.</div>
      </div>

      <div class="step-row" id="new-step-3" data-export-name="new-03-confirm-name">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">3</span><span><strong>Confirm name</strong> — check the card that appears (avatar + name + locator + campus), tap <em>Yes, it’s me</em>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="background:#fff">
          <div class="verify-card" style="pointer-events:none">
            <div class="verify-card-top ok"><div class="verify-icon"><i class="fa-solid fa-check"></i></div><div><div class="verify-title">Verified</div><div class="verify-subtitle">Locator verified successfully!</div></div></div>
            <div class="verify-card-body">
              <div class="verify-profile"><div class="verify-avatar">JD</div><div><div class="verify-name">Juan Dela Cruz</div><div class="verify-sub">Is this you? Tap Confirm if correct</div></div></div>
              <div class="verify-meta"><span><i class="fa-solid fa-hashtag"></i> 26417244</span><span><i class="fa-solid fa-building-columns"></i> Binan Campus</span></div>
              <div class="verify-hint">Found in campus records. Confirm to proceed with payment.</div>
            </div>
            <div class="verify-actions"><span class="btn btn-primary" style="flex:1;justify-content:center"><i class="fa-solid fa-check"></i> Yes, it’s me</span><span class="btn btn-secondary" style="flex:1;justify-content:center"><i class="fa-solid fa-xmark"></i> Not mine</span></div>
          </div>
        </div>
        <div class="fig-caption">Fig. 3 — Verification card. Confirm only if the name is exactly yours.</div>
      </div>

      <div class="step-row" id="new-step-4" data-export-name="new-04-proceed-button">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">4</span><span><strong>Click Proceed to Payment</strong> — after tapping <em>Yes, it’s me</em> the button appears. The helper text changes to confirmed.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div style="background:var(--bg);border:1px solid var(--line);border-radius:14px;padding:14px">
            <div style="text-align:center;color:var(--ok);font-size:13px;font-weight:700;margin-bottom:10px"><i class="fa-solid fa-circle-check"></i> ✓ Name confirmed! You may now proceed.</div>
            <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff;pointer-events:none"><i class="fa-solid fa-credit-card"></i> Proceed to Payment</button>
            <div style="text-align:center;font-size:11px;color:var(--muted);margin-top:8px">This is the real <code>#btnsubmit</code> from <code>guest.php</code> — it was hidden until verification.</div>
          </div>
        </div>
        <div class="fig-caption">Fig. 4 — Real Proceed button (appears only after confirmation).</div>
      </div>

      <div class="step-row" id="new-step-5" data-export-name="new-05-checkout-form">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">5</span><span><strong>Fill checkout form</strong> — amount, particulars, school year/sem, payer name, contact and email. Locator is locked, Transaction ID is prefilled.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div style="display:grid;gap:10px">
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Transaction ID</label><input type="text" value="UPHB_143022052026" readonly style="background:#f1f5f9;font-family:ui-monospace,monospace;font-size:12px"></div>
              <div class="field"><label>Amount (PHP) <span style="color:var(--err)">*</span></label><input type="text" value="₱ 5,000.00" readonly style="background:#fff;font-weight:800;color:var(--blue)"></div>
            </div>
            <div class="field"><label>Particulars <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>DOWNPAYMENT — selected (locked for New Enrollee)</option></select></div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>For School Year <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>2025-2026</option></select></div>
              <div class="field"><label>For Semester <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>1st Sem</option></select></div>
            </div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Student / Payer Name <span style="color:var(--err)">*</span></label><input type="text" value="Juan Dela Cruz" readonly style="background:#fff"></div>
              <div class="field"><label>Locator Number</label><input type="text" value="26417244" readonly style="background:#eef2ff;color:var(--blue);font-weight:800;text-align:center"></div>
            </div>
            <div class="field"><label>Contact Number</label><input type="text" value="09123456789" readonly style="background:#fff"></div>
            <div class="field"><label>Email Address <span style="color:var(--err)">*</span></label><input type="text" value="you@email.com" readonly style="background:#fff"></div>
          </div>
        </div>
        <div class="fig-caption">Fig. 5 — Real checkout fields from <code>payment.php</code> — identical inputs, same validation.</div>
      </div>

      <div class="step-row" id="new-step-6" data-export-name="new-06-review-pay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">6</span><span><strong>Review & Pay</strong> — check the Payment Summary, tick the confirmation, then click <em>Pay Now via DragonPay</em>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="display:grid;gap:10px">
          <div style="background:#fffbe6;border:1px solid #fde68a;border-radius:12px;padding:12px">
            <div style="font-weight:800;font-size:13px;color:#92400e;margin-bottom:6px"><i class="fa-solid fa-eye"></i> Review your details</div>
            <div style="font-size:12px;color:#475569;margin-bottom:8px">Please check the <strong>Payment Summary</strong> — amount, particulars, school year, semester, payer name and email must be correct.</div>
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#1e293b"><input type="checkbox" checked disabled style="accent-color:var(--blue)"> I have reviewed my payment details and confirm they are correct.</label>
          </div>
          <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff;pointer-events:none"><i class="fa-solid fa-lock"></i> Pay Now via <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" style="height:22px;background:#fff;padding:2px 6px;border-radius:6px;vertical-align:middle;margin-left:2px"></button>
          <div class="pay-summary" style="padding:12px;border-radius:12px">
            <div style="display:flex;align-items:center;gap:8px"><i class="fa-solid fa-receipt"></i><strong>Payment Summary</strong><span style="margin-left:auto;background:rgba(255,255,255,.18);padding:4px 8px;border-radius:999px;font-size:11px">NEW</span></div>
            <div class="row" style="font-size:13px"><span>Payee</span><strong>Juan Dela Cruz - 09123456789</strong></div>
            <div class="row" style="font-size:13px"><span>Amount</span><strong>₱ 5,000.00</strong></div>
            <div class="row" style="font-size:11px"><span>Description</span><strong style="font-size:11px">JUAN DELA CRUZ | ID:26417244 | Contact:09123456789 | DOWNPAYMENT | 2025-2026 1st Sem</strong></div>
          </div>
        </div>
        <div class="fig-caption">Fig. 6 — Real review gate + Pay Now + live Summary (from <code>payment.php</code>).</div>
      </div>

      <div class="step-row" id="new-step-7" data-export-name="new-07-dragonpay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">7</span><span><strong>Pay via DragonPay</strong> — choose <strong>Online Banking, E-Wallets, or Over the Counter</strong>, then complete. Keep your Transaction ID.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="display:grid;gap:10px;place-items:center;background:#fff">
          <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" class="dp-logo" style="height:30px">
          <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center">
            <span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none"><i class="fa-solid fa-building-columns"></i> Online Banking</span>
            <span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none"><i class="fa-solid fa-wallet"></i> E-Wallets</span>
            <span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none"><i class="fa-solid fa-store"></i> Over the Counter</span>
          </div>
          <div class="alert info" style="width:100%;justify-content:center;padding:10px 12px"><i class="fa-solid fa-circle-info"></i><div style="font-size:12px">You’ll be redirected to DragonPay — Transaction ID: <strong>UPHB_143022052026</strong></div></div>
        </div>
        <div class="fig-caption">Fig. 7 — DragonPay options. You’ll be redirected to complete payment.</div>
      </div>

      <div class="step-row" id="new-step-8" data-export-name="new-08-receipt">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">8</span><span><strong>Present screenshot to cashier to claim official receipt</strong> — after payment, screenshot the DragonPay confirmation and present it to the cashier.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="background:#f8fafc;display:grid;gap:8px;place-items:center;padding:16px">
          <div style="background:#fff;border:1px solid var(--line);border-radius:12px;padding:12px;width:100%;max-width:360px;box-shadow:0 8px 24px rgba(0,0,0,.08)">
            <div style="display:flex;align-items:center;gap:8px;color:var(--blue);font-weight:800;font-size:13px"><i class="fa-solid fa-circle-check" style="color:var(--blue)"></i> Payment Confirmed — DragonPay</div>
            <div style="font-size:11px;color:var(--muted);margin-top:4px">Txn: UPHB_143022052026 • ₱5,000.00 • Juan Dela Cruz</div>
            <div style="margin-top:8px;border:1px dashed var(--line);background:var(--bg);border-radius:8px;padding:8px;text-align:center;font-size:11px;color:var(--blue);font-weight:700"><i class="fa-solid fa-camera"></i> Screenshot this & show at cashier</div>
          </div>
        </div>
      </div>

    </div>
    <div class="alert info" style="margin-top:14px"><div><strong>If not found:</strong> Finish advising first — locator is issued there.</div></div>
    <a href="<?= $payments_base ?>guest" class="btn btn-primary no-print" style="margin-top:14px; background:var(--blue); color:#fff; width:100%; justify-content:center">Pay as New Enrollee</a>
  </div>
</div>

<!-- ENROLLED -->
<div id="tab-enrolled" class="tab-content">
  <div class="print-only" style="text-align:center; margin-bottom:10px; padding:12px; background:#f1f5f9; border:1px solid #ddd; border-radius:12px">
    <div style="font-weight:800; font-size:14px; color:var(--blue); letter-spacing:.04em; text-transform:uppercase">Enrolled Guide</div>
  </div>
  <div class="section" style="margin-top:0">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px"><span style="width:28px;height:28px;border-radius:999px;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px">1</span><strong>What you need</strong></div>
    <ul style="margin:0 0 14px 28px;color:var(--muted);font-size:14px;line-height:1.6">
      <li>Campus</li>
      <li>Student ID — on your <strong style="color:var(--text)">yellow form</strong>, above your name</li>
    </ul>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px"><span style="width:28px;height:28px;border-radius:999px;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px">2</span><strong>Steps</strong></div>

    <div class="steps-fig">

      <div class="step-row" id="enrolled-step-1" data-export-name="enrolled-01-select-campus">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">1</span><span><strong>Select campus</strong>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div class="field"><label>Select Your Campus</label><select disabled style="opacity:1;background:#fff"><option>Manila Campus — selected</option></select></div>
        </div>
        <div class="fig-caption">Fig. 1 — Campus selector.</div>
      </div>

      <div class="step-row" id="enrolled-step-2" data-export-name="enrolled-02-student-number">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">2</span><span><strong>Enter Student ID</strong> — copy the number from the <strong>yellow form above your name</strong>, tap <em>Verify</em>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div style="background:var(--bg);border:1px solid var(--line);border-radius:14px;padding:14px">
            <h4 style="margin:0 0 8px;color:var(--blue)"><i class="fa-solid fa-shield-check"></i> Verify Student Number</h4>
            <div class="verify-box">
              <div class="field" style="margin:0"><label>Student Number</label><input type="text" value="26-1234-567" readonly style="background:#fff;font-weight:700;color:var(--blue)"></div>
              <button class="btn-verify" style="opacity:1"><i class="fa-solid fa-magnifying-glass"></i> Verify</button>
            </div>
          </div>
        </div>
        <div class="fig-caption">Fig. 2 — Student number field. Use the exact number on your ID.</div>
      </div>

      <div class="step-row" id="enrolled-step-3" data-export-name="enrolled-03-confirm">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">3</span><span><strong>Confirm name</strong> — verify the card (avatar + student number + campus).</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div class="verify-card" style="pointer-events:none">
            <div class="verify-card-top ok"><div class="verify-icon"><i class="fa-solid fa-check"></i></div><div><div class="verify-title">Verified</div><div class="verify-subtitle">Student verified successfully!</div></div></div>
            <div class="verify-card-body"><div class="verify-profile"><div class="verify-avatar">MA</div><div><div class="verify-name">Maria Santos</div><div class="verify-sub">Is this you?</div></div></div><div class="verify-meta"><span><i class="fa-solid fa-id-card"></i> 26-1234-567</span><span><i class="fa-solid fa-building-columns"></i> Manila Campus</span></div></div>
            <div class="verify-actions"><span class="btn btn-primary" style="flex:1;justify-content:center"><i class="fa-solid fa-check"></i> Yes, it’s me</span><span class="btn btn-secondary" style="flex:1;justify-content:center"><i class="fa-solid fa-xmark"></i> Not mine</span></div>
          </div>
        </div>
        <div class="fig-caption">Fig. 3 — Verification card. Confirm only if the name matches.</div>
      </div>

      <div class="step-row" id="enrolled-step-4" data-export-name="enrolled-04-proceed-button">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">4</span><span><strong>Click Proceed to Payment</strong> — after confirming, the button appears and the helper text turns to confirmed.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div style="background:var(--bg);border:1px solid var(--line);border-radius:14px;padding:14px">
            <div style="text-align:center;color:var(--ok);font-size:13px;font-weight:700;margin-bottom:10px"><i class="fa-solid fa-circle-check"></i> ✓ Student confirmed! You may now proceed.</div>
            <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff;pointer-events:none"><i class="fa-solid fa-credit-card"></i> Proceed to Payment</button>
          </div>
        </div>
        <div class="fig-caption">Fig. 4 — Real Proceed button from <code>guestold_student.php</code> (hidden until verification).</div>
      </div>

      <div class="step-row" id="enrolled-step-5" data-export-name="enrolled-05-checkout-form">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">5</span><span><strong>Fill checkout form</strong> — choose particulars, amount, school year/sem, payer name, contact and email. Student number is locked.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div style="display:grid;gap:10px">
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Transaction ID</label><input type="text" value="UPHM_143022052026" readonly style="background:#f1f5f9;font-family:ui-monospace,monospace;font-size:12px"></div>
              <div class="field"><label>Amount (PHP) <span style="color:var(--err)">*</span></label><input type="text" value="₱ 8,250.00" readonly style="background:#fff;font-weight:800;color:var(--blue)"></div>
            </div>
            <div class="field"><label>Particulars <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>Tuition Fee — selected</option></select></div>
            <div class="field"><label>Other description (if not listed)</label><input type="text" placeholder="If not listed, type custom description here" disabled style="background:#fff"></div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>For School Year <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>2024-2025</option></select></div>
              <div class="field"><label>For Semester <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>1st Sem</option></select></div>
            </div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Student / Payer Name <span style="color:var(--err)">*</span></label><input type="text" value="Maria Santos" readonly style="background:#fff"></div>
              <div class="field"><label>Student Number</label><input type="text" value="26-1234-567" readonly style="background:#eef2ff;color:var(--blue);font-weight:800;text-align:center"></div>
            </div>
            <div class="field"><label>Contact Number</label><input type="text" value="09123456780" readonly style="background:#fff"></div>
            <div class="field"><label>Email Address <span style="color:var(--err)">*</span></label><input type="text" value="maria@email.com" readonly style="background:#fff"></div>
          </div>
        </div>
        <div class="fig-caption">Fig. 5 — Checkout from <code>payment_oldstud.php</code> — same fields, same order.</div>
      </div>

      <div class="step-row" id="enrolled-step-6" data-export-name="enrolled-06-review-pay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">6</span><span><strong>Review & Pay</strong> — check Payment Summary, confirm the checkbox, then <em>Pay Now via DragonPay</em>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="display:grid;gap:10px">
          <div style="background:#fffbe6;border:1px solid #fde68a;border-radius:12px;padding:12px">
            <div style="font-weight:800;font-size:13px;color:#92400e;margin-bottom:6px"><i class="fa-solid fa-eye"></i> Review your details</div>
            <div style="font-size:12px;color:#475569;margin-bottom:8px">Please check the <strong>Payment Summary</strong> — everything must be correct before proceeding.</div>
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#1e293b"><input type="checkbox" checked disabled style="accent-color:var(--blue)"> I have reviewed my payment details and confirm they are correct.</label>
          </div>
          <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff;pointer-events:none"><i class="fa-solid fa-lock"></i> Pay Now via <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" style="height:22px;background:#fff;padding:2px 6px;border-radius:6px;vertical-align:middle;margin-left:2px"></button>
          <div class="pay-summary" style="padding:12px;border-radius:12px">
            <div style="display:flex;align-items:center;gap:8px"><i class="fa-solid fa-receipt"></i><strong>Payment Summary</strong><span style="margin-left:auto;background:rgba(255,255,255,.18);padding:4px 8px;border-radius:999px;font-size:11px">ENROLLED</span></div>
            <div class="row" style="font-size:13px"><span>Payee</span><strong>Maria Santos - 09123456780</strong></div>
            <div class="row" style="font-size:13px"><span>Amount</span><strong>₱ 8,250.00</strong></div>
            <div class="row" style="font-size:11px"><span>Description</span><strong style="font-size:11px">MARIA SANTOS | ID:26-1234-567 | Contact:09123456780 | TUITION FEE | 2024-2025 1st Sem</strong></div>
          </div>
        </div>
        <div class="fig-caption">Fig. 6 — Review gate + Pay Now + Summary.</div>
      </div>

      <div class="step-row" id="enrolled-step-7" data-export-name="enrolled-07-dragonpay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">7</span><span><strong>Pay via DragonPay</strong> — Online Banking / E-Wallets / Over the Counter.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="display:grid;gap:8px;place-items:center;background:#fff">
          <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" class="dp-logo" style="height:30px">
          <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap"><span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none">Online Banking</span><span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none">E-Wallets</span><span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none">Over the Counter</span></div>
        </div>
        <div class="fig-caption">Fig. 7 — DragonPay options.</div>
      </div>

      <div class="step-row" id="enrolled-step-8" data-export-name="enrolled-08-receipt">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">8</span><span><strong>Present screenshot to cashier to claim official receipt</strong>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="background:#f8fafc;display:grid;place-items:center;padding:14px">
          <div style="background:#fff;border:1px solid var(--line);border-radius:12px;padding:12px;width:100%;max-width:360px"><div style="color:var(--blue);font-weight:800;font-size:13px"><i class="fa-solid fa-circle-check" style="color:var(--blue)"></i> Payment Confirmed — DragonPay</div><div style="font-size:11px;color:var(--muted);margin-top:4px">Txn: UPHM_143022052026 • ₱8,250.00 • Maria Santos (26-1234-567)</div><div style="margin-top:8px;border:1px dashed var(--line);background:var(--bg);border-radius:8px;padding:8px;text-align:center;font-size:11px;color:var(--blue);font-weight:700"><i class="fa-solid fa-camera"></i> Screenshot & show at cashier</div></div>
        </div>
      </div>

    </div>
    <div class="alert info" style="margin-top:14px"><div><strong>Tip:</strong> Use the exact student number on your ID/registration.</div></div>
    <a href="<?= $payments_base ?>guestold_student" class="btn btn-primary no-print" style="margin-top:14px; background:var(--blue); color:#fff; width:100%; justify-content:center; border:none">Pay as Enrolled</a>
  </div>
</div>

<!-- OTHER -->
<div id="tab-other" class="tab-content">
  <div class="print-only" style="text-align:center; margin-bottom:10px; padding:12px; background:#f1f5f9; border:1px solid #ddd; border-radius:12px">
    <div style="font-weight:800; font-size:14px; color:var(--blue); letter-spacing:.04em; text-transform:uppercase">Other Payment Guide</div>
  </div>
  <div class="section" style="margin-top:0">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px"><span style="width:28px;height:28px;border-radius:999px;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px">1</span><strong>What you need</strong></div>
    <ul style="margin:0 0 14px 28px;color:var(--muted);font-size:14px;line-height:1.6">
      <li>Payer name (for receipt) — add phone if you like (e.g., Juan Dela Cruz - 0912...)</li>
    </ul>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px"><span style="width:28px;height:28px;border-radius:999px;background:var(--blue);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px">2</span><strong>Steps</strong></div>

    <div class="steps-fig">

      <div class="step-row" id="other-step-1" data-export-name="other-01-select-campus">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">1</span><span><strong>Select campus & Proceed</strong> — choose the campus where you are requesting payment, then tap <em>Proceed to Payment</em>. No verification needed.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div style="display:grid;gap:10px">
            <div class="field"><label>Select Your Campus <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>Binan Campus — selected</option></select><small style="color:var(--muted)">Choose the campus where you are requesting payment.</small></div>
            <button class="btn btn-primary" style="width:100%;background:var(--blue);color:#fff;pointer-events:none"><i class="fa-solid fa-arrow-right"></i> Proceed to Payment</button>
          </div>
        </div>
        <div class="fig-caption">Fig. 1 — Real campus selector + Proceed from <code>guestold.php</code>.</div>
      </div>

      <div class="step-row" id="other-step-2" data-export-name="other-02-checkout-form">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">2</span><span><strong>Fill checkout form</strong> — payer name, amount, particulars, school year/sem, contact and email. Reference is optional for Other.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live">
          <div style="display:grid;gap:10px">
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Transaction ID</label><input type="text" value="UPHB_143022052026" readonly style="background:#f1f5f9;font-family:ui-monospace,monospace;font-size:12px"></div>
              <div class="field"><label>Amount (PHP) <span style="color:var(--err)">*</span></label><input type="text" value="₱ 1,500.00" readonly style="background:#fff;font-weight:800;color:var(--blue)"></div>
            </div>
            <div class="field"><label>Particulars <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>GOOD MORAL — selected</option></select></div>
            <div class="field"><label>Other description (if not listed)</label><input type="text" placeholder="If not listed, type custom description here" disabled style="background:#fff"></div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>For School Year <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>2024-2025</option></select></div>
              <div class="field"><label>For Semester <span style="color:var(--err)">*</span></label><select disabled style="opacity:1;background:#fff"><option>1st Sem</option></select></div>
            </div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Student / Payer Name <span style="color:var(--err)">*</span></label><input type="text" value="Juan Dela Cruz" readonly style="background:#fff"></div>
              <div class="field"><label>Reference (optional)</label><input type="text" placeholder="Optional" readonly style="background:#fff"></div>
            </div>
            <div class="field"><label>Contact Number</label><input type="text" value="09123456789" readonly style="background:#fff"></div>
            <div class="field"><label>Email Address <span style="color:var(--err)">*</span></label><input type="text" value="you@email.com" readonly style="background:#fff"></div>
          </div>
        </div>
        <div class="fig-caption">Fig. 2 — Real checkout from <code>paymentold.php</code> — same order as New/Enrolled.</div>
      </div>

      <div class="step-row" id="other-step-3" data-export-name="other-03-review-pay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">3</span><span><strong>Review & Pay</strong> — check the Payment Summary, confirm the checkbox, then <em>Pay Now via DragonPay</em>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="display:grid;gap:10px">
          <div style="background:#fffbe6;border:1px solid #fde68a;border-radius:12px;padding:12px">
            <div style="font-weight:800;font-size:13px;color:#92400e;margin-bottom:6px"><i class="fa-solid fa-eye"></i> Review your details</div>
            <div style="font-size:12px;color:#475569;margin-bottom:8px">Please check the <strong>Payment Summary</strong> — amount, particulars, payer name and email must be correct.</div>
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#1e293b"><input type="checkbox" checked disabled style="accent-color:var(--blue)"> I have reviewed my payment details and confirm they are correct.</label>
          </div>
          <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff;pointer-events:none"><i class="fa-solid fa-lock"></i> Pay Now via <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" style="height:22px;background:#fff;padding:2px 6px;border-radius:6px;vertical-align:middle;margin-left:2px"></button>
          <div class="pay-summary" style="padding:12px;border-radius:12px">
            <div style="display:flex;align-items:center;gap:8px"><i class="fa-solid fa-receipt"></i><strong>Payment Summary</strong><span style="margin-left:auto;background:rgba(255,255,255,.18);padding:4px 8px;border-radius:999px;font-size:11px">OTHER</span></div>
            <div class="row" style="font-size:13px"><span>Payee</span><strong>Juan Dela Cruz - 09123456789</strong></div>
            <div class="row" style="font-size:13px"><span>Amount</span><strong>₱ 1,500.00</strong></div>
            <div class="row" style="font-size:11px"><span>Description</span><strong style="font-size:11px">JUAN DELA CRUZ | Contact:09123456789 | GOOD MORAL | 2024-2025 1st Sem</strong></div>
          </div>
        </div>
        <div class="fig-caption">Fig. 3 — Review gate + Pay Now + Summary (identical flow for all types).</div>
      </div>

      <div class="step-row" id="other-step-4" data-export-name="other-04-dragonpay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">4</span><span><strong>Pay via DragonPay</strong> — Online Banking / E-Wallets / Over the Counter.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="display:grid;gap:8px;place-items:center;background:#fff">
          <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" class="dp-logo" style="height:30px">
          <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap"><span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none">Online Banking</span><span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none">E-Wallets</span><span class="btn" style="background:var(--blue);color:#fff;padding:8px 12px;font-size:11px;border:none">Over the Counter</span></div>
        </div>
        <div class="fig-caption">Fig. 4 — DragonPay options.</div>
      </div>

      <div class="step-row" id="other-step-5" data-export-name="other-05-receipt">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">5</span><span><strong>Present screenshot to cashier to claim official receipt</strong>.</span></div>
          <button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button>
        </div>
        <div class="fig fig-live" style="background:#f8fafc;display:grid;place-items:center;padding:14px">
          <div style="background:#fff;border:1px solid var(--line);border-radius:12px;padding:12px;width:100%;max-width:360px"><div style="color:var(--blue);font-weight:800;font-size:13px"><i class="fa-solid fa-circle-check" style="color:var(--blue)"></i> Payment Confirmed — DragonPay</div><div style="font-size:11px;color:var(--muted);margin-top:4px">Txn: UPHB_143022052026 • ₱1,500.00 • Juan Dela Cruz</div><div style="margin-top:8px;border:1px dashed var(--line);background:var(--bg);border-radius:8px;padding:8px;text-align:center;font-size:11px;color:var(--blue);font-weight:700"><i class="fa-solid fa-camera"></i> Screenshot & show at cashier</div></div>
        </div>
      </div>

    </div>
    <div class="alert info" style="margin-top:14px"><div><strong>No verification needed</strong> — for alumni, parents, guests, or any general fee.</div></div>
    <a href="<?= $payments_base ?>guestold" class="btn btn-primary no-print" style="margin-top:14px; background:var(--blue); color:#fff; width:100%; justify-content:center; border:none">Pay Other Fees</a>
  </div>
</div>

<div class="section after-payment" style="margin-top:14px">
  <h3 style="margin:0 0 8px; font-size:16px">After payment</h3>
  <ul style="margin:0 0 0 18px; color:var(--muted); font-size:13.5px; line-height:1.7">
    <li>You’ll be redirected to DragonPay to complete payment.</li>
    <li>Receipt is sent to the email you entered — keep your Transaction ID.</li>
    <li><strong style="color:var(--text)">Present a screenshot of the DragonPay confirmation to the cashier to claim official receipt</strong> — save or screenshot the success page and show it at the cashier window.</li>
    <li>For help: Accounting (02) 779-5310 — have your Transaction ID ready.</li>
  </ul>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.querySelectorAll('.tab-btn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-'+btn.dataset.tab).classList.add('active');
    history.replaceState({},'', '#'+btn.dataset.tab);
  });
});
if(location.hash){
  const h=location.hash.replace('#','');
  const b=document.querySelector('.tab-btn[data-tab="'+h+'"]');
  if(b) b.click();
}
document.querySelectorAll('.print-date').forEach(el=>{
  el.textContent = new Date().toLocaleDateString('en-PH', {year:'numeric', month:'long', day:'numeric'});
});

// sticky tabs + step follow-along
(function(){
  const tabsNav = document.querySelector('.tabs-navigation');
  const prog = document.getElementById('stepProgress');
  const progText = document.getElementById('stepProgressText');
  if(!tabsNav) return;
  const tabsOffset = tabsNav.offsetTop;
  function onScroll(){
    const stuck = window.scrollY > (tabsOffset - 84);
    tabsNav.classList.toggle('is-stuck', stuck);
    if(prog){
      const activeTab = document.querySelector('.tab-content.active');
      const show = activeTab && window.scrollY > (tabsOffset + 40);
      prog.style.display = show ? 'inline-flex' : 'none';
    }
  }
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();

  let observer = null;
  function getActiveSteps(){
    const tab = document.querySelector('.tab-content.active');
    return tab ? Array.from(tab.querySelectorAll('.step-row')) : [];
  }
  function updateProgress(activeRow){
    const steps = getActiveSteps();
    if(!steps.length || !progText) return;
    const idx = steps.indexOf(activeRow);
    const total = steps.length;
    const titleEl = activeRow.querySelector('.step-text strong');
    const title = titleEl ? titleEl.textContent.trim() : ('Step '+(idx+1));
    progText.innerHTML = '<strong>Step '+(idx+1)+' of '+total+'</strong> — '+ title;
    steps.forEach(r=>r.classList.toggle('is-active', r===activeRow));
  }
  function initObserver(){
    if(observer) observer.disconnect();
    const steps = getActiveSteps();
    if(!steps.length) return;
    // highlight first by default
    updateProgress(steps[0]);
    observer = new IntersectionObserver((entries)=>{
      // pick most visible
      let best = null, bestRatio = 0;
      entries.forEach(e=>{
        if(e.isIntersecting && e.intersectionRatio > bestRatio){
          bestRatio = e.intersectionRatio; best = e.target;
        }
      });
      if(best) updateProgress(best);
    }, { rootMargin: '-140px 0px -55% 0px', threshold: [0,0.25,0.5,0.75,1] });
    steps.forEach(s=>observer.observe(s));
  }
  // re-init when tab changes
  document.querySelectorAll('.tab-btn').forEach(btn=>{
    btn.addEventListener('click', ()=> setTimeout(initObserver, 80));
  });
  // also on hash change
  window.addEventListener('hashchange', ()=> setTimeout(initObserver, 80));
  // click step num to scroll
  document.addEventListener('click', (e)=>{
    const num = e.target.closest('.step-num');
    if(!num) return;
    const row = num.closest('.step-row');
    if(row) row.scrollIntoView({behavior:'smooth', block:'start'});
  });
  // init
  setTimeout(initObserver, 300);
  // on resize re-evaluate
  window.addEventListener('resize', onScroll);
})();

function showToast(msg){
  let t=document.getElementById('export-toast');
  if(!t){ t=document.createElement('div'); t.id='export-toast'; t.className='export-toast'; document.body.appendChild(t); }
  t.textContent=msg; t.classList.add('show');
  clearTimeout(t._hide); t._hide=setTimeout(()=>t.classList.remove('show'), 2200);
}

async function exportStep(btn){
  const row = btn.closest('.step-row');
  if(!row || typeof html2canvas === 'undefined'){ showToast('Export unavailable'); return; }
  const name = row.dataset.exportName || row.id || 'step';
  const origText = btn.innerHTML;
  btn.disabled=true; btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Saving…';
  btn.style.visibility='hidden';
  try{
    const canvas = await html2canvas(row, {
      scale: 2,
      backgroundColor: '#ffffff',
      useCORS: true,
      logging: false,
      windowWidth: row.scrollWidth,
      onclone: (clonedDoc)=>{
        const clonedRow = clonedDoc.getElementById(row.id);
        if(clonedRow){
          clonedRow.style.boxShadow='none';
          clonedRow.style.border='1px solid #e2e8f0';
          clonedRow.querySelectorAll('.step-export-btn').forEach(b=>b.style.display='none');
        }
      }
    });
    const ctx = canvas.getContext('2d');
    ctx.save();
    ctx.fillStyle = 'rgba(15,32,64,.55)';
    ctx.font = '600 ' + Math.max(10, Math.round(canvas.width/90)) + 'px Inter, sans-serif';
    ctx.textAlign = 'right';
    ctx.fillText('UPHSL Online Payments • '+ name, canvas.width - 14, canvas.height - 10);
    ctx.restore();
    const url = canvas.toDataURL('image/png');
    const a=document.createElement('a');
    a.href=url; a.download=name+'.png';
    document.body.appendChild(a); a.click(); a.remove();
    showToast('Saved '+name+'.png');
  }catch(e){
    console.error(e);
    showToast('Export failed');
  }finally{
    btn.style.visibility='';
    btn.disabled=false; btn.innerHTML=origText;
  }
}

async function exportAllVisible(){
  const tab = document.querySelector('.tab-content.active');
  if(!tab){ showToast('No tab selected'); return; }
  const rows = tab.querySelectorAll('.step-row');
  if(!rows.length){ showToast('No steps'); return; }
  showToast('Exporting '+rows.length+' images…');
  for(const row of rows){
    const btn = row.querySelector('.step-export-btn');
    if(btn) await exportStep(btn);
    await new Promise(r=>setTimeout(r, 400));
  }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
