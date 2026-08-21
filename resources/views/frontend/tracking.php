<!-- Track Repair Page -->
<section class="page-hero">
  <div class="container">
    <div class="section-badge"><i data-lucide="activity"></i> Live Tracking</div>
    <h1>Track Repair Status</h1>
    <p>Enter your unique Repair ID and registered mobile number to see real-time updates on your device.</p>
  </div>
</section>

<section class="section" style="background:var(--bg);">
  <div class="container">
    <div class="tracker-card">
      <div style="text-align:center;margin-bottom:28px;">
        <div style="width:56px;height:56px;background:var(--accent-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:var(--accent);box-shadow:0 4px 14px rgba(37, 99, 235, 0.15);">
          <i data-lucide="search" style="width:26px;height:26px;"></i>
        </div>
        <h2 style="font-size:1.35rem;font-weight:900;color:var(--text);">Find Your Repair Ticket</h2>
        <p style="font-size:0.875rem;color:var(--text-muted);margin-top:6px;">
          Format: <strong>AMN-LR-260001</strong> (Provided on your intake receipt)
        </p>
      </div>

      <?php if (!empty($flash_error)): ?>
      <div style="background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;padding:12px 16px;border-radius:var(--radius-sm);font-size:0.875rem;font-weight:700;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
        <i data-lucide="alert-circle" style="color:#EF4444;width:18px;height:18px;"></i>
        <span><?= htmlspecialchars($flash_error, ENT_QUOTES) ?></span>
      </div>
      <?php endif; ?>

      <form method="POST" action="/track-repair">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:14px;">
          <div class="form-group" style="margin-bottom:0;">
            <label>Repair Tracking ID</label>
            <input
              class="tracker-input"
              type="text"
              name="tracking_id"
              id="repairIdInput"
              placeholder="e.g. AMN-LR-260001"
              autocomplete="off"
              style="text-transform:uppercase;font-weight:700;letter-spacing:0.5px;"
              required
            />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label>Registered Mobile Number</label>
            <input
              class="tracker-input"
              type="tel"
              name="phone"
              placeholder="Enter 10-digit phone number"
              autocomplete="tel"
              pattern="[0-9]{10}"
              required
            />
          </div>
          <button type="submit" class="btn btn--primary btn--lg" style="width:100%;margin-top:8px;">
            <i data-lucide="search"></i> Check Live Status
          </button>
        </div>
      </form>

      <div style="margin-top:24px;padding:16px 20px;background:var(--accent-light);border:1px solid rgba(37, 99, 235, 0.15);border-radius:var(--radius-sm);font-size:0.875rem;color:var(--text);">
        <strong style="color:var(--accent);display:flex;align-items:center;gap:6px;margin-bottom:4px;">
          <i data-lucide="help-circle" style="width:16px;height:16px;"></i> Don't have your Repair ID?
        </strong>
        Call our workshop at <a href="tel:<?= htmlspecialchars(site_phone(), ENT_QUOTES) ?>" style="color:var(--accent);font-weight:700;"><?= htmlspecialchars(site_phone(), ENT_QUOTES) ?></a> or
        <a href="<?= site_whatsapp_link('Hello, I do not have my Repair ID. Can you please check my status?') ?>" style="color:#16A34A;font-weight:700;" target="_blank" rel="noopener">WhatsApp Us</a>
        and we will look it up using your phone number.
      </div>
    </div>
  </div>
</section>

<!-- How Tracking Works -->
<section class="section section--dark">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Repair Stages</div>
      <h2 class="section-title">What Each Stage Means</h2>
      <p class="section-subtitle">Here is what happens at every step of your device lifecycle in our workshop.</p>
    </div>
    <div style="max-width:680px;margin-inline:auto;">
      <?php
      $stages = [
        ['icon'=>'package',       'title'=>'Device Received',  'color'=>'#38BDF8', 'text'=>'We have logged your laptop in the workshop queue and assigned an inspection ticket ID.'],
        ['icon'=>'search',        'title'=>'Under Diagnosis',  'color'=>'#F59E0B', 'text'=>'Engineer is inspecting components under microscope to identify the exact fault and calculate quote.'],
        ['icon'=>'wrench',        'title'=>'Repair In Progress','color'=>'#6366F1', 'text'=>'After your approval, genuine or high-grade OEM parts are installed and soldered.'],
        ['icon'=>'check-square',  'title'=>'Quality Testing',  'color'=>'#8B5CF6', 'text'=>'Full 24-point hardware diagnostics, thermal benchmarks, and stability test are completed.'],
        ['icon'=>'package-check', 'title'=>'Ready for Pickup', 'color'=>'#10B981', 'text'=>'Repair & quality test passed. Invoice & warranty card generated for pickup.'],
      ];
      foreach ($stages as $i => $s): ?>
      <div style="display:flex;gap:20px;<?= $i < count($stages) - 1 ? 'padding-bottom:28px;border-left:2px solid rgba(255,255,255,0.12);' : '' ?>margin-left:18px;padding-left:28px;position:relative;">
        <div style="position:absolute;left:-18px;top:0;width:36px;height:36px;background:var(--bg-card-dark2);border:2px solid <?= $s['color'] ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;color:<?= $s['color'] ?>;box-shadow:0 0 12px <?= $s['color'] ?>44;">
          <i data-lucide="<?= $s['icon'] ?>" style="width:16px;height:16px;"></i>
        </div>
        <div>
          <strong style="color:<?= $s['color'] ?>;display:block;margin-bottom:4px;font-size:1rem;"><?= htmlspecialchars($s['title'], ENT_QUOTES) ?></strong>
          <p style="font-size:0.875rem;color:#94A3B8;line-height:1.6;"><?= htmlspecialchars($s['text'], ENT_QUOTES) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
