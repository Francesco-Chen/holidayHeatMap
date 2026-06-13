<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday Heatmap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
    /* ═══════════════════════════════════════════════════════════
       RESET & BASE
       ═══════════════════════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg: #0f1117;
        --bg-card: rgba(255,255,255,0.04);
        --bg-card-hover: rgba(255,255,255,0.07);
        --border: rgba(255,255,255,0.08);
        --text: #e4e4e7;
        --text-dim: #71717a;
        --text-bright: #fafafa;
        --accent: #6366f1;
        --accent-glow: rgba(99,102,241,0.25);
        --green: #22c55e;
        --yellow: #eab308;
        --red: #ef4444;
        --radius: 12px;
        --font: 'Inter', -apple-system, sans-serif;
    }

    html { font-size: 15px; }
    body {
        font-family: var(--font);
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
    }

    /* ═══════════════════════════════════════════════════════════
       LAYOUT
       ═══════════════════════════════════════════════════════════ */
    .app {
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem 1.5rem 4rem;
    }

    .header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-bright);
        letter-spacing: -0.02em;
    }
    .header h1 span {
        background: linear-gradient(135deg, var(--green), var(--yellow), var(--red));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .header p {
        color: var(--text-dim);
        margin-top: 0.4rem;
        font-size: 0.93rem;
    }

    /* ── Panels ─────────────────────────────────────────────── */
    .panels {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 1.5rem;
        align-items: start;
    }
    @media (max-width: 800px) {
        .panels { grid-template-columns: 1fr; }
    }

    .card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.5rem;
        backdrop-filter: blur(12px);
        transition: background 0.2s;
    }
    .card:hover { background: var(--bg-card-hover); }
    .card h2 {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-dim);
        margin-bottom: 1rem;
    }

    /* ── Left sidebar ──────────────────────────────────────── */
    .sidebar { display: flex; flex-direction: column; gap: 1.5rem; }

    /* Admin section */
    #adminSection textarea {
        width: 100%;
        min-height: 120px;
        background: rgba(0,0,0,0.3);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: var(--font);
        font-size: 0.9rem;
        padding: 0.75rem;
        resize: vertical;
        transition: border-color 0.2s;
    }
    #adminSection textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-glow);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.6rem 1.2rem;
        border: none;
        border-radius: 8px;
        font-family: var(--font);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary {
        background: var(--accent);
        color: #fff;
        width: 100%;
        margin-top: 0.75rem;
    }
    .btn-primary:hover {
        background: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 4px 16px var(--accent-glow);
    }
    .btn-primary:active { transform: translateY(0); }

    /* Employee picker */
    .employee-list {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        max-height: 300px;
        overflow-y: auto;
    }
    .employee-list::-webkit-scrollbar { width: 4px; }
    .employee-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 2px; }

    .emp-btn {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 0.75rem;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 8px;
        color: var(--text);
        font-family: var(--font);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.15s;
        text-align: left;
    }
    .emp-btn:hover { background: rgba(255,255,255,0.05); }
    .emp-btn.active {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
        font-weight: 500;
    }
    .emp-avatar {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 600; color: #fff;
        flex-shrink: 0;
    }
    .emp-btn.active .emp-avatar {
        background: rgba(255,255,255,0.25);
    }
    .emp-count {
        margin-left: auto;
        font-size: 0.75rem;
        color: var(--text-dim);
        background: rgba(255,255,255,0.06);
        padding: 0.15rem 0.5rem;
        border-radius: 99px;
    }
    .emp-btn.active .emp-count { color: rgba(255,255,255,0.7); background: rgba(255,255,255,0.15); }

    .no-employee {
        text-align: center;
        color: var(--text-dim);
        font-size: 0.85rem;
        padding: 1.5rem 0;
    }

    /* ═══════════════════════════════════════════════════════════
       CALENDAR
       ═══════════════════════════════════════════════════════════ */
    .cal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .cal-nav {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .cal-nav button {
        background: rgba(255,255,255,0.06);
        border: 1px solid var(--border);
        color: var(--text);
        width: 32px; height: 32px;
        border-radius: 8px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
        font-size: 1rem;
    }
    .cal-nav button:hover { background: rgba(255,255,255,0.1); }
    .cal-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-bright);
        min-width: 160px;
        text-align: center;
    }

    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
    }
    .cal-weekday {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-dim);
        padding: 0.5rem 0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .cal-day {
        aspect-ratio: 1.3;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s;
        position: relative;
        border: 2px solid transparent;
        user-select: none;
    }
    .cal-day:hover:not(.empty):not(.other-month) {
        transform: scale(1.08);
        z-index: 2;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .cal-day.empty { cursor: default; }
    .cal-day.other-month {
        opacity: 0.25;
        cursor: default;
    }
    .cal-day.today {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 1px var(--accent);
    }
    .cal-day.selected {
        border-color: #fff !important;
        box-shadow: 0 0 0 2px rgba(255,255,255,0.5);
    }
    .cal-day .day-num { position: relative; z-index: 1; }
    .cal-day .day-count {
        font-size: 0.6rem;
        opacity: 0.8;
        margin-top: 1px;
    }
    .cal-day.non-working {
        opacity: 0.7;
    }
    .cal-day.non-working .day-num {
        color: rgba(255,255,255,0.5);
    }
    .cal-day .holiday-label {
        font-size: 0.48rem;
        opacity: 0.7;
        margin-top: 0px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        line-height: 1.1;
    }

    /* ── Legend ──────────────────────────────────────────────── */
    .legend {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
        font-size: 0.75rem;
        color: var(--text-dim);
    }
    .legend-bar {
        width: 150px;
        height: 10px;
        border-radius: 5px;
        background: linear-gradient(90deg, #22c55e, #eab308, #ef4444);
    }

    /* ── Toast ──────────────────────────────────────────────── */
    .toast {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #18181b;
        border: 1px solid var(--border);
        color: var(--text-bright);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-size: 0.9rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.35s;
        opacity: 0;
        z-index: 999;
    }
    .toast.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    /* ── Status dot ──────────────────────────────────────────── */
    .status-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        padding: 0.6rem 0.8rem;
        background: rgba(34,197,94,0.08);
        border: 1px solid rgba(34,197,94,0.15);
        border-radius: 8px;
        font-size: 0.8rem;
        color: var(--green);
    }
    .status-bar.warning {
        background: rgba(234,179,8,0.08);
        border-color: rgba(234,179,8,0.15);
        color: var(--yellow);
    }

    /* ── Vacation Summary ─────────────────────────────────────── */
    .vac-summary { display: none; }
    .vac-summary.visible { display: block; }
    .vac-summary-total {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    .vac-summary-total .total-num {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-bright);
    }
    .vac-summary-total .total-label {
        font-size: 0.85rem;
        color: var(--text-dim);
    }
    .vac-month-list {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    .vac-month-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 0.7rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s;
        background: transparent;
        border: 1px solid transparent;
        color: var(--text);
        font-family: var(--font);
        font-size: 0.88rem;
        width: 100%;
        text-align: left;
    }
    .vac-month-row:hover {
        background: rgba(255,255,255,0.05);
        border-color: var(--border);
    }
    .vac-month-row.current {
        background: rgba(99,102,241,0.12);
        border-color: rgba(99,102,241,0.25);
    }
    .vac-month-icon {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .vac-month-name {
        flex: 1;
    }
    .vac-month-days {
        font-weight: 600;
        color: var(--text-bright);
        font-size: 0.82rem;
    }
    .vac-month-bar-bg {
        width: 50px;
        height: 4px;
        background: rgba(255,255,255,0.08);
        border-radius: 2px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .vac-month-bar-fill {
        height: 100%;
        border-radius: 2px;
        transition: width 0.3s;
    }
    .vac-dates-detail {
        font-size: 0.75rem;
        color: var(--text-dim);
        padding: 0.25rem 0 0 1.2rem;
        line-height: 1.6;
    }
    .vac-no-data {
        text-align: center;
        color: var(--text-dim);
        font-size: 0.85rem;
        padding: 1rem 0;
    }

    /* ── Loading ──────────────────────────────────────────────── */
    .spinner {
        width: 18px; height: 18px;
        border: 2px solid var(--border);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin: 1rem auto;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div class="app">
    <!-- Header -->
    <div class="header">
        <h1>🗓️ Team <span>Holiday Heatmap</span></h1>
        <p>At a glance, grasp team holiday dynamics</p>
        <a href="ceo.php" style="display:inline-block;margin-top:0.75rem;padding:0.45rem 1.2rem;background:linear-gradient(135deg,#6366f1,#a855f7);color:#fff;border-radius:99px;font-size:0.82rem;font-weight:500;text-decoration:none;transition:all 0.2s;letter-spacing:0.02em;">👔 CEO Mode</a>
        <a href="holidays.php" style="display:inline-block;margin-top:0.75rem;margin-left:0.5rem;padding:0.45rem 1.2rem;background:rgba(255,255,255,0.1);color:#fff;border-radius:99px;font-size:0.82rem;font-weight:500;text-decoration:none;transition:all 0.2s;letter-spacing:0.02em;border:1px solid rgba(255,255,255,0.15);">⚙️ Settings</a>
    </div>

    <div class="panels">
        <!-- ── Left Sidebar ─────────────────────────────────── -->
        <div class="sidebar">
            <div class="card" id="adminSection">
                <h2>👤 Employee Mgmt</h2>
                <textarea id="employeeInput" placeholder="Enter one employee name per line, e.g.:&#10;John&#10;Jane&#10;Doe"></textarea>
                <button class="btn btn-primary" id="saveEmployeesBtn">💾 Save Employees</button>
            </div>

            <!-- Employee picker -->
            <div class="card" id="pickerSection">
                <h2>✏️ Select Name for Vacation</h2>
                <div class="employee-list" id="employeeList">
                    <div class="spinner"></div>
                </div>
                <div class="status-bar" id="statusBar" style="display:none;">
                    <span id="statusText"></span>
                </div>
            </div>

            <!-- Vacation summary by month -->
            <div class="card vac-summary" id="vacSummary">
                <h2>📊 Vacation Summary</h2>
                <div id="vacSummaryContent"></div>
            </div>
        </div>

        <!-- ── Calendar ─────────────────────────────────────── -->
        <div class="card">
            <div class="cal-header">
                <h2 style="margin-bottom:0;">📅 Holiday Calendar</h2>
                <div class="cal-nav">
                    <button id="prevMonth">◀</button>
                    <span class="cal-title" id="calTitle"></span>
                    <button id="nextMonth">▶</button>
                </div>
            </div>
            <div class="cal-grid" id="calGrid"></div>
            <div class="legend">
                <span>No one off</span>
                <div class="legend-bar"></div>
                <span>Everyone off</span>
                <span style="margin-left:1rem;display:inline-flex;align-items:center;gap:0.3rem;"><span style="display:inline-block;width:12px;height:12px;background:hsl(230,8%,22%);border-radius:3px;"></span> Weekend/Holiday</span>
            </div>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
// ═══════════════════════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════════════════════
const state = {
    employees: [],          // ["张三", "李四", ...]
    vacations: {},          // { "张三": ["2026-06-10", ...], ... }
    holidays: { rules: [], collective: [] }, // Dynamic holiday config
    selectedEmployee: null, // currently selected employee name
    currentYear: new Date().getFullYear(),
    currentMonth: new Date().getMonth(), // 0-indexed
};

// ═══════════════════════════════════════════════════════════════
// API
// ═══════════════════════════════════════════════════════════════
async function apiCall(action, data = {}) {
    const res = await fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action, ...data }),
    });
    return res.json();
}

// ═══════════════════════════════════════════════════════════════
// COLOR INTERPOLATION   green(120°) → yellow(60°) → red(0°)
// ═══════════════════════════════════════════════════════════════
function heatColor(ratio) {
    // ratio: 0 = nobody off (green), 1 = everyone off (red)
    const r = Math.max(0, Math.min(1, ratio));
    const hue = 120 * (1 - r);          // 120→0
    const sat = 70 + 15 * r;            // slightly more saturated toward red
    const lgt = 32 + 8 * Math.sin(r * Math.PI); // subtle brightness curve
    return `hsl(${hue}, ${sat}%, ${lgt}%)`;
}
function heatTextColor(ratio) {
    return ratio > 0.6 ? 'rgba(255,255,255,0.95)' : 'rgba(255,255,255,0.85)';
}

// ═══════════════════════════════════════════════════════════════
// ITALIAN HOLIDAYS + WEEKENDS
// ═══════════════════════════════════════════════════════════════
function computeEaster(year) {
    // Anonymous Gregorian algorithm
    const a = year % 19;
    const b = Math.floor(year / 100);
    const c = year % 100;
    const d = Math.floor(b / 4);
    const e = b % 4;
    const f = Math.floor((b + 8) / 25);
    const g = Math.floor((b - f + 1) / 3);
    const h = (19 * a + b - d - g + 15) % 30;
    const i = Math.floor(c / 4);
    const k = c % 4;
    const l = (32 + 2 * e + 2 * i - h - k) % 7;
    const m = Math.floor((a + 11 * h + 22 * l) / 451);
    const month = Math.floor((h + l - 7 * m + 114) / 31);
    const day = ((h + l - 7 * m + 114) % 31) + 1;
    return new Date(year, month - 1, day);
}

function buildHolidaysMap(year, config) {
    const pad = (n) => String(n).padStart(2, '0');
    const fmt = (dt) => `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}`;
    const h = {};

    // 1. Process rules
    if (config.rules) {
        let easter = null;
        config.rules.forEach(r => {
            if (r.type === 'fixed') {
                h[`${year}-${pad(r.month)}-${pad(r.day)}`] = r.name;
            } else if (r.type === 'easter_offset') {
                if (!easter) easter = computeEaster(year);
                const d = new Date(easter);
                d.setDate(d.getDate() + (r.offset || 0));
                h[fmt(d)] = r.name;
            }
        });
    }

    // 2. Process collective vacations
    if (config.collective) {
        config.collective.forEach(c => {
            if (c.date.startsWith(String(year))) {
                h[c.date] = c.name + " (Collective)";
            }
        });
    }

    return h;
}

function isWeekend(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    const dow = d.getDay();
    return dow === 0 || dow === 6;
}

// ═══════════════════════════════════════════════════════════════
// TOAST
// ═══════════════════════════════════════════════════════════════
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2200);
}

// ═══════════════════════════════════════════════════════════════
// RENDER EMPLOYEE LIST
// ═══════════════════════════════════════════════════════════════
function renderEmployeeList() {
    const el = document.getElementById('employeeList');
    if (state.employees.length === 0) {
        el.innerHTML = '<div class="no-employee">Please add employees above first</div>';
        return;
    }
    el.innerHTML = state.employees.map(name => {
        const isActive = state.selectedEmployee === name;
        const vacDays = (state.vacations[name] || []).length;
        const initial = name.charAt(0);
        return `
            <button class="emp-btn${isActive ? ' active' : ''}"
                    data-name="${name}"
                    onclick="selectEmployee('${name}')">
                <span class="emp-avatar">${initial}</span>
                <span>${name}</span>
                <span class="emp-count">${vacDays} days</span>
            </button>`;
    }).join('');
}

// ═══════════════════════════════════════════════════════════════
// SELECT EMPLOYEE
// ═══════════════════════════════════════════════════════════════
function selectEmployee(name) {
    state.selectedEmployee = name;
    renderEmployeeList();
    renderCalendar();
    updateStatus();
    renderVacSummary();
}

function updateStatus() {
    const bar = document.getElementById('statusBar');
    const txt = document.getElementById('statusText');
    if (state.selectedEmployee) {
        bar.style.display = 'flex';
        bar.className = 'status-bar';
        const days = (state.vacations[state.selectedEmployee] || []).length;
        txt.textContent = `Current: ${state.selectedEmployee}, ${days} days selected. Click dates to toggle.`;
    } else {
        bar.style.display = 'none';
    }
}

// ═══════════════════════════════════════════════════════════════
// RENDER CALENDAR
// ═══════════════════════════════════════════════════════════════
function renderCalendar() {
    const { currentYear: yr, currentMonth: mo } = state;
    const totalEmp = Math.max(1, state.employees.length);

    // Update title
    const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    document.getElementById('calTitle').textContent = `${monthNames[mo]} ${yr}`;

    // Build day count map for current month
    const dayCounts = {};
    Object.values(state.vacations).forEach(dates => {
        if (!Array.isArray(dates)) return;
        dates.forEach(d => {
            dayCounts[d] = (dayCounts[d] || 0) + 1;
        });
    });

    // Get first day of month and total days (Monday=0, Sunday=6)
    const firstDay = (new Date(yr, mo, 1).getDay() + 6) % 7;
    const daysInMonth = new Date(yr, mo + 1, 0).getDate();
    const today = new Date();
    const todayStr = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;

    // Selected employee's vacation set (for highlighting)
    const myVacSet = new Set(state.vacations[state.selectedEmployee] || []);

    const grid = document.getElementById('calGrid');
    let html = '';

    // Weekday headers (Monday first)
    const weekdays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    weekdays.forEach(w => {
        html += `<div class="cal-weekday">${w}</div>`;
    });

    // Empty cells before first day
    for (let i = 0; i < firstDay; i++) {
        html += `<div class="cal-day empty"></div>`;
    }

    // Dynamic holidays for this year
    const holidays = buildHolidaysMap(yr, state.holidays);

    // Day cells
    for (let d = 1; d <= daysInMonth; d++) {
        const dateStr = `${yr}-${String(mo+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const count = dayCounts[dateStr] || 0;
        const ratio = count / totalEmp;
        const isToday = dateStr === todayStr;
        const isSelected = myVacSet.has(dateStr);
        const weekend = isWeekend(dateStr);
        const holidayName = holidays[dateStr] || null;
        const nonWorking = weekend || !!holidayName;

        // Gray for non-working days, heatmap for working days
        const bg = nonWorking ? 'hsl(230,8%,22%)' : heatColor(ratio);
        const fg = nonWorking ? 'rgba(255,255,255,0.45)' : heatTextColor(ratio);

        let classes = 'cal-day';
        if (isToday) classes += ' today';
        if (isSelected) classes += ' selected';
        if (nonWorking) classes += ' non-working';

        const countLabel = count > 0 ? `${count} off` : '';
        const titleExtra = holidayName ? ` 🇮🇹 ${holidayName}` : (weekend ? ' (Weekend)' : '');
        const holidayTag = holidayName
            ? `<span class="holiday-label">🇮🇹${holidayName}</span>`
            : (weekend ? `<span class="holiday-label" style="opacity:0.4">Weekend</span>` : `<span class="day-count">${countLabel}</span>`);

        html += `<div class="${classes}"
                      style="background:${bg}; color:${fg};"
                      data-date="${dateStr}"
                      onclick="toggleDate('${dateStr}')"
                      title="${dateStr} - ${count}/${totalEmp} off${titleExtra}">
                    <span class="day-num">${d}</span>
                    ${holidayTag}
                 </div>`;
    }

    grid.innerHTML = html;
}

// ═══════════════════════════════════════════════════════════════
// TOGGLE DATE (click to add/remove vacation day)
// ═══════════════════════════════════════════════════════════════
async function toggleDate(dateStr) {
    if (!state.selectedEmployee) {
        showToast('⚠️ Please select your name on the left first');
        return;
    }
    const emp = state.selectedEmployee;
    let dates = state.vacations[emp] || [];

    if (dates.includes(dateStr)) {
        // Remove
        dates = dates.filter(d => d !== dateStr);
    } else {
        // Check if non-working
        const weekend = isWeekend(dateStr);
        const yr = parseInt(dateStr.split('-')[0]);
        const hmap = buildHolidaysMap(yr, state.holidays);
        if (weekend || hmap[dateStr]) {
            showToast('⚠️ Cannot add personal vacation on weekends or holidays');
            return;
        }
        // Add
        dates.push(dateStr);
    }
    dates.sort();
    state.vacations[emp] = dates;

    // Optimistic UI update
    renderCalendar();
    renderEmployeeList();
    updateStatus();
    renderVacSummary();

    // Save to server
    const res = await apiCall('save_vacation', { employee: emp, dates });
    if (res.success) {
        showToast(`✅ ${emp}'s vacation saved`);
    } else {
        showToast(`❌ Save failed: ${res.error || 'Unknown error'}`);
        // Reload data to correct state
        await loadAll();
    }
}

// ═══════════════════════════════════════════════════════════════
// SAVE EMPLOYEES
// ═══════════════════════════════════════════════════════════════
async function saveEmployees() {
    const raw = document.getElementById('employeeInput').value;
    const names = raw.split('\n').map(s => s.trim()).filter(Boolean);
    if (names.length === 0) {
        showToast('⚠️ Please enter at least one employee name');
        return;
    }
    const res = await apiCall('save_employees', { employees: names });
    if (res.success) {
        state.employees = res.employees;
        // Reset selection if the selected employee was removed
        if (state.selectedEmployee && !state.employees.includes(state.selectedEmployee)) {
            state.selectedEmployee = null;
        }
        showToast(`✅ Saved ${res.employees.length} employees`);
        // Reload vacations (may have been cleaned)
        const allData = await apiCall('get_all');
        if (allData.success) {
            state.vacations = allData.vacations || {};
        }
        renderEmployeeList();
        renderCalendar();
        updateStatus();
        renderVacSummary();
    } else {
        showToast(`❌ Save failed: ${res.error}`);
    }
}

// ═══════════════════════════════════════════════════════════════
// MONTH NAVIGATION
// ═══════════════════════════════════════════════════════════════
function prevMonth() {
    state.currentMonth--;
    if (state.currentMonth < 0) { state.currentMonth = 11; state.currentYear--; }
    renderCalendar();
}
function nextMonth() {
    state.currentMonth++;
    if (state.currentMonth > 11) { state.currentMonth = 0; state.currentYear++; }
    renderCalendar();
}

// ═══════════════════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════════════════
async function loadAll() {
    const data = await apiCall('get_all');
    if (data.success) {
        state.employees = data.employees || [];
        state.vacations = data.vacations || {};
        state.holidays = data.holidays || { rules: [], collective: [] };
        // Populate textarea
        document.getElementById('employeeInput').value = state.employees.join('\n');
        renderEmployeeList();
        renderCalendar();
        updateStatus();
        renderVacSummary();
    }
}

// ═══════════════════════════════════════════════════════════════
// RENDER VACATION SUMMARY (grouped by month)
// ═══════════════════════════════════════════════════════════════
function renderVacSummary() {
    const panel = document.getElementById('vacSummary');
    const content = document.getElementById('vacSummaryContent');
    if (!state.selectedEmployee) {
        panel.classList.remove('visible');
        return;
    }
    panel.classList.add('visible');

    const dates = state.vacations[state.selectedEmployee] || [];
    if (dates.length === 0) {
        content.innerHTML = '<div class="vac-no-data">No vacation records, click calendar to add</div>';
        return;
    }

    // Group by year-month
    const groups = {};
    dates.forEach(d => {
        const key = d.substring(0, 7); // "2026-06"
        if (!groups[key]) groups[key] = [];
        groups[key].push(d);
    });

    // Sort keys chronologically
    const sortedKeys = Object.keys(groups).sort();

    // Find max days in any month for bar scaling
    const maxDays = Math.max(...sortedKeys.map(k => groups[k].length));

    const monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const monthColors = [
        '#6366f1','#8b5cf6','#a855f7','#d946ef',
        '#ec4899','#f43f5e','#ef4444','#f97316',
        '#eab308','#84cc16','#22c55e','#14b8a6'
    ];

    let html = `<div class="vac-summary-total">
        <span class="total-num">${dates.length}</span>
        <span class="total-label">days off</span>
    </div>`;

    html += '<div class="vac-month-list">';
    sortedKeys.forEach(key => {
        const [yr, moStr] = key.split('-');
        const moIdx = parseInt(moStr, 10) - 1;
        const count = groups[key].length;
        const barPct = Math.round((count / maxDays) * 100);
        const color = monthColors[moIdx];
        const isCurrent = state.currentYear === parseInt(yr) && state.currentMonth === moIdx;

        const datesStr = groups[key].map(d => {
            const day = parseInt(d.split('-')[2], 10);
            return day;
        }).join(', ');

        html += `
            <button class="vac-month-row${isCurrent ? ' current' : ''}" onclick="goToMonth(${yr}, ${moIdx})">
                <span class="vac-month-icon" style="background:${color}"></span>
                <span class="vac-month-name">${monthLabels[moIdx]} ${yr}</span>
                <span class="vac-month-days">${count} days</span>
                <span class="vac-month-bar-bg">
                    <span class="vac-month-bar-fill" style="width:${barPct}%;background:${color}"></span>
                </span>
            </button>
            <div class="vac-dates-detail">${datesStr}</div>`;
    });
    html += '</div>';

    content.innerHTML = html;
}

function goToMonth(year, month) {
    state.currentYear = year;
    state.currentMonth = month;
    renderCalendar();
    renderVacSummary();
}

document.getElementById('saveEmployeesBtn').addEventListener('click', saveEmployees);
document.getElementById('prevMonth').addEventListener('click', prevMonth);
document.getElementById('nextMonth').addEventListener('click', nextMonth);

loadAll();
</script>

</body>
</html>
