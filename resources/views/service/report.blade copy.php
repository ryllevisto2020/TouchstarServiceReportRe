@extends('layouts.app')
@section('title', 'Machine Service Reports')
@section('content')
<!-- ── MAIN ── -->
<div class="max-w-full mx-auto px-6 py-7">

  <!-- Page Header -->
  <div class="flex items-end justify-between flex-wrap gap-3 mb-6">
    <div>
      <h2 class="text-[22px] font-bold text-gray-900 tracking-tight">Machine Service Reports</h2>
      <p class="text-[13px] text-gray-500 mt-1">Monitor and manage all medical equipment service records</p>
    </div>
    <div class="flex bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
      <button id="card-btn" onclick="setView('card')" class="flex items-center gap-2 px-4 h-9 text-[13px] font-semibold bg-blue-700 text-white transition-all">
        <i class="fas fa-th-large text-xs"></i> Cards
      </button>
      <button id="table-btn" onclick="setView('table')" class="flex items-center gap-2 px-4 h-9 text-[13px] font-semibold text-gray-500 hover:bg-gray-50 transition-all">
        <i class="fas fa-table text-xs"></i> Table
      </button>
    </div>
  </div>

  <!-- Stat Pills -->
  <div class="flex gap-2.5 flex-wrap mb-6">
    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 shadow-sm hover:shadow transition-shadow">
      <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
      <span class="text-[13px] text-gray-600 font-medium">Operational</span>
      <span id="count-op" class="font-mono-custom font-bold text-[14px] text-gray-900">—</span>
    </div>
    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 shadow-sm hover:shadow transition-shadow">
      <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0"></span>
      <span class="text-[13px] text-gray-600 font-medium">Maintenance</span>
      <span id="count-maint" class="font-mono-custom font-bold text-[14px] text-gray-900">—</span>
    </div>
    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 shadow-sm hover:shadow transition-shadow">
      <span class="w-2 h-2 rounded-full bg-blue-600 flex-shrink-0"></span>
      <span class="text-[13px] text-gray-600 font-medium">Standby</span>
      <span id="count-standby" class="font-mono-custom font-bold text-[14px] text-gray-900">—</span>
    </div>
    <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-full px-4 py-2 shadow-sm hover:shadow transition-shadow">
      <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
      <span class="text-[13px] text-gray-600 font-medium">Not Operational</span>
      <span id="count-notop" class="font-mono-custom font-bold text-[14px] text-gray-900">—</span>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
      <div>
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
        <select id="filter-status" onchange="applyFilters()" class="w-full h-10 px-3 bg-white border border-gray-300 rounded-xl text-[13px] text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          <option value="">All Statuses</option>
          <option value="Operational">Operational</option>
          <option value="Maintenance">Maintenance</option>
          <option value="Standby">Standby</option>
          <option value="Not Operational">Not Operational</option>
        </select>
      </div>
      <div>
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Location</label>
        <select id="filter-location" onchange="applyFilters()" class="w-full h-10 px-3 bg-white border border-gray-300 rounded-xl text-[13px] text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          <option value="">All Locations</option>
        </select>
      </div>
      <div>
        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Serial / Machine Name</label>
        <input type="text" id="filter-serial" oninput="applyFilters()" placeholder="Search serial or name…"
          class="w-full h-10 px-3 bg-white border border-gray-300 rounded-xl text-[13px] text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400">
      </div>
      <div>
        <button onclick="resetFilters()" class="w-full h-10 flex items-center justify-center gap-2 border border-gray-300 rounded-xl text-[13px] font-semibold text-gray-600 hover:bg-gray-50 transition-all">
          <i class="fas fa-undo-alt text-xs"></i> Reset Filters
        </button>
      </div>
    </div>
    <!-- Active Filters -->
    <div id="active-filters" class="hidden mt-4 pt-4 border-t border-gray-100 flex items-center gap-2 flex-wrap"></div>
  </div>

  <!-- Card Grid -->
  <div id="" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @foreach($machines as $machine)
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm card-hover flex flex-col">
      <div class="relative h-44 overflow-hidden bg-gray-100 flex-shrink-0">
        <img class="card-img w-full h-full object-cover" src="{{ $machine->image_path ? Storage::url($machine->image_path) : asset('machines/default-machine.jpg') }}" alt="{{ $machine->image_path ? Storage::url($machine->image_path) : asset('machines/default-machine.jpg') }}" onerror="this.src='https://placehold.co/400x176?text=No+Image'">
        @if($machine->status === 'Operational')
                <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-full text-[11px] font-bold backdrop-blur-md bg-green-100 text-green-700 border">
                  {{$machine->status}}
                </span>
        @elseif($machine->status === 'Maintenance')
                <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-full text-[11px] font-bold backdrop-blur-md bg-yellow-100 text-yellow-700">
                  {{$machine->status}}
                </span>
        @elseif($machine->status === 'Standby')
                <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-full text-[11px] font-bold backdrop-blur-md bg-blue-100 text-blue-700">
                  {{$machine->status}}
                </span>
        @elseif($machine->status === 'Not Operational')
                <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-full text-[11px] font-bold backdrop-blur-md bg-red-100 text-red-700">
                  {{$machine->status}}
                </span>
        @endif
      </div>
      <div class="p-4 flex-1">
        <div class="text-[15px] font-bold text-gray-900 leading-snug">{{$machine->name}}</div>
        <div class="font-mono-custom text-[11px] text-gray-400 mt-0.5"># {{$machine->serial_number}}</div>
        <div class="grid grid-cols-2 gap-2.5 mt-4">
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Location</div><div class="text-[13px] font-medium text-gray-700 mt-0.5 leading-tight">{{$machine->client_location}}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Region</div><div class="text-[13px] font-medium text-gray-700 mt-0.5">{{$machine->region}}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Last Service</div><div class="text-[13px] font-medium text-gray-700 mt-0.5">{{ date("M jS Y", strtotime($machine->last_service_date)) }}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Next PMS</div><div class="text-[13px] mt-0.5 ${dateCls(m.nextService)}">{{ date("M jS Y", strtotime($machine->next_service_date)) }}</div></div>
        </div>
      </div>
      <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/70 flex justify-end">
        <button onclick="openModal({{$machine->id}})" class="inline-flex items-center gap-2 px-4 h-9 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-[13px] font-semibold rounded-xl shadow-sm transition-all">
          <i class="fas fa-wrench text-xs"></i> Complete Service
        </button>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Table View -->
  <div id="table-view" class="hidden bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
    <table class="w-full border-collapse">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Machine</th>
          <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Location</th>
          <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
          <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Last Service</th>
          <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Next PMS</th>
          <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody id="table-body" class="divide-y divide-gray-100"></tbody>
    </table>
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
  <div id="pagination-bar" class="mt-5 flex items-center justify-between flex-wrap gap-3 bg-white border border-gray-200 rounded-2xl px-5 py-4 shadow-sm">
    <span id="pag-info" class="text-[13px] text-gray-500"></span>
    <div id="pag-btns" class="flex gap-1"></div>
  </div>
</div>
@include('service.modal')
<script>
  console.log({{ Illuminate\Support\Js::from($machines) }});  
const MACHINES = [
  { id:1,  name:'AutoAnalyzer Pro X3',    model:'AAP-X3',   serial:'TSM-2024-001', status:'Operational',     location:'Manila General Hospital',    region:'NCR',        lastService:'2025-10-12', nextService:'2026-04-12', image:'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=400&q=80' },
  { id:2,  name:'Hematology Analyzer Z5', model:'HAZ-5',    serial:'TSM-2024-002', status:'Maintenance',     location:'Cebu Medical Center',        region:'Region VII', lastService:'2025-09-01', nextService:'2026-03-01', image:'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&q=80' },
  { id:3,  name:'Blood Gas Monitor BG7',  model:'BGM-7',    serial:'TSM-2024-003', status:'Standby',         location:'Davao Doctors Hospital',     region:'Region XI',  lastService:'2025-11-20', nextService:'2026-05-20', image:'https://images.unsplash.com/photo-1530497610245-94d3c16cda28?w=400&q=80' },
  { id:4,  name:'Immunoassay System IS2', model:'ISS-2',    serial:'TSM-2024-004', status:'Not Operational', location:"St. Luke's Medical Center",   region:'NCR',        lastService:'2025-07-15', nextService:'2026-01-15', image:'https://images.unsplash.com/photo-1581595219315-a187dd40c322?w=400&q=80' },
  { id:5,  name:'Centrifuge MaxSpin 3000',model:'CMS-3000', serial:'TSM-2024-005', status:'Operational',     location:'Philippine General Hospital', region:'NCR',        lastService:'2025-12-01', nextService:'2026-06-01', image:'https://images.unsplash.com/photo-1576671081837-49000212a370?w=400&q=80' },
  { id:6,  name:'Ultrasound Compact UC4', model:'UCU-4',    serial:'TSM-2024-006', status:'Operational',     location:'Iloilo Mission Hospital',    region:'Region VI',  lastService:'2025-10-28', nextService:'2026-04-28', image:'https://images.unsplash.com/photo-1504439468489-c8920d796a29?w=400&q=80' },
  { id:7,  name:'Coagulation Analyzer CA1',model:'CAA-1',   serial:'TSM-2024-007', status:'Maintenance',     location:'Baguio General Hospital',    region:'CAR',        lastService:'2025-08-10', nextService:'2026-02-10', image:'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?w=400&q=80' },
  { id:8,  name:'Electrolyte Analyzer EA6',model:'EAA-6',   serial:'TSM-2024-008', status:'Operational',     location:'Makati Medical Center',      region:'NCR',        lastService:'2025-11-05', nextService:'2026-05-05', image:'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=400&q=80' },
  { id:9,  name:'Urinalysis System UA3',  model:'UAS-3',    serial:'TSM-2024-009', status:'Standby',         location:'Naga City Hospital',         region:'Region V',   lastService:'2025-09-22', nextService:'2026-03-22', image:'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=400&q=80' },
  { id:10, name:'Chemistry Analyzer CA9', model:'CHA-9',    serial:'TSM-2024-010', status:'Operational',     location:'Zamboanga Medical Center',   region:'Region IX',  lastService:'2025-12-15', nextService:'2026-06-15', image:'https://images.unsplash.com/photo-1565217271-2f9bef8c4c6f?w=400&q=80' },
  { id:11, name:'PCR Thermocycler TC2',   model:'PCT-2',    serial:'TSM-2024-011', status:'Maintenance',     location:'Philippine Heart Center',    region:'NCR',        lastService:'2025-08-25', nextService:'2026-02-25', image:'https://images.unsplash.com/photo-1530026405186-ed1f139313f8?w=400&q=80' },
  { id:12, name:'Flow Cytometer FC8',     model:'FCT-8',    serial:'TSM-2024-012', status:'Operational',     location:'National Kidney Institute',  region:'NCR',        lastService:'2025-11-30', nextService:'2026-05-30', image:'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&q=80' },
];

let currentView='card', filtered=[...MACHINES], currentPage=1;
const PER_PAGE=8;

// Populate locations
const lf=document.getElementById('filter-location');
[...new Set(MACHINES.map(m=>m.location))].sort().forEach(l=>{const o=document.createElement('option');o.value=l;o.textContent=l;lf.appendChild(o);});

const SBADGE={
  'Operational':     'bg-green-100 text-green-700 border border-green-200',
  'Maintenance':     'bg-yellow-100 text-yellow-700 border border-yellow-200',
  'Standby':         'bg-blue-100 text-blue-700 border border-blue-200',
  'Not Operational': 'bg-red-100 text-red-700 border border-red-200'
};

function dateCls(s){ const d=new Date(s),n=new Date(); if(d<n) return 'text-red-600 font-semibold'; if((d-n)/864e5<=7) return 'text-yellow-600 font-semibold'; return 'text-green-600 font-medium'; }
function fmt(s){ return new Date(s).toLocaleDateString('en-PH',{day:'2-digit',month:'short',year:'numeric'}); }

function applyFilters(){
  const st=document.getElementById('filter-status').value;
  const lo=document.getElementById('filter-location').value;
  const sr=document.getElementById('filter-serial').value.toLowerCase();
  filtered=MACHINES.filter(m=>(!st||m.status===st)&&(!lo||m.location===lo)&&(!sr||m.serial.toLowerCase().includes(sr)||m.name.toLowerCase().includes(sr)));
  currentPage=1; renderTags(st,lo,sr); render();
}

function renderTags(st,lo,sr){
  const af=document.getElementById('active-filters'); const t=[];
  if(st) t.push(`<span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Status: ${st} <button onclick="clearF('status')" class="opacity-60 hover:opacity-100 ml-1">✕</button></span>`);
  if(lo) t.push(`<span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Location: ${lo} <button onclick="clearF('location')" class="opacity-60 hover:opacity-100 ml-1">✕</button></span>`);
  if(sr) t.push(`<span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">Serial: ${sr} <button onclick="clearF('serial')" class="opacity-60 hover:opacity-100 ml-1">✕</button></span>`);
  if(t.length){ af.innerHTML='<span class="text-xs text-gray-400 font-semibold">Active:</span>'+t.join(''); af.classList.remove('hidden'); }
  else af.classList.add('hidden');
}

function clearF(t){ if(t==='status') document.getElementById('filter-status').value=''; if(t==='location') document.getElementById('filter-location').value=''; if(t==='serial') document.getElementById('filter-serial').value=''; applyFilters(); }
function resetFilters(){ ['filter-status','filter-location','filter-serial'].forEach(id=>{ const el=document.getElementById(id); el.tagName==='SELECT'?el.value='':el.value=''; }); applyFilters(); }

function render(){
  const c={Operational:0,Maintenance:0,Standby:0,'Not Operational':0};
  MACHINES.forEach(m=>c[m.status]++);
  document.getElementById('count-op').textContent=c.Operational;
  document.getElementById('count-maint').textContent=c.Maintenance;
  document.getElementById('count-standby').textContent=c.Standby;
  document.getElementById('count-notop').textContent=c['Not Operational'];

  const total=filtered.length, pages=Math.ceil(total/PER_PAGE)||1;
  if(currentPage>pages) currentPage=pages;
  const s=(currentPage-1)*PER_PAGE, e=Math.min(s+PER_PAGE,total);
  const pg=filtered.slice(s,e);

  const cv=document.getElementById('card-view'), tv=document.getElementById('table-view');
  document.getElementById('empty-state').classList.toggle('hidden',total>0);
  cv.style.display=(currentView==='card'&&total>0)?'grid':'none';
  if(currentView==='table'&&total>0) tv.classList.remove('hidden'); else tv.classList.add('hidden');

  renderCards(pg); renderTable(pg); renderPag(total,s,e,pages);
}

function renderCards(data){
  document.getElementById('card-view').innerHTML=data.map(m=>`
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm card-hover flex flex-col">
      <div class="relative h-44 overflow-hidden bg-gray-100 flex-shrink-0">
        <img class="card-img w-full h-full object-cover" src="${m.image}" alt="${m.name}" onerror="this.src='https://placehold.co/400x176?text=No+Image'">
        <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-full text-[11px] font-bold backdrop-blur-md ${SBADGE[m.status]||'bg-gray-100 text-gray-600'}">${m.status}</span>
      </div>
      <div class="p-4 flex-1">
        <div class="text-[15px] font-bold text-gray-900 leading-snug">${m.name}</div>
        <div class="font-mono-custom text-[11px] text-gray-400 mt-0.5"># ${m.serial}</div>
        <div class="grid grid-cols-2 gap-2.5 mt-4">
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Location</div><div class="text-[13px] font-medium text-gray-700 mt-0.5 leading-tight">${m.location}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Region</div><div class="text-[13px] font-medium text-gray-700 mt-0.5">${m.region}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Last Service</div><div class="text-[13px] font-medium text-gray-700 mt-0.5">${fmt(m.lastService)}</div></div>
          <div><div class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Next PMS</div><div class="text-[13px] mt-0.5 ${dateCls(m.nextService)}">${fmt(m.nextService)}</div></div>
        </div>
      </div>
      <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/70 flex justify-end">
        <button onclick="openModal(${m.id})" class="inline-flex items-center gap-2 px-4 h-9 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-[13px] font-semibold rounded-xl shadow-sm transition-all">
          <i class="fas fa-wrench text-xs"></i> Complete Service
        </button>
      </div>
    </div>`).join('');
}

function renderTable(data){
  document.getElementById('table-body').innerHTML=data.map(m=>`
    <tr class="hover:bg-gray-50/80 transition-colors">
      <td class="px-5 py-3.5">
        <div class="flex items-center gap-3">
          <img class="w-10 h-10 rounded-xl object-cover border border-gray-200 flex-shrink-0" src="${m.image}" onerror="this.src='https://placehold.co/40?text=?'" alt="${m.name}">
          <div>
            <div class="text-[13px] font-semibold text-gray-900">${m.name}</div>
            <div class="text-[12px] text-gray-500">${m.model}</div>
            <div class="font-mono-custom text-[11px] text-gray-400">${m.serial}</div>
          </div>
        </div>
      </td>
      <td class="px-5 py-3.5"><div class="text-[13px] font-medium text-gray-800">${m.location}</div><div class="text-[12px] text-gray-400">${m.region}</div></td>
      <td class="px-5 py-3.5"><span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold ${SBADGE[m.status]||''}">${m.status}</span></td>
      <td class="px-5 py-3.5 text-[13px] text-gray-600">${fmt(m.lastService)}</td>
      <td class="px-5 py-3.5 text-[13px] ${dateCls(m.nextService)}">${fmt(m.nextService)}</td>
      <td class="px-5 py-3.5">
        <button onclick="openModal(${m.id})" class="inline-flex items-center gap-1.5 px-3 h-8 bg-green-600 hover:bg-green-700 text-white text-[12px] font-semibold rounded-lg transition-all">
          <i class="fas fa-wrench text-[10px]"></i> Service
        </button>
      </td>
    </tr>`).join('');
}

function renderPag(total,s,e,pages){
  document.getElementById('pag-info').textContent=total===0?'No results':`Showing ${s+1}–${e} of ${total} machines`;
  const cont=document.getElementById('pag-btns'); cont.innerHTML='';
  function mkBtn(label,cb,disabled,active){
    const b=document.createElement('button');
    b.innerHTML=label; b.disabled=disabled;
    b.className=`w-8 h-8 border rounded-lg text-[13px] font-medium transition-all grid place-items-center ${active?'bg-blue-700 text-white border-blue-700 shadow-sm':disabled?'border-gray-200 text-gray-300 cursor-not-allowed':'border-gray-200 text-gray-600 hover:bg-gray-100 hover:border-gray-300'}`;
    if(!disabled) b.onclick=cb; cont.appendChild(b);
  }
  mkBtn('<i class="fas fa-chevron-left text-[10px]"></i>',()=>{currentPage--;render();},currentPage===1,false);
  for(let i=1;i<=pages;i++) mkBtn(i,((p)=>()=>{currentPage=p;render();})(i),false,i===currentPage);
  mkBtn('<i class="fas fa-chevron-right text-[10px]"></i>',()=>{currentPage++;render();},currentPage===pages,false);
}

function setView(v){
  currentView=v;
  document.getElementById('card-btn').className=`flex items-center gap-2 px-4 h-9 text-[13px] font-semibold transition-all ${v==='card'?'bg-blue-700 text-white':'text-gray-500 hover:bg-gray-50'}`;
  document.getElementById('table-btn').className=`flex items-center gap-2 px-4 h-9 text-[13px] font-semibold transition-all ${v==='table'?'bg-blue-700 text-white':'text-gray-500 hover:bg-gray-50'}`;
  render();
}

function openModal(id){
  const machine = MACHINES.find(x => x.id === id);
  document.getElementById('machine-id').value = id;
  document.getElementById('service-modal').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}

function closeServiceModal() {
  document.getElementById('service-modal').classList.add('hidden');
  document.body.style.overflow = '';
}

// Signature pad implementation
let signaturePad = null;
let signatureHistory = [];

function initSignaturePad() {
  const canvas = document.getElementById('signature-pad');
  if (!canvas) return;
  
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;
  
  signaturePad = new SignaturePad(canvas);
  
  // Save initial state
  signatureHistory.push(signaturePad.toData());
  
  document.getElementById('clear-signature').addEventListener('click', () => {
    signaturePad.clear();
    document.getElementById('signature-data').value = '';
    document.getElementById('signature-preview').classList.add('hidden');
    signatureHistory = [signaturePad.toData()];
  });
  
  document.getElementById('undo-signature').addEventListener('click', () => {
    if (signatureHistory.length > 1) {
      signatureHistory.pop();
      signaturePad.fromData(signatureHistory[signatureHistory.length - 1]);
      updateSignatureData();
    }
  });
  
  signaturePad.onEnd = () => {
    signatureHistory.push(signaturePad.toData());
    updateSignatureData();
  };
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

// Image upload handlers
function setupImageUpload(uploadAreaId, inputId, previewId, statusId, maxFiles = 10) {
  const uploadArea = document.getElementById(uploadAreaId);
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  const status = document.getElementById(statusId);
  let files = [];
  
  uploadArea.addEventListener('click', () => input.click());
  
  uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('border-blue-400', 'bg-blue-50/30');
  });
  
  uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('border-blue-400', 'bg-blue-50/30');
  });
  
  uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('border-blue-400', 'bg-blue-50/30');
    const droppedFiles = Array.from(e.dataTransfer.files);
    addFiles(droppedFiles);
  });
  
  input.addEventListener('change', (e) => {
    addFiles(Array.from(e.target.files));
  });
  
  function addFiles(newFiles) {
    if (files.length + newFiles.length > maxFiles) {
      alert(`Maximum ${maxFiles} images allowed`);
      return;
    }
    
    newFiles.forEach(file => {
      if (file.type.startsWith('image/')) {
        files.push(file);
        displayPreview(file);
      }
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
        <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-gray-200">
        <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">×</button>
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
    const dataTransfer = new DataTransfer();
    files.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
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
  newPart.className = 'grid grid-cols-3 gap-3';
  newPart.innerHTML = `
    <input type="number" name="qty[]" placeholder="Qty" class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
    <input type="text" name="particulars[]" placeholder="Particulars" class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
    <input type="text" name="si_dr_no[]" placeholder="S.I./D.R. No." class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
  `;
  container.appendChild(newPart);
});

// Others checkbox handling
document.getElementById('others-checkbox').addEventListener('change', (e) => {
  const othersInput = document.getElementById('others-input');
  othersInput.classList.toggle('hidden', !e.target.checked);
  if (!e.target.checked) {
    const input = othersInput.querySelector('input');
    if (input) input.value = '';
  }
});

// Draft functionality
document.getElementById('save-draft-btn').addEventListener('click', () => {
  const formData = new FormData(document.getElementById('service-form'));
  // Save draft logic here
  localStorage.setItem('serviceDraft', JSON.stringify(Object.fromEntries(formData)));
  alert('Draft saved successfully!');
});

document.getElementById('clear-draft-btn').addEventListener('click', () => {
  localStorage.removeItem('serviceDraft');
  document.getElementById('service-form').reset();
  alert('Draft cleared!');
});

// Check for saved draft on modal open
function loadDraft() {
  const draft = localStorage.getItem('serviceDraft');
  if (draft) {
    const data = JSON.parse(draft);
    // Populate form with draft data
    document.getElementById('clear-draft-btn').classList.remove('hidden');
  }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
  render();
  initSignaturePad();
  
  // Setup image uploads
  setupImageUpload('before-upload-area', 'before-images', 'before-image-preview', 'before-upload-status', 5);
  setupImageUpload('after-upload-area', 'after-images', 'after-image-preview', 'after-upload-status', 5);
  setupImageUpload('service-upload-area', 'service-images', 'service-image-preview', 'service-upload-status', 10);
  setupImageUpload('calibration-upload-area', 'calibration-images', 'calibration-image-preview', 'calibration-upload-status', 10);
  
  // Form submission
  document.getElementById('service-form').addEventListener('submit', (e) => {
    updateSignatureData();
    if (!document.getElementById('signature-data').value) {
      e.preventDefault();
      alert('Please provide your signature');
    }
  });
});

// Make functions globally available
window.setView = setView;
window.applyFilters = applyFilters;
window.resetFilters = resetFilters;
window.clearF = clearF;
window.openModal = openModal;
window.closeServiceModal = closeServiceModal;
</script>

<!-- Include Signature Pad library -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

@endsection