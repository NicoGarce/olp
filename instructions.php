<?php
$page_title = "How to Pay Online - Instructions";
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/header.php';
$is_admin_tools = function_exists('olp_isAdmin') && olp_isAdmin();
?>
<section style="max-width:720px; margin:0 auto 14px">
  <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
    <a href="<?= $payments_base ?>" class="btn" style="background:#fff;border:1px solid var(--line); padding:7px 12px; font-size:12px"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
    <span style="color:var(--muted);font-weight:600; font-size:12px">How to Pay • Pay Folder</span>
  </div>
  <div style="text-align:center">
    <h1 style="margin:0; font-size:28px; font-family:'Plus Jakarta Sans',sans-serif">How to Pay Online</h1>
    <p style="color:var(--muted); margin:8px 0 0; font-size:14.5px">Choose your payment type. Each tab shows exactly what to do.</p>
    <?php if (!empty($is_admin_tools)): ?>
    <div style="display:flex;gap:8px;justify-content:center;margin-top:12px" class="no-print">
      <button onclick="window.print()" class="btn" style="background:#fff; border:1px solid var(--line); padding:9px 16px; font-size:13px"><i class="fa-solid fa-print"></i> Print guide</button>
      <button onclick="exportAllVisible()" class="btn" style="background:var(--blue); color:#fff; border:1px solid var(--blue); padding:9px 16px; font-size:13px"><i class="fa-solid fa-images"></i> Export all steps</button>
    </div>
    <?php endif; ?>
  </div>
</section>

<div style="max-width:720px;margin:0 auto 14px;display:flex;gap:10px;align-items:flex-start;background:#fffbeb;border:1px solid #fcd34d;border-radius:14px;padding:12px 14px;color:#92400e;font-size:13px;line-height:1.5">
  <i class="fa-solid fa-triangle-exclamation" style="font-size:18px;color:#d97706;flex-shrink:0;margin-top:1px"></i>
  <div><strong>Reminder — Read carefully:</strong> Please read all instructions carefully and follow each step in order. Double-check your campus, ID/locator, and payment details before proceeding to avoid delays or failed verification.</div>
</div>

<div class="tabs-navigation no-print" role="tablist" style="justify-content:center" id="tabsTop">
  <button class="tab-btn" data-tab="new">New Enrollee</button>
  <button class="tab-btn active" data-tab="enrolled">Enrolled</button>
  <button class="tab-btn" data-tab="other">Other Payment</button>
</div>
<div id="stepProgress" class="step-progress no-print" aria-live="polite" style="display:none"><span class="sp-dot"></span><span id="stepProgressText">Step 1 of 8</span></div>

<style>
.steps-fig{display:grid;gap:24px;margin:8px 0 16px}
.step-row + .step-row{margin-top:2px}
/* extra breathing room for last transition (6→7) which was still tight */
#new-step-7, #enrolled-step-7, #other-step-4{margin-top:10px}
@media(max-width:640px){#new-step-7, #enrolled-step-7, #other-step-4{margin-top:14px}}
.step-row{border:1px solid var(--line);border-radius:16px;padding:16px;background:#fff;display:grid;gap:10px;position:relative;overflow:hidden;scroll-margin-top:140px;transition:border-color .2s,box-shadow .2s, transform .55s cubic-bezier(.16,1,.3,1), opacity .55s ease; will-change:transform,opacity; margin:0}
.step-row.is-active{border-color:var(--blue);box-shadow:0 8px 24px rgba(28,77,161,.12)}
/* scroll-reveal: each step fades/slides in as it enters viewport */
.step-row.reveal{opacity:0; transform:translateY(18px) scale(.98)}
.step-row.reveal.in-view{opacity:1; transform:translateY(0) scale(1)}
.step-row.reveal.in-view .step-row-head{animation: headIn .45s ease both}
.step-row.reveal.in-view .fig{animation: figIn .5s cubic-bezier(.16,1,.3,1) .08s both}
@keyframes headIn{from{opacity:0; transform:translateY(8px)} to{opacity:1; transform:translateY(0)}}
@keyframes figIn{from{opacity:0; transform:translateY(10px) scale(.98)} to{opacity:1; transform:translateY(0) scale(1)}}
.step-row.reveal.in-view .step-num{animation: numPop .5s cubic-bezier(.34,1.56,.64,1) .2s both}
@keyframes numPop{0%{transform:scale(.7)} 60%{transform:scale(1.08)} 100%{transform:scale(1)}}
@media(prefers-reduced-motion:reduce){
  .step-row.reveal{opacity:1; transform:none; transition:none}
  .step-row.reveal.in-view .step-row-head,
  .step-row.reveal.in-view .fig,
  .step-row.reveal.in-view .step-num{animation:none}
}
.step-row-head{display:flex;align-items:flex-start;gap:10px;justify-content:space-between}
.step-text{display:flex;gap:10px;align-items:flex-start;font-size:13.5px;line-height:1.6;color:var(--text);flex:1}
.step-text .step-num{flex-shrink:0;width:28px;height:28px;border-radius:999px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12.5px;background:var(--blue);color:#fff}
.step-export-btn{flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;border:1px solid var(--line);background:#fff;color:var(--blue);font-weight:800;font-size:11.5px;cursor:pointer;transition:.18s;white-space:nowrap}
.step-export-btn:hover{background:var(--blue);color:#fff;border-color:var(--blue);box-shadow:0 4px 12px rgba(28,77,161,.18)}
.step-export-btn:disabled{opacity:.6;cursor:wait}
.fig{border:1px solid #e2e8f0;border-radius:14px;padding:14px;background:#fbfdff}
.fig .field{margin:0}
.fig .field label{font-size:11px}
.fig .field input,.fig .field select{pointer-events:auto}
.fig-live{pointer-events:auto}
.fig-caption{font-size:11px;color:#94a3b8;text-align:center;margin-top:2px;font-style:italic}
.export-toast{position:fixed;bottom:18px;left:50%;transform:translateX(-50%) translateY(20px);background:#0f2040;color:#fff;padding:10px 16px;border-radius:999px;font-size:13px;font-weight:700;box-shadow:0 10px 30px rgba(0,0,0,.25);opacity:0;transition:.25s;z-index:9999}
.export-toast.show{opacity:1;transform:translateX(-50%) translateY(0)}
@media(max-width:640px){.step-row-head{flex-direction:column}.step-export-btn{align-self:flex-start}}
/* sticky tabs - follow along */
.tabs-navigation{position:sticky;top:84px;z-index:25;background:rgba(246,248,252,.92);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);margin:0 -20px;padding:10px 20px 12px;border-bottom:2px solid #e2e8f0;justify-content:center}
.tabs-navigation.is-stuck{box-shadow:0 6px 18px rgba(15,32,64,.08);border-bottom-color:var(--line)}
.step-progress{position:sticky;top:138px;z-index:24;background:#fff;border:1px solid var(--line);border-radius:999px;padding:6px 12px;display:inline-flex;align-items:center;gap:8px;font-size:12px;font-weight:700;color:var(--muted);box-shadow:0 4px 16px rgba(15,32,64,.06);margin:10px auto 0;left:50%;transform:translateX(-50%);max-width:calc(100% - 32px);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.step-progress strong{color:var(--blue)}
.step-progress .sp-dot{width:8px;height:8px;border-radius:999px;background:var(--blue);flex-shrink:0;animation:pulse 1.6s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.5}}
@media(max-width:860px){.tabs-navigation{top:68px}.step-progress{top:122px}.step-row{scroll-margin-top:165px}}
@media(max-width:640px){
  .tabs-navigation{top:64px;padding:8px 14px 10px;margin:0 -14px;justify-content:flex-start;overflow-x:auto;flex-wrap:nowrap;gap:6px;scrollbar-width:none;-webkit-overflow-scrolling:touch}
  .tabs-navigation::-webkit-scrollbar{display:none}
  .tab-btn{flex:0 0 auto;min-height:38px}
  .step-progress{top:112px;font-size:11px;padding:5px 10px}
  .step-row{scroll-margin-top:175px}
  .tabs-navigation--bottom{margin:20px -14px 0;padding-left:14px;padding-right:14px;overflow-x:auto;flex-wrap:nowrap;justify-content:flex-start;gap:6px;scrollbar-width:none}
  .tabs-navigation--bottom::-webkit-scrollbar{display:none}
  .tabs-navigation--bottom .tab-btn{flex:0 0 auto}
}
@media(max-width:480px){
  .tabs-navigation{gap:6px;padding-left:14px;padding-right:14px}
  .tab-btn{padding:9px 12px;font-size:12px;min-height:40px}
.step-row{scroll-margin-top:180px}
}
@media(max-width:380px){.tabs-navigation{top:60px}.step-progress{top:106px}
  .step-row{scroll-margin-top:185px}
  .tab-btn{font-size:11px;padding:7px 10px}
}
@media print{.tabs-navigation,.step-progress{display:none !important}}
/* Dragonpay sample — faithful replica scoped to .dp-sample — uses exact CSS you provided */
.dp-sample{font-family:Verdana, Arial, Helvetica, sans-serif}
.dp-sample p{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;color:#000;margin:0}
.dp-sample a:link{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;color:#003399;text-decoration:underline}
.dp-sample .text{font-family:Verdana, Arial, Helvetica, sans-serif;font-size:12px;color:#000}
.dp-sample .dropdownList{width:300px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;padding:2px;border:1px solid #7f9db9;background:#fff;border-radius:6px}
.dp-sample .workarea{background-color:#fff;border:1px solid #000;padding:10px;width:400px;min-height:320px;text-align:left;margin:0 auto;box-sizing:border-box;border-radius:14px;overflow:hidden;box-shadow:0 4px 16px rgba(15,32,64,.08)}
.dp-sample .workarea img{max-width:100%}
.dp-sample select option[disabled]{color:red}
.dp-sample #ContentPlaceHolder1_selectButton{height:30px;width:120px;font-family:Verdana,Arial,Helvetica,sans-serif;font-size:11px;cursor:pointer;border-radius:6px;border:1px solid #7f9db9;background:#fff;transition:.15s}
.dp-sample #ContentPlaceHolder1_selectButton:hover{background:#f1f5f9}
.dp-sample #ContentPlaceHolder1_selectButton:active{transform:scale(.98)}
.dp-sample #ContentPlaceHolder1_errorLabel{color:Red}
.dp-sample .dropdownList:focus{outline:none;border-color:#1c4da1;box-shadow:0 0 0 3px rgba(28,77,161,.12)}
/* interactive demo — override global fig-live  */
.fig.dp-sample{pointer-events:auto}
.dp-sample select,.dp-sample input,.dp-sample label,.dp-sample a,.dp-sample button{pointer-events:auto}
.dp-sample select{cursor:pointer}
@media screen and (max-width:480px), screen and (max-device-width:480px){
  .dp-sample .dropdownList{width:100%;max-width:280px}
  .dp-sample .workarea{border-style:none;width:100%;height:auto;min-height:350px;padding:4px;margin-top:1px;border-radius:12px}
  .dp-sample #ContentPlaceHolder1_errorLabel{font-size:12px;margin-top:4px;margin-bottom:8px}
}
</style>
<noscript><style>.step-row.reveal{opacity:1 !important; transform:none !important} .step-row.reveal .fig,.step-row.reveal .step-num{animation:none !important}</style></noscript>

<!-- NEW ENROLLEE -->
<div id="tab-new" class="tab-content">
  <div class="print-only" style="text-align:center; margin-bottom:10px; padding:12px; background:#f1f5f9; border:1px solid #ddd; border-radius:12px">
    <div style="font-weight:800; font-size:14px; color:var(--blue); letter-spacing:.04em; text-transform:uppercase">New Enrollee Guide</div>
  </div>
  <div class="section" style="margin-top:0">
<div id="new-what" style="display:flex;align-items:center;gap:8px;margin-bottom:10px;scroll-margin-top:160px"><strong style="font-size:14px">What you need</strong></div>
    <ul style="margin:0 0 14px 28px;color:var(--muted);font-size:14px;line-height:1.6">
      <li>Campus where you were advised</li>
      <li>Locator number — on your <strong style="color:var(--text)">white form</strong>, above your name</li>
    </ul>
    <div id="new-steps-head" style="display:flex;align-items:center;gap:8px;margin-bottom:4px"><strong style="font-size:14px">Steps</strong></div>
    

    <div class="steps-fig">

      <div class="step-row reveal" id="new-step-1" data-export-name="new-01-select-campus">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">1</span><span><strong>Select campus</strong> — pick where you were advised. The locator box appears after you choose.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live">
          <div class="field">
            <label>Select Your Campus</label>
                        <select style="background:#fff">
              <option value="" selected disabled hidden>Select Campus</option>
              <option value="UPHB">Binan Campus</option>
              <option value="UPHMU">Medical University</option>
              <option value="UPHG">GMA Campus</option>
              <option value="UPHM">Manila Campus</option>
              <option value="PHCP">Pangasinan Campus</option>
              <option value="UPHI">Isabela Campus</option>
              <option value="UPHR">Roxas Campus</option>
            </select>
            <small style="color:var(--muted)">Choose where you were advised.</small>
          </div>
          
        </div>
      </div>

      <div class="step-row reveal" id="new-step-2" data-export-name="new-02-enter-locator">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">2</span><span><strong>Enter locator</strong> — copy the number from the <strong>white form above your name</strong>, tap <em>Verify</em>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live">
          <div style="background:var(--bg);border:1px solid var(--line);border-radius:14px;padding:14px">
            <h4 style="margin:0 0 8px;color:var(--blue)"><i class="fa-solid fa-magnifying-glass"></i> Verify Locator Number</h4>
            <div style="text-align:center;color:var(--muted);font-size:13px;margin-bottom:10px">Please verify your locator number before proceeding</div>
            <div class="verify-box">
              <div class="field" style="margin:0"><label>Locator Number</label><input type="text" value="26417244" style="background:#fff;font-weight:700;color:var(--blue)" readonly></div>
              <button type="button" class="btn-verify" style="opacity:1"><i class="fa-solid fa-magnifying-glass"></i> Verify</button>
            </div>
          </div>
        </div>
      </div>

      <div class="step-row reveal" id="new-step-3" data-export-name="new-03-confirm-name">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">3</span><span><strong>Confirm name</strong> — check the card that appears (avatar + name + locator + campus), tap <em>Yes, it’s me</em>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live" style="background:#fff">
          <div class="verify-card">
            <div class="verify-card-top ok"><div class="verify-icon"><i class="fa-solid fa-check"></i></div><div><div class="verify-title">Verified</div><div class="verify-subtitle">Locator verified successfully!</div></div></div>
            <div class="verify-card-body">
              <div class="verify-profile"><div class="verify-avatar">JD</div><div><div class="verify-name">Juan Dela Cruz</div><div class="verify-sub">Is this you? Tap Confirm if correct</div></div></div>
              <div class="verify-meta"><span><i class="fa-solid fa-hashtag"></i> 26417244</span><span><i class="fa-solid fa-building-columns"></i> Binan Campus</span></div>
              <div class="verify-hint">Found in campus records. Confirm to proceed with payment.</div>
            </div>
            <div class="verify-actions"><span class="btn btn-primary" style="flex:1;justify-content:center"><i class="fa-solid fa-check"></i> Yes, it’s me</span><span class="btn btn-secondary" style="flex:1;justify-content:center"><i class="fa-solid fa-xmark"></i> Not mine</span></div>
          </div>
        </div>
      </div>

      <div class="step-row reveal" id="new-step-4" data-export-name="new-04-proceed-button">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">4</span><span><strong>Click Proceed to Payment</strong> — after tapping <em>Yes, it’s me</em> the button appears. The helper text changes to confirmed.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live">
          <div style="background:var(--bg);border:1px solid var(--line);border-radius:14px;padding:14px">
            <div style="text-align:center;color:var(--ok);font-size:13px;font-weight:700;margin-bottom:10px"><i class="fa-solid fa-circle-check"></i> ✓ Name confirmed! You may now proceed.</div>
            <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff"><i class="fa-solid fa-credit-card"></i> Proceed to Payment</button>
            <div style="text-align:center;font-size:11px;color:var(--muted);margin-top:8px">This is the real <code>#btnsubmit</code> from <code>guest.php</code> — it was hidden until verification.</div>
          </div>
        </div>
      </div>

            <div class="step-row reveal" id="new-step-5" data-export-name="new-05-checkout-review">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">5</span><span><strong>Fill checkout form, review & Pay</strong> — fill amount, particulars, school year/sem, payer name, contact and email (locator locked, Transaction ID prefilled), then check Payment Summary, tick confirmation, and click <em>Pay Now via DragonPay</em>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live" style="display:grid;gap:12px">
          <div style="display:grid;gap:10px">
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Transaction ID</label><input type="text" value="UPHB_143022052026" style="background:#f1f5f9;font-family:ui-monospace,monospace;font-size:12px" readonly></div>
              <div class="field"><label>Amount (PHP) <span style="color:var(--err)">*</span></label><input type="text" value="₱ 5,000.00" style="background:#fff;font-weight:800;color:var(--blue)" readonly></div>
            </div>
            <div class="field"><label>Particulars <span style="color:var(--err)">*</span></label><select style="background:#fff"><option>DOWNPAYMENT — selected (locked for New Enrollee)</option></select></div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>For School Year <span style="color:var(--err)">*</span></label><select style="background:#fff">
              <option>2025-2026</option>
              <option>2024-2025</option>
              <option>2023-2024</option>
            </select></div>
              <div class="field"><label>For Semester <span style="color:var(--err)">*</span></label><select style="background:#fff">
              <option>1st Sem</option>
              <option>2nd Sem</option>
              <option>Summer</option>
            </select></div>
            </div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Student / Payer Name <span style="color:var(--err)">*</span></label><input type="text" value="Juan Dela Cruz" style="background:#fff" readonly></div>
              <div class="field"><label>Locator Number</label><input type="text" value="26417244" style="background:#eef2ff;color:var(--blue);font-weight:800;text-align:center" readonly></div>
            </div>
            <div class="field"><label>Contact Number</label><input type="text" value="09123456789" style="background:#fff" readonly></div>
            <div class="field"><label>Email Address <span style="color:var(--err)">*</span></label><input type="text" value="you@email.com" style="background:#fff" readonly></div>
          </div>
          <div style="height:1px;background:var(--line);margin:2px 0"></div>
          <div style="background:#fffbe6;border:1px solid #fde68a;border-radius:12px;padding:12px">
            <div style="font-weight:800;font-size:13px;color:#92400e;margin-bottom:6px"><i class="fa-solid fa-eye"></i> Review your details</div>
            <div style="font-size:12px;color:#475569;margin-bottom:8px">Please check the <strong>Payment Summary</strong> — amount, particulars, school year, semester, payer name and email must be correct.</div>
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#1e293b"><input type="checkbox" checked style="accent-color:var(--blue)"> I have reviewed my payment details and confirm they are correct.</label>
          </div>
          <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff"><i class="fa-solid fa-lock"></i> Pay Now via <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" style="height:22px;background:#fff;padding:2px 6px;border-radius:6px;vertical-align:middle;margin-left:2px"></button>
          <div class="pay-summary" style="padding:12px;border-radius:12px">
            <div style="display:flex;align-items:center;gap:8px"><i class="fa-solid fa-receipt"></i><strong>Payment Summary</strong><span style="margin-left:auto;background:rgba(255,255,255,.18);padding:4px 8px;border-radius:999px;font-size:11px">NEW</span></div>
            <div class="row" style="font-size:13px"><span>Payee</span><strong>Juan Dela Cruz - 09123456789</strong></div>
            <div class="row" style="font-size:13px"><span>Amount</span><strong>₱ 5,000.00</strong></div>
            <div class="row" style="font-size:11px"><span>Description</span><strong style="font-size:11px">JUAN DELA CRUZ | ID:26417244 | Contact:09123456789 | DOWNPAYMENT | 2025-2026 1st Sem</strong></div>
          </div>
        </div>
      </div>
      </div>

      <div class="step-row reveal" id="new-step-6" data-export-name="new-06-dragonpay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">6</span><span><strong>Pay via DragonPay</strong> — choose <strong>Online Banking, E-Wallets, or Over the Counter</strong>, then complete. Keep your Transaction ID.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig dp-sample" style="padding:10px;display:grid;place-items:center;background:#fff">
          <!-- Faithful replica of Dragonpay Pay.aspx workarea — exact CSS you provided, scoped to .dp-sample -->
          <div class="workarea" id="ContentPlaceHolder1_Panel1">
            <img id="ContentPlaceHolder1_logoImage" src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="Dragonpay" style="height:28px;display:block">
            <br style="clear:both"><br>
            <div id="ContentPlaceHolder1_UpdatePanel1">
              <p><span id="ContentPlaceHolder1_mainMsg" style="display:inline-block;height:30px;">University of Perpetual Help System Laguna, Inc is requesting for <b>PHP1,000.00</b></span></p>
              <span id="ContentPlaceHolder1_SourceLabel" class="text" style="display:inline-block;width:45px;">Source</span>
              <select name="ctl00$ContentPlaceHolder1$fundSourceList" id="ContentPlaceHolder1_fundSourceList" class="dropdownList">
                <option selected value="" style="color:red;">---- SELECT A PAYMENT OPTION ----</option>
                <option value="DPAY">Dragonpay Prepaid Credits</option>
                <option value="" style="color:red;" disabled>------ ONLINE BANKING / E-WALLET ------</option>
                <option value="BOC">Bank of Commerce Online</option>
                <option value="BDO">BDO Fund Transfer (OLD)</option>
                <option value="BDRE">BDO Online E-Payment (NEW)</option>
                <option value="BPIA">BPI Online</option>
                <option value="CBDD">China Bank Mobile Banking</option>
                <option value="CBCB">China Bank Online Bills Payment</option>
                <option value="INPY">Instapay from any bank/ewallet</option>
                <option value="MAYB">Maybank Online Banking</option>
                <option value="PSNT">PESONet from any bank/ewallet</option>
                <option value="ABQR">QRPH (NEW)</option>
                <option value="RCDD">RCBC Online Direct Debit</option>
                <option value="UBDD">Unionbank Online Banking</option>
                <option value="GCSB">GCash Bills Pay</option>
                <option value="PYMB">Maya Bills Pay</option>
                <option value="" style="color:red;" disabled>----- OVER-THE-COUNTER/ATM BANKING -----</option>
                <option value="AUB">AUB Online/Cash Payment</option>
                <option value="BDOA">Banco de Oro ATM</option>
                <option value="BDRX">BDO Cash Deposit w/ Ref</option>
                <option value="BNRX">BDO Network Bank (formerly ONB) Cash Dep</option>
                <option value="BDXB">BDO Over-the-Counter Bills Payment</option>
                <option value="BPXB">BPI Cash Payment</option>
                <option value="CBXB">China Bank Cash Payment</option>
                <option value="PNXB">PNB Cash Payment</option>
                <option value="PNBB">PNB Internet Banking Bills Payment</option>
                <option value="RCXB">RCBC ATM/Cash Payment</option>
                <option value="SBCB">Security Bank Cash Payment</option>
                <option value="UBXB">Unionbank Cash Payment</option>
                <option value="" style="color:red;" disabled>----- OVER-THE-COUNTER OTHERS -----</option>
                <option value="711">7-Eleven</option>
                <option value="BAYD">Bayad Center</option>
                <option value="CEBL">Cebuana Lhuillier Bills Payment</option>
                <option value="ECPY">ECPay (GCash/Payment Centers)</option>
                <option value="ETAP">eTap</option>
                <option value="MLH">M. Lhuillier</option>
                <option value="PLWN">Palawan Pawnshop</option>
                <option value="PRHB">Perahub</option>
                <option value="POSB">Posible (Family Mart, Phoenix)</option>
                <option value="RDS">Robinsons Dept Store</option>
                <option value="SMR">SM Dept/Supermarket/Savemore Counter</option>
                <option value="USSC">USSC</option>
                <option value="VLRC">Villarica Pawnshop</option>
              </select>
              <div style="padding-top:8px">
                <span id="ContentPlaceHolder1_errorLabel" class="text" style="display:inline-block;color:Red;width:100%;">Select from the available fund sources</span>
                <div id="ContentPlaceHolder1_tacPanel" style="height:24px;width:100%;margin-top:4px">
                  <span class="text" title="You must agree to our Terms and Conditions before you can proceed."><input id="ContentPlaceHolder1_tacCheckBox" type="checkbox"><label for="ContentPlaceHolder1_tacCheckBox"> I agree to the <a href="https://www.dragonpay.ph/terms-and-conditions" target="_blank">Terms and Conditions</a></label></span>
                </div>
              </div>
              <div style="margin:8px"></div>
              <input type="submit" name="ctl00$ContentPlaceHolder1$selectButton" value="Select" id="ContentPlaceHolder1_selectButton"><br style="clear:right">
            </div>
          </div>
        </div>
      </div>

      <div class="step-row reveal" id="new-step-7" data-export-name="new-07-receipt">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">7</span><span><strong>Present screenshot to cashier to claim official receipt</strong> — after payment, screenshot the DragonPay confirmation and present it to the cashier.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
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
<div id="tab-enrolled" class="tab-content active">
  <div class="print-only" style="text-align:center; margin-bottom:10px; padding:12px; background:#f1f5f9; border:1px solid #ddd; border-radius:12px">
    <div style="font-weight:800; font-size:14px; color:var(--blue); letter-spacing:.04em; text-transform:uppercase">Enrolled Guide</div>
  </div>
  <div class="section" style="margin-top:0">
<div id="enrolled-what" style="display:flex;align-items:center;gap:8px;margin-bottom:10px;scroll-margin-top:160px"><strong style="font-size:14px">What you need</strong></div>
    <ul style="margin:0 0 14px 28px;color:var(--muted);font-size:14px;line-height:1.6">
      <li>Campus</li>
      <li>Student ID — on your <strong style="color:var(--text)">yellow form</strong>, above your name</li>
    </ul>
    <div id="enrolled-steps-head" style="display:flex;align-items:center;gap:8px;margin-bottom:4px"><strong style="font-size:14px">Steps</strong></div>
    

    <div class="steps-fig">

      <div class="step-row reveal" id="enrolled-step-1" data-export-name="enrolled-01-select-campus">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">1</span><span><strong>Select campus</strong>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live">
          <div class="field"><label>Select Your Campus</label><select style="background:#fff">
              <option value="" selected disabled hidden>Select Campus</option>
              <option value="UPHB">Binan Campus</option>
              <option value="UPHMU">Medical University</option>
              <option value="UPHG">GMA Campus</option>
              <option value="UPHM">Manila Campus</option>
              <option value="PHCP">Pangasinan Campus</option>
              <option value="UPHI">Isabela Campus</option>
              <option value="UPHR">Roxas Campus</option>
            </select></div>
        </div>
      </div>

      <div class="step-row reveal" id="enrolled-step-2" data-export-name="enrolled-02-student-number">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">2</span><span><strong>Enter Student ID</strong> — copy the number from the <strong>yellow form above your name</strong>, tap <em>Verify</em>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live">
          <div style="background:var(--bg);border:1px solid var(--line);border-radius:14px;padding:14px">
            <h4 style="margin:0 0 8px;color:var(--blue)"><i class="fa-solid fa-shield-check"></i> Verify Student Number</h4>
            <div class="verify-box">
              <div class="field" style="margin:0"><label>Student Number</label><input type="text" value="26-1234-567" style="background:#fff;font-weight:700;color:var(--blue)" readonly></div>
              <button class="btn-verify" style="opacity:1"><i class="fa-solid fa-magnifying-glass"></i> Verify</button>
            </div>
          </div>
        </div>
      </div>

      <div class="step-row reveal" id="enrolled-step-3" data-export-name="enrolled-03-confirm">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">3</span><span><strong>Confirm name</strong> — verify the card (avatar + student number + campus).</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live">
          <div class="verify-card">
            <div class="verify-card-top ok"><div class="verify-icon"><i class="fa-solid fa-check"></i></div><div><div class="verify-title">Verified</div><div class="verify-subtitle">Student verified successfully!</div></div></div>
            <div class="verify-card-body"><div class="verify-profile"><div class="verify-avatar">MA</div><div><div class="verify-name">Maria Santos</div><div class="verify-sub">Is this you?</div></div></div><div class="verify-meta"><span><i class="fa-solid fa-id-card"></i> 26-1234-567</span><span><i class="fa-solid fa-building-columns"></i> Manila Campus</span></div></div>
            <div class="verify-actions"><span class="btn btn-primary" style="flex:1;justify-content:center"><i class="fa-solid fa-check"></i> Yes, it’s me</span><span class="btn btn-secondary" style="flex:1;justify-content:center"><i class="fa-solid fa-xmark"></i> Not mine</span></div>
          </div>
        </div>
      </div>

      <div class="step-row reveal" id="enrolled-step-4" data-export-name="enrolled-04-proceed-button">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">4</span><span><strong>Click Proceed to Payment</strong> — after confirming, the button appears and the helper text turns to confirmed.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live">
          <div style="background:var(--bg);border:1px solid var(--line);border-radius:14px;padding:14px">
            <div style="text-align:center;color:var(--ok);font-size:13px;font-weight:700;margin-bottom:10px"><i class="fa-solid fa-circle-check"></i> ✓ Student confirmed! You may now proceed.</div>
            <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff"><i class="fa-solid fa-credit-card"></i> Proceed to Payment</button>
          </div>
        </div>
      </div>

            <div class="step-row reveal" id="enrolled-step-5" data-export-name="enrolled-05-checkout-review">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">5</span><span><strong>Fill checkout form, review & Pay</strong> — choose particulars, amount, school year/sem, payer name, contact and email (student number locked), then check Payment Summary, tick confirmation, and click <em>Pay Now via DragonPay</em>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live" style="display:grid;gap:12px">
          <div style="display:grid;gap:10px">
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Transaction ID</label><input type="text" value="UPHM_143022052026" style="background:#f1f5f9;font-family:ui-monospace,monospace;font-size:12px" readonly></div>
              <div class="field"><label>Amount (PHP) <span style="color:var(--err)">*</span></label><input type="text" value="₱ 8,250.00" style="background:#fff;font-weight:800;color:var(--blue)" readonly></div>
            </div>
            <div class="field"><label>Particulars <span style="color:var(--err)">*</span></label><select style="background:#fff">
              <option>Tuition Fee — selected</option>
              <option>DOWNPAYMENT</option>
              <option>BACK ACCOUNT</option>
              <option>CERTIFICATION</option>
              <option>GOOD MORAL</option>
            </select></div>
            <div class="field"><label>Other description (if not listed)</label><input type="text" placeholder="If not listed, type custom description here" disabled style="background:#fff" readonly></div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>For School Year <span style="color:var(--err)">*</span></label><select style="background:#fff">
              <option>2024-2025</option>
              <option>2023-2024</option>
              <option>2025-2026</option>
            </select></div>
              <div class="field"><label>For Semester <span style="color:var(--err)">*</span></label><select style="background:#fff">
              <option>1st Sem</option>
              <option>2nd Sem</option>
              <option>Summer</option>
            </select></div>
            </div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Student / Payer Name <span style="color:var(--err)">*</span></label><input type="text" value="Maria Santos" style="background:#fff" readonly></div>
              <div class="field"><label>Student Number</label><input type="text" value="26-1234-567" style="background:#eef2ff;color:var(--blue);font-weight:800;text-align:center" readonly></div>
            </div>
            <div class="field"><label>Contact Number</label><input type="text" value="09123456780" style="background:#fff" readonly></div>
            <div class="field"><label>Email Address <span style="color:var(--err)">*</span></label><input type="text" value="maria@email.com" style="background:#fff" readonly></div>
          </div>
          <div style="height:1px;background:var(--line);margin:2px 0"></div>
          <div style="background:#fffbe6;border:1px solid #fde68a;border-radius:12px;padding:12px">
            <div style="font-weight:800;font-size:13px;color:#92400e;margin-bottom:6px"><i class="fa-solid fa-eye"></i> Review your details</div>
            <div style="font-size:12px;color:#475569;margin-bottom:8px">Please check the <strong>Payment Summary</strong> — everything must be correct before proceeding.</div>
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#1e293b"><input type="checkbox" checked style="accent-color:var(--blue)"> I have reviewed my payment details and confirm they are correct.</label>
          </div>
          <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff"><i class="fa-solid fa-lock"></i> Pay Now via <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" style="height:22px;background:#fff;padding:2px 6px;border-radius:6px;vertical-align:middle;margin-left:2px"></button>
          <div class="pay-summary" style="padding:12px;border-radius:12px">
            <div style="display:flex;align-items:center;gap:8px"><i class="fa-solid fa-receipt"></i><strong>Payment Summary</strong><span style="margin-left:auto;background:rgba(255,255,255,.18);padding:4px 8px;border-radius:999px;font-size:11px">ENROLLED</span></div>
            <div class="row" style="font-size:13px"><span>Payee</span><strong>Maria Santos - 09123456780</strong></div>
            <div class="row" style="font-size:13px"><span>Amount</span><strong>₱ 8,250.00</strong></div>
            <div class="row" style="font-size:11px"><span>Description</span><strong style="font-size:11px">MARIA SANTOS | ID:26-1234-567 | Contact:09123456780 | TUITION FEE | 2024-2025 1st Sem</strong></div>
          </div>
        </div>
      </div>
      </div>

      <div class="step-row reveal" id="enrolled-step-6" data-export-name="enrolled-06-dragonpay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">6</span><span><strong>Pay via DragonPay</strong> — same <code>workarea</code> selector — pick Source, agree T&amp;C, Select.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig dp-sample" style="padding:10px;display:grid;place-items:center;background:#fff">
          <div class="workarea">
            <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="Dragonpay" style="height:28px;display:block">
            <br style="clear:both"><br>
            <div>
              <p><span style="display:inline-block;height:30px;">University of Perpetual Help System Laguna, Inc is requesting for <b>PHP1,000.00</b></span></p>
              <span class="text" style="display:inline-block;width:45px;">Source</span>
                            <select class="dropdownList">
                <option selected value="" style="color:red;">---- SELECT A PAYMENT OPTION ----</option>
                <option value="DPAY">Dragonpay Prepaid Credits</option>
                <option value="" style="color:red;" disabled>------ ONLINE BANKING / E-WALLET ------</option>
                <option value="BOC">Bank of Commerce Online</option>
                <option value="BDO">BDO Fund Transfer (OLD)</option>
                <option value="BDRE">BDO Online E-Payment (NEW)</option>
                <option value="BPIA">BPI Online</option>
                <option value="CBDD">China Bank Mobile Banking</option>
                <option value="CBCB">China Bank Online Bills Payment</option>
                <option value="INPY">Instapay from any bank/ewallet</option>
                <option value="MAYB">Maybank Online Banking</option>
                <option value="PSNT">PESONet from any bank/ewallet</option>
                <option value="ABQR">QRPH (NEW)</option>
                <option value="RCDD">RCBC Online Direct Debit</option>
                <option value="UBDD">Unionbank Online Banking</option>
                <option value="GCSB">GCash Bills Pay</option>
                <option value="PYMB">Maya Bills Pay</option>
                <option value="" style="color:red;" disabled>----- OVER-THE-COUNTER/ATM BANKING -----</option>
                <option value="AUB">AUB Online/Cash Payment</option>
                <option value="BDOA">Banco de Oro ATM</option>
                <option value="BDRX">BDO Cash Deposit w/ Ref</option>
                <option value="BNRX">BDO Network Bank (formerly ONB) Cash Dep</option>
                <option value="BDXB">BDO Over-the-Counter Bills Payment</option>
                <option value="BPXB">BPI Cash Payment</option>
                <option value="CBXB">China Bank Cash Payment</option>
                <option value="PNXB">PNB Cash Payment</option>
                <option value="PNBB">PNB Internet Banking Bills Payment</option>
                <option value="RCXB">RCBC ATM/Cash Payment</option>
                <option value="SBCB">Security Bank Cash Payment</option>
                <option value="UBXB">Unionbank Cash Payment</option>
                <option value="" style="color:red;" disabled>----- OVER-THE-COUNTER OTHERS -----</option>
                <option value="711">7-Eleven</option>
                <option value="BAYD">Bayad Center</option>
                <option value="CEBL">Cebuana Lhuillier Bills Payment</option>
                <option value="ECPY">ECPay (GCash/Payment Centers)</option>
                <option value="ETAP">eTap</option>
                <option value="MLH">M. Lhuillier</option>
                <option value="PLWN">Palawan Pawnshop</option>
                <option value="PRHB">Perahub</option>
                <option value="POSB">Posible (Family Mart, Phoenix)</option>
                <option value="RDS">Robinsons Dept Store</option>
                <option value="SMR">SM Dept/Supermarket/Savemore Counter</option>
                <option value="USSC">USSC</option>
                <option value="VLRC">Villarica Pawnshop</option>
              </select>
              <div style="padding-top:8px">
                <span class="text" style="display:inline-block;color:Red;width:100%;">Select from the available fund sources</span>
                <div style="height:24px;width:100%;margin-top:4px">
                  <span class="text"><input type="checkbox"><label> I agree to the <a href="https://www.dragonpay.ph/terms-and-conditions" target="_blank">Terms and Conditions</a></label></span>
                </div>
              </div>
              <div style="margin:8px"></div>
              <input type="submit" value="Select" style="height:30px;width:120px;">
            </div>
          </div>
        </div>
      </div>

      <div class="step-row reveal" id="enrolled-step-7" data-export-name="enrolled-07-receipt">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">7</span><span><strong>Present screenshot to cashier to claim official receipt</strong>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
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
<div id="other-what" style="display:flex;align-items:center;gap:8px;margin-bottom:10px;scroll-margin-top:160px"><strong style="font-size:14px">What you need</strong></div>
    <ul style="margin:0 0 14px 28px;color:var(--muted);font-size:14px;line-height:1.6">
      <li>Payer name (for receipt) — add phone if you like (e.g., Juan Dela Cruz - 0912...)</li>
    </ul>
    <div id="other-steps-head" style="display:flex;align-items:center;gap:8px;margin-bottom:4px"><strong style="font-size:14px">Steps</strong></div>
    

    <div class="steps-fig">

      <div class="step-row reveal" id="other-step-1" data-export-name="other-01-select-campus">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">1</span><span><strong>Select campus & Proceed</strong> — choose the campus where you are requesting payment, then tap <em>Proceed to Payment</em>. No verification needed.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live">
          <div style="display:grid;gap:10px">
            <div class="field"><label>Select Your Campus <span style="color:var(--err)">*</span></label>            <select style="background:#fff">
              <option value="" selected disabled hidden>Select Campus</option>
              <option value="UPHB">Binan Campus</option>
              <option value="UPHMU">Medical University</option>
              <option value="UPHG">GMA Campus</option>
              <option value="UPHM">Manila Campus</option>
              <option value="PHCP">Pangasinan Campus</option>
              <option value="UPHI">Isabela Campus</option>
              <option value="UPHR">Roxas Campus</option>
            </select><small style="color:var(--muted)">Choose the campus where you are requesting payment.</small></div>
            <button class="btn btn-primary" style="width:100%;background:var(--blue);color:#fff"><i class="fa-solid fa-arrow-right"></i> Proceed to Payment</button>
          </div>
        </div>
      </div>

            <div class="step-row reveal" id="other-step-2" data-export-name="other-02-checkout-review">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">2</span><span><strong>Fill checkout form, review & Pay</strong> — payer name, amount, particulars, school year/sem, contact and email (reference optional), then check Payment Summary, tick confirmation, and click <em>Pay Now via DragonPay</em>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig fig-live" style="display:grid;gap:12px">
          <div style="display:grid;gap:10px">
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Transaction ID</label><input type="text" value="UPHB_143022052026" style="background:#f1f5f9;font-family:ui-monospace,monospace;font-size:12px" readonly></div>
              <div class="field"><label>Amount (PHP) <span style="color:var(--err)">*</span></label><input type="text" value="₱ 1,500.00" style="background:#fff;font-weight:800;color:var(--blue)" readonly></div>
            </div>
            <div class="field"><label>Particulars <span style="color:var(--err)">*</span></label><select style="background:#fff">
              <option>GOOD MORAL — selected</option>
              <option>DOWNPAYMENT</option>
              <option>Tuition Fee</option>
              <option>CERTIFICATION</option>
              <option>COPY OF GRADES</option>
            </select></div>
            <div class="field"><label>Other description (if not listed)</label><input type="text" placeholder="If not listed, type custom description here" disabled style="background:#fff" readonly></div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>For School Year <span style="color:var(--err)">*</span></label><select style="background:#fff">
              <option>2024-2025</option>
              <option>2023-2024</option>
              <option>2025-2026</option>
            </select></div>
              <div class="field"><label>For Semester <span style="color:var(--err)">*</span></label><select style="background:#fff">
              <option>1st Sem</option>
              <option>2nd Sem</option>
              <option>Summer</option>
            </select></div>
            </div>
            <div class="row" style="grid-template-columns:1fr 1fr">
              <div class="field"><label>Student / Payer Name <span style="color:var(--err)">*</span></label><input type="text" value="Juan Dela Cruz" style="background:#fff" readonly></div>
              <div class="field"><label>Reference (optional)</label><input type="text" placeholder="Optional" style="background:#fff" readonly></div>
            </div>
            <div class="field"><label>Contact Number</label><input type="text" value="09123456789" style="background:#fff" readonly></div>
            <div class="field"><label>Email Address <span style="color:var(--err)">*</span></label><input type="text" value="you@email.com" style="background:#fff" readonly></div>
          </div>
          <div style="height:1px;background:var(--line);margin:2px 0"></div>
          <div style="background:#fffbe6;border:1px solid #fde68a;border-radius:12px;padding:12px">
            <div style="font-weight:800;font-size:13px;color:#92400e;margin-bottom:6px"><i class="fa-solid fa-eye"></i> Review your details</div>
            <div style="font-size:12px;color:#475569;margin-bottom:8px">Please check the <strong>Payment Summary</strong> — amount, particulars, payer name and email must be correct.</div>
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#1e293b"><input type="checkbox" checked style="accent-color:var(--blue)"> I have reviewed my payment details and confirm they are correct.</label>
          </div>
          <button class="btn btn-primary" style="width:100%;padding:14px 16px;font-size:15px;background:var(--blue);color:#fff"><i class="fa-solid fa-lock"></i> Pay Now via <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="DragonPay" style="height:22px;background:#fff;padding:2px 6px;border-radius:6px;vertical-align:middle;margin-left:2px"></button>
          <div class="pay-summary" style="padding:12px;border-radius:12px">
            <div style="display:flex;align-items:center;gap:8px"><i class="fa-solid fa-receipt"></i><strong>Payment Summary</strong><span style="margin-left:auto;background:rgba(255,255,255,.18);padding:4px 8px;border-radius:999px;font-size:11px">OTHER</span></div>
            <div class="row" style="font-size:13px"><span>Payee</span><strong>Juan Dela Cruz - 09123456789</strong></div>
            <div class="row" style="font-size:13px"><span>Amount</span><strong>₱ 1,500.00</strong></div>
            <div class="row" style="font-size:11px"><span>Description</span><strong style="font-size:11px">JUAN DELA CRUZ | Contact:09123456789 | GOOD MORAL | 2024-2025 1st Sem</strong></div>
          </div>
        </div>
      </div>
      </div>

      <div class="step-row reveal" id="other-step-3" data-export-name="other-03-dragonpay">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">3</span><span><strong>Pay via DragonPay</strong> — same selector as New/Enrolled — Source dropdown + T&amp;C + Select.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
        </div>
        <div class="fig dp-sample" style="padding:10px;display:grid;place-items:center;background:#fff">
          <div class="workarea">
            <img src="<?= $payments_base ?>assets/dragonpay-xendit-logo-removebg-preview.png" alt="Dragonpay" style="height:28px;display:block">
            <br style="clear:both"><br>
            <div>
              <p><span style="display:inline-block;height:30px;">University of Perpetual Help System Laguna, Inc is requesting for <b>PHP1,000.00</b></span></p>
              <span class="text" style="display:inline-block;width:45px;">Source</span>
                            <select class="dropdownList">
                <option selected value="" style="color:red;">---- SELECT A PAYMENT OPTION ----</option>
                <option value="DPAY">Dragonpay Prepaid Credits</option>
                <option value="" style="color:red;" disabled>------ ONLINE BANKING / E-WALLET ------</option>
                <option value="BOC">Bank of Commerce Online</option>
                <option value="BDO">BDO Fund Transfer (OLD)</option>
                <option value="BDRE">BDO Online E-Payment (NEW)</option>
                <option value="BPIA">BPI Online</option>
                <option value="CBDD">China Bank Mobile Banking</option>
                <option value="CBCB">China Bank Online Bills Payment</option>
                <option value="INPY">Instapay from any bank/ewallet</option>
                <option value="MAYB">Maybank Online Banking</option>
                <option value="PSNT">PESONet from any bank/ewallet</option>
                <option value="ABQR">QRPH (NEW)</option>
                <option value="RCDD">RCBC Online Direct Debit</option>
                <option value="UBDD">Unionbank Online Banking</option>
                <option value="GCSB">GCash Bills Pay</option>
                <option value="PYMB">Maya Bills Pay</option>
                <option value="" style="color:red;" disabled>----- OVER-THE-COUNTER/ATM BANKING -----</option>
                <option value="AUB">AUB Online/Cash Payment</option>
                <option value="BDOA">Banco de Oro ATM</option>
                <option value="BDRX">BDO Cash Deposit w/ Ref</option>
                <option value="BNRX">BDO Network Bank (formerly ONB) Cash Dep</option>
                <option value="BDXB">BDO Over-the-Counter Bills Payment</option>
                <option value="BPXB">BPI Cash Payment</option>
                <option value="CBXB">China Bank Cash Payment</option>
                <option value="PNXB">PNB Cash Payment</option>
                <option value="PNBB">PNB Internet Banking Bills Payment</option>
                <option value="RCXB">RCBC ATM/Cash Payment</option>
                <option value="SBCB">Security Bank Cash Payment</option>
                <option value="UBXB">Unionbank Cash Payment</option>
                <option value="" style="color:red;" disabled>----- OVER-THE-COUNTER OTHERS -----</option>
                <option value="711">7-Eleven</option>
                <option value="BAYD">Bayad Center</option>
                <option value="CEBL">Cebuana Lhuillier Bills Payment</option>
                <option value="ECPY">ECPay (GCash/Payment Centers)</option>
                <option value="ETAP">eTap</option>
                <option value="MLH">M. Lhuillier</option>
                <option value="PLWN">Palawan Pawnshop</option>
                <option value="PRHB">Perahub</option>
                <option value="POSB">Posible (Family Mart, Phoenix)</option>
                <option value="RDS">Robinsons Dept Store</option>
                <option value="SMR">SM Dept/Supermarket/Savemore Counter</option>
                <option value="USSC">USSC</option>
                <option value="VLRC">Villarica Pawnshop</option>
              </select>
              <div style="padding-top:8px">
                <span class="text" style="display:inline-block;color:Red;width:100%;">Select from the available fund sources</span>
                <div style="height:24px;width:100%;margin-top:4px">
                  <span class="text"><input type="checkbox"><label> I agree to the <a href="https://www.dragonpay.ph/terms-and-conditions" target="_blank">Terms and Conditions</a></label></span>
                </div>
              </div>
              <div style="margin:8px"></div>
              <input type="submit" value="Select" style="height:30px;width:120px;">
            </div>
          </div>
        </div>
      </div>

      <div class="step-row reveal" id="other-step-4" data-export-name="other-04-receipt">
        <div class="step-row-head">
          <div class="step-text"><span class="step-num">4</span><span><strong>Present screenshot to cashier to claim official receipt</strong>.</span></div>
          <?php if (!empty($is_admin_tools)): ?><button class="step-export-btn no-print" onclick="exportStep(this)"><i class="fa-solid fa-download"></i> Save image</button><?php endif; ?>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function activateTab(tab){
  document.querySelectorAll('.tab-btn').forEach(b=>b.classList.toggle('active', b.dataset.tab===tab));
  document.querySelectorAll('.tab-content').forEach(c=>c.classList.toggle('active', c.id==='tab-'+tab));
  history.replaceState({},'', '#'+tab);
  document.getElementById('tabsTop')?.scrollIntoView({behavior:'smooth', block:'start'});
  // re-trigger reveal for newly visible tab
  setTimeout(function(){
    const activeTab = document.querySelector('.tab-content.active');
    if(!activeTab) return;
    activeTab.querySelectorAll('.step-row.reveal:not(.in-view)').forEach(function(r){
      const rect=r.getBoundingClientRect();
      if(rect.top < window.innerHeight * 0.9){
        r.style.transitionDelay = '0ms';
        requestAnimationFrame(function(){ r.classList.add('in-view'); });
      }
    });
  }, 120);
}
document.querySelectorAll('.tab-btn').forEach(btn=>{
  btn.addEventListener('click',()=>activateTab(btn.dataset.tab));
});
if(location.hash){
  const h=location.hash.replace('#','');
  if(['new','enrolled','other'].includes(h)) activateTab(h);
} else {
  // default to enrolled on first open (no hash)
  activateTab('enrolled');
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
    }, { rootMargin: '-110px 0px -45% 0px', threshold: [0,0.2,0.4,0.6,0.8,1] });
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

// scroll-reveal: animate each step as it scrolls into view (staggered, respects reduced-motion)
(function(){
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const rows = document.querySelectorAll('.step-row.reveal');
  if(reduce){
    rows.forEach(function(r){ r.classList.add('in-view'); });
    return;
  }
  // stagger delay helper: index within its tab
  function setStagger(row){
    const fig = row.closest('.steps-fig');
    if(!fig) return;
    const siblings = Array.from(fig.querySelectorAll('.step-row.reveal'));
    const idx = siblings.indexOf(row);
    const delay = Math.min(idx * 70, 320); // 70ms per step, cap 320
    row.style.transitionDelay = delay + 'ms';
    const figEl = row.querySelector('.fig');
    if(figEl) figEl.style.animationDelay = (delay + 80) + 'ms';
    const numEl = row.querySelector('.step-num');
    if(numEl) numEl.style.animationDelay = (delay + 200) + 'ms';
  }
  const io = new IntersectionObserver(function(entries){
    entries.forEach(function(entry){
      if(entry.isIntersecting){
        const row = entry.target;
        setStagger(row);
        requestAnimationFrame(function(){ row.classList.add('in-view'); });
        io.unobserve(row);
      }
    });
  }, { threshold: 0.18, rootMargin: '0px 0px -8% 0px' });
  rows.forEach(function(r){ io.observe(r); });
  // also re-observe when tab changes (steps in newly active tab may have been hidden)
  document.querySelectorAll('.tab-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      setTimeout(function(){
        const tab = document.querySelector('.tab-content.active');
        if(!tab) return;
        tab.querySelectorAll('.step-row.reveal:not(.in-view)').forEach(function(r){
          // reset delay for fresh tab
          r.style.transitionDelay = '';
          io.observe(r);
          // nudge: if already in viewport (e.g. top steps), show quickly
          const rect = r.getBoundingClientRect();
          if(rect.top < window.innerHeight * 0.85) {
            setStagger(r);
            requestAnimationFrame(function(){ r.classList.add('in-view'); });
            io.unobserve(r);
          }
        });
      }, 100);
    });
  });
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
// Interactive demo — Dragonpay sample is usable but never posts (no gray bg, rounded)
(function(){
  // make the whole instructions page demo-interactive but block real submits/navigation
  document.addEventListener('submit', function(e){
    const inDemo = e.target.closest('.dp-sample') || e.target.closest('.fig');
    if(inDemo){ e.preventDefault(); showToast('Demo only — no payment processed'); }
  });
  document.addEventListener('click', function(e){
    const link = e.target.closest('.dp-sample a');
    if(link && link.href.includes('dragonpay.ph')){
      // allow opening T&C in new tab but mark as demo
      showToast('Demo — opening Terms & Conditions');
    }
  });
  document.querySelectorAll('.dp-sample').forEach(function(box){
    const sel = box.querySelector('select.dropdownList');
    const chk = box.querySelector('input[type="checkbox"]');
    const btn = box.querySelector('input[type="submit"]');
    const err = box.querySelector('[id*="errorLabel"]') || box.querySelector('.text[style*="color:Red"]');
    if(!sel || !btn) return;
    function clearErr(){ if(err) { err.textContent=''; err.style.visibility='hidden'; } sel.style.borderColor='#7f9db9'; }
    function showErr(msg){ if(err){ err.textContent=msg; err.style.visibility='visible'; err.style.color='Red'; } sel.style.borderColor='#e02424'; sel.style.boxShadow='0 0 0 3px rgba(224,36,36,.12)'; box.querySelector('.workarea').animate([{transform:'translateX(0)'},{transform:'translateX(-4px)'},{transform:'translateX(4px)'},{transform:'translateX(0)'}],{duration:300}); }
    sel.addEventListener('change', function(){
      clearErr();
      if(sel.value && sel.selectedOptions[0].disabled){ showErr('Please select a valid payment option'); sel.selectedIndex=0; }
    });
    if(chk) chk.addEventListener('change', clearErr);
    btn.addEventListener('click', function(e){
      e.preventDefault();
      const val = sel.value;
      const opt = sel.selectedOptions[0];
      const isHeader = !val || (opt && opt.disabled) || (opt && opt.style.color==='red' && !val);
      if(isHeader){ showErr('Select from the available fund sources'); showToast('Demo — please select a payment option'); return; }
      if(chk && !chk.checked){ showErr('You must agree to the Terms and Conditions'); showToast('Demo — tick “I agree” first'); return; }
      clearErr();
      btn.value='Processing…'; btn.disabled=true;
      showToast('Demo — would proceed to Dragonpay via ' + opt.textContent.trim() + ' (no charge)');
      setTimeout(function(){ btn.value='Select'; btn.disabled=false; }, 1600);
    });
  });
  // --- Make every step interactive (demo only) ---
  // 0) Typed fields: clickable but locked (readonly) — show preview toast, block edits
  document.querySelectorAll('.fig input[readonly]').forEach(function(inp){
    inp.style.cursor='pointer';
    if(!inp.title) inp.title='Demo — preview only (value locked)';
    inp.addEventListener('focus', function(){ try{ inp.select(); }catch(e){} showToast('Demo — preview only (value locked)'); });
    inp.addEventListener('click', function(){ try{ inp.select(); }catch(e){} });
    inp.addEventListener('keydown', function(e){
      const allowed = ['Tab','ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','Escape'];
      if(allowed.includes(e.key) || e.ctrlKey || e.metaKey) return;
      e.preventDefault();
      inp.animate([{transform:'translateX(0)'},{transform:'translateX(-2px)'},{transform:'translateX(2px)'},{transform:'translateX(0)'}],{duration:220});
      showToast('Demo — value locked');
    });
    inp.addEventListener('paste', function(e){ e.preventDefault(); showToast('Demo — paste disabled'); });
    inp.addEventListener('cut', function(e){ e.preventDefault(); showToast('Demo — value locked'); });
  });
  // 1) Campus selects (step 1) — show complete 7-campus list but keep selection locked
  document.querySelectorAll('#new-step-1 select, #enrolled-step-1 select, #other-step-1 select').forEach(function(sel){
    const lockedVal = sel.value;
    const lockedIdx = sel.selectedIndex;
    sel.title='Demo — tap to preview 7 campuses (selection locked)';
    sel.style.cursor='pointer';
    sel.addEventListener('change', function(e){
      e.preventDefault();
      sel.value = lockedVal;
      sel.selectedIndex = lockedIdx;
      sel.animate([{transform:'translateX(0)'},{transform:'translateX(-3px)'},{transform:'translateX(3px)'},{transform:'translateX(0)'}],{duration:250});
      showToast('Demo — preview only (campus locked)');
      return false;
    });
    sel.addEventListener('click', function(){ showToast('Demo — 7 campuses: Binan, Medical Univ., GMA, Manila, Pangasinan, Isabela, Roxas (preview)'); });
  });
  // 2) Verify locator / student number (step 2)
  document.querySelectorAll('#new-step-2 .btn-verify, #enrolled-step-2 .btn-verify').forEach(function(b){
    b.addEventListener('click', function(e){
      e.preventDefault();
      const row = b.closest('.step-row');
      const inp = row.querySelector('input[type="text"]');
      const v = inp ? inp.value.trim() : '';
      if(!v){ inp.style.borderColor='#e02424'; inp.style.boxShadow='0 0 0 3px rgba(224,36,36,.12)'; showToast('Demo — enter number first'); inp.animate([{transform:'translateX(0)'},{transform:'translateX(-4px)'},{transform:'translateX(4px)'},{transform:'translateX(0)'}],{duration:280}); return; }
      inp.style.borderColor='#a7f3d0'; inp.style.background='#f0fdf4';
      b.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Verifying…'; b.disabled=true;
      showToast('Demo — verifying ' + v + '…');
      setTimeout(function(){
        b.innerHTML='<i class="fa-solid fa-check"></i> Verified'; b.style.background='#0e9f6e';
        // reveal/emphasize next verification card
        const nextCard = document.querySelector(row.id.includes('new') ? '#new-step-3' : '#enrolled-step-3');
        if(nextCard){ nextCard.classList.add('is-active'); nextCard.scrollIntoView({behavior:'smooth', block:'center'}); nextCard.animate([{transform:'scale(1)'},{transform:'scale(1.015)'},{transform:'scale(1)'}],{duration:400}); }
        setTimeout(function(){ b.innerHTML='<i class="fa-solid fa-magnifying-glass"></i> Verify'; b.disabled=false; b.style.background=''; }, 1500);
      }, 900);
    });
  });
  // allow Enter on those inputs to trigger verify
  document.querySelectorAll('#new-step-2 input, #enrolled-step-2 input').forEach(function(inp){
    inp.addEventListener('keydown', function(e){ if(e.key==='Enter'){ e.preventDefault(); inp.closest('.verify-box').querySelector('.btn-verify').click(); } });
  });
  // 3) Verify card Yes/No (step 3)
  document.querySelectorAll('#new-step-3 .verify-actions .btn, #enrolled-step-3 .verify-actions .btn').forEach(function(b){
    b.addEventListener('click', function(e){
      e.preventDefault();
      const isYes = b.textContent.includes('Yes');
      showToast(isYes ? 'Demo — confirmed! Proceed enabled' : 'Demo — marked not mine (no action)');
      const target = b.closest('.tab-content').querySelector('[id*="step-4"]');
      if(isYes && target){ target.classList.add('is-active'); target.scrollIntoView({behavior:'smooth', block:'center'}); target.querySelector('.btn')?.animate([{transform:'scale(1)'},{transform:'scale(1.03)'},{transform:'scale(1)'}],{duration:350}); }
    });
  });
  // 4) Proceed to Payment (step 4 / other step 1)
  document.querySelectorAll('#new-step-4 .btn, #enrolled-step-4 .btn, #other-step-1 .btn').forEach(function(b){
    b.addEventListener('click', function(e){
      e.preventDefault();
      b.animate([{transform:'scale(1)'},{transform:'scale(.97)'},{transform:'scale(1)'}],{duration:200});
      showToast('Demo — would open checkout form');
      const checkout = b.closest('.tab-content').querySelector('[id*="step-5"],[id*="step-2"]');
      if(checkout) setTimeout(function(){ checkout.classList.add('is-active'); checkout.scrollIntoView({behavior:'smooth', block:'center'}); }, 300);
    });
  });
  // 5) Checkout form (step 5 / other step 2) — locked preview (clickable fields, values locked)
  document.querySelectorAll('#new-step-5 select, #enrolled-step-5 select, #other-step-2 select').forEach(function(sel){
    const lockedVal = sel.value;
    const lockedIdx = sel.selectedIndex;
    sel.style.cursor='pointer';
    sel.title='Demo — preview only (value locked)';
    sel.addEventListener('change', function(e){
      e.preventDefault();
      sel.value = lockedVal;
      sel.selectedIndex = lockedIdx;
      sel.animate([{transform:'translateX(0)'},{transform:'translateX(-3px)'},{transform:'translateX(3px)'},{transform:'translateX(0)'}],{duration:220});
      showToast('Demo — preview only (selection locked)');
    });
    sel.addEventListener('click', function(){ showToast('Demo — preview only'); });
  });
  // inputs are readonly — handled in (0) — just add field focus highlight
  document.querySelectorAll('#new-step-5 input, #enrolled-step-5 input, #other-step-2 input').forEach(function(el){
    el.addEventListener('focus', function(){ el.closest('.field')?.classList.add('field-focus'); });
    el.addEventListener('blur', function(){ el.closest('.field')?.classList.remove('field-focus'); });
  });
  // 6) Review & Pay (step 6 / other step 3) — checkbox toggles Pay Now
  document.querySelectorAll('#new-step-5 input[type="checkbox"], #enrolled-step-5 input[type="checkbox"], #other-step-2 input[type="checkbox"]').forEach(function(chk){
    // remove disabled already done, make sure enabled
    chk.disabled=false;
    const row = chk.closest('.step-row');
    const payBtn = row.querySelector('.btn.btn-primary');
    if(payBtn){
      // initial state: disable Pay Now until checked (demo)
      if(!chk.checked){ payBtn.style.opacity='.55'; payBtn.style.pointerEvents='none'; }
      chk.addEventListener('change', function(){
        if(chk.checked){ payBtn.style.opacity='1'; payBtn.style.pointerEvents='auto'; payBtn.animate([{transform:'scale(1)'},{transform:'scale(1.02)'},{transform:'scale(1)'}],{duration:300}); showToast('Demo — confirmed, Pay Now enabled'); }
        else { payBtn.style.opacity='.55'; payBtn.style.pointerEvents='none'; showToast('Demo — please confirm checkbox'); }
      });
    }
  });
  document.querySelectorAll('#new-step-5 .btn.btn-primary, #enrolled-step-5 .btn.btn-primary, #other-step-2 .btn.btn-primary').forEach(function(b){
    b.addEventListener('click', function(e){
      e.preventDefault();
      const row = b.closest('.step-row'); const chk = row.querySelector('input[type="checkbox"]');
      if(chk && !chk.checked){ chk.animate([{transform:'translateX(0)'},{transform:'translateX(-4px)'},{transform:'translateX(4px)'},{transform:'translateX(0)'}],{duration:280}); showToast('Demo — tick confirmation first'); return; }
      b.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Redirecting…'; showToast('Demo — would redirect to Dragonpay (no charge)');
      setTimeout(function(){ b.innerHTML='<i class="fa-solid fa-lock"></i> Pay Now via DragonPay'; }, 1400);
    });
  });
  // 7) Receipt demo — click to simulate screenshot
  document.querySelectorAll('#new-step-7 .fig, #enrolled-step-7 .fig, #other-step-4 .fig').forEach(function(fig){
    fig.style.cursor='pointer';
    fig.title='Demo — click to simulate screenshot';
    fig.addEventListener('click', function(){ showToast('Demo — screenshot saved (preview only)'); fig.animate([{transform:'scale(1)'},{transform:'scale(1.02)'},{transform:'scale(1)'}],{duration:250}); });
  });
  // prevent any real form submit inside figs
  document.querySelectorAll('.fig form').forEach(function(f){ f.addEventListener('submit', function(e){ e.preventDefault(); showToast('Demo only — no data sent'); }); });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
