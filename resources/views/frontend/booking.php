<?php
$old = $flash_input ?? [];
$fn  = fn($k) => htmlspecialchars($old[$k] ?? '', ENT_QUOTES);
$successId = $_GET['id'] ?? null;
?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container">
    <div class="section-badge"><i data-lucide="zap"></i> Fast &amp; Easy Booking</div>
    <h1>Book a Laptop Repair</h1>
    <p>Submit your repair request online or drop by our center in Saharsa. We'll inspect and confirm the quote before starting.</p>
  </div>
</section>

<section class="section" style="background:var(--bg);">
  <div class="container" style="max-width:760px;">

    <?php if ($successId): ?>
    <div style="background:#ECFDF5;border:1.5px solid #A7F3D0;border-radius:var(--radius-lg);padding:32px 24px;text-align:center;margin-bottom:32px;box-shadow:var(--shadow-sm);">
      <div style="width:52px;height:52px;background:#10B981;color:#FFFFFF;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 4px 14px rgba(16, 185, 129, 0.3);">
        <i data-lucide="check" style="width:28px;height:28px;"></i>
      </div>
      <h2 style="font-size:1.5rem;font-weight:900;color:#065F46;margin-bottom:8px;">Repair Booked Successfully!</h2>
      <p style="color:#047857;font-size:0.95rem;margin-bottom:16px;">Your unique tracking ID has been generated:</p>
      <div style="display:inline-block;background:#FFFFFF;padding:10px 24px;border-radius:var(--radius-sm);font-size:1.4rem;font-weight:900;color:var(--accent);letter-spacing:1px;border:2px dashed var(--accent);box-shadow:var(--shadow-xs);">
        <?= htmlspecialchars($successId, ENT_QUOTES) ?>
      </div>
      <p style="color:#047857;font-size:0.875rem;margin-top:16px;">Please save this ID to track your live repair progress or call our workshop.</p>
      <div style="margin-top:24px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
        <a href="/repair/<?= urlencode($successId) ?>" class="btn btn--primary"><i data-lucide="activity"></i> Track Live Status</a>
        <a href="/" class="btn btn--secondary">Back to Home</a>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
    <div style="background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;padding:14px 18px;border-radius:var(--radius-sm);font-size:0.9rem;font-weight:700;margin-bottom:24px;display:flex;align-items:center;gap:8px;">
      <i data-lucide="alert-triangle" style="width:18px;height:18px;color:#EF4444;"></i>
      <span><?= htmlspecialchars($flash_error, ENT_QUOTES) ?></span>
    </div>
    <?php endif; ?>

    <div class="booking-form-card">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
        <div style="width:36px;height:36px;background:var(--accent-light);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--accent);">
          <i data-lucide="wrench" style="width:18px;height:18px;"></i>
        </div>
        <h2 style="font-size:1.35rem;font-weight:900;color:var(--text);">Device &amp; Contact Details</h2>
      </div>
      <p style="font-size:0.9rem;color:var(--text-muted);margin-bottom:28px;">Please fill in the details below. Our certified engineer will inspect and contact you.</p>

      <form method="POST" action="/book-repair" style="display:flex;flex-direction:column;gap:18px;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES) ?>" />

        <!-- Customer details -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px;">
          <div class="form-group" style="margin-bottom:0;">
            <label>Your Full Name *</label>
            <input type="text" name="customer_name" class="form-input" value="<?= $fn('customer_name') ?>" required placeholder="e.g. Ramesh Sharma" />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label>Mobile Number *</label>
            <input type="tel" name="customer_phone" class="form-input" value="<?= $fn('customer_phone') ?>" required placeholder="10-digit mobile number" pattern="[0-9]{10}" />
          </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px;">
          <div class="form-group" style="margin-bottom:0;">
            <label>Email Address (Optional)</label>
            <input type="email" name="customer_email" class="form-input" value="<?= $fn('customer_email') ?>" placeholder="name@example.com" />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label>City / Location</label>
            <input type="text" name="customer_city" class="form-input" value="<?= $fn('customer_city') ?>" placeholder="e.g. Saharsa, Supaul..." />
          </div>
        </div>

        <!-- Device details -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:16px;">
          <div class="form-group" style="margin-bottom:0;">
            <label>Laptop Brand *</label>
            <input type="text" name="device_brand" class="form-input" value="<?= $fn('device_brand') ?>" required placeholder="Dell, HP, Lenovo, Apple, ASUS..." />
          </div>
          <div class="form-group" style="margin-bottom:0;">
            <label>Model Name / Number</label>
            <input type="text" name="device_model" class="form-input" value="<?= $fn('device_model') ?>" placeholder="e.g. Inspiron 15, Pavilion 14..." />
          </div>
        </div>

        <div class="form-group" style="margin-bottom:0;">
          <label>Service Category</label>
          <select name="service_id" class="form-select">
            <option value="">— Select a Service (or general inspection) —</option>
            <?php foreach (($services ?? []) as $svc): ?>
            <option value="<?= $svc['id'] ?>"><?= htmlspecialchars($svc['name'], ENT_QUOTES) ?> (From ₹<?= number_format((float)$svc['starting_price'], 0) ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group" style="margin-bottom:0;">
          <label>Problem Description *</label>
          <textarea name="problem_description" class="form-textarea" rows="3" required placeholder="Describe what issue you are facing (e.g. screen broken, not turning on, very slow, liquid spill)..."><?= $fn('problem_description') ?></textarea>
        </div>

        <button type="submit" class="btn btn--primary btn--lg" style="width:100%;margin-top:8px;">
          <i data-lucide="wrench"></i> Submit Repair Request
        </button>
      </form>
    </div>

  </div>
</section>
