@extends('layouts.app')
@section('title', 'Machine Service Reports')
@section('content')

<style>
/* ── Page responsive styles ── */
body { overflow-x: hidden; }

/* Page padding tighter on mobile */
.page-wrap {
    max-width: 100%;
    padding: 1.25rem 1rem 2rem;
}
@media (min-width: 640px) {
    .page-wrap { padding: 1.75rem 1.5rem 2rem; }
}

/* Header: stack on mobile */
.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

/* Stat pills: wrap naturally */
.stat-pills {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
}
.stat-pill {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 9999px;
    padding: 0.375rem 0.875rem;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    font-size: 0.8125rem;
}

/* Filter bar grid */
.filter-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
    align-items: end;
}
@media (min-width: 480px) {
    .filter-grid { grid-template-columns: 1fr 1fr; }
}
@media (min-width: 1024px) {
    .filter-grid { grid-template-columns: 1fr 1fr 1fr auto; }
}

/* Table: horizontal scroll on mobile */
.table-scroll-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.table-scroll-wrap table {
    min-width: 600px;
}

/* Card grid */
.card-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}
@media (min-width: 480px) {
    .card-grid { grid-template-columns: 1fr 1fr; }
}
@media (min-width: 1024px) {
    .card-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (min-width: 1280px) {
    .card-grid { grid-template-columns: repeat(4, 1fr); }
}

/* Pagination: wrap on mobile */
.pag-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1.25rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    padding: 0.875rem 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
</style>

<!-- ── MAIN ── -->
<div class="page-wrap">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2 class="text-xl sm:text-[22px] font-bold text-gray-900 tracking-tight">Machine Service Reports</h2>
            <p class="text-[13px] text-gray-500 mt-0.5">Monitor and manage all medical equipment service records</p>
        </div>
        <div class="flex bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <button id="card-btn" onclick="setView('card')"
                    class="flex items-center gap-2 px-4 h-9 text-[13px] font-semibold bg-blue-700 text-white transition-all">
                <i class="fas fa-th-large text-xs"></i> Cards
            </button>
            <button id="table-btn" onclick="setView('table')"
                    class="flex items-center gap-2 px-4 h-9 text-[13px] font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i class="fas fa-table text-xs"></i> Table
            </button>
        </div>
    </div>

    <!-- Stat Pills -->
    <div class="stat-pills">
        <div class="stat-pill">
            <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;flex-shrink:0;"></span>
            <span class="text-gray-600 font-medium">Operational</span>
            <span id="count-op" class="font-bold text-gray-900">—</span>
        </div>
        <div class="stat-pill">
            <span style="width:8px;height:8px;border-radius:50%;background:#eab308;flex-shrink:0;"></span>
            <span class="text-gray-600 font-medium">Maintenance</span>
            <span id="count-maint" class="font-bold text-gray-900">—</span>
        </div>
        <div class="stat-pill">
            <span style="width:8px;height:8px;border-radius:50%;background:#2563eb;flex-shrink:0;"></span>
            <span class="text-gray-600 font-medium">Standby</span>
            <span id="count-standby" class="font-bold text-gray-900">—</span>
        </div>
        <div class="stat-pill">
            <span style="width:8px;height:8px;border-radius:50%;background:#ef4444;flex-shrink:0;"></span>
            <span class="text-gray-600 font-medium">Not Operational</span>
            <span id="count-notop" class="font-bold text-gray-900">—</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 mb-5 shadow-sm">
        <div class="filter-grid">
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                <select id="filter-status" onchange="applyFilters()"
                        class="w-full h-10 px-3 bg-white border border-gray-300 rounded-xl text-[13px] text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="Operational">Operational</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Standby">Standby</option>
                    <option value="Not Operational">Not Operational</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Location</label>
                <select id="filter-location" onchange="applyFilters()"
                        class="w-full h-10 px-3 bg-white border border-gray-300 rounded-xl text-[13px] text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Locations</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Serial / Machine Name</label>
                <input type="text" id="filter-serial" oninput="applyFilters()" placeholder="Search serial or name…"
                       class="w-full h-10 px-3 bg-white border border-gray-300 rounded-xl text-[13px] text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400">
            </div>
            <div>
                <button onclick="resetFilters()"
                        class="w-full h-10 flex items-center justify-center gap-2 border border-gray-300 rounded-xl text-[13px] font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                    <i class="fas fa-undo-alt text-xs"></i> Reset
                </button>
            </div>
        </div>
        <div id="active-filters" class="hidden mt-4 pt-4 border-t border-gray-100 flex items-center gap-2 flex-wrap"></div>
    </div>

    <!-- Card Grid -->
    <div id="card-view" class="card-grid"></div>

    <!-- Table View -->
    <div id="table-view" class="hidden bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="table-scroll-wrap">
            <table class="w-full border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Machine</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Last Service</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Next PMS</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="divide-y divide-gray-100"></tbody>
            </table>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden bg-white border border-gray-200 rounded-2xl shadow-sm text-center py-16">
        <div class="w-[72px] h-[72px] rounded-full bg-gray-50 border border-gray-200 grid place-items-center mx-auto mb-5">
            <i class="fas fa-tools text-3xl text-gray-300"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900">No machines found</h3>
        <p class="text-[13px] text-gray-500 mt-1.5">Try adjusting your filters to see results.</p>
    </div>

    <!-- Pagination -->
    <div class="pag-bar" id="pagination-bar">
        <span id="pag-info" class="text-[13px] text-gray-500"></span>
        <div id="pag-btns" class="flex gap-1 flex-wrap"></div>
    </div>
</div>

@include('service.modal')

<script>
if({{ session('success') ? 'true' : 'false' }}){
    Swal.fire({ title: "Service Completed!", icon: "success", draggable: true });
}

const MACHINES = {{ Illuminate\Support\Js::from($machines) }};
let currentView = 'card', filtered = [...MACHINES], currentPage = 1;
const PER_PAGE = 8;

// Populate locations
const lf = document.getElementById('filter-location');
[...new Set(MACHINES.map(m => m.client_location))].sort().forEach(l => {
    const o = document.createElement('option');
    o.value = l; o.textContent = l;
    lf.appendChild(o);
});

const SBADGE = {
    'Operational':     'bg-green-100 text-green-700 border border-green-200',
    'Maintenance':     'bg-yellow-100 text-yellow-700 border border-yellow-200',
    'Standby':         'bg-blue-100 text-blue-700 border border-blue-200',
    'Not Operational': 'bg-red-100 text-red-700 border border-red-200'
};

function dateCls(s) {
    const d = new Date(s), n = new Date();
    if (d < n) return 'text-red-600 font-semibold';
    if ((d - n) / 864e5 <= 7) return 'text-yellow-600 font-semibold';
    return 'text-green-600 font-medium';
}
function fmt(s) {
    return new Date(s).toLocaleDateString('en-PH', { day: '2-digit', month: 'short', year: 'numeric' });
}

function applyFilters() {
    const st = document.getElementById('filter-status').value;
    const lo = document.getElementById('filter-location').value;
    const sr = document.getElementById('filter-serial').value.toLowerCase();
    filtered = MACHINES.filter(m =>
        (!st || m.status === st) &&
        (!lo || m.client_location === lo) &&
        (!sr || m.serial_number.toLowerCase().includes(sr) || m.name.toLowerCase().includes(sr))
    );
    currentPage = 1;
    renderTags(st, lo, sr);
    render();
}

function renderTags(st, lo, sr) {
    const af = document.getElementById('active-filters');
    const t = [];
    if (st) t.push(`<span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Status: ${st} <button onclick="clearF('status')" class="opacity-60 hover:opacity-100 ml-1">✕</button></span>`);
    if (lo) t.push(`<span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Location: ${lo} <button onclick="clearF('location')" class="opacity-60 hover:opacity-100 ml-1">✕</button></span>`);
    if (sr) t.push(`<span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">Serial: ${sr} <button onclick="clearF('serial')" class="opacity-60 hover:opacity-100 ml-1">✕</button></span>`);
    if (t.length) {
        af.innerHTML = '<span class="text-xs text-gray-400 font-semibold">Active:</span>' + t.join('');
        af.classList.remove('hidden');
    } else {
        af.classList.add('hidden');
    }
}

function clearF(t) {
    if (t === 'status') document.getElementById('filter-status').value = '';
    if (t === 'location') document.getElementById('filter-location').value = '';
    if (t === 'serial') document.getElementById('filter-serial').value = '';
    applyFilters();
}

function resetFilters() {
    ['filter-status', 'filter-location'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('filter-serial').value = '';
    applyFilters();
}

function render() {
    const c = { Operational: 0, Maintenance: 0, Standby: 0, 'Not Operational': 0 };
    MACHINES.forEach(m => c[m.status]++);
    document.getElementById('count-op').textContent = c.Operational;
    document.getElementById('count-maint').textContent = c.Maintenance;
    document.getElementById('count-standby').textContent = c.Standby;
    document.getElementById('count-notop').textContent = c['Not Operational'];

    const total = filtered.length, pages = Math.ceil(total / PER_PAGE) || 1;
    if (currentPage > pages) currentPage = pages;
    const s = (currentPage - 1) * PER_PAGE, e = Math.min(s + PER_PAGE, total);
    const pg = filtered.slice(s, e);

    const cv = document.getElementById('card-view');
    const tv = document.getElementById('table-view');
    document.getElementById('empty-state').classList.toggle('hidden', total > 0);
    cv.style.display = (currentView === 'card' && total > 0) ? 'grid' : 'none';
    tv.classList.toggle('hidden', !(currentView === 'table' && total > 0));

    renderCards(pg);
    renderTable(pg);
    renderPag(total, s, e, pages);
}

function renderCards(data) {
    document.getElementById('card-view').innerHTML = data.map(m => `
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm flex flex-col">
      <div class="relative overflow-hidden bg-gray-100 flex-shrink-0" style="height:11rem;">
        <img class="w-full h-full object-cover" src="storage/${m.image_path}" alt="${m.name}"
             onerror="this.src='https://placehold.co/400x176?text=No+Image'">
        <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-full text-[11px] font-bold ${SBADGE[m.status] || 'bg-gray-100 text-gray-600'}">${m.status}</span>
      </div>
      <div class="p-3.5 flex-1">
        <div class="text-[14px] font-bold text-gray-900 leading-snug">${m.name}</div>
        <div class="text-[11px] text-gray-400 mt-0.5"># ${m.serial_number}</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem 0.75rem;margin-top:0.75rem;">
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Location</div><div class="text-[12px] font-medium text-gray-700 mt-0.5 leading-tight">${m.client_location}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Region</div><div class="text-[12px] font-medium text-gray-700 mt-0.5">${m.city}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Last Service</div><div class="text-[12px] font-medium text-gray-700 mt-0.5">${fmt(m.last_service_date)}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Next PMS</div><div class="text-[12px] mt-0.5 ${dateCls(m.next_service_date)}">${fmt(m.next_service_date)}</div></div>
        </div>
      </div>
      <div class="px-3.5 py-2.5 border-t border-gray-100 bg-gray-50/70">
        <button onclick="openModal(${m.id})"
                class="w-full flex items-center justify-center gap-2 h-9 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-[13px] font-semibold rounded-xl shadow-sm transition-all">
          <i class="fas fa-wrench text-xs"></i> Complete Service
        </button>
      </div>
    </div>`).join('');
}

function renderTable(data) {
    document.getElementById('table-body').innerHTML = data.map(m => `
    <tr class="hover:bg-gray-50/80 transition-colors">
      <td class="px-4 py-3">
        <div class="flex items-center gap-3">
          <img class="w-10 h-10 rounded-xl object-cover border border-gray-200 flex-shrink-0"
               src="storage/${m.image_path}" onerror="this.src='https://placehold.co/40?text=?'" alt="${m.name}">
          <div>
            <div class="text-[13px] font-semibold text-gray-900">${m.name}</div>
            <div class="text-[12px] text-gray-500">${m.model}</div>
            <div class="text-[11px] text-gray-400">${m.serial_number}</div>
          </div>
        </div>
      </td>
      <td class="px-4 py-3"><div class="text-[13px] font-medium text-gray-800">${m.client_location}</div><div class="text-[12px] text-gray-400">${m.city}</div></td>
      <td class="px-4 py-3"><span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold ${SBADGE[m.status] || ''}">${m.status}</span></td>
      <td class="px-4 py-3 text-[13px] text-gray-600 whitespace-nowrap">${fmt(m.last_service_date)}</td>
      <td class="px-4 py-3 text-[13px] whitespace-nowrap ${dateCls(m.next_service_date)}">${fmt(m.next_service_date)}</td>
      <td class="px-4 py-3">
        <button onclick="openModal(${m.id})"
                class="inline-flex items-center gap-1.5 px-3 h-8 bg-green-600 hover:bg-green-700 text-white text-[12px] font-semibold rounded-lg transition-all whitespace-nowrap">
          <i class="fas fa-wrench text-[10px]"></i> Service
        </button>
      </td>
    </tr>`).join('');
}

function renderPag(total, s, e, pages) {
    document.getElementById('pag-info').textContent = total === 0
        ? 'No results'
        : `Showing ${s + 1}–${e} of ${total} machines`;
    const cont = document.getElementById('pag-btns');
    cont.innerHTML = '';
    function mkBtn(label, cb, disabled, active) {
        const b = document.createElement('button');
        b.innerHTML = label;
        b.disabled = disabled;
        b.className = `w-8 h-8 border rounded-lg text-[13px] font-medium transition-all grid place-items-center ${active ? 'bg-blue-700 text-white border-blue-700' : disabled ? 'border-gray-200 text-gray-300 cursor-not-allowed' : 'border-gray-200 text-gray-600 hover:bg-gray-100'}`;
        if (!disabled) b.onclick = cb;
        cont.appendChild(b);
    }
    mkBtn('<i class="fas fa-chevron-left text-[10px]"></i>', () => { currentPage--; render(); }, currentPage === 1, false);
    for (let i = 1; i <= pages; i++) {
        mkBtn(i, ((p) => () => { currentPage = p; render(); })(i), false, i === currentPage);
    }
    mkBtn('<i class="fas fa-chevron-right text-[10px]"></i>', () => { currentPage++; render(); }, currentPage === pages, false);
}

function setView(v) {
    currentView = v;
    document.getElementById('card-btn').className = `flex items-center gap-2 px-4 h-9 text-[13px] font-semibold transition-all ${v === 'card' ? 'bg-blue-700 text-white' : 'text-gray-500 hover:bg-gray-50'}`;
    document.getElementById('table-btn').className = `flex items-center gap-2 px-4 h-9 text-[13px] font-semibold transition-all ${v === 'table' ? 'bg-blue-700 text-white' : 'text-gray-500 hover:bg-gray-50'}`;
    render();
}

let signaturePad = null;
let signatureHistory = [];

function openModal(id) {
    document.getElementById('machine-id').value = id;
    document.getElementById('service-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    requestAnimationFrame(() => {
        const canvas = document.getElementById('signature-pad');
        if (!canvas) return;
        const ratio = window.devicePixelRatio || 1;
        const w = canvas.offsetWidth;
        const h = 150;
        canvas.width = w * ratio;
        canvas.height = h * ratio;
        canvas.style.width = w + 'px';
        canvas.style.height = h + 'px';
        const ctx = canvas.getContext('2d');
        ctx.scale(ratio, ratio);
        if (typeof SignaturePad !== 'undefined') {
            if (!signaturePad) {
                signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgb(255,255,255)' });
            } else {
                signaturePad.clear();
            }
        }
    });
}

function closeServiceModal() {
    document.getElementById('service-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

function updateSignatureData() {
    if (signaturePad && !signaturePad.isEmpty()) {
        const dataURL = signaturePad.toDataURL();
        document.getElementById('signature-data').value = dataURL;
        document.getElementById('signature-image').src = dataURL;
        document.getElementById('signature-preview').classList.remove('hidden');
    } else {
        document.getElementById('signature-data').value = '';
        document.getElementById('signature-preview').classList.add('hidden');
    }
}

function setupImageUpload(uploadAreaId, inputId, previewId, statusId, maxFiles = 10) {
    const uploadArea = document.getElementById(uploadAreaId);
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const status = document.getElementById(statusId);
    let files = [];

    uploadArea.addEventListener('click', () => input.click());
    uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('border-blue-400'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('border-blue-400'));
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400');
        addFiles(Array.from(e.dataTransfer.files));
    });
    input.addEventListener('change', (e) => addFiles(Array.from(e.target.files)));

    function addFiles(newFiles) {
        if (files.length + newFiles.length > maxFiles) {
            alert(`Maximum ${maxFiles} images allowed`);
            return;
        }
        newFiles.forEach(file => {
            if (file.type.startsWith('image/')) { files.push(file); displayPreview(file); }
        });
        updateFileInput();
        updateStatus();
    }

    function displayPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" style="width:100%;height:5rem;object-fit:cover;border-radius:0.5rem;border:1px solid #e5e7eb;">
                <button type="button" style="position:absolute;top:4px;right:4px;background:#ef4444;color:white;border-radius:50%;width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:12px;border:none;cursor:pointer;">×</button>
            `;
            div.querySelector('button').addEventListener('click', () => {
                files = files.filter(f => f !== file);
                div.remove();
                updateFileInput();
                updateStatus();
            });
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    function updateStatus() {
        if (files.length > 0) {
            status.querySelector('.count').textContent = files.length;
            status.classList.remove('hidden');
        } else {
            status.classList.add('hidden');
        }
    }
}

// Parts management
document.getElementById('add-part').addEventListener('click', () => {
    const container = document.getElementById('parts-container');
    const newPart = document.createElement('div');
    newPart.className = 'parts-row';
    newPart.innerHTML = `
        <input type="number" name="qty[]" placeholder="Qty" class="svc-input parts-qty">
        <input type="text" name="particulars[]" placeholder="Particulars" class="svc-input parts-part">
        <input type="text" name="si_dr_no[]" placeholder="S.I./D.R. No." class="svc-input parts-si">
    `;
    container.appendChild(newPart);
});

// Others checkbox
document.getElementById('others-checkbox').addEventListener('change', (e) => {
    const othersInput = document.getElementById('others-input');
    othersInput.classList.toggle('hidden', !e.target.checked);
    if (!e.target.checked) {
        const input = othersInput.querySelector('input');
        if (input) input.value = '';
    }
});

// Signature controls
document.getElementById('clear-signature').addEventListener('click', () => {
    if (signaturePad) {
        signaturePad.clear();
        document.getElementById('signature-data').value = '';
        document.getElementById('signature-preview').classList.add('hidden');
        signatureHistory = [];
    }
});

document.getElementById('undo-signature').addEventListener('click', () => {
    if (signaturePad && signatureHistory.length > 0) {
        signatureHistory.pop();
        signaturePad.fromData(signatureHistory[signatureHistory.length - 1] || []);
        updateSignatureData();
    }
});

// Draft
document.getElementById('save-draft-btn').addEventListener('click', () => {
    const formData = new FormData(document.getElementById('service-form'));
    localStorage.setItem('serviceDraft', JSON.stringify(Object.fromEntries(formData)));
    alert('Draft saved successfully!');
});

document.getElementById('clear-draft-btn').addEventListener('click', () => {
    localStorage.removeItem('serviceDraft');
    document.getElementById('service-form').reset();
    alert('Draft cleared!');
});

document.addEventListener('DOMContentLoaded', () => {
    render();
    setupImageUpload('before-upload-area', 'before-images', 'before-image-preview', 'before-upload-status', 5);
    setupImageUpload('after-upload-area', 'after-images', 'after-image-preview', 'after-upload-status', 5);
    setupImageUpload('service-upload-area', 'service-images', 'service-image-preview', 'service-upload-status', 10);
    setupImageUpload('calibration-upload-area', 'calibration-images', 'calibration-image-preview', 'calibration-upload-status', 10);

    document.getElementById('service-form').addEventListener('submit', (e) => {
        // signature validation handled by jQuery block in modal
    });
});

window.setView = setView;
window.applyFilters = applyFilters;
window.resetFilters = resetFilters;
window.clearF = clearF;
window.openModal = openModal;
window.closeServiceModal = closeServiceModal;
</script>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
@include('service.pending')
@endsection