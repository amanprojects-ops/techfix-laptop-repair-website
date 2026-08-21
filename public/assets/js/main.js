/* ============================================================
   TechFix — Frontend JavaScript (main.js)
   Features:
   - Lucide Icons Safe Initialization
   - Dynamic Navbar Scroll Effects
   - Mobile Nav Drawer & Overlay
   - Smart Problem Diagnosis Tool
   - FAQ Accordion with Micro-animations
   - Input Helpers & Validation
   - Smooth Anchors Navigation
   ============================================================ */

function initLucideIcons() {
  if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
    try {
      lucide.createIcons();
    } catch (e) {
      console.warn('Lucide icons render note:', e);
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {

  // ── 1. Init Lucide Icons ─────────────────────────────────────
  initLucideIcons();


  // ── 2. Navbar Scroll Behavior ────────────────────────────────
  const navbar = document.getElementById('navbar');
  if (navbar) {
    const handleScroll = () => {
      if (window.scrollY > 10) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
  }


  // ── 3. Mobile Navigation Drawer ──────────────────────────────
  const hamburger        = document.getElementById('hamburger');
  const mobileNav        = document.getElementById('mobileNav');
  const mobileNavOverlay = document.getElementById('mobileNavOverlay');
  const mobileNavClose   = document.getElementById('mobileNavClose');

  function openMobileNav() {
    mobileNav?.classList.add('open');
    mobileNavOverlay?.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeMobileNav() {
    mobileNav?.classList.remove('open');
    mobileNavOverlay?.classList.remove('open');
    document.body.style.overflow = '';
  }

  hamburger?.addEventListener('click', (e) => {
    e.stopPropagation();
    openMobileNav();
  });

  mobileNavClose?.addEventListener('click', closeMobileNav);
  mobileNavOverlay?.addEventListener('click', closeMobileNav);

  // Close when pressing Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && mobileNav?.classList.contains('open')) {
      closeMobileNav();
    }
  });

  // Close on navigation link click
  document.querySelectorAll('.mobile-nav__link').forEach(link => {
    link.addEventListener('click', closeMobileNav);
  });


  // ── 4. FAQ Accordion ─────────────────────────────────────────
  document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      if (!item) return;
      const isOpen = item.classList.contains('open');

      // Close other open FAQ items
      document.querySelectorAll('.faq-item.open').forEach(openItem => {
        if (openItem !== item) {
          openItem.classList.remove('open');
        }
      });

      // Toggle current item
      if (!isOpen) {
        item.classList.add('open');
      } else {
        item.classList.remove('open');
      }

      initLucideIcons();
    });
  });


  // ── 5. Smart Diagnosis Tool ──────────────────────────────────
  const problemData = {
    'not-charging': {
      icon: '🔋',
      service: 'Battery Replacement / Charging Jack Repair',
      time: '1–3 Hours',
      price: 'From ₹999',
      warranty: '6 Months',
      desc: 'Could be a faulty battery cell, damaged charging jack, or power management IC issue. We perform instant pin-level voltage diagnosis.'
    },
    'screen-broken': {
      icon: '🖥️',
      service: 'Original Quality Screen Replacement',
      time: '2–4 Hours',
      price: 'From ₹2,499',
      warranty: '90 Days',
      desc: 'Cracked, lines on screen, or flickering display. Replaced with grade-A original quality panels with exact resolution match.'
    },
    'keyboard': {
      icon: '⌨️',
      service: 'Keyboard Replacement & Key Repair',
      time: '1–3 Hours',
      price: 'From ₹1,499',
      warranty: '90 Days',
      desc: 'Non-responsive keys, liquid damage, or backlight failure. Compatible OEM keyboards for Dell, HP, Lenovo, ASUS & Acer in stock.'
    },
    'not-turning-on': {
      icon: '⚡',
      service: 'Motherboard Chip-Level Diagnosis & Repair',
      time: '1–3 Days',
      price: 'Quote on Diagnosis',
      warranty: '90 Days',
      desc: 'Power IC short circuit, BIOS corruption, or blown MOSFETs. We test component by component under microscope and restore the board.'
    },
    'liquid-damage': {
      icon: '💧',
      service: 'Ultrasonic Liquid Damage Chemical Wash & Repair',
      time: '1–2 Days',
      price: 'From ₹1,499',
      warranty: '30 Days',
      desc: 'Do not turn device on. We perform ultrasonic chemical cleaning to remove corrosion and resolder damaged traces.'
    },
    'overheating': {
      icon: '🔥',
      service: 'Thermal Overhaul & Fan Replacement',
      time: '1–2 Hours',
      price: 'From ₹799',
      warranty: '90 Days',
      desc: 'Deep internal dust removal, copper heatsink cleaning, and premium thermal paste application (Arctic MX-4 / Kryonaut).'
    },
    'slow': {
      icon: '🐢',
      service: 'NVMe SSD Speed Upgrade & RAM Expansion',
      time: '1–2 Hours',
      price: 'From ₹499',
      warranty: '3–5 Years (Parts)',
      desc: 'Transform your slow laptop into 10x faster with High-Speed NVMe SSD upgrade, RAM expansion and clean OS installation.'
    },
    'data-recovery': {
      icon: '💾',
      service: 'Professional Data Recovery (HDD / SSD / NVMe)',
      time: '1–2 Days',
      price: 'From ₹1,999',
      warranty: 'Confidential & Safe',
      desc: 'Accidental formatting, RAW partitions, clicking drives, or unreadable sectors. 100% confidential and safe recovery.'
    }
  };

  const diagResult   = document.getElementById('diagResult');
  const diagIcon     = document.getElementById('diagIcon');
  const diagService  = document.getElementById('diagService');
  const diagTime     = document.getElementById('diagTime');
  const diagPrice    = document.getElementById('diagPrice');
  const diagWarranty = document.getElementById('diagWarranty');
  const diagDesc     = document.getElementById('diagDesc');
  const diagCTA      = document.getElementById('diagCTA');

  document.querySelectorAll('.problem-card').forEach(card => {
    card.addEventListener('click', () => {
      document.querySelectorAll('.problem-card').forEach(c => c.classList.remove('active'));
      card.classList.add('active');

      const problemKey = card.dataset.problem;
      const data = problemData[problemKey];
      if (!data || !diagResult) return;

      diagIcon.textContent = data.icon;
      diagService.textContent = data.service;
      
      if (diagTime) diagTime.innerHTML = `<i data-lucide="clock"></i> ${data.time}`;
      if (diagPrice) diagPrice.innerHTML = `<i data-lucide="indian-rupee"></i> ${data.price}`;
      if (diagWarranty) diagWarranty.innerHTML = `<i data-lucide="shield"></i> ${data.warranty}`;
      if (diagDesc) diagDesc.textContent = data.desc;
      if (diagCTA) diagCTA.style.display = 'inline-flex';

      diagResult.classList.add('active');

      initLucideIcons();

      // Smooth scroll into view on small devices
      if (window.innerWidth < 768) {
        diagResult.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    });
  });


  // ── 6. Smooth Scroll for Page Anchors ────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === '#' || !href) return;
      
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const offset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--navbar-h')) || 72;
        const targetPos = target.getBoundingClientRect().top + window.scrollY - offset - 12;
        window.scrollTo({ top: targetPos, behavior: 'smooth' });
      }
    });
  });


  // ── 7. Input Helpers (Auto uppercase for tracking ID) ────────
  const trackingInputs = document.querySelectorAll('input[name="tracking_id"], #repairIdInput');
  trackingInputs.forEach(input => {
    input.addEventListener('input', () => {
      input.value = input.value.toUpperCase();
    });
  });

});
