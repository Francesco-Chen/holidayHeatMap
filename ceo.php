<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEO Mode - Holiday Heatmap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
        --bg: #08090d;
        --bg-card: rgba(255,255,255,0.035);
        --bg-card-hover: rgba(255,255,255,0.06);
        --border: rgba(255,255,255,0.07);
        --text: #d4d4d8;
        --text-dim: #71717a;
        --text-bright: #fafafa;
        --accent: #818cf8;
        --accent-bg: rgba(129,140,248,0.12);
        --green: #22c55e;
        --yellow: #eab308;
        --red: #ef4444;
        --font: 'Inter', -apple-system, sans-serif;
    }
    html { font-size: 14px; }
    body {
        font-family: var(--font);
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
    }

    /* ── Top bar ─────────────────────────────────────────────── */
    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem;
        border-bottom: 1px solid var(--border);
        background: rgba(8,9,13,0.9);
        backdrop-filter: blur(12px);
        position: sticky; top: 0; z-index: 50;
    }
    .topbar-left { display: flex; align-items: center; gap: 1rem; }
    .topbar h1 {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-bright);
    }
    .topbar h1 .badge {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: #fff;
        font-size: 0.65rem;
        padding: 0.2rem 0.55rem;
        border-radius: 99px;
        font-weight: 600;
        letter-spacing: 0.05em;
        vertical-align: middle;
        margin-left: 0.5rem;
    }
    .back-link {
        color: var(--text-dim);
        text-decoration: none;
        font-size: 0.85rem;
        transition: color 0.15s;
    }
    .back-link:hover { color: var(--text-bright); }

    /* ── Layout ──────────────────────────────────────────────── */
    .dashboard {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem 2rem 4rem;
    }

    /* ── Stat cards ──────────────────────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.2s;
    }
    .stat-card:hover {
        background: var(--bg-card-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
    .stat-label {
        font-size: 0.75rem;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 600;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-bright);
        margin-top: 0.25rem;
        line-height: 1.1;
    }
    .stat-sub {
        font-size: 0.78rem;
        color: var(--text-dim);
        margin-top: 0.3rem;
    }
    .stat-value.green { color: var(--green); }
    .stat-value.yellow { color: var(--yellow); }
    .stat-value.red { color: var(--red); }

    /* ── Card generic ────────────────────────────────────────── */
    .card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .card-title {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-dim);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ── Main grid ───────────────────────────────────────────── */
    .main-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.5rem;
        align-items: start;
    }
    @media (max-width: 1000px) {
        .main-grid { grid-template-columns: 1fr; }
    }

    /* ── Year heatmap ────────────────────────────────────────── */
    .year-nav {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .year-nav button {
        background: rgba(255,255,255,0.06);
        border: 1px solid var(--border);
        color: var(--text);
        width: 30px; height: 30px;
        border-radius: 8px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
        font-size: 0.9rem;
    }
    .year-nav button:hover { background: rgba(255,255,255,0.1); }
    .year-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-bright);
        min-width: 60px;
        text-align: center;
    }

    .months-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }
    @media (max-width: 700px) {
        .months-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .mini-month h3 {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-dim);
        margin-bottom: 0.35rem;
        cursor: pointer;
        transition: color 0.15s;
    }
    .mini-month h3:hover { color: var(--accent); }
    .mini-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 2px;
    }
    .mini-weekday {
        text-align: center;
        font-size: 0.55rem;
        color: var(--text-dim);
        padding: 1px 0;
    }
    .mini-day {
        aspect-ratio: 1;
        border-radius: 3px;
        cursor: pointer;
        transition: all 0.12s;
        position: relative;
    }
    .mini-day:hover {
        transform: scale(1.5);
        z-index: 5;
        box-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }
    .mini-day.empty { cursor: default; }
    .mini-day.emp-highlight {
        outline: 2px solid var(--accent);
        outline-offset: -1px;
        z-index: 3;
    }
    .mini-day.today-mark {
        outline: 2px solid #fff;
        outline-offset: -1px;
    }

    /* ── Heatmap legend ──────────────────────────────────────── */
    .heatmap-legend {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
        font-size: 0.72rem;
        color: var(--text-dim);
    }
    .legend-gradient {
        width: 120px;
        height: 8px;
        border-radius: 4px;
        background: linear-gradient(90deg, #22c55e, #eab308, #ef4444);
    }

    /* ── Day detail panel ────────────────────────────────────── */
    .day-detail { min-height: 200px; }
    .day-detail-empty {
        text-align: center;
        color: var(--text-dim);
        padding: 3rem 1rem;
        font-size: 0.9rem;
    }
    .detail-date {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-bright);
        margin-bottom: 0.5rem;
    }
    .detail-stat {
        font-size: 0.82rem;
        color: var(--text-dim);
        margin-bottom: 1rem;
    }
    .detail-people-list {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    .detail-person {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 0.7rem;
        border-radius: 8px;
        transition: background 0.15s;
    }
    .detail-person:hover { background: rgba(255,255,255,0.04); }
    .detail-person .avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 600; color: #fff;
        flex-shrink: 0;
    }
    .detail-person .name { font-size: 0.9rem; }
    .detail-person.on-vacation .name { color: var(--red); }
    .detail-person.available .name { color: var(--green); }
    .detail-person .vac-tag {
        margin-left: auto;
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 99px;
        font-weight: 500;
    }
    .detail-person.on-vacation .vac-tag {
        background: rgba(239,68,68,0.15);
        color: var(--red);
    }
    .detail-person.available .vac-tag {
        background: rgba(34,197,94,0.15);
        color: var(--green);
    }

    /* ── Employee filter ─────────────────────────────────────── */
    .emp-filter select {
        width: 100%;
        padding: 0.6rem 0.8rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text);
        font-family: var(--font);
        font-size: 0.9rem;
        cursor: pointer;
        transition: border-color 0.2s;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2371717a' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
    }
    .emp-filter select:focus {
        outline: none;
        border-color: var(--accent);
    }

    /* ── Employee vacation detail ─────────────────────────────── */
    .emp-vac-detail { margin-top: 1rem; }
    .emp-vac-month {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.85rem;
    }
    .emp-vac-month:last-child { border-bottom: none; }
    .emp-vac-month .month-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .emp-vac-month .month-name { flex: 1; }
    .emp-vac-month .month-count {
        font-weight: 600;
        color: var(--text-bright);
    }
    .emp-vac-dates {
        font-size: 0.73rem;
        color: var(--text-dim);
        padding: 0.15rem 0 0.4rem 1rem;
    }
    .emp-vac-total {
        display: flex;
        align-items: baseline;
        gap: 0.4rem;
        margin-bottom: 0.75rem;
    }
    .emp-vac-total .big { font-size: 1.8rem; font-weight: 800; color: var(--text-bright); }
    .emp-vac-total .label { font-size: 0.85rem; color: var(--text-dim); }

    /* ── Team ranking ────────────────────────────────────────── */
    .ranking-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 0;
        font-size: 0.85rem;
    }
    .ranking-pos {
        width: 22px; height: 22px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        background: rgba(255,255,255,0.06);
        color: var(--text-dim);
        flex-shrink: 0;
    }
    .ranking-pos.gold { background: rgba(234,179,8,0.2); color: #eab308; }
    .ranking-pos.silver { background: rgba(192,192,192,0.15); color: #c0c0c0; }
    .ranking-pos.bronze { background: rgba(205,127,50,0.15); color: #cd7f32; }
    .ranking-name { flex: 1; }
    .ranking-bar-bg {
        width: 80px; height: 4px;
        background: rgba(255,255,255,0.06);
        border-radius: 2px;
        overflow: hidden;
    }
    .ranking-bar-fill {
        height: 100%;
        border-radius: 2px;
        background: var(--accent);
        transition: width 0.3s;
    }
    .ranking-count {
        font-weight: 600;
        color: var(--text-bright);
        min-width: 30px;
        text-align: right;
    }

    /* ── Monthly coverage bars ───────────────────────────────── */
    .coverage-chart {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        height: 100px;
        padding-top: 0.5rem;
    }
    .coverage-bar-wrap {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        height: 100%;
    }
    .coverage-bar-outer {
        flex: 1;
        width: 100%;
        background: rgba(255,255,255,0.04);
        border-radius: 4px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        overflow: hidden;
        position: relative;
    }
    .coverage-bar {
        width: 100%;
        border-radius: 4px 4px 0 0;
        transition: height 0.4s;
        min-height: 2px;
    }
    .coverage-label {
        font-size: 0.6rem;
        color: var(--text-dim);
        text-align: center;
    }
    .coverage-value {
        font-size: 0.6rem;
        color: var(--text-dim);
        text-align: center;
    }

    /* ── Toast ───────────────────────────────────────────────── */
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
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.35s;
        opacity: 0;
        z-index: 999;
    }
    .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
    </style>
</head>
<body>

<!-- ── Top bar ───────────────────────────────────────────────── -->
<div class="topbar">
    <div class="topbar-left">
        <a href="index.php" class="back-link">← Back</a>
        <h1>👔 CEO Mode<span class="badge">EXECUTIVE</span></h1>
    </div>
</div>

<div class="dashboard">
    <!-- ── Stats row ─────────────────────────────────────────── -->
    <div class="stats-row" id="statsRow"></div>

    <!-- ── Main grid ─────────────────────────────────────────── -->
    <div class="main-grid">
        <!-- Left: Year heatmap + coverage -->
        <div>
            <div class="card">
                <div class="card-title">
                    <span>🗓️ Annual Heatmap</span>
                    <div style="flex:1"></div>
                    <div class="year-nav">
                        <button onclick="changeYear(-1)">◀</button>
                        <span class="year-title" id="yearTitle"></span>
                        <button onclick="changeYear(1)">▶</button>
                    </div>
                </div>
                <div class="months-grid" id="monthsGrid"></div>
                <div class="heatmap-legend">
                    <span>No one off</span>
                    <div class="legend-gradient"></div>
                    <span>Everyone off</span>
                    <span style="margin-left:1rem;display:inline-flex;align-items:center;gap:0.3rem;"><span style="display:inline-block;width:10px;height:10px;background:hsl(230,8%,18%);border-radius:2px;"></span> Weekend/Holiday</span>
                </div>
            </div>

            <!-- Monthly coverage -->
            <div class="card">
                <div class="card-title">📊 Monthly Vacation Days</div>
                <div class="coverage-chart" id="coverageChart"></div>
            </div>
        </div>

        <!-- Right sidebar -->
        <div>
            <!-- Day detail -->
            <div class="card day-detail">
                <div class="card-title">📋 Date Details</div>
                <div id="dayDetail">
                    <div class="day-detail-empty">Click a date in the heatmap<br>to view holiday details</div>
                </div>
            </div>

            <!-- Employee filter -->
            <div class="card">
                <div class="card-title">🔍 Employee Vacation Query</div>
                <div class="emp-filter">
                    <select id="empSelect" onchange="selectEmp(this.value)">
                        <option value="">-- Select Employee --</option>
                    </select>
                </div>
                <div id="empVacDetail" class="emp-vac-detail"></div>
            </div>

            <!-- Ranking -->
            <div class="card">
                <div class="card-title">🏆 Vacation Ranking</div>
                <div id="rankingList"></div>
            </div>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
// ═══════════════════════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════════════════════
const S = {
    employees: [],
    vacations: {},
    holidays: { rules: [], collective: [] },
    viewYear: new Date().getFullYear(),
    selectedDate: null,   // "YYYY-MM-DD"
    selectedEmp: null,    // employee name
};

const MONTH_NAMES = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const WEEKDAYS = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];  // indexed by getDay()
const WEEKDAYS_HEADER = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];  // Monday-first display
const AVATAR_COLORS = [
    '#6366f1','#8b5cf6','#a855f7','#d946ef','#ec4899',
    '#f43f5e','#ef4444','#f97316','#eab308','#84cc16',
    '#22c55e','#14b8a6','#06b6d4','#3b82f6',
];

// ═══════════════════════════════════════════════════════════════
// API
// ═══════════════════════════════════════════════════════════════
async function api(action, data = {}) {
    const r = await fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action, ...data }),
    });
    return r.json();
}

// ═══════════════════════════════════════════════════════════════
// COLOR
// ═══════════════════════════════════════════════════════════════
function heatColor(ratio) {
    const r = Math.max(0, Math.min(1, ratio));
    const hue = 120 * (1 - r);
    const sat = 70 + 15 * r;
    const lgt = 28 + 10 * Math.sin(r * Math.PI);
    return `hsl(${hue}, ${sat}%, ${lgt}%)`;
}
function avatarColor(i) { return AVATAR_COLORS[i % AVATAR_COLORS.length]; }

function pad2(n) { return String(n).padStart(2, '0'); }

// ═══════════════════════════════════════════════════════════════
// HOLIDAYS LOGIC
// ═══════════════════════════════════════════════════════════════
function computeEaster(year) {
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
    const dow = new Date(dateStr + 'T00:00:00').getDay();
    return dow === 0 || dow === 6;
}

// ═══════════════════════════════════════════════════════════════
// BUILD DAY COUNT MAP
// ═══════════════════════════════════════════════════════════════
function buildDayCounts() {
    const map = {};
    Object.values(S.vacations).forEach(dates => {
        if (!Array.isArray(dates)) return;
        dates.forEach(d => { map[d] = (map[d] || 0) + 1; });
    });
    return map;
}

// ═══════════════════════════════════════════════════════════════
// RENDER STATS
// ═══════════════════════════════════════════════════════════════
function renderStats() {
    const dayCounts = buildDayCounts();
    const totalEmp = S.employees.length || 1;

    let totalDays = 0;
    let peakDate = null, peakCount = 0;
    const yr = S.viewYear;
    Object.entries(dayCounts).forEach(([d, c]) => {
        if (d.startsWith(String(yr))) {
            totalDays += c;
            if (c > peakCount) { peakCount = c; peakDate = d; }
        }
    });

    const avgPerEmp = totalEmp > 0 ? (totalDays / totalEmp).toFixed(1) : 0;

    const today = new Date();
    const todayStr = `${today.getFullYear()}-${pad2(today.getMonth()+1)}-${pad2(today.getDate())}`;
    const todayOff = dayCounts[todayStr] || 0;
    const todayAvail = Math.max(0, S.employees.length - todayOff);

    const peakLabel = peakDate ? `${peakDate} (${peakCount}人)` : '—';

    document.getElementById('statsRow').innerHTML = `
        <div class="stat-card">
            <div class="stat-label">On Duty Today</div>
            <div class="stat-value green">${todayAvail}/${S.employees.length}</div>
            <div class="stat-sub">${todayOff > 0 ? todayOff + ' off' : 'Everyone on duty ✓'}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Vacation Days in ${yr}</div>
            <div class="stat-value">${totalDays}</div>
            <div class="stat-sub">Avg ${avgPerEmp} days/person</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Peak Day</div>
            <div class="stat-value ${peakCount >= totalEmp ? 'red' : peakCount > totalEmp*0.5 ? 'yellow' : ''}">${peakCount}</div>
            <div class="stat-sub">${peakLabel}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Team Size</div>
            <div class="stat-value">${S.employees.length}</div>
            <div class="stat-sub">Registered Employees</div>
        </div>`;
}

// ═══════════════════════════════════════════════════════════════
// RENDER YEAR HEATMAP
// ═══════════════════════════════════════════════════════════════
function renderYear() {
    const yr = S.viewYear;
    document.getElementById('yearTitle').textContent = yr;
    const dayCounts = buildDayCounts();
    const totalEmp = Math.max(1, S.employees.length);
    const today = new Date();
    const todayStr = `${today.getFullYear()}-${pad2(today.getMonth()+1)}-${pad2(today.getDate())}`;
    const holidays = buildHolidaysMap(yr, S.holidays);

    const empSet = S.selectedEmp ? new Set(S.vacations[S.selectedEmp] || []) : null;

    let html = '';
    for (let mo = 0; mo < 12; mo++) {
        const firstDay = (new Date(yr, mo, 1).getDay() + 6) % 7;
        const daysInMonth = new Date(yr, mo + 1, 0).getDate();

        html += `<div class="mini-month"><h3>${MONTH_NAMES[mo]}</h3>`;
        html += '<div class="mini-grid">';

        WEEKDAYS_HEADER.forEach(w => { html += `<div class="mini-weekday">${w}</div>`; });

        for (let i = 0; i < firstDay; i++) {
            html += '<div class="mini-day empty"></div>';
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${yr}-${pad2(mo+1)}-${pad2(d)}`;
            const count = dayCounts[dateStr] || 0;
            const ratio = count / totalEmp;
            const weekend = isWeekend(dateStr);
            const holidayName = holidays[dateStr] || null;
            const nonWorking = weekend || !!holidayName;
            const bg = nonWorking ? 'hsl(230,8%,18%)' : heatColor(ratio);
            const isToday = dateStr === todayStr;
            const isHighlight = empSet && empSet.has(dateStr);
            const isSelected = dateStr === S.selectedDate;

            let cls = 'mini-day';
            if (isToday) cls += ' today-mark';
            if (isHighlight) cls += ' emp-highlight';

            let border = isSelected ? 'outline:2px solid var(--accent);outline-offset:-1px;' : '';
            const titleExtra = holidayName ? ` Holiday: ${holidayName}` : (weekend ? ' (Weekend)' : '');

            html += `<div class="${cls}" style="background:${bg};${border}"
                          title="${dateStr}: ${count}/${totalEmp} off${titleExtra}"
                          onclick="clickDate('${dateStr}')"></div>`;
        }
        html += '</div></div>';
    }
    document.getElementById('monthsGrid').innerHTML = html;
}

function clickDate(dateStr) {
    S.selectedDate = dateStr;
    renderYear();
    renderDayDetail();
}

function renderDayDetail() {
    const el = document.getElementById('dayDetail');
    if (!S.selectedDate) {
        el.innerHTML = '<div class="day-detail-empty">点击热力图中的日期<br>查看当天休假详情</div>';
        return;
    }

    const d = S.selectedDate;
    const parts = d.split('-');
    const yr = parseInt(parts[0]);
    const dateLabel = `${MONTH_NAMES[parseInt(parts[1])-1]} ${parseInt(parts[2])}, ${yr}`;
    const weekday = WEEKDAYS[new Date(d).getDay()];
    const weekend = isWeekend(d);
    const holidays = buildHolidaysMap(yr, S.holidays);
    const holidayName = holidays[d] || null;

    const onVac = [];
    const available = [];
    S.employees.forEach((name, i) => {
        const dates = S.vacations[name] || [];
        if (dates.includes(d)) {
            onVac.push({ name, idx: i });
        } else {
            available.push({ name, idx: i });
        }
    });

    let html = `<div class="detail-date">${weekday}, ${dateLabel}</div>`;

    if (holidayName) {
        html += `<div style="display:inline-block;padding:0.2rem 0.6rem;background:rgba(99,102,241,0.12);color:#818cf8;border-radius:6px;font-size:0.78rem;margin-bottom:0.5rem;">🏖️ ${holidayName}</div>`;
    } else if (weekend) {
        html += `<div style="display:inline-block;padding:0.2rem 0.6rem;background:rgba(255,255,255,0.06);color:var(--text-dim);border-radius:6px;font-size:0.78rem;margin-bottom:0.5rem;">Weekend</div>`;
    }

    html += `<div class="detail-stat">${onVac.length} off, ${available.length} on duty</div>`;
    html += '<div class="detail-people-list">';

    onVac.forEach(p => {
        html += `<div class="detail-person on-vacation">
            <span class="avatar" style="background:${avatarColor(p.idx)}">${p.name[0]}</span>
            <span class="name">${p.name}</span>
            <span class="vac-tag">Off</span>
        </div>`;
    });
    available.forEach(p => {
        html += `<div class="detail-person available">
            <span class="avatar" style="background:${avatarColor(p.idx)}">${p.name[0]}</span>
            <span class="name">${p.name}</span>
            <span class="vac-tag">On Duty</span>
        </div>`;
    });

    html += '</div>';
    el.innerHTML = html;
}

function renderFilterSelect() {
    const sel = document.getElementById('empSelect');
    let html = '<option value="">-- 选择员工 --</option>';
    S.employees.forEach(name => {
        html += `<option value="${name}">${name}</option>`;
    });
    sel.innerHTML = html;
}

function selectEmp(name) {
    S.selectedEmp = name || null;
    renderYear();
    renderEmpVacDetail();
}

function renderEmpVacDetail() {
    const el = document.getElementById('empVacDetail');
    if (!S.selectedEmp) { el.innerHTML = ''; return; }

    const dates = S.vacations[S.selectedEmp] || [];
    if (dates.length === 0) {
        el.innerHTML = '<div style="text-align:center;color:var(--text-dim);padding:1rem 0;font-size:0.85rem;">No vacation records</div>';
        return;
    }

    const groups = {};
    dates.forEach(d => {
        const key = d.substring(0, 7);
        if (!groups[key]) groups[key] = [];
        groups[key].push(d);
    });
    const sortedKeys = Object.keys(groups).sort();

    const monthColors = [
        '#6366f1','#8b5cf6','#a855f7','#d946ef',
        '#ec4899','#f43f5e','#ef4444','#f97316',
        '#eab308','#84cc16','#22c55e','#14b8a6'
    ];

    let html = `<div class="emp-vac-total">
        <span class="big">${dates.length}</span>
        <span class="label">天假期</span>
    </div>`;

    sortedKeys.forEach(key => {
        const [yr, moStr] = key.split('-');
        const moIdx = parseInt(moStr, 10) - 1;
        const count = groups[key].length;
        const datesStr = groups[key].map(d => parseInt(d.split('-')[2])).join(', ');
        const color = monthColors[moIdx];

        html += `<div class="emp-vac-month">
            <span class="month-dot" style="background:${color}"></span>
            <span class="month-name">${MONTH_NAMES[moIdx]} ${yr}</span>
            <span class="month-count">${count} days</span>
        </div>
        <div class="emp-vac-dates">${datesStr}</div>`;
    });

    el.innerHTML = html;
}

// ═══════════════════════════════════════════════════════════════
// RANKING
// ═══════════════════════════════════════════════════════════════
function renderRanking() {
    const yr = S.viewYear;
    // Count vacation days per employee for this year
    const counts = S.employees.map(name => {
        const dates = (S.vacations[name] || []).filter(d => d.startsWith(String(yr)));
        return { name, count: dates.length };
    }).sort((a, b) => b.count - a.count);

    const maxCount = counts.length > 0 ? Math.max(1, counts[0].count) : 1;
    const el = document.getElementById('rankingList');

    if (counts.length === 0) {
        el.innerHTML = '<div style="text-align:center;color:var(--text-dim);padding:1rem;">No data</div>';
        return;
    }

    el.innerHTML = counts.map((c, i) => {
        let posClass = '';
        if (i === 0) posClass = 'gold';
        else if (i === 1) posClass = 'silver';
        else if (i === 2) posClass = 'bronze';

        const pct = Math.round((c.count / maxCount) * 100);

        return `<div class="ranking-row">
            <span class="ranking-pos ${posClass}">${i + 1}</span>
            <span class="ranking-name">${c.name}</span>
            <span class="ranking-bar-bg"><span class="ranking-bar-fill" style="width:${pct}%"></span></span>
            <span class="ranking-count">${c.count} days</span>
        </div>`;
    }).join('');
}

// ═══════════════════════════════════════════════════════════════
// MONTHLY COVERAGE CHART
// ═══════════════════════════════════════════════════════════════
function renderCoverage() {
    const yr = S.viewYear;
    const dayCounts = buildDayCounts();
    const totalEmp = Math.max(1, S.employees.length);

    // Sum vacation person-days per month
    const monthlySums = Array(12).fill(0);
    Object.entries(dayCounts).forEach(([d, c]) => {
        if (d.startsWith(String(yr))) {
            const mo = parseInt(d.split('-')[1], 10) - 1;
            monthlySums[mo] += c;
        }
    });
    const maxSum = Math.max(1, ...monthlySums);

    const el = document.getElementById('coverageChart');
    el.innerHTML = monthlySums.map((sum, i) => {
        const pct = Math.round((sum / maxSum) * 100);
        const ratio = totalEmp > 0 ? sum / (totalEmp * new Date(yr, i+1, 0).getDate()) : 0;
        const color = heatColor(Math.min(1, ratio * 2));
        return `<div class="coverage-bar-wrap">
            <div class="coverage-value">${sum || ''}</div>
            <div class="coverage-bar-outer">
                <div class="coverage-bar" style="height:${pct}%;background:${color}"></div>
            </div>
            <div class="coverage-label">${MONTH_NAMES[i]}</div>
        </div>`;
    }).join('');
}

// ═══════════════════════════════════════════════════════════════
// YEAR NAV
// ═══════════════════════════════════════════════════════════════
function changeYear(delta) {
    S.viewYear += delta;
    renderAll();
}

// ═══════════════════════════════════════════════════════════════
// RENDER ALL
// ═══════════════════════════════════════════════════════════════
function renderAll() {
    renderStats();
    renderYear();
    renderDayDetail();
    renderEmpVacDetail();
    renderRanking();
    renderCoverage();
}

// ═══════════════════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════════════════
async function init() {
    const data = await api('get_all');
    if (data.success) {
        S.employees = data.employees || [];
        S.vacations = data.vacations || {};
        S.holidays = data.holidays || { rules: [], collective: [] };
        
        S.selectedDate = `${S.viewYear}-${pad2(new Date().getMonth()+1)}-${pad2(new Date().getDate())}`;
        
        renderFilterSelect();
        renderAll();
    }
}

init();
</script>
</body>
</html>
