<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Service Catalog</h2>
      <span class="header-subtitle">Manage laptop repair services displayed on customer website</span>
    </div>
  </div>
</header>

<div style="padding:24px;display:grid;grid-template-columns:1fr 340px;gap:24px;">

  <!-- Services Table Card -->
  <div class="table-card" style="height:fit-content;">
    <?php if (!empty($flash_success)): ?>
    <div style="background:#ECFDF5;border-bottom:1px solid #A7F3D0;color:#065F46;padding:12px 18px;font-size:0.875rem;font-weight:700;">
      <i class="fas fa-check-circle" style="color:#10B981;"></i> <?= htmlspecialchars($flash_success, ENT_QUOTES) ?>
    </div>
    <?php endif; ?>

    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Service Name</th>
            <th>Starting Price</th>
            <th>Est. Turnaround</th>
            <th>Status</th>
            <th style="text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($services as $svc): ?>
          <tr>
            <td>
              <div style="font-weight:800;color:var(--text-primary);font-size:0.9rem;"><?= htmlspecialchars($svc['name'], ENT_QUOTES) ?></div>
              <div style="font-size:0.8rem;color:var(--text-muted);margin-top:2px;"><?= htmlspecialchars(mb_strimwidth($svc['short_description'] ?? '', 0, 60, '...'), ENT_QUOTES) ?></div>
            </td>
            <td>
              <span style="font-weight:800;color:var(--primary-color);">₹<?= number_format((float)$svc['starting_price'], 0) ?></span>
            </td>
            <td>
              <span style="font-size:0.85rem;color:var(--text-secondary);font-weight:500;">
                <i class="fas fa-clock" style="font-size:12px;color:var(--text-muted);margin-right:4px;"></i>
                <?= (int)$svc['estimated_days'] ?> day<?= $svc['estimated_days'] > 1 ? 's' : '' ?>
              </span>
            </td>
            <td>
              <span class="status-pill" style="background:<?= $svc['status']==='active' ? '#ECFDF5' : '#FEF2F2' ?>;color:<?= $svc['status']==='active' ? '#065F46' : '#991B1B' ?>;border:1px solid <?= $svc['status']==='active' ? '#A7F3D0' : '#FECACA' ?>;">
                <?= ucfirst($svc['status']) ?>
              </span>
            </td>
            <td style="text-align:right;">
              <form method="POST" action="<?= url('/admin/services/' . $svc['id'] . '/delete') ?>" style="display:inline;">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
                <button type="submit" onclick="return confirm('Toggle status for this service?')" class="btn-secondary btn-sm" style="color:var(--text-muted);">
                  <?= $svc['status']==='active' ? 'Deactivate' : 'Activate' ?>
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add New Service Card -->
  <div class="form-card" style="height:fit-content;">
    <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:18px;display:flex;align-items:center;gap:8px;">
      <i class="fas fa-plus-circle"></i> Add New Service
    </div>
    <form method="POST" action="<?= url('/admin/services') ?>" style="display:flex;flex-direction:column;gap:14px;">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
      <div class="form-field">
        <label>Service Name *</label>
        <input type="text" name="name" required placeholder="e.g. Keyboard Replacement" class="form-control" />
      </div>
      <div class="form-field">
        <label>Short Description</label>
        <textarea name="short_description" rows="2" placeholder="Brief 1-line description..." class="form-control"></textarea>
      </div>
      <div class="form-field">
        <label>Starting Price (₹) *</label>
        <input type="number" name="starting_price" required placeholder="e.g. 1499" min="0" step="1" class="form-control" />
      </div>
      <div class="form-field">
        <label>Estimated Days</label>
        <input type="number" name="estimated_days" value="1" min="1" max="30" class="form-control" />
      </div>
      <div class="form-field">
        <label>Warranty (Days)</label>
        <input type="number" name="warranty_days" value="90" min="0" max="365" class="form-control" />
      </div>
      <button type="submit" class="btn-primary" style="justify-content:center;margin-top:6px;">
        <i class="fas fa-plus"></i> Create Service
      </button>
    </form>
  </div>

</div>
