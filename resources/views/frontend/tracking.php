<!-- Track Repair Page -->
<section class="page-hero">
  <div class="container">
    <div class="section-badge">Live Status</div>
    <h1>Track Your Repair</h1>
    <p>Enter your Repair ID and phone number to see real-time status updates on your device.</p>
  </div>
</section>

<section class="section" style="background:var(--bg);">
  <div class="container">
    <div class="tracker-card">
      <div style="text-align:center;margin-bottom:24px;">
        <div style="width:56px;height:56px;background:var(--accent-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:var(--accent);">
          <i data-lucide="search" style="width:24px;height:24px;"></i>
        </div>
        <h2 style="font-size:1.25rem;font-weight:800;color:var(--text);">Track Your Repair</h2>
        <p style="font-size:0.875rem;color:var(--text-muted);margin-top:6px;">
          Your Repair ID was given when you dropped your device.<br />
          Format: <strong>AMN-LR-260001</strong>
        </p>
      </div>

      <?php if (!empty($flash_error)): ?>
      <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;border-radius:10px;font-size:0.875rem;font-weight:600;margin-bottom:16px;text-align:center;">
        ⚠ <?= htmlspecialchars($flash_error, ENT_QUOTES) ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="/track-repair">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
        <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:12px;">
          <input
            class="tracker-input"
            type="text"
            name="tracking_id"
            id="repairIdInput"
            placeholder="e.g. AMN-LR-260001"
            autocomplete="off"
            style="text-transform:uppercase;width:100%;"
            required
          />
          <input
            class="tracker-input"
            type="tel"
            name="phone"
            placeholder="Your registered phone number"
            autocomplete="tel"
            style="width:100%;"
            required
          />
          <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;">
            <i data-lucide="search"></i> Track Repair
          </button>
        </div>
      </form>

      <div style="margin-top:24px;padding:16px;background:var(--accent-light);border-radius:var(--radius-sm);font-size:0.875rem;color:var(--text);">
        <strong>Don't have your Repair ID?</strong><br />
        Call <a href="tel:+919876543210" style="color:var(--accent);font-weight:600;">+91 98765 43210</a> or
        <a href="https://wa.me/919876543210" style="color:var(--whatsapp,#25d366);font-weight:600;" target="_blank" rel="noopener">WhatsApp us</a>
        and we'll look it up using your phone number.
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
      <p class="section-subtitle">Here's what happens at every step of your repair.</p>
    </div>
    <div style="max-width:640px;margin-inline:auto;">
      <?php
      $stages = [
        ['icon'=>'package',       'title'=>'Device Received',  'color'=>'var(--accent)', 'text'=>'We have received your device and logged it in our system. You\'ll get a Repair ID at this stage.'],
        ['icon'=>'search',        'title'=>'Under Diagnosis',  'color'=>'var(--accent)', 'text'=>'Our technician has inspected the device and identified the root cause. You may receive a call with a quotation.'],
        ['icon'=>'wrench',        'title'=>'Repair In Progress','color'=>'var(--accent)', 'text'=>'You have approved the quote and repair work has begun. Our technician is fixing your device.'],
        ['icon'=>'check-square',  'title'=>'Quality Testing',  'color'=>'var(--accent)', 'text'=>'Repair is done. We run full hardware and software tests before handing it back.'],
        ['icon'=>'package-check', 'title'=>'Ready for Pickup', 'color'=>'#34D399',       'text'=>'Your device is repaired, tested, and ready. We\'ll notify you via call/SMS.'],
      ];
      foreach ($stages as $i => $s): ?>
      <div style="display:flex;gap:20px;<?= $i < count($stages) - 1 ? 'padding-bottom:28px;border-left:2px solid rgba(37,99,235,0.3);' : '' ?>margin-left:18px;padding-left:28px;position:relative;">
        <div style="position:absolute;left:-18px;top:0;width:36px;height:36px;background:rgba(37,99,235,0.15);border:2px solid <?= $s['color'] ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;color:<?= $s['color'] ?>;">
          <i data-lucide="<?= $s['icon'] ?>" style="width:16px;height:16px;"></i>
        </div>
        <div>
          <strong style="color:<?= $s['color'] ?>;display:block;margin-bottom:4px;"><?= htmlspecialchars($s['title'], ENT_QUOTES) ?></strong>
          <p style="font-size:0.875rem;color:#94A3B8;line-height:1.6;"><?= htmlspecialchars($s['text'], ENT_QUOTES) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
