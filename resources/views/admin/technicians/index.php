<header class="header">
  <div class="header-left">
    <button id="sidebar-toggle" class="sidebar-toggle"><i class="fas fa-bars"></i></button>
    <div class="header-title-wrap">
      <h2>Technicians</h2>
      <span class="header-subtitle">Manage workshop technicians</span>
    </div>
  </div>
</header>
<div style="padding:1.5rem;display:grid;grid-template-columns:1fr 340px;gap:1.5rem;">
  <!-- List -->
  <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;overflow:hidden;">
    <?php if ($flash_success): ?><div style="background:#d1fae5;color:#065f46;padding:12px 16px;font-size:0.875rem;font-weight:600;">✓ <?= htmlspecialchars($flash_success, ENT_QUOTES) ?></div><?php endif; ?>
    <?php if ($flash_error): ?><div style="background:#fee2e2;color:#991b1b;padding:12px 16px;font-size:0.875rem;font-weight:600;">⚠ <?= htmlspecialchars($flash_error, ENT_QUOTES) ?></div><?php endif; ?>
    <?php if (empty($technicians)): ?>
    <div style="padding:3rem;text-align:center;color:var(--text-muted);"><i class="fas fa-user-cog" style="font-size:2rem;opacity:.3;margin-bottom:12px;display:block;"></i>No technicians yet. Add the first one.</div>
    <?php else: ?>
    <table style="width:100%;border-collapse:collapse;">
      <thead><tr style="background:var(--table-header);">
        <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Technician</th>
        <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Phone</th>
        <th style="padding:10px 16px;text-align:left;font-size:0.78rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;">Status</th>
        <th style="padding:10px 16px;"></th>
      </tr></thead>
      <tbody>
        <?php foreach ($technicians as $t): ?>
        <tr style="border-top:1px solid var(--border);">
          <td style="padding:12px 16px;">
            <div style="font-weight:600;color:var(--text);font-size:0.875rem;"><?= htmlspecialchars($t['name'], ENT_QUOTES) ?></div>
            <div style="font-size:0.78rem;color:var(--text-muted);"><?= htmlspecialchars($t['email'], ENT_QUOTES) ?></div>
          </td>
          <td style="padding:12px 16px;font-size:0.875rem;color:var(--text-muted);"><?= htmlspecialchars($t['phone'] ?? '—', ENT_QUOTES) ?></td>
          <td style="padding:12px 16px;">
            <span style="font-size:0.75rem;font-weight:700;padding:4px 10px;border-radius:999px;background:<?= $t['status']==='active' ? '#d1fae5' : '#fee2e2' ?>;color:<?= $t['status']==='active' ? '#065f46' : '#991b1b' ?>;">
              <?= ucfirst($t['status']) ?>
            </span>
          </td>
          <td style="padding:12px 16px;text-align:right;">
            <form method="POST" action="/admin/technicians/<?= $t['id'] ?>/toggle" style="display:inline;">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
              <button type="submit" style="font-size:0.78rem;font-weight:600;padding:5px 12px;border-radius:6px;border:1px solid var(--border);background:var(--card-bg);color:var(--text-muted);cursor:pointer;">
                <?= $t['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- Add Form -->
  <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:12px;padding:1.5rem;height:fit-content;">
    <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);margin-bottom:16px;"><i class="fas fa-user-plus"></i> Add New Technician</div>
    <form method="POST" action="/admin/technicians" style="display:flex;flex-direction:column;gap:12px;">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" />
      <div>
        <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Full Name *</label>
        <input type="text" name="name" required placeholder="Technician name" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
      </div>
      <div>
        <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Email *</label>
        <input type="email" name="email" required placeholder="email@example.com" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
      </div>
      <div>
        <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Phone</label>
        <input type="tel" name="phone" placeholder="Optional" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
      </div>
      <div>
        <label style="font-size:0.78rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:6px;">Password *</label>
        <input type="password" name="password" required placeholder="Minimum 8 characters" style="width:100%;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:var(--card-bg);color:var(--text);font-size:0.875rem;" />
      </div>
      <button type="submit" style="font-size:0.875rem;font-weight:700;padding:9px;border-radius:8px;background:var(--accent);color:#fff;border:none;cursor:pointer;"><i class="fas fa-user-plus"></i> Add Technician</button>
    </form>
  </div>
</div>
