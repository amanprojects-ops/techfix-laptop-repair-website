<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Customers</h2>
      <span class="header-subtitle">All registered customers</span>
    </div>
  </div>
</header>
<div style="padding:1.5rem;">
  <form method="GET" action="/admin/customers" style="margin-bottom:1.25rem;display:flex;gap:10px;">
    <input type="text" name="q" value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>" placeholder="Search by name, phone, email..." style="flex:1;padding:9px 14px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
    <button type="submit" style="padding:9px 18px;border-radius:8px;background:var(--accent);color:#fff;font-weight:700;border:none;cursor:pointer;font-size:0.875rem;">Search</button>
    <?php if ($search): ?><a href="/admin/customers" style="padding:9px 16px;border-radius:8px;border:1px solid var(--border);color:var(--text-muted);text-decoration:none;font-size:0.875rem;">Clear</a><?php endif; ?>
  </form>
  <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
    <?php if (empty($customers)): ?>
    <div style="padding:3rem;text-align:center;color:var(--text-muted);">No customers found.</div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:var(--table-header);">
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Name</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Phone</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Email</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">City</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Total Jobs</th>
            <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Joined</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($customers as $c): ?>
          <tr style="border-top:1px solid var(--border);">
            <td style="padding:12px 16px;font-weight:600;color:var(--text);font-size:0.875rem;"><?= htmlspecialchars($c['name'], ENT_QUOTES) ?></td>
            <td style="padding:12px 16px;font-size:0.875rem;color:var(--text-muted);"><?= htmlspecialchars($c['phone'], ENT_QUOTES) ?></td>
            <td style="padding:12px 16px;font-size:0.875rem;color:var(--text-muted);"><?= htmlspecialchars($c['email'] ?? '—', ENT_QUOTES) ?></td>
            <td style="padding:12px 16px;font-size:0.875rem;color:var(--text-muted);"><?= htmlspecialchars($c['city'] ?? '—', ENT_QUOTES) ?></td>
            <td style="padding:12px 16px;font-size:0.875rem;font-weight:700;color:var(--accent);"><?= (int)($c['total_jobs'] ?? 0) ?></td>
            <td style="padding:12px 16px;font-size:0.8rem;color:var(--text-muted);"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
