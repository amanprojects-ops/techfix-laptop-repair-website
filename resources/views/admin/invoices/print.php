<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Invoice_<?= htmlspecialchars($invoice['invoice_number'] ?? 'INV', ENT_QUOTES) ?> — <?= htmlspecialchars(site_name(), ENT_QUOTES) ?></title>
  
  <!-- Preconnect & Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      background: #F1F5F9;
      font-family: 'Inter', sans-serif;
      color: #0F172A;
      padding: 30px 15px;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
    .no-print-bar {
      max-width: 860px;
      margin: 0 auto 20px auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #FFFFFF;
      padding: 12px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.06);
      border: 1px solid #E2E8F0;
    }
    .btn-print {
      background: #2563EB;
      color: #FFFFFF;
      border: none;
      padding: 9px 18px;
      border-radius: 6px;
      font-weight: 700;
      font-size: 0.9rem;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      transition: background 0.2s;
    }
    .btn-print:hover {
      background: #1D4ED8;
    }
    .btn-back {
      background: #F8FAFC;
      color: #475569;
      border: 1px solid #CBD5E1;
      padding: 8px 16px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 0.88rem;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-back:hover {
      background: #E2E8F0;
    }

    @media print {
      body {
        background: #FFFFFF !important;
        padding: 0 !important;
      }
      .no-print-bar {
        display: none !important;
      }
      .invoice-container {
        box-shadow: none !important;
        border: none !important;
        max-width: 100% !important;
        padding: 10px !important;
        border-radius: 0 !important;
      }
      @page {
        margin: 10mm;
      }
    }
  </style>
</head>
<body>

  <!-- Floating Print & Back Actions -->
  <div class="no-print-bar">
    <div style="display: flex; align-items: center; gap: 12px;">
      <a href="<?= url('/admin/invoices/' . ($invoice['id'] ?? '')) ?>" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Invoice Details
      </a>
      <span style="font-size: 0.88rem; color: #64748B;">Template: <strong><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $invoice['template_key'] ?? 'modern')), ENT_QUOTES) ?></strong></span>
    </div>

    <div style="display: flex; align-items: center; gap: 10px;">
      <button onclick="window.print()" class="btn-print">
        <i class="fas fa-print"></i> Print / Save as PDF
      </button>
    </div>
  </div>

  <!-- Rendered Invoice HTML -->
  <div class="print-wrapper">
    <?= $renderedHtml ?>
  </div>

  <script>
    // Auto-trigger print dialog if ?auto=1 in URL
    if (new URLSearchParams(window.location.search).get('auto') === '1') {
      window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => { window.print(); }, 300);
      });
    }
  </script>

</body>
</html>
