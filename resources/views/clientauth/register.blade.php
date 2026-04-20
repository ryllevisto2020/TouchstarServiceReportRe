@extends('layouts.app')
@section('title', 'Client Management')
@section('content')

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-hospital-user text-blue-600"></i> Client Management System
            </h1>
            <p class="text-gray-500 mt-1">Manage hospitals, clinics & laboratories | Contact persons, pathologists, and key staff</p>
        </div>

        <!-- Toolbar with stats -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6 flex flex-wrap justify-between items-center">
            <div>
                <h2 class="text-lg font-semibold"><i class="fas fa-building mr-2 text-blue-500"></i>Client Directory</h2>
                <div class="flex gap-5 text-sm mt-1">
                    <span>Total Clients: <strong id="totalClientsCount">0</strong></span>
                    <span class="text-indigo-600"><i class="fas fa-user-md"></i> Active Partners: <strong id="activeClientsCount">0</strong></span>
                    <span class="text-amber-500"><i class="fas fa-phone-alt"></i> With Contact Person: <strong id="withContactCount">0</strong></span>
                </div>
            </div>
            <div class="flex gap-3 mt-3 sm:mt-0">
                <input type="text" id="searchClientInput" placeholder="Search hospital, contact, city..." class="border rounded-lg px-4 py-2 w-64 text-sm focus:ring-2 focus:ring-blue-300">
                <button id="addClientBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 transition shadow-sm">
                    <i class="fas fa-plus-circle"></i> Add Client
                </button>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hospital / Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pathologist</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Head MedTech</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact Person</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="clientTableBody" class="divide-y divide-gray-200 bg-white"></tbody>
                </table>
            </div>
            <div id="clientPagination" class="px-6 py-3 border-t flex justify-between items-center text-sm bg-gray-50"></div>
        </div>
    </div>


@include('clientauth.modal')
<script>
    // ==================== DATA MODEL ====================
    let clients = [];
    let currentPage = 1;
    const PAGE_SIZE = 6;
    let searchTerm = "";
    let currentImageDataUrl = null;

    // Load/Save data
    function loadData() {
        const stored = localStorage.getItem("client_management");
        if (stored) {
            clients = JSON.parse(stored);
        } else {
            clients = [
                { id: "1", clientName: "St. Luke's Medical Center", address: "279 E Rodriguez Sr. Blvd, Quezon City", pathologist: "Dr. Maria Santos", headMedtech: "Rodrigo Cruz, RMT", contactPersonName: "Atty. John Rivera", contactPhone: "+632 8723-0101", contactEmail: "clients@stlukes.ph", status: "active", additionalInfo: "JCI accredited. Net 30 terms.", clientImage: null },
                { id: "2", clientName: "Makati Medical Center", address: "2 Amorsolo St., Makati City", pathologist: "Dr. Luisa Dimagiba", headMedtech: "Marlon Reyes, RMT", contactPersonName: "Michael Tan", contactPhone: "+632 8888-8999", contactEmail: "michael.tan@makatimed.ph", status: "active", additionalInfo: "Annual contract renewal Q1.", clientImage: null },
                { id: "3", clientName: "Cebu Doctors' Hospital", address: "Osmeña Blvd, Cebu City", pathologist: "Dr. Emilio Neri", headMedtech: "Karen Go, RMT", contactPersonName: "Dr. Susan Fernandez", contactPhone: "+63 32 255 5522", contactEmail: "susanf@cebudoctors.com", status: "pending", additionalInfo: "Awaiting signed MOU.", clientImage: null }
            ];
            saveData();
        }
        refreshUI();
    }

    function saveData() { 
        localStorage.setItem("client_management", JSON.stringify(clients)); 
    }

    function refreshUI() {
        updateStats();
        renderTable();
    }

    function updateStats() {
        document.getElementById("totalClientsCount").innerText = clients.length;
        document.getElementById("activeClientsCount").innerText = clients.filter(c => c.status === 'active').length;
        document.getElementById("withContactCount").innerText = clients.filter(c => c.contactPersonName && c.contactPersonName.trim() !== "").length;
    }

    function getFiltered() {
        if (!searchTerm.trim()) return clients;
        const term = searchTerm.toLowerCase();
        return clients.filter(c => 
            c.clientName.toLowerCase().includes(term) ||
            (c.address && c.address.toLowerCase().includes(term)) ||
            (c.contactPersonName && c.contactPersonName.toLowerCase().includes(term)) ||
            (c.pathologist && c.pathologist.toLowerCase().includes(term))
        );
    }

    // ==================== TABLE RENDER ====================
    function renderTable() {
        const filtered = getFiltered();
        const totalPages = Math.ceil(filtered.length / PAGE_SIZE);
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);
        const start = (currentPage - 1) * PAGE_SIZE;
        const pageData = filtered.slice(start, start + PAGE_SIZE);
        const tbody = document.getElementById("clientTableBody");

        if (pageData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-12 text-gray-400">No clients found</td></tr>`;
            document.getElementById("clientPagination").innerHTML = "";
            return;
        }

        tbody.innerHTML = pageData.map(c => {
            const statusBadge = c.status === 'active' ? 'bg-green-100 text-green-700' : (c.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
            const statusText = c.status === 'active' ? 'Active' : (c.status === 'pending' ? 'Pending' : 'Inactive');
            const imageThumb = c.clientImage ? 
                `<img src="${c.clientImage}" class="w-10 h-10 rounded-full object-cover">` : 
                `<div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center"><i class="fas fa-building text-blue-500"></i></div>`;
            
            return `<tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        ${imageThumb}
                        <div class="font-semibold">${escapeHtml(c.clientName)}</div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">${escapeHtml(c.address ? c.address.substring(0, 40) : '—')}</td>
                <td class="px-6 py-4 text-sm">${escapeHtml(c.pathologist || '—')}</td>
                <td class="px-6 py-4 text-sm">${escapeHtml(c.headMedtech || '—')}</td>
                <td class="px-6 py-4 text-sm">${escapeHtml(c.contactPersonName || '—')}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 rounded-full text-xs font-semibold ${statusBadge}">${statusText}</span></td>
                <td class="px-6 py-4 space-x-2">
                    <button onclick="editClient('${c.id}')" class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></button>
                    <button onclick="deleteClient('${c.id}')" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                    <button onclick="viewClient('${c.id}')" class="text-green-500 hover:text-green-700"><i class="fas fa-eye"></i></button>
                </td>
            </tr>`;
        }).join('');

        // Pagination
        let pagHtml = `<div class="text-sm text-gray-600">Showing ${start+1}-${Math.min(start+PAGE_SIZE, filtered.length)} of ${filtered.length}</div><div class="flex gap-1">`;
        for (let i = 1; i <= totalPages; i++) {
            pagHtml += `<button onclick="goToPage(${i})" class="px-3 py-1 border rounded ${i===currentPage ? 'bg-blue-500 text-white' : 'bg-white'}">${i}</button>`;
        }
        pagHtml += `</div>`;
        document.getElementById("clientPagination").innerHTML = pagHtml;
    }

    window.goToPage = (p) => { currentPage = p; renderTable(); };

    function escapeHtml(str) { 
        if(!str) return ''; 
        return str.replace(/[&<>]/g, function(m) {
            if(m === '&') return '&amp;';
            if(m === '<') return '&lt;';
            if(m === '>') return '&gt;';
            return m;
        });
    }

    // ==================== MODAL FUNCTIONS ====================
    function openModal(editId = null) {
        const modal = document.getElementById("clientModal");
        if (!modal) return;
        modal.classList.remove("hidden");
        
        if (editId) {
            const client = clients.find(c => c.id === editId);
            if (client) {
                document.getElementById("clientId").value = client.id;
                document.getElementById("clientName").value = client.clientName;
                document.getElementById("address").value = client.address || '';
                document.getElementById("pathologist").value = client.pathologist || '';
                document.getElementById("headMedtech").value = client.headMedtech || '';
                document.getElementById("contactPersonName").value = client.contactPersonName || '';
                document.getElementById("contactPhone").value = client.contactPhone || '';
                document.getElementById("contactEmail").value = client.contactEmail || '';
                document.getElementById("clientStatus").value = client.status;
                document.getElementById("additionalInfo").value = client.additionalInfo || '';
                if (client.clientImage) loadImagePreview(client.clientImage);
                else resetImagePreview();
                document.getElementById("modalTitle").innerHTML = '<i class="fas fa-edit"></i> Edit Client';
            }
        } else {
            const form = document.getElementById("clientForm");
            if (form) form.reset();
            document.getElementById("clientId").value = '';
            resetImagePreview();
            const statusSelect = document.getElementById("clientStatus");
            if (statusSelect) statusSelect.value = 'active';
            document.getElementById("modalTitle").innerHTML = '<i class="fas fa-plus"></i> Add Client';
        }
    }

    function closeModal() {
        const modal = document.getElementById("clientModal");
        if (modal) modal.classList.add("hidden");
        resetImagePreview();
    }

    window.editClient = (id) => openModal(id);
    
    window.deleteClient = (id) => {
        Swal.fire({ title: "Delete?", text: "This client will be removed", icon: "warning", showCancelButton: true }).then(res => {
            if (res.isConfirmed) {
                clients = clients.filter(c => c.id !== id);
                saveData();
                refreshUI();
                Swal.fire("Deleted", "", "success");
            }
        });
    };

    window.viewClient = (id) => {
    const c = clients.find(c => c.id === id);
    if (!c) return;
    
    const statusClass = c.status === 'active' ? 'bg-green-100 text-green-700' : (c.status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
    const statusText = c.status === 'active' ? 'Active Partner' : (c.status === 'pending' ? 'Pending Contract' : 'Inactive');
    const statusIcon = c.status === 'active' ? 'fa-check-circle' : (c.status === 'pending' ? 'fa-clock' : 'fa-times-circle');
    
    const imageHtml = c.clientImage ? 
        `<img src="${c.clientImage}" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">` : 
        `<div class="w-28 h-28 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
            <i class="fas fa-hospital fa-3x text-white"></i>
        </div>`;
    
    const content = `
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-700 -mt-6 -mx-6 px-6 py-8 rounded-t-2xl relative">
            <div class="absolute top-4 right-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusClass} shadow-sm">
                    <i class="fas ${statusIcon} mr-1"></i> ${statusText}
                </span>
            </div>
            <div class="flex flex-col items-center text-center">
                ${imageHtml}
                <h2 class="text-2xl font-bold text-white mt-4">${escapeHtml(c.clientName)}</h2>
                <p class="text-blue-100 text-sm mt-1">Client ID: ${c.id}</p>
                ${c.address ? `<p class="text-blue-100 text-sm mt-2"><i class="fas fa-map-marker-alt mr-1"></i> ${escapeHtml(c.address)}</p>` : ''}
            </div>
        </div>
        
        <!-- Content Body -->
        <div class="px-6 py-6">
            <!-- Key Personnel Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">
                    <i class="fas fa-users text-blue-500 mr-2"></i>Key Personnel
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-teal-500">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Pathologist</div>
                        <div class="font-semibold text-gray-800 mt-1">${escapeHtml(c.pathologist || '—')}</div>
                        <div class="text-xs text-gray-400 mt-1"><i class="fas fa-user-md"></i> Medical Director</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-500">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Head MedTech</div>
                        <div class="font-semibold text-gray-800 mt-1">${escapeHtml(c.headMedtech || '—')}</div>
                        <div class="text-xs text-gray-400 mt-1"><i class="fas fa-microscope"></i> Laboratory Supervisor</div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">
                    <i class="fas fa-address-card text-green-500 mr-2"></i>Contact Information
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Primary Contact</div>
                            <div class="font-semibold text-gray-800 mt-1 flex items-center gap-2">
                                <i class="fas fa-user-tie text-amber-600"></i>
                                ${escapeHtml(c.contactPersonName || '—')}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Phone Number</div>
                            <div class="font-semibold text-gray-800 mt-1 flex items-center gap-2">
                                <i class="fas fa-phone-alt text-blue-500"></i>
                                ${escapeHtml(c.contactPhone || '—')}
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-xs text-gray-500 uppercase tracking-wide">Email Address</div>
                            <div class="font-semibold text-gray-800 mt-1 flex items-center gap-2">
                                <i class="fas fa-envelope text-red-500"></i>
                                ${c.contactEmail ? `<a href="mailto:${escapeHtml(c.contactEmail)}" class="text-blue-600 hover:underline">${escapeHtml(c.contactEmail)}</a>` : '—'}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information Section -->
            ${c.additionalInfo ? `
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">
                    <i class="fas fa-info-circle text-indigo-500 mr-2"></i>Additional Information
                </h3>
                <div class="bg-amber-50 rounded-lg p-4 border-l-4 border-amber-500">
                    <div class="text-sm text-gray-700 whitespace-pre-wrap">${escapeHtml(c.additionalInfo)}</div>
                </div>
            </div>
            ` : ''}
            
            <!-- Metadata Footer -->
            <div class="mt-6 pt-4 border-t text-xs text-gray-400 flex justify-between items-center">
                <div>
                    <i class="far fa-calendar-alt mr-1"></i> Created: ${new Date(parseInt(c.id) || Date.now()).toLocaleDateString()}
                </div>
                <div>
                    <i class="fas fa-database mr-1"></i> Record ID: ${c.id}
                </div>
            </div>
        </div>
    `;
    
    const viewContent = document.getElementById("viewClientContent");
    if (viewContent) viewContent.innerHTML = content;
    const viewModal = document.getElementById("viewClientModal");
    if (viewModal) viewModal.classList.remove("hidden");
};

    window.closeViewClientModal = () => {
        const modal = document.getElementById("viewClientModal");
        if (modal) modal.classList.add("hidden");
    };

    // ==================== IMAGE HANDLING ====================
    function resetImagePreview() {
        const preview = document.getElementById("clientImagePrev");
        if (preview) {
            preview.innerHTML = `<i class="fas fa-building text-4xl text-gray-400"></i>`;
            preview.classList.add("bg-gray-100");
        }
        const input = document.getElementById("clientImage");
        if (input) input.value = '';
        currentImageDataUrl = null;
    }

    function loadImagePreview(imageUrl) {
        const preview = document.getElementById("clientImagePrev");
        if (preview) {
            preview.innerHTML = `<img src="${imageUrl}" class="w-full h-full object-cover rounded-xl">`;
            preview.classList.remove("bg-gray-100");
        }
        currentImageDataUrl = imageUrl;
    }

    const imageInput = document.getElementById("clientImage");
    if (imageInput) {
        imageInput.addEventListener("change", function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire("Error", "Image must be less than 2MB", "error");
                    this.value = "";
                    return;
                }
                const reader = new FileReader();
                reader.onload = (ev) => {
                    currentImageDataUrl = ev.target.result;
                    const preview = document.getElementById("clientImagePrev");
                    if (preview) {
                        preview.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover rounded-xl">`;
                        preview.classList.remove("bg-gray-100");
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // ==================== FORM SUBMIT ====================
    const clientForm = document.getElementById("clientForm");
    if (clientForm) {
        clientForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const id = document.getElementById("clientId").value;
            const formData = {
                clientName: document.getElementById("clientName").value.trim(),
                address: document.getElementById("address").value.trim(),
                pathologist: document.getElementById("pathologist").value.trim(),
                headMedtech: document.getElementById("headMedtech").value.trim(),
                contactPersonName: document.getElementById("contactPersonName").value.trim(),
                contactPhone: document.getElementById("contactPhone").value.trim(),
                contactEmail: document.getElementById("contactEmail").value.trim(),
                status: document.getElementById("clientStatus").value,
                additionalInfo: document.getElementById("additionalInfo").value.trim(),
                clientImage: currentImageDataUrl
            };

            if (!formData.clientName) {
                Swal.fire("Error", "Client name required", "error");
                return;
            }

            if (id) {
                const index = clients.findIndex(c => c.id === id);
                if (index !== -1) clients[index] = { ...clients[index], ...formData };
                Swal.fire("Updated", "Client updated", "success");
            } else {
                const newId = Date.now().toString();
                clients.unshift({ id: newId, ...formData });
                Swal.fire("Added", "Client added", "success");
            }

            saveData();
            refreshUI();
            closeModal();
            currentPage = 1;
            renderTable();
        });
    }

    // ==================== SEARCH & INIT ====================
    const searchInput = document.getElementById("searchClientInput");
    if (searchInput) {
        searchInput.addEventListener("input", (e) => {
            searchTerm = e.target.value;
            currentPage = 1;
            renderTable();
        });
    }

    const addBtn = document.getElementById("addClientBtn");
    if (addBtn) addBtn.addEventListener("click", () => openModal());
    
    const closeModalBtn = document.getElementById("closeClientModal");
    if (closeModalBtn) closeModalBtn.addEventListener("click", closeModal);
    
    const cancelModalBtn = document.getElementById("cancelModalBtn");
    if (cancelModalBtn) cancelModalBtn.addEventListener("click", closeModal);
    
    window.onclick = (e) => {
        const modal = document.getElementById("clientModal");
        const viewModal = document.getElementById("viewClientModal");
        if (e.target === modal) closeModal();
        if (e.target === viewModal) window.closeViewClientModal();
    };

    loadData();
</script>
@endsection