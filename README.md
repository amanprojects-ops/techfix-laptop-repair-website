# TechFix — Laptop Repair Center Website

> **A project by [AmanProjects](https://github.com/amanprojects-ops)**

![AmanProjects](https://img.shields.io/badge/Built%20by-AmanProjects-2563EB?style=for-the-badge&logo=github)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

A modern, conversion-focused laptop repair business website for **TechFix Laptop Repair Center, Saharsa, Bihar**. Designed to turn visitors into customers — not just a brochure site.

**Live Demo:** https://amanprojects-ops.github.io/techfix-laptop-repair-website

---

## Screenshots

```
┌─────────────────────────────────────────────┐
│  YOUR LAPTOP. OUR EXPERTISE.               │
│  Fast & Reliable Laptop Repair             │
│  [ Book a Repair ]  [ WhatsApp Us ]        │
│                                             │
│  ★★★★★ 4.9  |  10K+ Repairs  |  90D Warranty │
└─────────────────────────────────────────────┘
```

---

## Features

### Core Pages
| Page | Description |
|------|-------------|
| `index.html` | Full homepage with 13 sections |
| `pricing.html` | Transparent pricing tables for all services |
| `contact.html` | Multi-step booking form (4 steps) |
| `repair-status.html` | Live repair tracking by Repair ID |

### Homepage Sections
- **Sticky Navbar** — dropdown services menu, mobile hamburger drawer
- **Hero** — animated diagnosis card, trust signals, dual CTA
- **Trust Bar** — 4.9 rating, 10K+ devices, 10 years, 90-day warranty
- **"What's Wrong?"** — problem-based diagnosis tool (8 problem cards → instant service recommendation)
- **Services Grid** — 12 service cards with price, time, warranty
- **Repair Process** — 5-step visual flow
- **Why Choose Us** — proof-based, 6 cards
- **Before / After Gallery** — visual repair proof
- **Customer Reviews** — 6 verified Google-style reviews
- **FAQ Accordion** — 6 common questions
- **Location Section** — address, hours, directions
- **Final CTA** — Book / Call / WhatsApp
- **Footer** — links, social, locations

### Interactive Features
- Smart diagnosis tool — click problem → get service, price, ETA, warranty
- Multi-step booking form — Device → Problem → Service type → Details → Confirmation with Repair ID
- Repair tracker — enter Repair ID, see live timeline with stages
- FAQ accordion — smooth open/close
- Mobile bottom sticky bar — Call / WhatsApp / Book always visible
- Navbar scroll shadow
- Mobile nav drawer with overlay

### Design System
| Token | Value |
|-------|-------|
| Background | `#F8FAFC` / Dark `#0A0F1E` |
| Primary Accent | Electric Blue `#2563EB` |
| Text | Near Black `#0F172A` |
| Cards | White / `#1A2235` |
| Border Radius | 16–20px |
| Font | Inter |
| Icons | Lucide |

### SEO & Local Business
- `LocalBusiness` JSON-LD structured data on homepage
- Location-specific meta descriptions
- Semantic HTML5 structure (header, nav, main, section, footer)
- URL structure ready for location pages (Saharsa, Supaul, Madhepura)

---

## Project Structure

```
techfix-laptop-repair-website/
│
├── index.html              # Homepage (13 sections)
├── pricing.html            # Full pricing tables
├── contact.html            # Booking form
├── repair-status.html      # Repair tracker
│
├── assets/
│   ├── css/
│   │   └── styles.css          # Complete design system (~41KB)
│   ├── js/
│   │   └── main.js             # All interactivity (~16KB)
│   └── images/                 # Logo & shop photos
│
├── admin/                      # TechFix Admin Management Portal
│   ├── assets/                 # Admin CSS, JS, and SVG Icons
│   └── ...
├── .gitignore
└── README.md
```

---

## Getting Started

### Option 1 — Direct Open
No build tools needed. Just open `index.html` in any browser.

```bash
# Clone the repo
git clone https://github.com/amanprojects-ops/techfix-laptop-repair-website.git

# Open in browser
cd techfix-laptop-repair-website
start index.html        # Windows
open index.html         # macOS
```

### Option 2 — Live Server (Recommended for development)
If you have VS Code:
1. Install the **Live Server** extension
2. Right-click `index.html` → **Open with Live Server**

---

## Customization Guide

### 1. Business Details
Search and replace across all HTML files:

| Placeholder | Replace With |
|-------------|-------------|
| `TechFix` | Your shop name |
| `+91 98765 43210` | Your phone number |
| `info@amanprojects.com` | Your email |
| `Main Market Road, Near Bus Stand, Saharsa` | Your address |
| `852201` | Your PIN code |

### 2. Pricing
Edit the `<tbody>` rows in `pricing.html` — each row follows:
```html
<tr>
  <td class="td-service">Service Name</td>
  <td class="td-price">₹X,XXX</td>
  <td>Time</td>
  <td class="td-warranty">Warranty</td>
</tr>
```

### 3. Colors
Edit CSS variables at the top of `css/styles.css`:
```css
:root {
  --accent:       #2563EB;   /* Change primary color */
  --bg-dark:      #0A0F1E;   /* Change dark background */
  --whatsapp:     #25D366;   /* WhatsApp green */
}
```

### 4. WhatsApp Link
Replace `919876543210` with your number (country code + number, no spaces):
```html
href="https://wa.me/91XXXXXXXXXX"
```

### 5. Google Maps
Replace the map placeholder in `index.html` and `contact.html` with an actual Google Maps embed iframe.

### 6. Reviews & Stats
Update the trust bar numbers and review cards in `index.html` with your real data.

---

## Repair Tracker — Demo IDs

The tracker on `repair-status.html` works with these demo Repair IDs:

| Repair ID | Device | Status |
|-----------|--------|--------|
| `LR-2026-10293` | Dell Inspiron 15 | Repair In Progress |
| `LR-2026-10281` | HP Pavilion 14 | Ready for Pickup |
| `LR-2026-10270` | Lenovo IdeaPad 3 | Diagnosis Complete |

To connect to a real backend, replace the `demoRepairs` object in `js/main.js` with an API call.

---

## Deployment

### Netlify (Free — Recommended)
1. Go to [netlify.com](https://netlify.com)
2. Drag and drop the project folder
3. Done — live in 30 seconds

### GitHub Pages
```bash
# Push to GitHub, then enable Pages in repo settings
# Settings → Pages → Source: main branch → / (root)
```

### Vercel
```bash
npm i -g vercel
vercel
```

---

## Tech Stack

- **HTML5** — Semantic markup, JSON-LD structured data
- **CSS3** — Custom properties, Grid, Flexbox, responsive breakpoints
- **Vanilla JavaScript** — No frameworks, no dependencies
- **[Lucide Icons](https://lucide.dev)** — via CDN
- **[Inter Font](https://fonts.google.com/specimen/Inter)** — via Google Fonts

Zero build tools. Zero npm installs. Opens straight in browser.

---

## Browser Support

| Browser | Support |
|---------|---------|
| Chrome | ✅ Full |
| Firefox | ✅ Full |
| Safari | ✅ Full |
| Edge | ✅ Full |
| Mobile Chrome | ✅ Full |
| Mobile Safari | ✅ Full |

---

## License

MIT License — free to use, modify, and deploy for your own repair business.

---

## Author

<table>
  <tr>
    <td align="center">
      <strong>AmanProjects</strong><br/>
      <a href="https://github.com/amanprojects-ops">@amanprojects-ops</a><br/>
      <sub>Designed & Built with ❤️</sub>
    </td>
  </tr>
</table>

---

<div align="center">

**© 2026 AmanProjects — techfix-laptop-repair-website**

_If this helped you, give it a ⭐ on GitHub_

</div>
