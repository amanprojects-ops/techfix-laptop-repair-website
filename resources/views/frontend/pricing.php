<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container">
    <div class="section-badge"><i data-lucide="shield-check"></i> 100% Transparent Rates</div>
    <h1>Laptop Repair Pricing</h1>
    <p>Starting rates for standard laptop repairs in Saharsa. Exact parts and cost are confirmed after free diagnosis before we touch anything.</p>
  </div>
</section>

<!-- PRICING CONTENT -->
<section class="section" style="background:var(--bg);">
  <div class="container">

    <div class="pricing-note" style="margin-bottom:36px; max-width:760px; margin-inline:auto;">
      <strong>How our pricing works:</strong> All prices listed are <em>starting estimates</em>. Final cost depends on the exact model number, hardware component quality (Original vs OEM), and repair complexity. Free inspection and quotation before work begins — <strong>Zero hidden charges</strong>.
    </div>

    <!-- Catalog services from DB -->
    <div class="pricing-section" style="max-width:960px; margin-inline:auto;">
      <div class="pricing-section__header">
        <div class="pricing-section__icon"><i data-lucide="wrench"></i></div>
        <h2>Standard Service &amp; Component Rates</h2>
      </div>

      <div class="pricing-table-wrapper">
        <table class="pricing-table" aria-label="Repair services pricing">
          <thead>
            <tr>
              <th>Repair Service</th>
              <th>Starting Price</th>
              <th>Est. Turnaround</th>
              <th>Warranty Backing</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($services)): ?>
              <?php foreach ($services as $svc): ?>
              <tr>
                <td>
                  <strong><?= htmlspecialchars($svc['name'], ENT_QUOTES) ?></strong>
                  <p style="font-size:0.8125rem;color:var(--text-muted);margin-top:3px;"><?= htmlspecialchars($svc['short_description'] ?? '', ENT_QUOTES) ?></p>
                </td>
                <td><span class="price-val">₹<?= number_format((float)$svc['starting_price'], 0) ?></span></td>
                <td>
                  <span style="display:inline-flex;align-items:center;gap:4px;font-size:0.875rem;font-weight:600;color:var(--text-muted);">
                    <i data-lucide="clock" style="width:14px;height:14px;color:var(--accent);"></i>
                    <?= (int)$svc['estimated_days'] ?> Day<?= $svc['estimated_days'] > 1 ? 's' : '' ?>
                  </span>
                </td>
                <td><span class="warranty-badge"><i data-lucide="shield" style="width:12px;height:12px;display:inline-block;vertical-align:middle;margin-right:2px;"></i><?= (int)$svc['warranty_days'] ?> Days</span></td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td>
                  <strong>Screen Replacement</strong>
                  <p style="font-size:0.8125rem;color:var(--text-muted);">Cracked, lines or black screen</p>
                </td>
                <td><span class="price-val">₹2,499</span></td>
                <td>2–4 Hours</td>
                <td><span class="warranty-badge">90 Days</span></td>
              </tr>
              <tr>
                <td>
                  <strong>Motherboard Chip-Level Repair</strong>
                  <p style="font-size:0.8125rem;color:var(--text-muted);">Power IC, short circuits &amp; dead board</p>
                </td>
                <td><span class="price-val">₹1,999</span></td>
                <td>1–3 Days</td>
                <td><span class="warranty-badge">90 Days</span></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div style="text-align:center;margin-top:40px;">
      <a href="/book-repair" class="btn btn--primary btn--lg">
        <i data-lucide="wrench"></i> Book Your Repair Online
      </a>
    </div>

  </div>
</section>
