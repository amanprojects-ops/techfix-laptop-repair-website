<!-- ===================== HERO ===================== -->
<section class="hero" id="home">
  <div class="hero__bg-grid"></div>
  <div class="container hero__inner">
    <div class="hero__content">
      <div class="hero__badge">
        <i data-lucide="zap"></i> Same Day Repair Available
      </div>
      <h1 class="hero__title">
        Your Laptop.<br />
        <span class="text-accent">Our Expertise.</span>
      </h1>
      <p class="hero__subtitle">
        From broken screens to motherboard-level repairs — our technicians diagnose and fix your laptop with transparent pricing and warranty-backed service.
      </p>
      <div class="hero__trust-line">
        <span><i data-lucide="check-circle"></i> Genuine Parts</span>
        <span><i data-lucide="check-circle"></i> Transparent Pricing</span>
        <span><i data-lucide="check-circle"></i> 90-Day Warranty</span>
      </div>
      <div class="hero__cta">
        <a href="<?= url('/book-repair') ?>" class="btn btn--primary btn--lg">
          <i data-lucide="wrench"></i> Book a Repair
        </a>
        <a href="<?= site_whatsapp_link() ?>" class="btn btn--whatsapp btn--lg" target="_blank" rel="noopener">
          <i data-lucide="message-circle"></i> WhatsApp Us
        </a>
      </div>
    </div>

    <div class="hero__visual">
      <div class="hero__device-card">
        <div class="device-card__header">
          <span class="device-dot red"></span>
          <span class="device-dot yellow"></span>
          <span class="device-dot green"></span>
          <span class="device-card__title">Diagnosis Report</span>
        </div>
        <div class="device-card__body">
          <div class="diag-item done"><i data-lucide="check"></i> Hardware Scan Complete</div>
          <div class="diag-item done"><i data-lucide="check"></i> Battery Health: Poor → Replacing</div>
          <div class="diag-item active"><i data-lucide="loader"></i> Screen Calibration...</div>
          <div class="diag-item pending"><i data-lucide="clock"></i> Final Quality Check</div>
        </div>
        <div class="device-card__footer">
          <span class="status-badge">In Progress</span>
          <span>ETA: 2 hours</span>
        </div>
      </div>
      <div class="hero__floating-tag tag1"><i data-lucide="shield-check"></i> Warranty Backed</div>
      <div class="hero__floating-tag tag2"><i data-lucide="star"></i> 4.9 Rating</div>
    </div>
  </div>
</section>


<!-- ===================== TRUST BAR ===================== -->
<section class="trust-bar">
  <div class="container trust-bar__inner">
    <div class="trust-stat">
      <div class="trust-stat__icon"><i data-lucide="star"></i></div>
      <div>
        <div class="trust-stat__value">4.9 ★</div>
        <div class="trust-stat__label">Google Rating</div>
      </div>
    </div>
    <div class="trust-divider"></div>
    <div class="trust-stat">
      <div class="trust-stat__icon"><i data-lucide="laptop"></i></div>
      <div>
        <div class="trust-stat__value">10,000+</div>
        <div class="trust-stat__label">Devices Repaired</div>
      </div>
    </div>
    <div class="trust-divider"></div>
    <div class="trust-stat">
      <div class="trust-stat__icon"><i data-lucide="calendar"></i></div>
      <div>
        <div class="trust-stat__value">10+ Years</div>
        <div class="trust-stat__label">Experience</div>
      </div>
    </div>
    <div class="trust-divider"></div>
    <div class="trust-stat">
      <div class="trust-stat__icon"><i data-lucide="shield"></i></div>
      <div>
        <div class="trust-stat__value">90 Days</div>
        <div class="trust-stat__label">Repair Warranty</div>
      </div>
    </div>
    <div class="trust-divider"></div>
    <div class="trust-stat">
      <div class="trust-stat__icon"><i data-lucide="zap"></i></div>
      <div>
        <div class="trust-stat__value">Same Day</div>
        <div class="trust-stat__label">Selected Repairs</div>
      </div>
    </div>
  </div>
</section>


<!-- ===================== DIAGNOSE MY LAPTOP ===================== -->
<section class="diagnose section" id="diagnose">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Smart Diagnosis</div>
      <h2 class="section-title">What's Wrong With Your Laptop?</h2>
      <p class="section-subtitle">Not sure which service you need? Tell us the problem — we'll guide you.</p>
    </div>
    <div class="diagnose__grid">
      <div class="problem-card" data-problem="not-charging">
        <div class="problem-card__icon">🔋</div>
        <div class="problem-card__label">Not Charging</div>
      </div>
      <div class="problem-card" data-problem="screen-broken">
        <div class="problem-card__icon">🖥️</div>
        <div class="problem-card__label">Screen Broken</div>
      </div>
      <div class="problem-card" data-problem="keyboard">
        <div class="problem-card__icon">⌨️</div>
        <div class="problem-card__label">Keyboard Problem</div>
      </div>
      <div class="problem-card" data-problem="not-turning-on">
        <div class="problem-card__icon">⚡</div>
        <div class="problem-card__label">Not Turning On</div>
      </div>
      <div class="problem-card" data-problem="liquid-damage">
        <div class="problem-card__icon">💧</div>
        <div class="problem-card__label">Liquid Damage</div>
      </div>
      <div class="problem-card" data-problem="overheating">
        <div class="problem-card__icon">🔥</div>
        <div class="problem-card__label">Overheating</div>
      </div>
      <div class="problem-card" data-problem="slow">
        <div class="problem-card__icon">🐢</div>
        <div class="problem-card__label">Very Slow</div>
      </div>
      <div class="problem-card" data-problem="data-recovery">
        <div class="problem-card__icon">💾</div>
        <div class="problem-card__label">Data Lost</div>
      </div>
    </div>

    <!-- Diagnosis Result -->
    <div class="diagnose__result" id="diagResult">
      <div class="diag-result__inner">
        <div class="diag-result__icon" id="diagIcon">🔧</div>
        <div class="diag-result__content">
          <div class="diag-result__service" id="diagService">Select a problem above</div>
          <div class="diag-result__meta">
            <span id="diagTime"><i data-lucide="clock"></i> —</span>
            <span id="diagPrice"><i data-lucide="indian-rupee"></i> —</span>
            <span id="diagWarranty"><i data-lucide="shield"></i> —</span>
          </div>
          <p class="diag-result__desc" id="diagDesc">Click any problem card to get an instant recommendation.</p>
        </div>
        <a href="<?= url('/book-repair') ?>" class="btn btn--primary" id="diagCTA" style="display:none;">Book This Repair →</a>
      </div>
    </div>
  </div>
</section>


<!-- ===================== SERVICES ===================== -->
<section class="services section section--dark" id="services">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">What We Fix</div>
      <h2 class="section-title">Our Repair Services</h2>
      <p class="section-subtitle">Hardware-level diagnosis to complete repair — all under one roof.</p>
    </div>
    <div class="services__grid">
      <?php if (!empty($services)): ?>
        <?php foreach ($services as $svc): ?>
        <div class="service-card">
          <div class="service-card__icon"><i data-lucide="<?= htmlspecialchars($svc['icon'] ?: 'wrench', ENT_QUOTES) ?>"></i></div>
          <h3 class="service-card__name"><?= htmlspecialchars($svc['name'], ENT_QUOTES) ?></h3>
          <p class="service-card__desc"><?= htmlspecialchars($svc['short_description'] ?? 'Quality hardware & component level repair.', ENT_QUOTES) ?></p>
          <div class="service-card__meta">
            <span class="meta-item"><i data-lucide="indian-rupee"></i> From ₹<?= number_format((float)$svc['starting_price'], 0) ?></span>
            <span class="meta-item"><i data-lucide="clock"></i> <?= (int)$svc['estimated_days'] ?> Day<?= $svc['estimated_days'] > 1 ? 's' : '' ?></span>
            <span class="meta-item warranty"><i data-lucide="shield"></i> <?= (int)$svc['warranty_days'] ?> Days</span>
          </div>
          <a href="<?= url('/book-repair') ?>" class="service-card__cta">Book Now →</a>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="service-card">
          <div class="service-card__icon"><i data-lucide="monitor"></i></div>
          <h3 class="service-card__name">Screen Replacement</h3>
          <p class="service-card__desc">Cracked, flickering or dead display — original quality replacement panels.</p>
          <div class="service-card__meta">
            <span class="meta-item"><i data-lucide="indian-rupee"></i> From ₹2,499</span>
            <span class="meta-item"><i data-lucide="clock"></i> 2–4 Hours</span>
            <span class="meta-item warranty"><i data-lucide="shield"></i> 90 Days</span>
          </div>
          <a href="<?= url('/book-repair') ?>" class="service-card__cta">Book Now →</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>


<!-- ===================== REPAIR PROCESS ===================== -->
<section class="process section" id="process">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">How It Works</div>
      <h2 class="section-title">Simple. Transparent. Fast.</h2>
      <p class="section-subtitle">No black boxes — you know exactly what's happening at every step.</p>
    </div>
    <div class="process__steps">
      <div class="process-step">
        <div class="process-step__num">01</div>
        <div class="process-step__icon"><i data-lucide="calendar-check"></i></div>
        <h3>Book Your Device</h3>
        <p>Walk in or book online. Tell us what's wrong — we'll set up a slot for you.</p>
      </div>
      <div class="process-arrow"><i data-lucide="arrow-right"></i></div>
      <div class="process-step">
        <div class="process-step__num">02</div>
        <div class="process-step__icon"><i data-lucide="search"></i></div>
        <h3>Free Diagnosis</h3>
        <p>Our technician inspects the device and identifies the root cause accurately.</p>
      </div>
      <div class="process-arrow"><i data-lucide="arrow-right"></i></div>
      <div class="process-step">
        <div class="process-step__num">03</div>
        <div class="process-step__icon"><i data-lucide="file-text"></i></div>
        <h3>You Approve Quote</h3>
        <p>We share a transparent cost estimate. Repair only starts after your approval.</p>
      </div>
      <div class="process-arrow"><i data-lucide="arrow-right"></i></div>
      <div class="process-step">
        <div class="process-step__num">04</div>
        <div class="process-step__icon"><i data-lucide="wrench"></i></div>
        <h3>Repair Begins</h3>
        <p>Certified technicians carry out the repair using genuine or quality-matched parts.</p>
      </div>
      <div class="process-arrow"><i data-lucide="arrow-right"></i></div>
      <div class="process-step">
        <div class="process-step__num">05</div>
        <div class="process-step__icon"><i data-lucide="package-check"></i></div>
        <h3>Test & Deliver</h3>
        <p>Quality tested before handover. Warranty card issued for eligible repairs.</p>
      </div>
    </div>
  </div>
</section>


<!-- ===================== WHY CHOOSE US ===================== -->
<section class="why-us section section--dark" id="about">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Why TechFix</div>
      <h2 class="section-title">Proof, Not Promises</h2>
      <p class="section-subtitle">We let the results speak — not marketing copy.</p>
    </div>
    <div class="why-us__grid">
      <div class="why-card">
        <div class="why-card__num">01</div>
        <div class="why-card__icon"><i data-lucide="eye"></i></div>
        <h3>Transparent Diagnosis</h3>
        <p>No repair starts without your approval. You see the problem, quote, and timeline — before we touch anything.</p>
      </div>
      <div class="why-card">
        <div class="why-card__num">02</div>
        <div class="why-card__icon"><i data-lucide="shield-check"></i></div>
        <h3>Warranty-Backed Work</h3>
        <p>Every eligible repair comes with a written 90-day warranty. If it fails, we fix it again — no questions.</p>
      </div>
      <div class="why-card">
        <div class="why-card__num">03</div>
        <div class="why-card__icon"><i data-lucide="circuit-board"></i></div>
        <h3>Motherboard-Level Expertise</h3>
        <p>We don't just swap parts — we do chip-level diagnosis and component repair for complex hardware failures.</p>
      </div>
      <div class="why-card">
        <div class="why-card__num">04</div>
        <div class="why-card__icon"><i data-lucide="package"></i></div>
        <h3>Genuine Parts Only</h3>
        <p>Parts are disclosed before replacement. Original or high-quality equivalents — always clearly stated.</p>
      </div>
      <div class="why-card">
        <div class="why-card__num">05</div>
        <div class="why-card__icon"><i data-lucide="map-pin"></i></div>
        <h3>Local & Trusted</h3>
        <p>Serving Saharsa, Supaul, and Madhepura since 2014. Over 10,000 happy customers in Bihar.</p>
      </div>
      <div class="why-card">
        <div class="why-card__num">06</div>
        <div class="why-card__icon"><i data-lucide="activity"></i></div>
        <h3>Live Repair Tracking</h3>
        <p>Know your device's repair status in real-time. No need to call and ask — just track online.</p>
      </div>
    </div>
  </div>
</section>


<!-- ===================== BEFORE / AFTER ===================== -->
<section class="before-after section" id="gallery">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Our Work</div>
      <h2 class="section-title">Before & After</h2>
      <p class="section-subtitle">Real repairs. Real results.</p>
    </div>
    <div class="ba-grid">
      <div class="ba-card">
        <div class="ba-card__images">
          <div class="ba-side">
            <div class="ba-label before-label">BEFORE</div>
            <div class="ba-img-placeholder before-img">
              <i data-lucide="monitor-x"></i>
              <span>Cracked Screen</span>
            </div>
          </div>
          <div class="ba-divider"><i data-lucide="arrow-right"></i></div>
          <div class="ba-side">
            <div class="ba-label after-label">AFTER</div>
            <div class="ba-img-placeholder after-img">
              <i data-lucide="monitor-check"></i>
              <span>Crystal Clear Display</span>
            </div>
          </div>
        </div>
        <p class="ba-card__caption">Screen Replacement — Dell Inspiron 15</p>
      </div>

      <div class="ba-card">
        <div class="ba-card__images">
          <div class="ba-side">
            <div class="ba-label before-label">BEFORE</div>
            <div class="ba-img-placeholder before-img">
              <i data-lucide="power-off"></i>
              <span>Dead Laptop</span>
            </div>
          </div>
          <div class="ba-divider"><i data-lucide="arrow-right"></i></div>
          <div class="ba-side">
            <div class="ba-label after-label">AFTER</div>
            <div class="ba-img-placeholder after-img">
              <i data-lucide="power"></i>
              <span>Fully Working</span>
            </div>
          </div>
        </div>
        <p class="ba-card__caption">Motherboard Repair — HP Pavilion</p>
      </div>

      <div class="ba-card">
        <div class="ba-card__images">
          <div class="ba-side">
            <div class="ba-label before-label">BEFORE</div>
            <div class="ba-img-placeholder before-img">
              <i data-lucide="battery-low"></i>
              <span>30 Min Backup</span>
            </div>
          </div>
          <div class="ba-divider"><i data-lucide="arrow-right"></i></div>
          <div class="ba-side">
            <div class="ba-label after-label">AFTER</div>
            <div class="ba-img-placeholder after-img">
              <i data-lucide="battery-full"></i>
              <span>5+ Hours Backup</span>
            </div>
          </div>
        </div>
        <p class="ba-card__caption">Battery Replacement — Lenovo IdeaPad</p>
      </div>
    </div>
  </div>
</section>


<!-- ===================== REVIEWS ===================== -->
<section class="reviews section section--dark" id="reviews">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Customer Reviews</div>
      <h2 class="section-title">What Our Customers Say</h2>
      <div class="reviews-rating">
        <span class="stars">★★★★★</span>
        <span class="rating-num">4.9 / 5</span>
        <span class="rating-count">840+ Reviews on Google</span>
      </div>
    </div>
    <div class="reviews__grid">
      <div class="review-card">
        <div class="review-card__stars">★★★★★</div>
        <p class="review-card__text">"Excellent service. They diagnosed the motherboard issue correctly and repaired it within 2 days. Very transparent about pricing — no hidden charges."</p>
        <div class="review-card__author">
          <div class="author-avatar">R</div>
          <div>
            <div class="author-name">Rahul Kumar</div>
            <div class="author-tag"><i data-lucide="check-circle"></i> Verified Customer</div>
          </div>
        </div>
      </div>
      <div class="review-card">
        <div class="review-card__stars">★★★★★</div>
        <p class="review-card__text">"Got my screen replaced in just 3 hours. The quality is perfect, can't tell it was replaced. Very professional team and good pricing too."</p>
        <div class="review-card__author">
          <div class="author-avatar">P</div>
          <div>
            <div class="author-name">Priya Singh</div>
            <div class="author-tag"><i data-lucide="check-circle"></i> Verified Customer</div>
          </div>
        </div>
      </div>
      <div class="review-card">
        <div class="review-card__stars">★★★★★</div>
        <p class="review-card__text">"My laptop had liquid damage and I thought it was gone. TechFix recovered it completely. They also recovered all my data. Highly recommended!"</p>
        <div class="review-card__author">
          <div class="author-avatar">A</div>
          <div>
            <div class="author-name">Amit Jha</div>
            <div class="author-tag"><i data-lucide="check-circle"></i> Verified Customer</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===================== FAQ ===================== -->
<section class="faq section" id="faq">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">FAQ</div>
      <h2 class="section-title">Frequently Asked Questions</h2>
      <p class="section-subtitle">Got questions? We've got answers.</p>
    </div>
    <div class="faq__list">
      <div class="faq-item">
        <button class="faq-question">How long does a typical repair take? <i data-lucide="chevron-down"></i></button>
        <div class="faq-answer">
          <p>Most common repairs like screen replacement, battery replacement, SSD upgrade, and RAM expansion are completed within <strong>2 to 4 hours (same day)</strong>. Complex motherboard repairs typically take 1–3 business days.</p>
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-question">Do you offer a warranty on repairs? <i data-lucide="chevron-down"></i></button>
        <div class="faq-answer">
          <p>Yes! We provide a <strong>written 90-day warranty</strong> on parts and labor for most hardware repairs. Battery replacements carry a 6-month warranty.</p>
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-question">What if my laptop cannot be repaired? <i data-lucide="chevron-down"></i></button>
        <div class="faq-answer">
          <p>If our technicians find that the device cannot be safely or cost-effectively repaired, we will inform you right away. Diagnosis is free — there is <strong>no charge</strong> if we cannot fix it.</p>
        </div>
      </div>
      <div class="faq-item">
        <button class="faq-question">Can I track my repair status online? <i data-lucide="chevron-down"></i></button>
        <div class="faq-answer">
          <p>Yes! Use our <a href="<?= url('/track-repair') ?>" style="color:var(--accent)">Repair Tracking page</a> and enter your Repair ID to see real-time status updates.</p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===================== LOCATION ===================== -->
<section class="location section section--dark" id="location">
  <div class="container">
    <div class="section-header">
      <div class="section-badge">Find Us</div>
      <h2 class="section-title">Visit Our Center</h2>
      <p class="section-subtitle">Walk in anytime — no appointment needed for standard repairs.</p>
    </div>
    <div class="location__inner">
      <div class="location__info">
        <div class="location-detail">
          <div class="location-detail__icon"><i data-lucide="map-pin"></i></div>
          <div>
            <strong>Address</strong>
            <p><?= nl2br(htmlspecialchars(site_address(), ENT_QUOTES)) ?></p>
          </div>
        </div>
        <div class="location-detail">
          <div class="location-detail__icon"><i data-lucide="phone"></i></div>
          <div>
            <strong>Phone</strong>
            <p><a href="tel:<?= htmlspecialchars(site_phone(), ENT_QUOTES) ?>"><?= htmlspecialchars(site_phone(), ENT_QUOTES) ?></a></p>
          </div>
        </div>
        <div class="location-detail">
          <div class="location-detail__icon"><i data-lucide="clock"></i></div>
          <div>
            <strong>Working Hours</strong>
            <p><?= nl2br(htmlspecialchars((string)setting('working_hours', 'Monday – Saturday: 9:00 AM – 8:00 PM'), ENT_QUOTES)) ?></p>
          </div>
        </div>
        <div class="location-detail">
          <div class="location-detail__icon"><i data-lucide="map"></i></div>
          <div>
            <strong>Also Serving</strong>
            <p>Supaul • Madhepura • Khagaria • Purnia</p>
          </div>
        </div>
        <a href="<?= htmlspecialchars((string)setting('google_map_url', 'https://maps.google.com'), ENT_QUOTES) ?>" target="_blank" rel="noopener" class="btn btn--primary" style="margin-top:1rem;">
          <i data-lucide="navigation"></i> Get Directions
        </a>
      </div>
      <div class="location__map">
        <div class="map-placeholder">
          <i data-lucide="map-pin"></i>
          <p><?= htmlspecialchars(site_name(), ENT_QUOTES) ?> Repair Center</p>
          <span><?= htmlspecialchars(setting('city', 'Saharsa') . ', ' . setting('state', 'Bihar'), ENT_QUOTES) ?></span>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===================== FINAL CTA ===================== -->
<section class="final-cta">
  <div class="container final-cta__inner">
    <h2>Get Your Laptop Fixed Today</h2>
    <p>Stop struggling with a broken laptop. Walk in or book online — we'll take it from here.</p>
    <div class="final-cta__btns">
      <a href="<?= url('/book-repair') ?>" class="btn btn--primary btn--lg">
        <i data-lucide="wrench"></i> Book a Repair
      </a>
      <a href="tel:<?= htmlspecialchars(site_phone(), ENT_QUOTES) ?>" class="btn btn--outline btn--lg">
        <i data-lucide="phone"></i> Call Now
      </a>
      <a href="<?= site_whatsapp_link() ?>" class="btn btn--whatsapp btn--lg" target="_blank" rel="noopener">
        <i data-lucide="message-circle"></i> WhatsApp
      </a>
    </div>
  </div>
</section>
