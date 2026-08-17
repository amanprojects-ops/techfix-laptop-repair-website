# 🛠️ TechFix — Laptop Repair Admin & Workshop Management Portal

> **Dedicated Admin Portal, Live Lab Queue, Job Card Generator, and Financial Analytics Suite built for TechFix Laptop Repair Center (Saharsa, Bihar).**

---

## 📌 Overview

**TechFix Admin Portal** is customized specifically for modern laptop and computer repair workshops. It empowers technicians and shop managers to handle device intake, track multi-stage repair lifecycles, print invoices & barcode job sheets, monitor spare parts inventory, and analyze workshop KPIs in real-time.

---

## ✨ Key Features

- 🎨 **Tailored TechFix Branding & Logo**: Built with custom SVG branding, deep navy/electric blue design system, and Google Fonts Inter.
- 💻 **Live Workshop Repair Queue**: Real-time management of active tickets (`LR-2026-XXXXX`), status filters, technician assignment, and WhatsApp notification shortcuts.
- 📋 **Device Intake & Job Card Generator (`create.html`)**: Multi-section intake form capturing customer details, brand, model, serials, customer complaints, accessories received, and financial estimates.
- ⚡ **Diagnostics & Stage Progression (`edit.html`)**: 6-stage lifecycle tracking (Received ➔ Diagnosing ➔ In-Progress ➔ Quality Testing ➔ Ready for Pickup ➔ Delivered).
- 🧾 **Printable Job Sheet & Official Invoice (`view.html`)**: Full itemized tax invoice with spare parts, labor charges, warranty certificate (90-day TechFix Guarantee), and printable voucher styling.
- 📊 **Visual Workshop Analytics (`reports.html`)**: Chart.js graphs for repair intake vs completion trends, revenue breakdown, and individual engineer CSAT/efficiency rankings.
- 🔐 **Secure Staff Authentication (`login.html`, `register.html`, `forgot-password.html`)**: Role-based technician portal with client-side validation and password show/hide toggles.

---

## 📂 Directory Structure

```text
admin/
├── assets/
│   ├── css/
│   │   ├── styles.css      # TechFix Admin Design System
│   │   └── auth.css        # Auth Layout & Glow Effects
│   ├── js/
│   │   ├── script.js       # Core Drawer, Table Filters & Chart.js Visualizations
│   │   └── auth.js         # Authentication Form Validation
│   └── images/
│       ├── logo.svg        # TechFix Admin Vector Brandmark
│       └── icon.svg        # Standalone Icon / Favicon
├── index.html              # Repair Operations Dashboard Overview
├── create.html             # New Device Intake & Job Card Generator
├── edit.html               # Update Repair Stage & Diagnostic Findings
├── view.html               # Printable Job Sheet & Tax Invoice
├── reports.html            # Workshop Analytics & Technician Performance
├── blank.html              # Custom Workshop Extension Template
├── login.html              # Staff Login Portal
├── register.html           # Staff Registration Page
└── forgot-password.html    # Password Reset Page
```

---

## 🚀 Quick Usage

- **Admin Dashboard**: Open [`admin/index.html`](file:///c:/Users/Technical%20Aman/Desktop/test/aman-laptop-reparing/admin/index.html)
- **New Device Intake**: Open [`admin/create.html`](file:///c:/Users/Technical%20Aman/Desktop/test/aman-laptop-reparing/admin/create.html)
- **View Job Sheet**: Open [`admin/view.html`](file:///c:/Users/Technical%20Aman/Desktop/test/aman-laptop-reparing/admin/view.html)
- **Staff Login**: Open [`admin/login.html`](file:///c:/Users/Technical%20Aman/Desktop/test/aman-laptop-reparing/admin/login.html)
- **Live Customer Tracker**: Open [`repair-status.html`](file:///c:/Users/Technical%20Aman/Desktop/test/aman-laptop-reparing/repair-status.html)
