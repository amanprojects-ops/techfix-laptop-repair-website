<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Workshop Technicians</h2>
      <span class="header-subtitle">Manage lab engineers, contact details, and repair assignment</span>
    </div>
  </div>
</header>

<div style="padding:24px;display:grid;grid-template-columns:1fr 340px;gap:24px;">

  <!-- Technician List Card -->
  <div class="table-card" style="height:fit-content;">
    <?php if (!empty($flash_success)): ?>
    <div style="background:#ECFDF5;border-bottom:1px solid #A7F3D0;color:#065F46;padding:12px 18px;font-size:0.875rem;font-weight:700;">
      <i class="fas fa-check-circle" style="color:#10B981;"></i> <?= htmlspecialchars($flash_success, ENT_QUOTES) ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($flash_error)): ?>
    <div style="background:#FEF2F2;border-bottom:1px solid #FECACA;color:#991B1B;padding:12px 18px;font-size:0.875rem;font-weight:700;">
      <i class="fas fa-exclamation-triangle" style="color:#EF4444;"></i> <?= htmlspecialchars($flash_error, ENT_QUOTES) ?>
    </div>
    <?php endif; ?>

    <?php if (empty($technicians)): ?>
    <div style="padding:3.5rem 2rem;text-align:center;color:var(--text-muted);">
      <i class="fas fa-user-cog" style="font-size:3rem;margin-bottom:14px;display:block;opacity:.35;"></i>
      <strong style="font-size:1rem;color:var(--text-primary);">No technicians registered yet.</strong>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Technician Name</th>
            <th>Phone</th>
            <th>Specialization</th>
            <th>Status</th>
            <th style="text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($technicians as $t): ?>
          <tr>
            <td>
              <strong style="color:var(--text-primary);font-size:0.9rem;display:flex;align-items:center;gap:8px;">
                <span style="width:28px;height:28px;background:var(--primary-light);color:var(--primary-color);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;">
                  <?= strtoupper(substr($t['name'], 0, 1)) ?>
                </span>
                <?= htmlspecialchars($t['name'], ENT_QUOTES) ?>
              </strong>
              <span style="font-size:0.78rem;color:var(--text-muted);padding-left:36px;"><?= htmlspecialchars($t['email'] ?? '', ENT_QUOTES) ?></span>
            </td>
            <td>
              <a href="tel:<?= htmlspecialchars($t['phone'] ?? '', ENT_QUOTES) ?>" style="color:var(--primary-color);font-weight:600;">
                <?= htmlspecialchars($t['phone'] ?? '—', ENT_QUOTES) ?>
              </a>
            </td>
            <td>
              <span style="font-size:0.85rem;color:var(--text-secondary);"><?= htmlspecialchars($t['specialization'] ?? 'Chip-Level Hardware', ENT_QUOTES) ?></span>
            </td>
            <td>
              <span class="status-pill" style="background:<?= $t['status']==='active' ? '#ECFDF5' : '#FEF2F2' ?>;color:<?= $t['status']==='active' ? '#065F46' : '#991B1B' ?>;border:1px solid <?= $t['status']==='active' ? '#A7F3D0' : '#FECACA' ?>;">
                <?= ucfirst($t['status']) ?>
              </span>
            </td>
            <td style="text-align:right;">
              <form method="POST" action="<?= url('/admin/technicians/' . $t['id'] . '/toggle') ?>" style="display:inline;">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
                <button type="submit" class="btn-secondary btn-sm" style="color:var(--text-muted);">
                  <?= $t['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <!-- Add Technician Card -->
  <div class="form-card" style="height:fit-content;">
    <div style="font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--primary-color);margin-bottom:18px;display:flex;align-items:center;gap:8px;">
      <i class="fas fa-user-plus"></i> Add New Technician
    </div>
    <form method="POST" action="<?= url('/admin/technicians') ?>" style="display:flex;flex-direction:column;gap:14px;">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
      <div class="form-field">
        <label>Full Name *</label>
        <input type="text" name="name" required placeholder="e.g. Sunil Kumar" class="form-control" />
      </div>
      <div class="form-field">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="sunil@techfix.in" class="form-control" />
      </div>
      <div class="form-field">
        <label>Phone Number *</label>
        <input type="tel" name="phone" required placeholder="10-digit mobile number" class="form-control" />
      </div>
      <div class="form-field">
        <label>Specialization</label>
        <input type="text" name="specialization" placeholder="e.g. Motherboard BGA, Display Panels" class="form-control" />
      </div>
      <button type="submit" class="btn-primary" style="justify-content:center;margin-top:6px;">
        <i class="fas fa-user-check"></i> Register Technician
      </button>
    </form>
  </div>

</div>
