<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container">
    <div class="section-badge">Transparent Pricing</div>
    <h1>Repair Pricing</h1>
    <p>Starting prices for all services. Final cost depends on your device model and parts — confirmed before we begin.</p>
  </div>
</section>

<!-- PRICING CONTENT -->
<section class="section" style="background:var(--bg);">
  <div class="container">

    <div class="pricing-note" style="margin-bottom:40px; max-width:720px; margin-inline:auto;">
      <strong>How our pricing works:</strong> All prices listed are <em>starting from</em> values. The exact cost depends on your specific laptop model, the parts required, and the severity of damage. We provide a <strong>free diagnosis</strong> and share the full quote before starting any repair — no surprise charges.
    </div>

    <!-- Catalog services from DB -->
    <div class="pricing-section">
      <div class="pricing-section__header">
        <div class="pricing-section__icon"><i data-lucide="wrench"></i></div>
        <h2>Standard Hardware & Service Rates</h2>
      </div>
      <table class="pricing-table" aria-label="Repair services pricing">
        <thead>
          <tr>
            <th>Service</th>
            <th>Starting Price</th>
            <th>Typical Time</th>
            <th>Warranty</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($services)): ?>
            <?php foreach ($services as $svc): ?>
            <tr>
              <td>
                <strong><?= htmlspecialchars($svc['name'], ENT_QUOTES) ?></strong>
                <p style="font-size:0.8rem;color:var(--text-muted);margin-top:2px;"><?= htmlspecialchars($svc['short_description'] ?? '', ENT_QUOTES) ?></p>
              </td>
              <td><span class="price-val">₹<?= number_format((float)$svc['starting_price'], 0) ?></span></td>
              <td><?= (int)$svc['estimated_days'] ?> Day<?= $svc['estimated_days'] > 1 ? 's' : '' ?></td>
              <td><span class="warranty-badge"><?= (int)$svc['warranty_days'] ?> Days</span></td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td><strong>Screen Replacement</strong></td>
              <td><span class="price-val">₹2,499</span></td>
              <td>2–4 Hours</td>
              <td><span class="warranty-badge">90 Days</span></td>
            </tr>
            <tr>
              <td><strong>Motherboard Repair</strong></td>
              <td><span class="price-val">₹1,999</span></td>
              <td>1–3 Days</td>
              <td><span class="warranty-badge">90 Days</span></td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div style="text-align:center;margin-top:40px;">
      <a href="/book-repair" class="btn btn--primary btn--lg">
        <i data-lucide="wrench"></i> Book a Repair Online
      </a>
    </div>

  </div>
</section>
