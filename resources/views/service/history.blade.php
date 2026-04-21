@extends('layouts.app')
@section('title', 'Service Report History')
@section('content')

    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Service Reports</h1>
                <p class="text-slate-500 mt-1 text-sm">Complete service history & maintenance records — track every repair, PMS, and calibration</p>
            </div>
            <div class="flex space-x-3 no-print">
                <button onclick="exportReportsDemo()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl shadow-sm transition-all duration-200">
                    <i class="fas fa-download text-sm"></i> Export CSV
                </button>
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-medium rounded-xl shadow-sm transition-all duration-200">
                    <i class="fas fa-print text-sm"></i> Print Reports
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><i class="fas fa-clipboard-list text-xl"></i></div>
                <div class="ml-4"><p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Total Services</p><p class="text-2xl font-bold text-slate-800" id="statTotal">0</p></div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="fas fa-calendar-check text-xl"></i></div>
                <div class="ml-4"><p class="text-xs font-medium text-slate-400 uppercase tracking-wide">This Month</p><p class="text-2xl font-bold text-slate-800" id="statMonthly">0</p></div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center"><i class="fas fa-user-cog text-xl"></i></div>
                <div class="ml-4"><p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Active Engineers</p><p class="text-2xl font-bold text-slate-800" id="statEngineers">0</p></div>
            </div>
           
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/40">
                <h3 class="text-base font-semibold text-slate-700"><i class="fas fa-sliders-h mr-2 text-slate-500"></i>Filter Service Reports</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">Client Name</label><select id="filterClient" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:ring-2 focus:ring-blue-200 px-3 py-2"><option value="">All Clients</option><option>WellMed Diagnostics</option><option>MetroHealth Labs</option><option>St. Catherine Hospital</option><option>Northside Imaging</option><option>Makati Medical Center</option></select></div>
                    <div><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">Serial Number</label><input type="text" id="filterSerial" placeholder="Enter serial number" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2"></div>
                    <div><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">Location</label><select id="filterLocation" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2"><option value="">All Locations</option><option>Manila</option><option>Cebu</option><option>Davao</option><option>Laguna</option><option>Quezon City</option></select></div>
                    <div><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">Service Type</label><select id="filterType" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2"><option value="">All Types</option><option>PMS</option><option>Troubleshooting</option><option>Installation</option><option>Warranty</option></select></div>
                    <div><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">From Date</label><input type="date" id="filterDateFrom" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2"></div>
                    <div><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">To Date</label><input type="date" id="filterDateTo" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2"></div>
                    <div><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">Engineer</label><select id="filterEngineer" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2"><option value="">All Engineers</option><option>Michael Tan</option><option>Sarah Gomez</option><option>James Cruz</option><option>Patricia Lim</option><option>Anna Reyes</option></select></div>
                    <div><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">Equipment Status</label><select id="filterStatus" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2"><option value="">All Status</option><option>Operational</option><option>Not Operational</option></select></div>
                </div>
                <div class="mt-5"><label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">Keyword (Issue / Action)</label><input type="text" id="filterKeyword" placeholder="Search root cause, findings, actions..." class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm px-3 py-2"></div>
                <div class="flex justify-between items-center mt-6 pt-4 border-t border-slate-100">
                    <div class="text-sm text-slate-500"><span id="resultCount">0</span> records displayed</div>
                    <div class="flex space-x-3"><button id="resetFiltersBtn" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-xl transition"><i class="fas fa-undo-alt mr-1"></i>Reset</button><button id="applyFiltersBtn" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition"><i class="fas fa-filter mr-1"></i>Apply Filters</button></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-white flex justify-between items-center">
                <h3 class="text-lg font-semibold text-slate-800"><i class="fas fa-history mr-2 text-slate-500"></i>Service history log</h3>
                <span class="text-xs text-slate-400" id="pageIndicator">Page 1 of 1</span>
            </div>
            <div class="overflow-x-auto custom-scroll">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Machine Details</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Service Info</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Service Details</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Personnel</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-slate-100 bg-white"></tbody>
                </table>
            </div>
            <div id="noResultsMessage" class="text-center py-12 hidden">
                <i class="fas fa-clipboard-list text-5xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 font-medium">No service records match your filters.</p>
                <p class="text-slate-400 text-sm mt-1">Try adjusting your search criteria.</p>
            </div>
            <div class="bg-white px-6 py-3 border-t border-slate-200 flex justify-between items-center">
                <p class="text-xs text-slate-500" id="paginationInfo">Showing 0 of 0 records</p>
                <div class="flex space-x-2">
                    <button id="prevPageBtn" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 transition">Prev</button>
                    <button id="nextPageBtn" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 transition">Next</button>
                </div>
            </div>
        </div>
    </main>

    <!-- ========== DETAIL MODAL  ========== -->
    <div id="detailModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 hidden modal-transition">
        <div class="bg-white rounded-2xl shadow-xl w-11/12 md:w-2/3 lg:w-1/2 max-h-[85vh] overflow-y-auto m-4">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800"><i class="fas fa-file-alt mr-2 text-blue-500"></i>Service Report Details</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div id="modalContent" class="p-6 space-y-5 text-sm"></div>
        </div>
    </div>

    <script>
        const MOCK_REPORTS = [
            { id: 1001, machine_name: "HemaTech X100", model: "HTX-100", serial_number: "SN-HTX-8821", client_location: "Manila", client_name: "WellMed Diagnostics", service_type: "PMS", service_date: "2025-02-10", formatted_date: "Feb 10, 2025", root_cause_findings: "Calibration drift on laser module. QC outliers detected on HGB channel.", action_taken: "Realigned optics, performed full QC calibration, replaced cuvette washer.", parts_replaced: [{qty:1, name:"Laser diode"}], equipment_status: "Operational", recommendations: "Schedule quarterly calibration and monthly QC checks.", service_engineer: "Michael Tan", approved_by: "Dr. Reyes", service_images: 2, completion_notes: "All parameters within spec." },
            { id: 1002, machine_name: "MRI 3T Pro", model: "SIGNA", serial_number: "SN-MRI-4532", client_location: "Cebu", client_name: "MetroHealth Labs", service_type: "Troubleshooting", service_date: "2025-02-18", formatted_date: "Feb 18, 2025", root_cause_findings: "Noise artifact in axial view, RF interference suspected.", action_taken: "Replaced RF amplifier board, performed system noise test.", parts_replaced: [{qty:1, name:"RF Amp Board"}], equipment_status: "Operational", recommendations: "Monitor weekly for 1 month.", service_engineer: "Sarah Gomez", approved_by: "Dr. Alonzo", service_images: 3 },
            { id: 1003, machine_name: "Ultrasound Logiq E10", model: "E10", serial_number: "SN-ULT-1290", client_location: "Davao", client_name: "St. Catherine Hospital", service_type: "Installation", service_date: "2025-02-05", formatted_date: "Feb 5, 2025", root_cause_findings: "New installation & configuration of ultrasound system.", action_taken: "Installed software, calibrated all probes, trained sonographers.", parts_replaced: [], equipment_status: "Operational", recommendations: "User training completion required.", service_engineer: "James Cruz", approved_by: "Engr. Villanueva", service_images: 5 },
            { id: 1004, machine_name: "Centrifuge CL-2", model: "CryoSpin", serial_number: "SN-CEN-876", client_location: "Laguna", client_name: "Northside Imaging", service_type: "Warranty", service_date: "2025-01-28", formatted_date: "Jan 28, 2025", root_cause_findings: "Motor not spinning, rotor imbalance error.", action_taken: "Replaced motor assembly and rotor locking mechanism.", parts_replaced: [{qty:1, name:"Drive motor"},{qty:1,name:"Rotor lock"}], equipment_status: "Operational", recommendations: "Check belt tension monthly.", service_engineer: "Patricia Lim", approved_by: "Mr. Lim", service_images: 1 },
            { id: 1005, machine_name: "HemaTech X100", model: "HTX-100", serial_number: "SN-HTX-8821", client_location: "Manila", client_name: "WellMed Diagnostics", service_type: "Troubleshooting", service_date: "2025-01-15", formatted_date: "Jan 15, 2025", root_cause_findings: "Intermittent power failure, unit reboots randomly.", action_taken: "Replaced PSU capacitor bank, verified voltage stability.", parts_replaced: [{qty:1, name:"Power supply unit"}], equipment_status: "Operational", recommendations: "Add voltage regulator to outlet.", service_engineer: "Michael Tan", approved_by: "Dr. Reyes", service_images: 2 },
            { id: 1006, machine_name: "Anesthesia Workstation", model: "Aisys CS2", serial_number: "SN-AN-3340", client_location: "Manila", client_name: "WellMed Diagnostics", service_type: "PMS", service_date: "2025-02-22", formatted_date: "Feb 22, 2025", root_cause_findings: "Gas flow sensor deviation, O2 readings unstable.", action_taken: "Calibrated flow sensors, replaced O2 cell, updated firmware.", parts_replaced: [{qty:1, name:"O2 sensor"}], equipment_status: "Operational", recommendations: "Recalibrate after 6 months.", service_engineer: "Sarah Gomez", approved_by: "Dr. Cruz", service_images: 4 },
            { id: 1007, machine_name: "Ventilator V800", model: "V800", serial_number: "SN-VENT-5678", client_location: "Cebu", client_name: "MetroHealth Labs", service_type: "Troubleshooting", service_date: "2025-02-14", formatted_date: "Feb 14, 2025", root_cause_findings: "Alarm false triggers, pressure sensor drift.", action_taken: "Firmware update & sensor recalibration, replaced pressure transducer.", parts_replaced: [{qty:1, name:"Pressure sensor"}], equipment_status: "Operational", recommendations: "Monitor alarm logs weekly.", service_engineer: "James Cruz", approved_by: "Dr. Alonzo", service_images: 2 },
            { id: 1008, machine_name: "ECG Mac 2000", model: "MAC 2000", serial_number: "SN-ECG-9921", client_location: "Quezon City", client_name: "Makati Medical Center", service_type: "PMS", service_date: "2025-02-28", formatted_date: "Feb 28, 2025", root_cause_findings: "Baseline wander on lead II, electrode cable wear.", action_taken: "Replaced patient cable, cleaned electrode inputs, performed signal test.", parts_replaced: [{qty:1, name:"10-lead patient cable"}], equipment_status: "Operational", recommendations: "Replace cables annually.", service_engineer: "Anna Reyes", approved_by: "Dr. Santos", service_images: 1 }
        ];

        let filteredData = [...MOCK_REPORTS];
        let currentPage = 1;
        const rowsPerPage = 6;

        function updateStats() {
            const total = filteredData.length;
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();
            const monthly = filteredData.filter(r => {
                let d = new Date(r.service_date);
                return d.getMonth() === currentMonth && d.getFullYear() === currentYear;
            }).length;
            const uniqueEngineers = [...new Set(filteredData.map(r => r.service_engineer))].length;
            document.getElementById('statTotal').innerText = total;
            document.getElementById('statMonthly').innerText = monthly;
            document.getElementById('statEngineers').innerText = uniqueEngineers;
        }

        // Render table with pagination
        function renderTable() {
            const totalRecords = filteredData.length;
            const totalPages = Math.max(1, Math.ceil(totalRecords / rowsPerPage));
            if (currentPage > totalPages) currentPage = totalPages;
            const start = (currentPage - 1) * rowsPerPage;
            const paginated = filteredData.slice(start, start + rowsPerPage);
            const tbody = document.getElementById('tableBody');
            const noResultDiv = document.getElementById('noResultsMessage');
            
            if (filteredData.length === 0) {
                tbody.innerHTML = '';
                noResultDiv.classList.remove('hidden');
                document.getElementById('resultCount').innerText = '0';
                document.getElementById('paginationInfo').innerText = 'Showing 0 of 0 records';
                document.getElementById('pageIndicator').innerText = `Page 0 of 0`;
                updateStats();
                return;
            }
            noResultDiv.classList.add('hidden');
            
            tbody.innerHTML = paginated.map(record => {
                const statusClass = record.equipment_status === 'Operational' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700';
                const partsBadge = record.parts_replaced?.length ? `<span class="inline-flex items-center gap-1 text-xs text-slate-500 mt-1"><i class="fas fa-tools"></i> ${record.parts_replaced.length} part(s)</span>` : '';
                const machineInitial = record.machine_name.charAt(0);
                return `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 font-semibold text-sm">${machineInitial}</div>
                                <div>
                                    <div class="font-semibold text-slate-800">${record.machine_name}</div>
                                    <div class="text-xs text-slate-400">${record.model} | SN: ${record.serial_number}</div>
                                    <div class="text-xs text-slate-500 mt-0.5"><i class="fas fa-map-marker-alt text-[10px] mr-1"></i>${record.client_location}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-700">${record.formatted_date}</div>
                            <div class="mt-1"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">${record.service_type}</span></div>
                            ${record.service_images ? `<div class="text-xs text-slate-400 mt-1"><i class="fas fa-camera mr-1"></i>${record.service_images} images</div>` : ''}
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <div class="text-xs text-slate-600"><span class="font-semibold">Issue:</span> ${record.root_cause_findings.substring(0, 70)}${record.root_cause_findings.length > 70 ? '…' : ''}</div>
                            <div class="text-xs text-slate-600 mt-1"><span class="font-semibold">Action:</span> ${record.action_taken.substring(0, 60)}${record.action_taken.length > 60 ? '…' : ''}</div>
                            ${partsBadge}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-slate-800">${record.service_engineer}</div>
                            <div class="text-xs text-slate-400">Service Engineer</div>
                            <div class="text-xs text-slate-500 mt-1">Approved: ${record.approved_by}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ${statusClass}">${record.equipment_status}</span>
                            <div class="text-xs text-slate-400 mt-1 font-mono">ID: #${record.id}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="viewDetails(${record.id})" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50 transition" title="View Details"><i class="fas fa-eye"></i></button>
                                <button onclick="editMock(${record.id})" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50 transition" title="Edit Report"><i class="fas fa-edit"></i></button>
                                <button onclick="deleteMock(${record.id})" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition" title="Delete Report"><i class="fas fa-trash-alt"></i></button>
                                <button onclick="printMock(${record.id})" class="text-slate-500 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition" title="Print Report"><i class="fas fa-print"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            const startIdx = totalRecords === 0 ? 0 : start + 1;
            const endIdx = Math.min(start + rowsPerPage, totalRecords);
            document.getElementById('paginationInfo').innerText = `Showing ${startIdx}–${endIdx} of ${totalRecords} records`;
            document.getElementById('pageIndicator').innerText = `Page ${currentPage} of ${totalPages}`;
            document.getElementById('resultCount').innerText = totalRecords;
            updateStats();
            document.getElementById('prevPageBtn').disabled = currentPage === 1;
            document.getElementById('nextPageBtn').disabled = currentPage === totalPages;
        }

        // Filter logic
        function applyFilters() {
            const client = document.getElementById('filterClient').value;
            const serial = document.getElementById('filterSerial').value.trim().toLowerCase();
            const location = document.getElementById('filterLocation').value;
            const serviceType = document.getElementById('filterType').value;
            const dateFrom = document.getElementById('filterDateFrom').value;
            const dateTo = document.getElementById('filterDateTo').value;
            const engineer = document.getElementById('filterEngineer').value;
            const eqStatus = document.getElementById('filterStatus').value;
            const keyword = document.getElementById('filterKeyword').value.trim().toLowerCase();

            filteredData = MOCK_REPORTS.filter(r => {
                if (client && r.client_name !== client) return false;
                if (serial && !r.serial_number.toLowerCase().includes(serial)) return false;
                if (location && r.client_location !== location) return false;
                if (serviceType && r.service_type !== serviceType) return false;
                if (engineer && r.service_engineer !== engineer) return false;
                if (eqStatus && r.equipment_status !== eqStatus) return false;
                if (dateFrom && new Date(r.service_date) < new Date(dateFrom)) return false;
                if (dateTo && new Date(r.service_date) > new Date(dateTo)) return false;
                if (keyword && !(r.root_cause_findings.toLowerCase().includes(keyword) || r.action_taken.toLowerCase().includes(keyword) || (r.recommendations && r.recommendations.toLowerCase().includes(keyword)))) return false;
                return true;
            });
            currentPage = 1;
            renderTable();
        }

        function resetFilters() {
            document.getElementById('filterClient').value = '';
            document.getElementById('filterSerial').value = '';
            document.getElementById('filterLocation').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterDateFrom').value = '';
            document.getElementById('filterDateTo').value = '';
            document.getElementById('filterEngineer').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterKeyword').value = '';
            filteredData = [...MOCK_REPORTS];
            currentPage = 1;
            renderTable();
        }

        // View details modal
        window.viewDetails = (id) => {
            const report = filteredData.find(r => r.id === id);
            if (!report) return;
            const partsHtml = report.parts_replaced?.length ? `<div class="bg-slate-50 rounded-xl p-4"><p class="text-xs font-semibold text-slate-500 uppercase mb-2">Parts Replaced</p><ul class="space-y-1">${report.parts_replaced.map(p => `<li class="text-sm flex items-center gap-2"><i class="fas fa-microchip text-slate-400 text-xs"></i><span class="font-medium">${p.qty}x</span> ${p.name}</li>`).join('')}</ul></div>` : '<div class="text-slate-400 italic text-sm">No parts replaced</div>';
            document.getElementById('modalContent').innerHTML = `
                <div class="grid grid-cols-2 gap-4">
                    <div><p class="text-xs font-semibold text-slate-400 uppercase">Machine</p><p class="font-medium text-slate-800">${report.machine_name} (${report.model})</p></div>
                    <div><p class="text-xs font-semibold text-slate-400 uppercase">Serial / Location</p><p class="text-slate-700">${report.serial_number} | ${report.client_location}</p></div>
                    <div><p class="text-xs font-semibold text-slate-400 uppercase">Service Type</p><p><span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-700">${report.service_type}</span></p></div>
                    <div><p class="text-xs font-semibold text-slate-400 uppercase">Service Date</p><p class="text-slate-700">${report.formatted_date}</p></div>
                    <div><p class="text-xs font-semibold text-slate-400 uppercase">Service Engineer</p><p class="text-slate-700">${report.service_engineer}</p></div>
                    <div><p class="text-xs font-semibold text-slate-400 uppercase">Approved By</p><p class="text-slate-700">${report.approved_by}</p></div>
                    <div class="col-span-2"><p class="text-xs font-semibold text-slate-400 uppercase">Root Cause / Findings</p><p class="text-slate-700 bg-slate-50 p-3 rounded-lg">${report.root_cause_findings}</p></div>
                    <div class="col-span-2"><p class="text-xs font-semibold text-slate-400 uppercase">Action Taken</p><p class="text-slate-700 bg-slate-50 p-3 rounded-lg">${report.action_taken}</p></div>
                    <div class="col-span-2"><p class="text-xs font-semibold text-slate-400 uppercase">Recommendations</p><p class="text-slate-700">${report.recommendations || 'N/A'}</p></div>
                    <div><p class="text-xs font-semibold text-slate-400 uppercase">Equipment Status</p><p><span class="inline-flex px-2.5 py-1 rounded-full text-xs ${report.equipment_status === 'Operational' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'}">${report.equipment_status}</span></p></div>
                    <div><p class="text-xs font-semibold text-slate-400 uppercase">Images Attached</p><p class="text-slate-700">${report.service_images || 0} photos</p></div>
                </div>
                ${partsHtml}
                <div class="border-t pt-3 mt-2 text-xs text-slate-400 flex justify-end"><i class="far fa-clock mr-1"></i> Record ID: #${report.id}</div>
            `;
            document.getElementById('detailModal').classList.remove('hidden');
        };
        
        window.closeModal = () => document.getElementById('detailModal').classList.add('hidden');
        window.editMock = (id) => { Swal.fire({ title: 'Edit Mode (Demo)', text: `Edit form for report #${id} would open here. Integrate backend endpoint.`, icon: 'info', confirmButtonColor: '#2563eb' }); };
        window.deleteMock = (id) => {
            Swal.fire({ title: 'Confirm Deletion', text: `Delete service report #${id}? This action cannot be undone.`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete' }).then((res) => {
                if (res.isConfirmed) {
                    const idx = MOCK_REPORTS.findIndex(r => r.id === id);
                    if (idx !== -1) MOCK_REPORTS.splice(idx, 1);
                    filteredData = filteredData.filter(r => r.id !== id);
                    if (filteredData.length === 0 && currentPage > 1) currentPage--;
                    renderTable();
                    Swal.fire('Deleted!', 'Record has been removed.', 'success');
                }
            });
        };
        window.printMock = (id) => { Swal.fire({ title: 'Print Preview', text: `Generating print view for report #${id}`, icon: 'success', timer: 1500, showConfirmButton: false }); };
        window.exportReportsDemo = () => { Swal.fire({ title: 'Export CSV', text: 'Exporting filtered service reports as CSV (demo).', icon: 'success' }); };
        
        document.getElementById('prevPageBtn').addEventListener('click', () => { if (currentPage > 1) { currentPage--; renderTable(); } });
        document.getElementById('nextPageBtn').addEventListener('click', () => { const totalPages = Math.ceil(filteredData.length / rowsPerPage); if (currentPage < totalPages) { currentPage++; renderTable(); } });
        document.getElementById('applyFiltersBtn').addEventListener('click', applyFilters);
        document.getElementById('resetFiltersBtn').addEventListener('click', resetFilters);
        document.getElementById('detailModal').addEventListener('click', (e) => { if (e.target === document.getElementById('detailModal')) closeModal(); });
        
        renderTable();
    </script>
@endsection