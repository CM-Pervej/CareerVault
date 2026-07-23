@extends('layouts.app')

@section('title', 'LifeVault — Dashboard')

@section('content')

{{-- Tailwind CDN (dev only) + DaisyUI --}}
<link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com"></script>

<div id="lv-app" class="min-h-screen bg-base-200 p-6 font-sans">

    {{-- ── Header ── --}}
    {{-- <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center">
                <i class="fa-solid fa-vault text-primary-content text-lg"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold leading-none">LifeVault</h1>
                <p class="text-xs text-base-content/50 mt-0.5">Your job search, all in one place</p>
            </div>
        </div>
        <button onclick="openModal('add-application-modal')"
            class="btn btn-primary btn-sm gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Add application
        </button>
    </div> --}}

    {{-- ── Stat cards ── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-100 rounded-2xl shadow-sm border border-base-300 p-4">
            <div class="stat-title text-xs font-semibold uppercase tracking-widest">Applied</div>
            <div class="stat-value text-primary text-3xl font-bold" id="stat-applied">0</div>
        </div>
        <div class="stat bg-base-100 rounded-2xl shadow-sm border border-base-300 p-4">
            <div class="stat-title text-xs font-semibold uppercase tracking-widest">Interviews</div>
            <div class="stat-value text-warning text-3xl font-bold" id="stat-interview">0</div>
        </div>
        <div class="stat bg-base-100 rounded-2xl shadow-sm border border-base-300 p-4">
            <div class="stat-title text-xs font-semibold uppercase tracking-widest">Offers</div>
            <div class="stat-value text-success text-3xl font-bold" id="stat-offer">0</div>
        </div>
        <div class="stat bg-base-100 rounded-2xl shadow-sm border border-base-300 p-4">
            <div class="stat-title text-xs font-semibold uppercase tracking-widest">Companies</div>
            <div class="stat-value text-3xl font-bold" id="stat-companies">0</div>
        </div>
    </div>

    {{-- ── Main grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Recent Applications --}}
        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body p-5">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-base-content/50 flex items-center gap-2">
                        <i class="fa-solid fa-briefcase"></i> Recent applications
                    </h2>
                    <div class="flex gap-1" id="filter-btns">
                        <button onclick="filterApps('all')" data-filter="all"
                            class="btn btn-xs btn-primary filter-btn">All</button>
                        <button onclick="filterApps('interview')" data-filter="interview"
                            class="btn btn-xs btn-ghost filter-btn">Interview</button>
                        <button onclick="filterApps('applied')" data-filter="applied"
                            class="btn btn-xs btn-ghost filter-btn">Applied</button>
                        <button onclick="filterApps('rejected')" data-filter="rejected"
                            class="btn btn-xs btn-ghost filter-btn">Rejected</button>
                    </div>
                </div>

                <div id="app-list" class="divide-y divide-base-200 mt-2"></div>

                <div class="mt-4">
                    <button onclick="openModal('add-application-modal')"
                        class="btn btn-outline btn-primary btn-sm w-full gap-2">
                        <i class="fa-solid fa-plus text-xs"></i> Add application
                    </button>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="flex flex-col gap-5">

            {{-- Job Platforms --}}
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body p-5">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-base-content/50 flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-globe"></i> Job platforms
                    </h2>
                    <div id="platform-list" class="divide-y divide-base-200 mt-2"></div>
                    <div class="mt-4">
                        <button onclick="openModal('add-platform-modal')"
                            class="btn btn-outline btn-primary btn-sm w-full gap-2">
                            <i class="fa-solid fa-plus text-xs"></i> Add platform
                        </button>
                    </div>
                </div>
            </div>

            {{-- Company Career Sites --}}
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body p-5">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-base-content/50 flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-building"></i> Company career sites
                    </h2>
                    <div id="company-list" class="divide-y divide-base-200 mt-2"></div>
                    <div class="mt-4">
                        <button onclick="openModal('add-company-modal')"
                            class="btn btn-outline btn-primary btn-sm w-full gap-2">
                            <i class="fa-solid fa-plus text-xs"></i> Add company
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ════════════════════════════════ MODALS ════════════════════════════════ --}}

{{-- Add Application Modal --}}
<dialog id="add-application-modal" class="modal">
    <div class="modal-box w-11/12 max-w-lg">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
        </form>
        <h3 class="font-bold text-base mb-4 flex items-center gap-2">
            <i class="fa-solid fa-briefcase text-primary"></i> New application
        </h3>
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="form-control">
                <label class="label py-0.5"><span class="label-text text-xs font-semibold">Job title</span></label>
                <input id="f-title" type="text" placeholder="Senior Product Designer"
                    class="input input-bordered input-sm w-full" />
            </div>
            <div class="form-control">
                <label class="label py-0.5"><span class="label-text text-xs font-semibold">Company</span></label>
                <input id="f-company" type="text" placeholder="Stripe"
                    class="input input-bordered input-sm w-full" />
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="form-control">
                <label class="label py-0.5"><span class="label-text text-xs font-semibold">Platform</span></label>
                <select id="f-platform" class="select select-bordered select-sm w-full">
                    <option value="">Select platform</option>
                    <option>LinkedIn</option>
                    <option>Wellfound</option>
                    <option>Glassdoor</option>
                    <option>Indeed</option>
                    <option>BDJobs</option>
                    <option>Company website</option>
                    <option>Referral</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="form-control">
                <label class="label py-0.5"><span class="label-text text-xs font-semibold">Date applied</span></label>
                <input id="f-date" type="date" class="input input-bordered input-sm w-full" />
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="form-control">
                <label class="label py-0.5"><span class="label-text text-xs font-semibold">Location</span></label>
                <input id="f-location" type="text" placeholder="Dhaka / Remote"
                    class="input input-bordered input-sm w-full" />
            </div>
            <div class="form-control">
                <label class="label py-0.5"><span class="label-text text-xs font-semibold">Work type</span></label>
                <select id="f-worktype" class="select select-bordered select-sm w-full">
                    <option value="">Select type</option>
                    <option>Remote</option>
                    <option>Onsite</option>
                    <option>Hybrid</option>
                </select>
            </div>
        </div>
        <div class="form-control mb-3">
            <label class="label py-0.5"><span class="label-text text-xs font-semibold">Job URL</span></label>
            <input id="f-url" type="url" placeholder="https://stripe.com/jobs/..."
                class="input input-bordered input-sm w-full" />
        </div>
        <div class="form-control mb-4">
            <label class="label py-0.5"><span class="label-text text-xs font-semibold">Status</span></label>
            <div class="flex gap-2 flex-wrap" id="status-picker">
                <button type="button" onclick="pickStatus('applied')" data-s="applied"
                    class="btn btn-sm status-pick btn-success btn-outline">Applied</button>
                <button type="button" onclick="pickStatus('interview')" data-s="interview"
                    class="btn btn-sm status-pick btn-warning btn-outline">Interview</button>
                <button type="button" onclick="pickStatus('offer')" data-s="offer"
                    class="btn btn-sm status-pick btn-accent btn-outline">Offer</button>
                <button type="button" onclick="pickStatus('rejected')" data-s="rejected"
                    class="btn btn-sm status-pick btn-error btn-outline">Rejected</button>
            </div>
        </div>
        <div class="form-control mb-4">
            <label class="label py-0.5"><span class="label-text text-xs font-semibold">Notes</span></label>
            <textarea id="f-notes" class="textarea textarea-bordered textarea-sm w-full h-16"
                placeholder="Referral, follow-up dates, interview tips..."></textarea>
        </div>
        <div class="modal-action mt-0">
            <form method="dialog"><button class="btn btn-sm btn-ghost">Cancel</button></form>
            <button onclick="saveApplication()" class="btn btn-sm btn-primary gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i> Save application
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Add Platform Modal --}}
<dialog id="add-platform-modal" class="modal">
    <div class="modal-box max-w-sm">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
        </form>
        <h3 class="font-bold text-base mb-4 flex items-center gap-2">
            <i class="fa-solid fa-globe text-primary"></i> Add platform
        </h3>
        <div class="form-control mb-3">
            <label class="label py-0.5"><span class="label-text text-xs font-semibold">Platform name</span></label>
            <input id="p-name" type="text" placeholder="e.g. Remotive"
                class="input input-bordered input-sm w-full" />
        </div>
        <div class="form-control mb-4">
            <label class="label py-0.5"><span class="label-text text-xs font-semibold">Website URL</span></label>
            <input id="p-url" type="url" placeholder="https://remotive.com"
                class="input input-bordered input-sm w-full" />
        </div>
        <div class="modal-action mt-0">
            <form method="dialog"><button class="btn btn-sm btn-ghost">Cancel</button></form>
            <button onclick="savePlatform()" class="btn btn-sm btn-primary gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i> Save platform
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Add Company Modal --}}
<dialog id="add-company-modal" class="modal">
    <div class="modal-box max-w-sm">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3">✕</button>
        </form>
        <h3 class="font-bold text-base mb-4 flex items-center gap-2">
            <i class="fa-solid fa-building text-primary"></i> Add company career site
        </h3>
        <div class="form-control mb-3">
            <label class="label py-0.5"><span class="label-text text-xs font-semibold">Company name</span></label>
            <input id="c-name" type="text" placeholder="e.g. Grameenphone"
                class="input input-bordered input-sm w-full" />
        </div>
        <div class="form-control mb-3">
            <label class="label py-0.5"><span class="label-text text-xs font-semibold">Careers URL</span></label>
            <input id="c-url" type="url" placeholder="https://company.com/careers"
                class="input input-bordered input-sm w-full" />
        </div>
        <div class="form-control mb-4">
            <label class="label py-0.5"><span class="label-text text-xs font-semibold">Status</span></label>
            <select id="c-status" class="select select-bordered select-sm w-full">
                <option value="active">Actively monitoring</option>
                <option value="saved">Saved</option>
            </select>
        </div>
        <div class="modal-action mt-0">
            <form method="dialog"><button class="btn btn-sm btn-ghost">Cancel</button></form>
            <button onclick="saveCompany()" class="btn btn-sm btn-primary gap-2">
                <i class="fa-solid fa-floppy-disk text-xs"></i> Save company
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Delete confirm modal --}}
<dialog id="delete-modal" class="modal">
    <div class="modal-box max-w-xs">
        <h3 class="font-bold text-base mb-1">Remove entry?</h3>
        <p class="text-sm text-base-content/60 mb-4">This will remove it from your LifeVault.</p>
        <div class="modal-action mt-0">
            <form method="dialog"><button class="btn btn-sm btn-ghost">Cancel</button></form>
            <button id="confirm-delete-btn" class="btn btn-sm btn-error">Remove</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

{{-- Toast --}}
<div id="toast-box" class="toast toast-top toast-end z-50" style="display:none">
    <div id="toast-msg" class="alert alert-success text-sm py-2 px-4 shadow-md"></div>
</div>

{{-- ════════════════════════════════ JAVASCRIPT ════════════════════════════════ --}}
<script>
// ── State ──────────────────────────────────────────────────────────────────
const STATE = {
    applications: [
        { id:1, title:'Senior Software Engineer', company:'Stripe',  platform:'Career',   date:'Jun 25, 2025', location:'Dhaka', worktype:'Remote', status:'interview', url:'', notes:'' },
        { id:2, title:'Backend Developer',         company:'Notion',  platform:'LinkedIn', date:'Jun 23, 2025', location:'Dhaka', worktype:'Onsite', status:'applied',   url:'', notes:'' },
        { id:3, title:'Full-stack Developer',      company:'Samsung', platform:'Facebook', date:'Jun 22, 2025', location:'Dhaka', worktype:'Onsite', status:'rejected',  url:'', notes:'' },
        { id:4, title:'Full-stack Engineer',       company:'Google',  platform:'BDJobs',  date:'Jun 21, 2025', location:'Dhaka', worktype:'Hybrid', status:'offer',     url:'', notes:'' },
    ],
    platforms: [
        { id:1, name:'LinkedIn',  url:'https://linkedin.com',    applied:9 },
        { id:2, name:'Glassdoor', url:'https://glassdoor.com',   applied:7 },
        { id:3, name:'Wellfound', url:'https://wellfound.com',   applied:5 },
        { id:4, name:'Indeed',    url:'https://indeed.com',      applied:3 },
    ],
    companies: [
        { id:1, name:'Samsung',   url:'https://samsung.com/careers',   status:'active' },
        { id:2, name:'Walton',    url:'https://waltonbd.com/career',   status:'active' },
        { id:3, name:'Envobyte',  url:'https://envobyte.com/careers',  status:'saved'  },
    ],
    nextId: 100,
    currentFilter: 'all',
    selectedStatus: 'applied',
    pendingDelete: null,
};

// ── Badge helpers ──────────────────────────────────────────────────────────
const STATUS_BADGE = {
    interview: 'badge badge-sm badge-info text-xs font-semibold',
    applied:   'badge badge-sm badge-success text-xs font-semibold',
    rejected:  'badge badge-sm badge-error text-xs font-semibold',
    offer:     'badge badge-sm badge-accent text-xs font-semibold',
    selected:  'badge badge-sm badge-secondary text-xs font-semibold',
};
const TYPE_BADGE = {
    Remote: 'badge badge-sm badge-outline badge-success text-xs',
    Onsite: 'badge badge-sm badge-outline badge-warning text-xs',
    Hybrid: 'badge badge-sm badge-outline badge-info text-xs',
    '':     '',
};

function cap(s){ return s ? s.charAt(0).toUpperCase() + s.slice(1) : ''; }

// ── Render: Applications ───────────────────────────────────────────────────
function renderApps() {
    const list = document.getElementById('app-list');
    const filtered = STATE.currentFilter === 'all'
        ? STATE.applications
        : STATE.applications.filter(a => a.status === STATE.currentFilter);

    if (!filtered.length) {
        list.innerHTML = `<p class="text-xs text-base-content/40 text-center py-6">No applications yet.</p>`;
        return;
    }

    list.innerHTML = filtered.map(a => `
        <div class="flex items-center gap-3 py-2.5 app-item" data-id="${a.id}">
            <span class="text-center text-xs font-bold bg-base-200 border border-base-300 rounded-lg px-2 py-1 whitespace-nowrap min-w-[60px]">${a.platform}</span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate">${a.title}</p>
                <p class="text-xs text-base-content/50">${a.company} &middot; ${a.date}</p>
            </div>
            <span class="text-xs text-base-content/40 whitespace-nowrap hidden sm:block">${a.location}</span>
            <div class="flex gap-1.5 items-center flex-shrink-0">
                <span class="${STATUS_BADGE[a.status] || 'badge badge-sm'}">${cap(a.status)}</span>
                ${a.worktype ? `<span class="${TYPE_BADGE[a.worktype] || 'badge badge-sm badge-outline'}">${a.worktype}</span>` : ''}
            </div>
            <button onclick="deleteEntry('app', ${a.id})" class="btn btn-xs btn-ghost text-error opacity-0 group-hover:opacity-100 hover:opacity-100 ml-1" title="Remove">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        </div>
    `).join('');

    // hover reveal delete btn
    list.querySelectorAll('.app-item').forEach(row => {
        row.classList.add('group');
    });
}

// ── Render: Platforms ──────────────────────────────────────────────────────
function renderPlatforms() {
    const list = document.getElementById('platform-list');
    if (!STATE.platforms.length) {
        list.innerHTML = `<p class="text-xs text-base-content/40 text-center py-4">No platforms added yet.</p>`;
        return;
    }
    list.innerHTML = STATE.platforms.map((p, i) => `
        <div class="flex items-center gap-3 py-2.5 group">
            <span class="text-xs font-bold text-base-content/30 w-5 text-center">${i+1}</span>
            <span class="text-sm font-semibold flex-1">${p.name}</span>
            <span class="text-xs text-base-content/50">${p.applied} applied</span>
            <a href="${p.url}" target="_blank" class="text-xs text-primary hover:underline">${p.url.replace('https://','')}</a>
            <button onclick="deleteEntry('platform', ${p.id})" class="btn btn-xs btn-ghost text-error opacity-0 group-hover:opacity-100" title="Remove">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        </div>
    `).join('');
}

// ── Render: Companies ──────────────────────────────────────────────────────
function renderCompanies() {
    const list = document.getElementById('company-list');
    if (!STATE.companies.length) {
        list.innerHTML = `<p class="text-xs text-base-content/40 text-center py-4">No companies added yet.</p>`;
        return;
    }
    list.innerHTML = STATE.companies.map(c => `
        <div class="flex items-center gap-3 py-2.5 group">
            <span class="w-2 h-2 rounded-full flex-shrink-0 ${c.status === 'active' ? 'bg-success' : 'bg-base-300'}"></span>
            <span class="text-sm font-semibold flex-1">${c.name}</span>
            <a href="${c.url}" target="_blank" class="text-xs text-primary hover:underline">${c.url.replace('https://','')}</a>
            <button onclick="deleteEntry('company', ${c.id})" class="btn btn-xs btn-ghost text-error opacity-0 group-hover:opacity-100" title="Remove">
                <i class="fa-solid fa-trash-can text-xs"></i>
            </button>
        </div>
    `).join('');
}

// ── Render: Stats ──────────────────────────────────────────────────────────
function renderStats() {
    const counts = STATE.applications.reduce((acc, a) => {
        acc[a.status] = (acc[a.status] || 0) + 1;
        return acc;
    }, {});
    animateCount('stat-applied',   counts.applied   || 0);
    animateCount('stat-interview', counts.interview || 0);
    animateCount('stat-offer',     counts.offer     || 0);
    document.getElementById('stat-companies').textContent = STATE.companies.length;
}

function animateCount(id, target) {
    const el = document.getElementById(id);
    const start = parseInt(el.textContent) || 0;
    const diff = target - start;
    const steps = 20;
    let step = 0;
    const t = setInterval(() => {
        step++;
        el.textContent = Math.round(start + (diff * step / steps));
        if (step >= steps) clearInterval(t);
    }, 18);
}

// ── Full render ────────────────────────────────────────────────────────────
function renderAll() {
    renderApps();
    renderPlatforms();
    renderCompanies();
    renderStats();
}

// ── Filter ─────────────────────────────────────────────────────────────────
function filterApps(f) {
    STATE.currentFilter = f;
    document.querySelectorAll('.filter-btn').forEach(b => {
        const active = b.dataset.filter === f;
        b.className = `btn btn-xs filter-btn ${active ? 'btn-primary' : 'btn-ghost'}`;
    });
    renderApps();
}

// ── Status picker ──────────────────────────────────────────────────────────
const STATUS_CLASSES = {
    applied:   'btn btn-sm status-pick btn-success',
    interview: 'btn btn-sm status-pick btn-warning',
    offer:     'btn btn-sm status-pick btn-accent',
    rejected:  'btn btn-sm status-pick btn-error',
};
function pickStatus(s) {
    STATE.selectedStatus = s;
    document.querySelectorAll('.status-pick').forEach(b => {
        const ds = b.dataset.s;
        b.className = STATUS_CLASSES[ds] + (ds === s ? '' : ' btn-outline');
    });
}

// ── Modal helpers ──────────────────────────────────────────────────────────
function openModal(id) { document.getElementById(id).showModal(); }
function closeModal(id) { document.getElementById(id).close(); }

// ── Save: Application ──────────────────────────────────────────────────────
function saveApplication() {
    const title    = document.getElementById('f-title').value.trim();
    const company  = document.getElementById('f-company').value.trim();
    const platform = document.getElementById('f-platform').value;
    const date     = document.getElementById('f-date').value;
    const location = document.getElementById('f-location').value.trim();
    const worktype = document.getElementById('f-worktype').value;
    const url      = document.getElementById('f-url').value.trim();
    const notes    = document.getElementById('f-notes').value.trim();

    if (!title || !company) {
        showToast('Please fill in job title and company.', 'error');
        return;
    }

    const formatted = date ? new Date(date).toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' }) : 'Today';

    STATE.applications.unshift({
        id: STATE.nextId++,
        title, company, platform: platform || 'Other',
        date: formatted, location: location || '—',
        worktype, status: STATE.selectedStatus, url, notes,
    });

    // bump platform count
    const plat = STATE.platforms.find(p => p.name === platform);
    if (plat) plat.applied++;

    closeModal('add-application-modal');
    clearAppForm();
    renderAll();
    showToast(`"${title}" at ${company} added.`);
}

function clearAppForm() {
    ['f-title','f-company','f-location','f-url','f-notes'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('f-platform').value = '';
    document.getElementById('f-worktype').value = '';
    document.getElementById('f-date').value = new Date().toISOString().split('T')[0];
    STATE.selectedStatus = 'applied';
    document.querySelectorAll('.status-pick').forEach(b => {
        const ds = b.dataset.s;
        b.className = STATUS_CLASSES[ds] + (ds === 'applied' ? '' : ' btn-outline');
    });
}

// ── Save: Platform ─────────────────────────────────────────────────────────
function savePlatform() {
    const name = document.getElementById('p-name').value.trim();
    const url  = document.getElementById('p-url').value.trim();
    if (!name || !url) { showToast('Please fill in name and URL.', 'error'); return; }
    STATE.platforms.push({ id: STATE.nextId++, name, url, applied: 0 });
    closeModal('add-platform-modal');
    document.getElementById('p-name').value = '';
    document.getElementById('p-url').value = '';
    renderPlatforms();
    showToast(`"${name}" platform added.`);
}

// ── Save: Company ──────────────────────────────────────────────────────────
function saveCompany() {
    const name   = document.getElementById('c-name').value.trim();
    const url    = document.getElementById('c-url').value.trim();
    const status = document.getElementById('c-status').value;
    if (!name || !url) { showToast('Please fill in name and URL.', 'error'); return; }
    STATE.companies.push({ id: STATE.nextId++, name, url, status });
    closeModal('add-company-modal');
    document.getElementById('c-name').value = '';
    document.getElementById('c-url').value = '';
    renderCompanies();
    renderStats();
    showToast(`"${name}" added to career sites.`);
}

// ── Delete ─────────────────────────────────────────────────────────────────
function deleteEntry(type, id) {
    STATE.pendingDelete = { type, id };
    const modal = document.getElementById('delete-modal');
    document.getElementById('confirm-delete-btn').onclick = () => {
        const { type, id } = STATE.pendingDelete;
        if (type === 'app')      STATE.applications = STATE.applications.filter(x => x.id !== id);
        if (type === 'platform') STATE.platforms    = STATE.platforms.filter(x => x.id !== id);
        if (type === 'company')  STATE.companies    = STATE.companies.filter(x => x.id !== id);
        modal.close();
        renderAll();
        showToast('Entry removed.');
    };
    modal.showModal();
}

// ── Toast ──────────────────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const box = document.getElementById('toast-box');
    const el  = document.getElementById('toast-msg');
    el.className = `alert text-sm py-2 px-4 shadow-md ${type === 'error' ? 'alert-error' : 'alert-success'}`;
    el.innerHTML = `<i class="fa-solid ${type === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'} text-xs"></i> ${msg}`;
    box.style.display = 'block';
    clearTimeout(box._t);
    box._t = setTimeout(() => box.style.display = 'none', 3000);
}

// ── Init ───────────────────────────────────────────────────────────────────
document.getElementById('f-date').value = new Date().toISOString().split('T')[0];
renderAll();
</script>

@endsection