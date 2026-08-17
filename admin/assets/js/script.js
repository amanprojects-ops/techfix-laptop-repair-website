/* ============================================================
   TechFix — Admin Portal Core Script
   Features:
   - Sidebar Collapsible & Mobile Drawer Toggle
   - Submenu Accordion
   - Real-time Ticket Search Filter & Status Filter
   - Chart.js Visual Analytics Initialization
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {
    // ── Sidebar & Mobile Drawer Setup ────────────────────────
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    let sidebarOverlay = document.querySelector('.sidebar-overlay');
    if (!sidebarOverlay && sidebar) {
        sidebarOverlay = document.createElement('div');
        sidebarOverlay.className = 'sidebar-overlay';
        document.body.appendChild(sidebarOverlay);
    }

    function closeMobileSidebar() {
        if (sidebar) sidebar.classList.remove('active');
        if (sidebarOverlay) sidebarOverlay.classList.remove('active');
    }

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                sidebar.classList.toggle('active');
                if (sidebarOverlay) sidebarOverlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                if (mainContent) mainContent.classList.toggle('expanded');
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeMobileSidebar);
    }

    // Auto-close drawer on mobile when clicking navigation links
    document.querySelectorAll('.main-nav a:not(.menu-toggle)').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) closeMobileSidebar();
        });
    });

    // ── Submenu Toggle ───────────────────────────────────────
    document.querySelectorAll('.menu-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            const isOpen = parent.classList.contains('open');

            // Close all sibling submenus
            document.querySelectorAll('.has-submenu').forEach(item => {
                if (item !== parent) item.classList.remove('open');
            });

            if (!isOpen) {
                parent.classList.add('open');
            } else {
                parent.classList.remove('open');
            }
        });
    });

    // ── Live Table Search & Filter ───────────────────────────
    const headerSearch = document.getElementById('headerSearch');
    const statusFilter = document.getElementById('statusFilterSelect');
    const dataTableRows = document.querySelectorAll('.data-table tbody tr');

    function filterTable() {
        const query = headerSearch ? headerSearch.value.trim().toLowerCase() : '';
        const status = statusFilter ? statusFilter.value.toLowerCase() : 'all';

        dataTableRows.forEach(row => {
            const rowText = row.innerText.toLowerCase();
            const matchesQuery = !query || rowText.includes(query);
            
            let matchesStatus = true;
            if (status !== 'all') {
                const statusBadge = row.querySelector('.status-badge');
                if (statusBadge) {
                    matchesStatus = statusBadge.innerText.toLowerCase().includes(status);
                }
            }

            if (matchesQuery && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (headerSearch) {
        headerSearch.addEventListener('input', filterTable);
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', filterTable);
    }

    // ── Charts Initialization ────────────────────────────────
    initRepairCharts();
});

function initRepairCharts() {
    if (typeof Chart === 'undefined') return;

    // 1. Repair Trend Chart (Intake vs Delivered)
    const trendCanvas = document.getElementById('repairTrendChart');
    if (trendCanvas) {
        new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels: ['Week 1 (Aug 1-7)', 'Week 2 (Aug 8-14)', 'Week 3 (Aug 15-21)', 'Week 4 (Aug 22-28)'],
                datasets: [
                    {
                        label: 'Laptops Received',
                        data: [38, 45, 42, 35],
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 5,
                        pointBackgroundColor: '#2563EB'
                    },
                    {
                        label: 'Repairs Completed & Delivered',
                        data: [34, 40, 39, 29],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 5,
                        pointBackgroundColor: '#10B981'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Service Category Doughnut Chart
    const catCanvas = document.getElementById('serviceCategoryChart');
    if (catCanvas) {
        new Chart(catCanvas, {
            type: 'doughnut',
            data: {
                labels: [
                    'Screen Replacement (35%)',
                    'Motherboard Chipset (25%)',
                    'Battery & Charging (20%)',
                    'SSD & RAM Upgrade (12%)',
                    'Liquid Damage Repair (8%)'
                ],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
                    backgroundColor: [
                        '#2563EB', // Blue
                        '#8B5CF6', // Purple
                        '#10B981', // Green
                        '#38BDF8', // Cyan
                        '#F59E0B'  // Amber
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 14,
                            font: { size: 11.5 }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // 3. Repairs by Brand Bar Chart
    const brandCanvas = document.getElementById('brandRevenueChart');
    if (brandCanvas) {
        new Chart(brandCanvas, {
            type: 'bar',
            data: {
                labels: ['Dell', 'HP', 'Lenovo', 'Apple MacBook', 'Asus ROG/TUF', 'Acer', 'Others'],
                datasets: [{
                    label: 'Repairs Logged (This Month)',
                    data: [54, 46, 32, 22, 16, 12, 8],
                    backgroundColor: [
                        '#2563EB',
                        '#38BDF8',
                        '#10B981',
                        '#64748B',
                        '#F59E0B',
                        '#8B5CF6',
                        '#94A3B8'
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
}
