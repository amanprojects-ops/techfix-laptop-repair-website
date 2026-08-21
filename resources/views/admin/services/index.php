<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Service Catalog</h2>
      <span class="header-subtitle">Manage repair services shown on the website</span>
    </div>
  </div>
</header>
<div style="padding:1.5rem;display:grid;grid-template-columns:1fr 340px;gap:1.5rem;">

  <!-- Services Table -->
  <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
    <?php if ($flash_success): ?><div style="background:#d1fae5;color:#065f46;padding:12px 16px;font-size:0.875rem;font-weight:600;">✓ <?= htmlspecialchars($flash_success, ENT_QUOTES) ?></div><?php endif; ?>
    <table style="width:100%;border-collapse:collapse;">
      <thead><tr style="background:var(--table-header);">
        <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Service</th>
        <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Starting Price</th>
        <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Est. Days</th>
        <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Status</th>
        <th style="padding:10px 16px;"></th>
      </tr></thead>
      <tbody>
        <?php foreach ($services as $svc): ?>
        <tr style="border-top:1px solid var(--border);">
          <td style="padding:12px 16px;">
            <div style="font-weight:600;color:var(--text);font-size:0.875rem;"><?= htmlspecialchars($svc['name'], ENT_QUOTES) ?></div>
            <div style="font-size:0.78rem;color:var(--text-muted);"><?= htmlspecialchars(mb_strimwidth($svc['short_description'] ?? '', 0, 60, '...'), ENT_QUOTES) ?></div>
          </td>
          <td style="padding:12px 16px;font-size:0.875rem;font-weight:700;color:var(--text);">₹<?= number_format((float)$svc['starting_price'], 0) ?></td>
          <td style="padding:12px 16px;font-size:0.875rem;color:var(--text-muted);"><?= (int)$svc['estimated_days'] ?> day<?= $svc['estimated_days'] > 1 ? 's' : '' ?></td>
          <td style="padding:12px 16px;">
            <span style="font-size:0.75rem;font-weight:700;padding:4px 10px;border-radius:999px;background:<?= $svc['status']==='active' ? '#d1fae5' : '#fee2e2' ?>;color:<?= $svc['status']==='active' ? '#065f46' : '#991b1b' ?>;">
              <?= ucfirst($svc['status']) ?>
            </span>
          </td>
          <td style="padding:12px 16px;text-align:right;">
            <form method="POST" action="/admin/services/<?= $svc['id'] ?>/delete" style="display:inline;">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
              <button type="submit" onclick="return confirm('Deactivate this service?')" style="font-size:0.78rem;font-weight:600;padding:5px 12px;border-radius:6px;border:1px solid var(--border);background:var(--card-bg);color:var(--text-muted);cursor:pointer;">Deactivate</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Add Service -->
  <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.5rem;height:fit-content;">
    <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:16px;"><i class="fas fa-plus"></i> Add New Service</div>
    <form method="POST" action="/admin/services" style="display:flex;flex-direction:column;gap:12px;">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
      <div>
        <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Service Name *</label>
        <input type="text" name="name" required placeholder="e.g. Keyboard Replacement" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
      </div>
      <div>
        <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Short Description</label>
        <textarea name="short_description" rows="2" placeholder="Brief description..." style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;resize:vertical;"></textarea>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div>
          <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Starting Price (₹)</label>
          <input type="number" name="starting_price" min="0" step="50" placeholder="0" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
        <div>
          <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Est. Days</label>
          <input type="number" name="estimated_days" min="1" value="1" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
        </div>
      </div>
      <div>
        <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Icon (Lucide name)</label>
        <input type="text" name="icon" placeholder="wrench, monitor, cpu..." style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
      </div>
      <button type="submit" style="font-size:0.875rem;font-weight:700;padding:9px;border-radius:8px;background:var(--accent);color:#fff;border:none;cursor:pointer;"><i class="fas fa-plus"></i> Add Service</button>
    </form>
  </div>

</div>
