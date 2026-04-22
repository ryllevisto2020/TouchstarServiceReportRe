@push('scripts')
<script>
let pendingServices = [];
function loadPendingServices() {
    // Example: Load from localStorage drafts
    const drafts = [];
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        if (key && key.startsWith('serviceDraft_')) {
            try {
                const draft = JSON.parse(localStorage.getItem(key));
                drafts.push({
                    id: key.replace('serviceDraft_', ''),
                    machine_name: draft.machine_name || 'Unknown Machine',
                    serial_number: draft.serial_number || 'N/A',
                    created_at: draft.created_at || new Date().toISOString(),
                    status: 'draft'
                });
            } catch(e) {}
        }
    }
    pendingServices = drafts;
    updatePendingBadge();
    renderPendingTable();
}

// Update floating button badge
function updatePendingBadge() {
    const badge = document.getElementById('pending-badge');
    if (badge) {
        const count = pendingServices.length;
        badge.textContent = count;
        badge.classList.toggle('hidden', count === 0);
    }
}

// Render the pending table
function renderPendingTable() {
    const tbody = document.getElementById('pending-table-body');
    if (!tbody) return;
    
    if (pendingServices.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-2 block"></i>
                    No pending services found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = pendingServices.map(service => `
        <tr class="border-b border-gray-100 hover:bg-amber-50 transition-colors">
            <td class="px-3 py-3">
                <div class="font-medium text-gray-800 text-sm">${escapeHtml(service.machine_name)}</div>
                <div class="text-xs text-gray-400">${escapeHtml(service.serial_number)}</div>
            </td>
            <td class="px-3 py-3 text-sm text-gray-500">${formatDate(service.created_at)}</td>
            <td class="px-3 py-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                    <i class="fas fa-pen-fancy mr-1 text-10"></i> Draft
                </span>
            </td>
            <td class="px-3 py-3">
                <div class="flex gap-1">
                    <button onclick="editPendingService('${service.id}')" 
                            class="px-2.5 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button onclick="submitPendingService('${service.id}')" 
                            class="px-2.5 py-1.5 text-xs font-medium text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                        <i class="fas fa-check-circle mr-1"></i> Submit
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Helper functions
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-PH', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function editPendingService(draftId) {
    const draft = localStorage.getItem(`serviceDraft_${draftId}`);
    if (draft) {
        const data = JSON.parse(draft);
        populateServiceForm(data);
        closePendingModal();
        if (typeof openModal === 'function') {
            openModal(data.machine_id);
        }
    }
}

function submitPendingService(draftId) {
    const draft = localStorage.getItem(`serviceDraft_${draftId}`);
    if (draft) {
        const data = JSON.parse(draft);
        // Populate and submit
        populateServiceForm(data);
        closePendingModal();
        if (typeof openModal === 'function') {
            openModal(data.machine_id);
        }
        setTimeout(() => {
            const submitBtn = document.getElementById('submit-service-btn');
            if (submitBtn) {
                submitBtn.click();
            }
        }, 500);
    }
}

function populateServiceForm(data) {
    // Populate machine ID
    const machineIdField = document.getElementById('machine-id');
    if (machineIdField && data.machine_id) machineIdField.value = data.machine_id;
    
    // Service types
    if (data.service_type && Array.isArray(data.service_type)) {
        document.querySelectorAll('input[name="service_type[]"]').forEach(cb => {
            cb.checked = data.service_type.includes(cb.value);
        });
        if (data.service_type.includes('others_specified') && data.others_specified) {
            document.getElementById('others-checkbox').checked = true;
            document.getElementById('others-input').classList.remove('hidden');
            const othersInput = document.querySelector('#others-input input');
            if (othersInput) othersInput.value = data.others_specified;
        }
    }
    
    // Textareas
    const fields = ['identification', 'root_cause', 'action_taken', 'recommendations'];
    fields.forEach(field => {
        const el = document.querySelector(`textarea[name="${field}"]`);
        if (el && data[field]) el.value = data[field];
    });
    
    // Equipment status
    if (data.equipment_status) {
        const radio = document.querySelector(`input[name="equipment_status"][value="${data.equipment_status}"]`);
        if (radio) radio.checked = true;
    }
    
    // Signature
    if (data.medtech_signature) {
        const signatureData = document.getElementById('signature-data');
        if (signatureData) signatureData.value = data.medtech_signature;
        const preview = document.getElementById('signature-image');
        if (preview) preview.src = data.medtech_signature;
        document.getElementById('signature-preview')?.classList.remove('hidden');
    }
    
    // Approved by
    const approvedBy = document.querySelector('input[name="approved_by"]');
    if (approvedBy && data.approved_by) approvedBy.value = data.approved_by;
}

// Toggle pending modal
function togglePendingModal() {
    const modal = document.getElementById('pending-modal');
    if (modal) {
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden')) {
            renderPendingTable();
        }
    }
}

function closePendingModal() {
    const modal = document.getElementById('pending-modal');
    if (modal) modal.classList.add('hidden');
}

// Load on page ready
document.addEventListener('DOMContentLoaded', function() {
    loadPendingServices();
    
    // Close modal when clicking backdrop
    const backdrop = document.getElementById('pending-modal-backdrop');
    if (backdrop) {
        backdrop.addEventListener('click', closePendingModal);
    }
    
    // Escape key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePendingModal();
        }
    });
});

// Override save draft to store with machine info
const originalSaveDraft = window.saveDraft;
window.saveDraft = function() {
    const form = document.getElementById('service-form');
    if (form) {
        const formData = new FormData(form);
        const draftData = {};
        formData.forEach((value, key) => {
            if (key.includes('[]')) {
                const cleanKey = key.replace('[]', '');
                if (!draftData[cleanKey]) draftData[cleanKey] = [];
                draftData[cleanKey].push(value);
            } else {
                draftData[key] = value;
            }
        });
        
        // Add metadata
        draftData.machine_name = document.querySelector('.card-view .font-bold')?.innerText || 'Unknown';
        draftData.serial_number = document.querySelector('.card-view .text-gray-400')?.innerText.replace('# ', '') || 'N/A';
        draftData.created_at = new Date().toISOString();
        draftData.machine_id = document.getElementById('machine-id')?.value || 'unknown';
        
        const draftId = 'draft_' + Date.now();
        localStorage.setItem(`serviceDraft_${draftId}`, JSON.stringify(draftData));
        
        // Show success message
        Swal.fire({
            title: 'Draft Saved!',
            text: 'Your service report has been saved to pending list.',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
        
        loadPendingServices();
    }
};

// Expose functions globally
window.togglePendingModal = togglePendingModal;
window.closePendingModal = closePendingModal;
window.editPendingService = editPendingService;
window.submitPendingService = submitPendingService;
</script>
@endpush

<!-- Floating Button - Bottom Right -->
<button onclick="togglePendingModal()" 
        class="fixed bottom-6 right-6 z-40 w-14 h-14 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 flex items-center justify-center text-white border-0 cursor-pointer group">
    <i class="fas fa-clock text-xl"></i>
    <span id="pending-badge" 
          class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold min-w-[20px] h-5 px-1 rounded-full flex items-center justify-center shadow-md hidden">
        0
    </span>
</button>

<div id="pending-modal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div id="pending-modal-backdrop" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    
    <div class="absolute bottom-0 right-0 w-full sm:relative sm:max-w-4xl sm:mx-auto sm:my-8 sm:w-11/12">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-5 py-4 flex justify-between items-center flex-shrink-0">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-hourglass-half"></i>
                    Pending Services
                    <span id="pending-count" class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">0</span>
                </h3>
                <button onclick="closePendingModal()" class="text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Table Content -->
            <div class="flex-1 overflow-auto p-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Machine</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pending-table-body" class="divide-y divide-gray-100">
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-400">
                                    <i class="fas fa-spinner fa-spin text-2xl mb-2 block"></i>
                                    Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-400 flex justify-between items-center flex-shrink-0">
                <span><i class="fas fa-info-circle mr-1"></i> Drafts are saved locally</span>
                <button onclick="closePendingModal()" class="text-gray-400 hover:text-gray-600">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveDraftBtn = document.getElementById('save-draft-btn');
    if (saveDraftBtn && typeof window.saveDraft === 'function') {
        // Replace existing onclick
        const newBtn = saveDraftBtn.cloneNode(true);
        saveDraftBtn.parentNode.replaceChild(newBtn, saveDraftBtn);
        newBtn.addEventListener('click', window.saveDraft);
    }
});
</script>