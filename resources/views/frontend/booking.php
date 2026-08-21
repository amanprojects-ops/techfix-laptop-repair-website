<?php
$old = $flash_input ?? [];
$fn  = fn($k) => htmlspecialchars($old[$k] ?? '', ENT_QUOTES);
$successId = $_GET['id'] ?? null;
?>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container">
    <div class="section-badge">Fast &amp; Easy</div>
    <h1>Book a Repair</h1>
    <p>Submit your repair request online or drop by our center in Saharsa.</p>
  </div>
</section>

<section class="section" style="background:var(--bg);">
  <div class="container" style="max-width:760px;">

    <?php if ($successId): ?>
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:12px;padding:24px;text-align:center;margin-bottom:30px;">
      <div style="width:48px;height:48px;background:#10b981;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.5rem;">✓</div>
      <h2 style="font-size:1.4rem;font-weight:800;color:#065f46;margin-bottom:8px;">Repair Booked Successfully!</h2>
      <p style="color:#047857;font-size:0.95rem;margin-bottom:14px;">Your tracking ID has been generated:</p>
      <div style="display:inline-block;background:#fff;padding:8px 20px;border-radius:8px;font-size:1.3rem;font-weight:800;color:var(--accent);letter-spacing:1px;border:1.5px dashed var(--accent);">
        <?= htmlspecialchars($successId, ENT_QUOTES) ?>
      </div>
      <p style="color:#047857;font-size:0.85rem;margin-top:14px;">Please keep this ID safe to track your repair progress.</p>
      <div style="margin-top:20px;display:flex;gap:12px;justify-content:center;">
        <a href="/repair/<?= urlencode($successId) ?>" class="btn btn--primary"><i data-lucide="activity"></i> Track Live Status</a>
        <a href="/" class="btn btn--outline">Back to Home</a>
      </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
    <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 16px;border-radius:8px;font-size:0.875rem;font-weight:600;margin-bottom:20px;">
      ⚠ <?= htmlspecialchars($flash_error, ENT_QUOTES) ?>
    </div>
    <?php endif; ?>

    <div class="booking-form-card" style="background:var(--card-bg);border:1px solid var(--border);border-radius:16px;padding:32px;">
      <h2 style="font-size:1.3rem;font-weight:800;color:var(--text);margin-bottom:6px;">Device &amp; Contact Details</h2>
      <p style="font-size:0.875rem;color:var(--text-muted);margin-bottom:24px;">Please fill in the details below. Our technician will review and get in touch.</p>

      <form method="POST" action="/book-repair" style="display:flex;flex-direction:column;gap:18px;">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES) ?>" />

        <!-- Customer details -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--text);margin-bottom:6px;">Your Name *</label>
            <input type="text" name="customer_name" value="<?= $fn('customer_name') ?>" required placeholder="Full Name" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--bg);color:var(--text);font-size:0.875rem;" />
          </div>
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--text);margin-bottom:6px;">Mobile Number *</label>
            <input type="tel" name="customer_phone" value="<?= $fn('customer_phone') ?>" required placeholder="10-digit mobile number" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--bg);color:var(--text);font-size:0.875rem;" />
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--text);margin-bottom:6px;">Email (Optional)</label>
            <input type="email" name="customer_email" value="<?= $fn('customer_email') ?>" placeholder="name@example.com" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--bg);color:var(--text);font-size:0.875rem;" />
          </div>
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--text);margin-bottom:6px;">City / Town</label>
            <input type="text" name="customer_city" value="<?= $fn('customer_city') ?>" placeholder="e.g. Saharsa" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--bg);color:var(--text);font-size:0.875rem;" />
          </div>
        </div>

        <!-- Device details -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--text);margin-bottom:6px;">Laptop Brand *</label>
            <input type="text" name="device_brand" value="<?= $fn('device_brand') ?>" required placeholder="e.g. Dell, HP, Lenovo, Apple" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--bg);color:var(--text);font-size:0.875rem;" />
          </div>
          <div>
            <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--text);margin-bottom:6px;">Model Name / Number</label>
            <input type="text" name="device_model" value="<?= $fn('device_model') ?>" placeholder="e.g. Inspiron 15, Pavilion" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--bg);color:var(--text);font-size:0.875rem;" />
          </div>
        </div>

        <div>
          <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--text);margin-bottom:6px;">Service Required</label>
          <select name="service_id" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--bg);color:var(--text);font-size:0.875rem;">
            <option value="">— Select a Service (or leave empty) —</option>
            <?php foreach (($services ?? []) as $svc): ?>
            <option value="<?= $svc['id'] ?>"><?= htmlspecialchars($svc['name'], ENT_QUOTES) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label style="display:block;font-size:0.8rem;font-weight:700;color:var(--text);margin-bottom:6px;">Problem Description *</label>
          <textarea name="problem_description" rows="3" required placeholder="Describe what issue you are facing with your laptop..." style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:8px;background:var(--bg);color:var(--text);font-size:0.875rem;resize:vertical;"><?= $fn('problem_description') ?></textarea>
        </div>

        <button type="submit" class="btn btn--primary btn--lg" style="width:100%;justify-content:center;margin-top:8px;">
          <i data-lucide="wrench"></i> Submit Repair Request
        </button>
      </form>
    </div>

  </div>
</section>
