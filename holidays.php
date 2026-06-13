<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holiday Settings - Holiday Heatmap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
        --bg: #08090d;
        --bg-card: rgba(255,255,255,0.035);
        --border: rgba(255,255,255,0.07);
        --text: #d4d4d8;
        --text-dim: #71717a;
        --text-bright: #fafafa;
        --accent: #818cf8;
        --red: #ef4444;
        --green: #22c55e;
        --font: 'Inter', -apple-system, sans-serif;
    }
    body {
        font-family: var(--font);
        background: var(--bg);
        color: var(--text);
        min-height: 100vh;
        line-height: 1.5;
        padding-bottom: 4rem;
    }

    /* ── Top bar ─────────────────────────────────────────────── */
    .topbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 2rem; border-bottom: 1px solid var(--border);
        background: rgba(8,9,13,0.9); backdrop-filter: blur(12px);
        position: sticky; top: 0; z-index: 50;
    }
    .topbar-left { display: flex; align-items: center; gap: 1rem; }
    .topbar h1 { font-size: 1.15rem; font-weight: 700; color: var(--text-bright); }
    .back-link {
        color: var(--text-dim); text-decoration: none;
        font-size: 0.85rem; transition: color 0.15s;
    }
    .back-link:hover { color: var(--text-bright); }

    .container { max-width: 900px; margin: 2rem auto; padding: 0 1.5rem; }

    /* ── Cards ───────────────────────────────────────────────── */
    .card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .card-title {
        font-size: 1.1rem; font-weight: 600; color: var(--text-bright);
        margin-bottom: 1rem; padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border);
        display: flex; align-items: center; gap: 0.5rem;
    }

    /* ── Lists ───────────────────────────────────────────────── */
    .list-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.75rem 1rem;
        background: rgba(255,255,255,0.02);
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }
    .list-item-main { flex: 1; }
    .list-item-title { font-weight: 600; color: var(--text-bright); font-size: 0.95rem; }
    .list-item-desc { font-size: 0.8rem; color: var(--text-dim); margin-top: 0.2rem; }
    .btn-delete {
        background: transparent; border: 1px solid rgba(239,68,68,0.3);
        color: var(--red); cursor: pointer; padding: 0.4rem 0.8rem;
        border-radius: 6px; font-size: 0.8rem; transition: all 0.2s;
    }
    .btn-delete:hover { background: rgba(239,68,68,0.15); border-color: var(--red); }

    /* ── Forms ───────────────────────────────────────────────── */
    .add-form {
        display: flex; gap: 0.75rem; align-items: flex-end;
        margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed var(--border);
        flex-wrap: wrap;
    }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; flex: 1; min-width: 150px; }
    .form-group label { font-size: 0.75rem; color: var(--text-dim); font-weight: 500; }
    .form-group input, .form-group select {
        padding: 0.6rem 0.8rem; background: rgba(0,0,0,0.3);
        border: 1px solid var(--border); border-radius: 8px;
        color: var(--text); font-family: var(--font); font-size: 0.9rem;
    }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: var(--accent); }
    .btn-primary {
        background: var(--accent); color: #fff; border: none;
        padding: 0.65rem 1.2rem; border-radius: 8px; font-weight: 600;
        cursor: pointer; transition: all 0.2s; font-size: 0.9rem; white-space: nowrap;
    }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

    /* ── Toast ───────────────────────────────────────────────── */
    .toast {
        position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%) translateY(100px);
        background: #18181b; border: 1px solid var(--border); color: var(--text-bright);
        padding: 0.75rem 1.5rem; border-radius: 10px; font-size: 0.9rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.35s;
        opacity: 0; z-index: 999;
    }
    .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <a href="index.php" class="back-link">← Back to Home</a>
        <h1>⚙️ Holiday Settings</h1>
    </div>
</div>

<div class="container">
    <!-- RULES SECTION -->
    <div class="card">
        <div class="card-title">🇮🇹 Public & City Holidays</div>
        <p style="font-size:0.85rem;color:var(--text-dim);margin-bottom:1rem;">
            Configure fixed annual holidays (e.g. New Year) and Easter-related holidays. Applied automatically.
        </p>
        <div id="rulesList"></div>

        <div class="add-form">
            <div class="form-group">
                <label>Type</label>
                <select id="ruleType" onchange="toggleRuleInputs()">
                    <option value="fixed">Fixed Date</option>
                    <option value="easter_offset">Easter Offset</option>
                </select>
            </div>
            <div class="form-group" id="groupMonth">
                <label>Month (1-12)</label>
                <input type="number" id="ruleMonth" min="1" max="12" value="1">
            </div>
            <div class="form-group" id="groupDay">
                <label>Day (1-31)</label>
                <input type="number" id="ruleDay" min="1" max="31" value="1">
            </div>
            <div class="form-group" id="groupOffset" style="display:none;">
                <label>Offset Days (0=Same day, 1=Next day)</label>
                <input type="number" id="ruleOffset" value="0">
            </div>
            <div class="form-group">
                <label>Holiday Name (e.g. Christmas)</label>
                <input type="text" id="ruleName" placeholder="Holiday Name">
            </div>
            <button class="btn-primary" onclick="addRule()">+ Add Rule</button>
        </div>
    </div>

    <!-- COLLECTIVE SECTION -->
    <div class="card">
        <div class="card-title">👥 Collective Vacations</div>
        <p style="font-size:0.85rem;color:var(--text-dim);margin-bottom:1rem;">
            Add specific dates for collective vacations (e.g. team building). Grayed out on heatmap.
        </p>
        <div id="collList"></div>

        <div class="add-form">
            <div class="form-group">
                <label>Date</label>
                <input type="date" id="collDate">
            </div>
            <div class="form-group">
                <label>Description (e.g. Summer Break)</label>
                <input type="text" id="collName" placeholder="Event Name">
            </div>
            <button class="btn-primary" onclick="addCollective()">+ Add Date</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
let state = {
    rules: [],
    collective: []
};

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2200);
}

async function api(action, data = {}) {
    const r = await fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action, ...data }),
    });
    return r.json();
}

async function loadData() {
    const res = await api('get_holidays');
    if (res.success && res.holidays) {
        state.rules = res.holidays.rules || [];
        state.collective = res.holidays.collective || [];
        // Legacy conversion if reading from the old pre-populated format
        if (res.holidays.national) {
            convertLegacy(res.holidays);
        }
    }
    renderAll();
}

async function saveData() {
    const res = await api('save_holidays', { holidays: state });
    if (res.success) showToast('✅ Saved successfully');
    else showToast('❌ Save failed');
}

function convertLegacy(old) {
    state.rules = [];
    state.collective = old.collective || [];
    let idCounter = 1;
    const addRule = (r, prefix) => {
        if (r.date) {
            const [m, d] = r.date.split('-');
            state.rules.push({ id: 'r'+(idCounter++), type: 'fixed', month: parseInt(m), day: parseInt(d), name: (prefix ? prefix+' ' : '') + r.name });
        } else if (r.type === 'easter') {
            state.rules.push({ id: 'r'+(idCounter++), type: 'easter_offset', offset: r.offset, name: (prefix ? prefix+' ' : '') + r.name });
        }
    };
    (old.national || []).forEach(r => addRule(r, ''));
    (old.city || []).forEach(r => addRule(r, r.city));
    saveData();
}

// ── UI Logic ──────────────────────────────────────────────────────
function toggleRuleInputs() {
    const t = document.getElementById('ruleType').value;
    document.getElementById('groupMonth').style.display = t === 'fixed' ? 'flex' : 'none';
    document.getElementById('groupDay').style.display = t === 'fixed' ? 'flex' : 'none';
    document.getElementById('groupOffset').style.display = t === 'easter_offset' ? 'flex' : 'none';
}

function renderAll() {
    // Render rules
    const rl = document.getElementById('rulesList');
    if (state.rules.length === 0) {
        rl.innerHTML = '<div style="color:var(--text-dim);font-size:0.9rem;padding:1rem 0;">No rules found</div>';
    } else {
        rl.innerHTML = state.rules.map(r => {
            let desc = r.type === 'fixed' ? `Every year, ${r.month}/${r.day}` : `Easter offset: ${r.offset} days`;
            return `<div class="list-item">
                <div class="list-item-main">
                    <div class="list-item-title">${r.name}</div>
                    <div class="list-item-desc">${desc}</div>
                </div>
                <button class="btn-delete" onclick="deleteRule('${r.id}')">Delete</button>
            </div>`;
        }).join('');
    }

    // Render collective
    const cl = document.getElementById('collList');
    // Sort collective by date
    state.collective.sort((a,b) => a.date.localeCompare(b.date));
    
    if (state.collective.length === 0) {
        cl.innerHTML = '<div style="color:var(--text-dim);font-size:0.9rem;padding:1rem 0;">No collective vacations</div>';
    } else {
        cl.innerHTML = state.collective.map(c => {
            return `<div class="list-item">
                <div class="list-item-main">
                    <div class="list-item-title">${c.name}</div>
                    <div class="list-item-desc">${c.date}</div>
                </div>
                <button class="btn-delete" onclick="deleteCollective('${c.id}')">Delete</button>
            </div>`;
        }).join('');
    }
}

function addRule() {
    const t = document.getElementById('ruleType').value;
    const name = document.getElementById('ruleName').value.trim();
    if (!name) return showToast('⚠️ Please enter a name');

    const rule = { id: 'r' + Date.now().toString(36), type: t, name };
    if (t === 'fixed') {
        rule.month = parseInt(document.getElementById('ruleMonth').value);
        rule.day = parseInt(document.getElementById('ruleDay').value);
        if (!rule.month || !rule.day) return showToast('⚠️ Invalid date');
    } else {
        rule.offset = parseInt(document.getElementById('ruleOffset').value) || 0;
    }

    state.rules.push(rule);
    document.getElementById('ruleName').value = '';
    renderAll();
    saveData();
}

function deleteRule(id) {
    state.rules = state.rules.filter(r => r.id !== id);
    renderAll();
    saveData();
}

function addCollective() {
    const d = document.getElementById('collDate').value;
    const n = document.getElementById('collName').value.trim();
    if (!d || !n) return showToast('⚠️ Please enter date and description');

    state.collective.push({ id: 'c' + Date.now().toString(36), date: d, name: n });
    document.getElementById('collDate').value = '';
    document.getElementById('collName').value = '';
    renderAll();
    saveData();
}

function deleteCollective(id) {
    state.collective = state.collective.filter(c => c.id !== id);
    renderAll();
    saveData();
}

loadData();
</script>
</body>
</html>
