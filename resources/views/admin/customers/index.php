<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Registered Customers</h2>
      <span class="header-subtitle">Directory of all customers and laptop repair history</span>
    </div>
  </div>
  <div class="header-right">
    <a href="/admin/repairs/create" class="btn-primary"><i class="fas fa-user-plus"></i> New Intake</a>
  </div>
</header>

<div style="padding:24px;">
  <form method="GET" action="/admin/customers" style="margin-bottom:20px;display:flex;gap:10px;max-width:600px;">
    <input type="text" name="q" value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>" placeholder="Search by customer name, phone number, email..." class="form-control" />
    <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Search</button>
    <?php if ($search): ?>
    <a href="/admin/customers" class="btn-secondary">Clear</a>
    <?php endif; ?>
  </form>

  <div class="table-card">
    <?php if (empty($customers)): ?>
    <div style="padding:3.5rem 2rem;text-align:center;color:var(--text-muted);">
      <i class="fas fa-users" style="font-size:3rem;margin-bottom:14px;display:block;opacity:.35;"></i>
      <strong style="font-size:1rem;color:var(--text-primary);">No customers found matching your criteria.</strong>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="data-table">
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>City / Location</th>
            <th>Total Repairs</th>
            <th>Member Since</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($customers as $c): ?>
          <tr>
            <td>
              <strong style="color:var(--text-primary);font-size:0.9rem;display:flex;align-items:center;gap:8px;">
                <span style="width:28px;height:28px;background:var(--primary-light);color:var(--primary-color);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;">
                  <?= strtoupper(substr($c['name'], 0, 1)) ?>
                </span>
                <?= htmlspecialchars($c['name'], ENT_QUOTES) ?>
              </strong>
            </td>
            <td>
              <a href="tel:<?= htmlspecialchars($c['phone'], ENT_QUOTES) ?>" style="color:var(--primary-color);font-weight:600;">
                <?= htmlspecialchars($c['phone'], ENT_QUOTES) ?>
              </a>
            </td>
            <td>
              <span style="font-size:0.85rem;color:var(--text-secondary);"><?= htmlspecialchars($c['email'] ?? '—', ENT_QUOTES) ?></span>
            </td>
            <td>
              <span style="font-size:0.85rem;color:var(--text-secondary);"><?= htmlspecialchars($c['city'] ?? '—', ENT_QUOTES) ?></span>
            </td>
            <td>
              <span style="font-weight:800;color:var(--primary-color);background:var(--primary-light);padding:3px 10px;border-radius:var(--radius-full);font-size:0.8rem;">
                <?= (int)($c['total_jobs'] ?? 0) ?> jobs
              </span>
            </td>
            <td>
              <span style="font-size:0.8125rem;color:var(--text-muted);"><?= date('d M Y', strtotime($c['created_at'])) ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
