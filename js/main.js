/* ============================================================
   TechFix — main.js
   Features:
   - Lucide icons init
   - Navbar scroll behavior
   - Mobile nav drawer
   - Dropdown keyboard nav
   - Problem diagnosis tool
   - FAQ accordion
   - Booking form multi-step
   - Repair tracker
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  // ── Init Lucide Icons ──────────────────────────────────
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }


  // ── Navbar scroll shadow ───────────────────────────────
  const navbar = document.getElementById('navbar');
  if (navbar) {
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 10);
    }, { passive: true });
  }


  // ── Mobile Nav ─────────────────────────────────────────
  const hamburger   = document.getElementById('hamburger');
  const mobileNav   = document.getElementById('mobileNav');
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

  hamburger?.addEventListener('click', openMobileNav);
  mobileNavClose?.addEventListener('click', closeMobileNav);
  mobileNavOverlay?.addEventListener('click', closeMobileNav);

  // Close on mobile link click
  document.querySelectorAll('.mobile-nav__link').forEach(link => {
    link.addEventListener('click', closeMobileNav);
  });


  // ── FAQ Accordion ──────────────────────────────────────
  document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      const isOpen = item.classList.contains('open');

      // Close all
      document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));

      // Open clicked (unless it was already open)
      if (!isOpen) item.classList.add('open');

      // Re-init icons (chevron rotates via CSS transform)
      if (typeof lucide !== 'undefined') lucide.createIcons();
    });
  });


  // ── Diagnosis Tool ─────────────────────────────────────
  const problemData = {
    'not-charging': {
      icon: '🔋',
      service: 'Battery Replacement / Charging Jack Repair',
      time: '1–3 Hours',
      price: 'From ₹999',
      warranty: '6 Months',
      desc: 'Could be a faulty battery, damaged charging jack, or power management issue. We\'ll diagnose the exact cause and fix it — same day in most cases.'
    },
    'screen-broken': {
      icon: '🖥️',
      service: 'Screen Replacement',
      time: '2–4 Hours',
      price: 'From ₹2,499',
      warranty: '90 Days',
      desc: 'Cracked or shattered display — we replace it with original quality panels. Most screen replacements are done within the same day.'
    },
    'keyboard': {
      icon: '⌨️',
      service: 'Keyboard Replacement / Repair',
      time: '1–3 Hours',
      price: 'From ₹1,499',
      warranty: '90 Days',
      desc: 'Individual keys, backlight issues, or complete keyboard failure. We have compatible keyboards for all major laptop brands in stock.'
    },
    'not-turning-on': {
      icon: '⚡',
      service: 'Motherboard Diagnosis & Repair',
      time: '1–3 Days',
      price: 'After Free Diagnosis',
      warranty: '90 Days',
      desc: 'Could be a power IC failure, BIOS issue, or short circuit. Our technicians perform component-level motherboard diagnosis to find the exact fault.'
    },
    'liquid-damage': {
      icon: '💧',
      service: 'Liquid Damage Repair',
      time: '1–2 Days',
      price: 'From ₹1,499',
      warranty: '30 Days',
      desc: 'Bring it in immediately — do not turn it on. We\'ll clean the board, remove corrosion, and restore damaged components. Faster action = higher success rate.'
    },
    'overheating': {
      icon: '🔥',
      service: 'Thermal Cleaning & Fan Repair',
      time: '1–2 Hours',
      price: 'From ₹799',
      warranty: '90 Days',
      desc: 'Deep cleaning, thermal paste replacement and fan repair. Your laptop will run cooler and faster after this service.'
    },
    'slow': {
      icon: '🐢',
      service: 'SSD Upgrade / RAM Upgrade / Windows Reinstall',
      time: '1–3 Hours',
      price: 'From ₹499',
      warranty: '1 Year (parts)',
      desc: 'A slow laptop is usually fixed by upgrading to an SSD, adding more RAM, or a clean Windows reinstall. We\'ll recommend the most cost-effective solution.'
    },
    'data-recovery': {
      icon: '💾',
      service: 'Data Recovery',
      time: '1–2 Days',
      price: 'From ₹1,999',
      warranty: 'Best Effort',
      desc: 'Accidentally deleted files, formatted drive, or failed storage. We use professional tools to recover your important data from HDDs and SSDs.'
    }
  };

  const diagResult  = document.getElementById('diagResult');
  const diagIcon    = document.getElementById('diagIcon');
  const diagService = document.getElementById('diagService');
  const diagTime    = document.getElementById('diagTime');
  const diagPrice   = document.getElementById('diagPrice');
  const diagWarranty= document.getElementById('diagWarranty');
  const diagDesc    = document.getElementById('diagDesc');
  const diagCTA     = document.getElementById('diagCTA');

  document.querySelectorAll('.problem-card').forEach(card => {
    card.addEventListener('click', () => {
      // Highlight selected
      document.querySelectorAll('.problem-card').forEach(c => c.classList.remove('active'));
      card.classList.add('active');

      const problem = card.dataset.problem;
      const data = problemData[problem];
      if (!data || !diagResult) return;

      diagIcon.textContent = data.icon;
      diagService.textContent = data.service;
      diagTime.innerHTML = `<i data-lucide="clock"></i> ${data.time}`;
      diagPrice.innerHTML = `<i data-lucide="indian-rupee"></i> ${data.price}`;
      diagWarranty.innerHTML = `<i data-lucide="shield"></i> ${data.warranty}`;
      diagDesc.textContent = data.desc;
      diagCTA.style.display = 'inline-flex';
      diagResult.classList.add('active');

      // Re-init icons in result
      if (typeof lucide !== 'undefined') lucide.createIcons();

      // Scroll into view on mobile
      diagResult.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });
  });


  // ── Smooth scroll for anchor links ─────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--navbar-h')) || 68;
        const top = target.getBoundingClientRect().top + window.scrollY - offset - 16;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

}); // end DOMContentLoaded


/* ============================================================
   BOOKING FORM (contact.html)
   ============================================================ */
function initBookingForm() {
  const form = document.getElementById('bookingForm');
  if (!form) return;

  let currentStep = 1;
  const totalSteps = 4;

  const formData = {
    device: '',
    problems: [],
    serviceType: '',
    name: '',
    phone: '',
    location: '',
    description: ''
  };

  function showStep(step) {
    document.querySelectorAll('.form-step').forEach((el, i) => {
      el.classList.toggle('active', i + 1 === step);
    });
    updateStepIndicator(step);
    currentStep = step;
    if (typeof lucide !== 'undefined') lucide.createIcons();
  }

  function updateStepIndicator(step) {
    document.querySelectorAll('.step-dot').forEach((dot, i) => {
      dot.classList.remove('active', 'done');
      if (i + 1 < step) dot.classList.add('done');
      else if (i + 1 === step) dot.classList.add('active');
    });
  }

  // Option button toggle
  document.querySelectorAll('.option-btn[data-single]').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.closest('.option-grid');
      group.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
      btn.classList.add('selected');
    });
  });

  document.querySelectorAll('.option-btn[data-multi]').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.classList.toggle('selected');
    });
  });

  // Next step buttons
  document.getElementById('nextStep1')?.addEventListener('click', () => {
    const selected = document.querySelector('.option-btn[data-single].selected[data-field="device"]');
    if (!selected) {
      showFormError('Please select your device type.');
      return;
    }
    formData.device = selected.dataset.value;
    showStep(2);
  });

  document.getElementById('nextStep2')?.addEventListener('click', () => {
    const selected = [...document.querySelectorAll('.option-btn[data-multi].selected')];
    if (selected.length === 0) {
      showFormError('Please select at least one problem.');
      return;
    }
    formData.problems = selected.map(b => b.dataset.value);
    showStep(3);
  });

  document.getElementById('nextStep3')?.addEventListener('click', () => {
    const serviceSelected = document.querySelector('.option-btn[data-single].selected[data-field="service"]');
    if (!serviceSelected) {
      showFormError('Please select a service type.');
      return;
    }
    formData.serviceType = serviceSelected.dataset.value;
    showStep(4);
  });

  // Back buttons
  document.querySelectorAll('.btn-back').forEach(btn => {
    btn.addEventListener('click', () => {
      if (currentStep > 1) showStep(currentStep - 1);
    });
  });

  // Submit
  document.getElementById('submitBooking')?.addEventListener('click', () => {
    const name  = document.getElementById('customerName')?.value.trim();
    const phone = document.getElementById('customerPhone')?.value.trim();

    if (!name) { showFormError('Please enter your name.'); return; }
    if (!phone || !/^[6-9]\d{9}$/.test(phone)) {
      showFormError('Please enter a valid 10-digit mobile number.');
      return;
    }

    formData.name  = name;
    formData.phone = phone;
    formData.location    = document.getElementById('customerLocation')?.value.trim() || '';
    formData.description = document.getElementById('issueDescription')?.value.trim() || '';

    // Generate booking ID
    const bookingId = 'LR-2026-' + Math.floor(10000 + Math.random() * 90000);
    document.getElementById('bookingId').textContent = bookingId;
    document.getElementById('confirmName').textContent = name;

    showStep(5); // confirmation step
  });

  showStep(1);
}

function showFormError(msg) {
  // Simple error display — remove previous
  document.querySelectorAll('.form-error').forEach(el => el.remove());
  const err = document.createElement('p');
  err.className = 'form-error';
  err.style.cssText = 'color:#DC2626;font-size:0.85rem;font-weight:600;margin-top:8px;';
  err.textContent = msg;
  const active = document.querySelector('.form-step.active');
  if (active) active.appendChild(err);
  setTimeout(() => err.remove(), 3000);
}

document.addEventListener('DOMContentLoaded', initBookingForm);


/* ============================================================
   REPAIR TRACKER (repair-status.html)
   ============================================================ */
function initRepairTracker() {
  const trackBtn = document.getElementById('trackBtn');
  if (!trackBtn) return;

  // Demo data for testing
  const demoRepairs = {
    'LR-2026-10293': {
      id: 'LR-2026-10293',
      device: 'Dell Inspiron 15 (2022)',
      service: 'Screen Replacement',
      technician: 'Rakesh Kumar',
      receivedDate: '12 Aug 2026',
      status: 3, // 1=received, 2=diagnosed, 3=approved/repairing, 4=testing, 5=ready
    },
    'LR-2026-10281': {
      id: 'LR-2026-10281',
      device: 'HP Pavilion 14',
      service: 'Motherboard Repair',
      technician: 'Sunil Singh',
      receivedDate: '10 Aug 2026',
      status: 5,
    },
    'LR-2026-10270': {
      id: 'LR-2026-10270',
      device: 'Lenovo IdeaPad 3',
      service: 'Battery Replacement',
      technician: 'Amit Jha',
      receivedDate: '9 Aug 2026',
      status: 2,
    }
  };

  const steps = [
    { label: 'Device Received', desc: 'We have received your device.' },
    { label: 'Diagnosis Complete', desc: 'Technician has identified the issue.' },
    { label: 'Repair In Progress', desc: 'Repair approved, work has started.' },
    { label: 'Quality Testing', desc: 'Final quality check before delivery.' },
    { label: 'Ready for Pickup', desc: 'Your device is ready. Please collect it.' },
  ];

  trackBtn.addEventListener('click', () => {
    const input = document.getElementById('repairIdInput');
    const id = input?.value.trim().toUpperCase();

    if (!id) {
      showTrackerError('Please enter your Repair ID.');
      return;
    }

    const repair = demoRepairs[id];
    const resultEl = document.getElementById('trackerResult');

    if (!repair) {
      showTrackerError(`No repair found for ID: ${id}. Please check and try again.`);
      if (resultEl) resultEl.classList.remove('visible');
      return;
    }

    // Populate repair info
    document.getElementById('trRepairId').textContent   = repair.id;
    document.getElementById('trDevice').textContent     = repair.device;
    document.getElementById('trService').textContent    = repair.service;
    document.getElementById('trTech').textContent       = repair.technician;
    document.getElementById('trDate').textContent       = repair.receivedDate;

    // Build timeline
    const timeline = document.getElementById('repairTimeline');
    if (timeline) {
      timeline.innerHTML = steps.map((step, i) => {
        const stepNum = i + 1;
        let stateClass = 'pending';
        let iconName = 'circle';
        if (stepNum < repair.status) { stateClass = 'done'; iconName = 'check'; }
        else if (stepNum === repair.status) { stateClass = 'active'; iconName = 'loader'; }

        return `
          <div class="timeline-step ${stateClass}">
            <div class="timeline-dot"><i data-lucide="${iconName}"></i></div>
            <div class="timeline-content">
              <strong>${step.label}</strong>
              <span>${stepNum <= repair.status ? step.desc : 'Pending'}</span>
            </div>
          </div>
        `;
      }).join('');
    }

    resultEl?.classList.add('visible');
    if (typeof lucide !== 'undefined') lucide.createIcons();

    resultEl?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });

  // Allow Enter key
  document.getElementById('repairIdInput')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') trackBtn.click();
  });
}

function showTrackerError(msg) {
  document.querySelectorAll('.tracker-error').forEach(el => el.remove());
  const err = document.createElement('p');
  err.className = 'tracker-error';
  err.style.cssText = 'color:#DC2626;font-size:0.875rem;font-weight:600;margin-top:8px;';
  err.textContent = msg;
  document.getElementById('trackBtn')?.insertAdjacentElement('afterend', err);
  setTimeout(() => err.remove(), 4000);
}

document.addEventListener('DOMContentLoaded', initRepairTracker);
