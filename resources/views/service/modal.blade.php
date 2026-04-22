<!-- Service Modal -->
<div id="service-modal" class="fixed z-50 inset-0 hidden" style="overflow:hidden;">

    <!-- Backdrop -->
    <div id="modal-backdrop" onclick="closeServiceModal()" class="absolute inset-0 bg-gray-900/70" style="backdrop-filter:blur(4px);"></div>

    <!-- Scroll wrapper -->
    <div class="absolute inset-0 overflow-y-auto flex items-end sm:items-center justify-center sm:p-4">

        <!-- Modal Container -->
        <div id="modal-container" class="relative w-full bg-white text-left shadow-2xl"
             style="border-radius:1rem 1rem 0 0; max-height:92dvh; overflow-y:auto; display:flex; flex-direction:column;">

            @media sm { border-radius:1rem; }

            <!-- Header (sticky) -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 py-4 sm:px-6 flex-shrink-0"
                 style="position:sticky;top:0;z-index:10;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-base sm:text-xl font-bold text-white leading-tight">Complete Service Report</h3>
                        <p class="mt-0.5 text-blue-100 text-xs sm:text-sm">Fill out the required fields to complete the service report</p>
                    </div>
                    <button type="button" onclick="closeServiceModal()"
                            class="text-white/80 hover:text-white focus:outline-none transition-colors flex-shrink-0 mt-0.5">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="service-form" method="POST" action="/service/add" enctype="multipart/form-data" style="flex:1;display:flex;flex-direction:column;">
                @csrf
                <input type="hidden" id="machine-id" name="machine_id" value="">

                <div class="px-4 pt-5 pb-4 sm:px-6" style="flex:1;">

                    <!-- Two-column on lg, single on mobile -->
                    <div class="svc-grid">

                        <!-- ── LEFT COLUMN ── -->
                        <div style="display:flex;flex-direction:column;gap:1.25rem;">

                            <!-- Service Type -->
                            <div>
                                <label class="svc-label">Type of Service <span class="text-red-500">*</span></label>
                                <div class="svc-checkbox-grid">
                                    <label class="svc-check-card">
                                        <input type="checkbox" name="service_type[]" value="PMS" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2.5 text-sm font-medium">PMS</span>
                                    </label>
                                    <label class="svc-check-card">
                                        <input type="checkbox" name="service_type[]" value="Troubleshooting" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2.5 text-sm font-medium">Troubleshooting</span>
                                    </label>
                                    <label class="svc-check-card">
                                        <input type="checkbox" name="service_type[]" value="Installation" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2.5 text-sm font-medium">Installation</span>
                                    </label>
                                    <label class="svc-check-card">
                                        <input type="checkbox" name="service_type[]" value="Warranty" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2.5 text-sm font-medium">Warranty</span>
                                    </label>
                                    <label class="svc-check-card">
                                        <input type="checkbox" name="service_type[]" value="Calibration" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2.5 text-sm font-medium">Calibration</span>
                                    </label>
                                    <label class="svc-check-card">
                                        <input type="checkbox" id="others-checkbox" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2.5 text-sm font-medium">Others</span>
                                    </label>
                                </div>
                                <div id="others-input" class="mt-2.5 hidden">
                                    <input type="text" name="service_type[]" placeholder="Please specify..."
                                           class="svc-input">
                                </div>
                            </div>

                            <!-- Identification -->
                            <div>
                                <label class="svc-label">Identification/Verification <span class="text-red-500">*</span></label>
                                <textarea name="identification" rows="3" class="svc-textarea"
                                          placeholder="Enter identification details..." required></textarea>
                            </div>

                            <!-- Root Cause -->
                            <div>
                                <label class="svc-label">Root Cause/Findings <span class="text-red-500">*</span></label>
                                <textarea name="root_cause" rows="3" class="svc-textarea"
                                          placeholder="Describe the root cause..." required></textarea>
                            </div>

                            <!-- Action Taken -->
                            <div>
                                <label class="svc-label">Action Taken <span class="text-red-500">*</span></label>
                                <textarea name="action_taken" rows="3" class="svc-textarea"
                                          placeholder="Describe actions taken..." required></textarea>
                            </div>
                        </div>

                        <!-- ── RIGHT COLUMN ── -->
                        <div style="display:flex;flex-direction:column;gap:1.25rem;">

                            <!-- Equipment Status -->
                            <div>
                                <label class="svc-label">Equipment Status <span class="text-red-500">*</span></label>
                                <div class="svc-radio-row">
                                    <label class="svc-check-card" style="flex:1;">
                                        <input type="radio" name="equipment_status" value="Operational"
                                               class="text-blue-600 focus:ring-blue-500" required>
                                        <span class="ml-2.5 text-sm font-medium">Operational</span>
                                    </label>
                                    <label class="svc-check-card" style="flex:1;">
                                        <input type="radio" name="equipment_status" value="Not Operational"
                                               class="text-blue-600 focus:ring-blue-500" required>
                                        <span class="ml-2.5 text-sm font-medium">Not Operational</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Recommendations -->
                            <div>
                                <label class="svc-label">Recommendations</label>
                                <textarea name="recommendations" rows="3" class="svc-textarea"
                                          placeholder="Enter recommendations..."></textarea>
                            </div>

                            <!-- Parts Replaced -->
                            <div>
                                <label class="svc-label">Parts Replaced</label>
                                <div id="parts-container" style="display:flex;flex-direction:column;gap:0.75rem;">
                                    <div class="parts-row">
                                        <input type="number" name="qty[]" placeholder="Qty" class="svc-input parts-qty">
                                        <input type="text" name="particulars[]" placeholder="Particulars" class="svc-input parts-part">
                                        <input type="text" name="si_dr_no[]" placeholder="S.I./D.R. No." class="svc-input parts-si">
                                    </div>
                                </div>
                                <button type="button" id="add-part"
                                        class="mt-3 flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                    <i class="fas fa-plus-circle mr-2"></i> Add Another Part
                                </button>
                            </div>

                            <!-- Before Images -->
                            <div>
                                <label class="svc-label"><i class="fas fa-camera mr-1.5 text-blue-500"></i> Before Images <span class="text-xs text-gray-400 font-normal">(Max 5)</span></label>
                                <div id="before-upload-area" class="svc-upload-area">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-sm font-medium text-blue-600">Tap to upload</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 20MB</p>
                                    <div id="before-upload-status" class="text-xs text-green-600 mt-1.5 hidden upload-status">
                                        <i class="fas fa-check-circle mr-1"></i><span class="count">0</span> files ready
                                    </div>
                                    <input id="before-images" name="before_images[]" type="file" class="hidden" multiple accept="image/*">
                                </div>
                                <div id="before-image-preview" class="svc-preview-grid"></div>
                            </div>

                            <!-- After Images -->
                            <div>
                                <label class="svc-label"><i class="fas fa-camera mr-1.5 text-blue-500"></i> After Images <span class="text-xs text-gray-400 font-normal">(Max 5)</span></label>
                                <div id="after-upload-area" class="svc-upload-area">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-sm font-medium text-blue-600">Tap to upload</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 20MB</p>
                                    <div id="after-upload-status" class="text-xs text-green-600 mt-1.5 hidden upload-status">
                                        <i class="fas fa-check-circle mr-1"></i><span class="count">0</span> files ready
                                    </div>
                                    <input id="after-images" name="after_images[]" type="file" class="hidden" multiple accept="image/*">
                                </div>
                                <div id="after-image-preview" class="svc-preview-grid"></div>
                            </div>

                            <!-- Service Images -->
                            <div>
                                <label class="svc-label"><i class="fas fa-camera mr-1.5 text-blue-500"></i> Service Images <span class="text-xs text-gray-400 font-normal">(Max 10)</span></label>
                                <div id="service-upload-area" class="svc-upload-area">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-sm font-medium text-blue-600">Tap to upload</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 20MB</p>
                                    <div id="service-upload-status" class="text-xs text-green-600 mt-1.5 hidden upload-status">
                                        <i class="fas fa-check-circle mr-1"></i><span class="count">0</span> files ready
                                    </div>
                                    <input id="service-images" name="images[]" type="file" class="hidden" multiple accept="image/*">
                                </div>
                                <div id="service-image-preview" class="svc-preview-grid"></div>
                            </div>

                            <!-- Calibration Images -->
                            <div>
                                <label class="svc-label"><i class="fas fa-camera mr-1.5 text-blue-500"></i> Calibration Images <span class="text-xs text-gray-400 font-normal">(Max 10)</span></label>
                                <div id="calibration-upload-area" class="svc-upload-area">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-sm font-medium text-blue-600">Tap to upload</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 20MB</p>
                                    <div id="calibration-upload-status" class="text-xs text-green-600 mt-1.5 hidden upload-status">
                                        <i class="fas fa-check-circle mr-1"></i><span class="count">0</span> files ready
                                    </div>
                                    <input id="calibration-images" name="calibration_images[]" type="file" class="hidden" multiple accept="image/*">
                                </div>
                                <div id="calibration-image-preview" class="svc-preview-grid"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Signature Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <label class="svc-label">
                            <i class="fas fa-signature mr-1.5 text-blue-500"></i> MedTech E-Signature <span class="text-red-500">*</span>
                        </label>
                        <div class="border border-gray-300 rounded-xl p-3 sm:p-5 bg-white">
                            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                                <div class="border border-gray-300 rounded-lg bg-white overflow-hidden">
                                    <canvas id="signature-pad" class="touch-none bg-white"
                                            style="width:100%;height:150px;display:block;"></canvas>
                                </div>
                                <div style="display:flex;gap:0.75rem;">
                                    <button type="button" id="clear-signature"
                                            class="flex-1 py-2.5 px-4 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-eraser mr-2"></i> Clear
                                    </button>
                                    <button type="button" id="undo-signature"
                                            class="flex-1 py-2.5 px-4 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-undo mr-2"></i> Undo
                                    </button>
                                </div>
                                <div id="signature-preview" class="hidden">
                                    <p class="text-sm text-gray-600 mb-2">Signature Preview:</p>
                                    <div class="border border-gray-300 rounded-lg p-3 bg-white">
                                        <img id="signature-image" class="max-h-12 mx-auto" alt="Signature preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="signature-data" name="medtech_signature" required>
                    </div>

                    <!-- Personnel Section -->
                    <div class="mt-5 svc-personnel-grid">
                        <div>
                            <label class="svc-label">
                                <i class="fas fa-user-check mr-1.5 text-blue-500"></i> Approved By (MedTech) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="approved_by" placeholder="Enter name of MedTech approver"
                                   class="svc-input" required>
                            <p class="text-xs text-gray-400 mt-1.5">The person who will approve this service report</p>
                        </div>
                        <div>
                            <label class="svc-label">
                                <i class="fas fa-user-cog mr-1.5 text-blue-500"></i> Service Engineer
                            </label>
                            <div class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50">
                                <div class="text-gray-700 font-medium text-sm" id="service-engineer-name">
                                    {{$employee_details->emp_first_name}} {{$employee_details->emp_last_name}}
                                </div>
                                <div class="text-xs text-gray-400 mt-1">
                                    <i class="fas fa-building mr-1"></i> {{$employee_details->emp_position}}
                                </div>
                            </div>
                            <input type="hidden" name="service_engineer" value="{{$employee_details->emp_first_name}} {{$employee_details->emp_last_name}}">
                            <input type="hidden" name="service_engineer_department" value="{{$employee_details->emp_deparment}}">
                        </div>
                    </div>
                </div>

                <!-- Footer (sticky) -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 border-t border-gray-200 flex-shrink-0"
                     style="position:sticky;bottom:0;z-index:10;">
                    <div id="draft-status-indicator"></div>
                    <div class="svc-footer-btns">
                        <div class="svc-footer-left">
                            <button type="button" id="save-draft-btn" class="svc-btn-draft">
                                <i class="fas fa-save mr-1.5"></i>Save Draft
                            </button>
                            <button type="button" id="clear-draft-btn" class="svc-btn-clear hidden">
                                <i class="fas fa-times mr-1.5"></i>Clear Draft
                            </button>
                        </div>
                        <div class="svc-footer-right">
                            <button type="button" onclick="closeServiceModal()" class="svc-btn-cancel">
                                Cancel
                            </button>
                            <button type="submit" id="submit-service-btn" class="svc-btn-submit">
                                <i class="fas fa-check-circle mr-1.5"></i>Complete Service
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.svc-label {
    display: block;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}
.svc-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    box-sizing: border-box;
}
.svc-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.svc-textarea {
    width: 100%;
    padding: 0.75rem 0.875rem;
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
    font-size: 0.875rem;
    outline: none;
    resize: vertical;
    transition: border-color 0.15s, box-shadow 0.15s;
    box-sizing: border-box;
    font-family: inherit;
}
.svc-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.svc-check-card {
    display: flex;
    align-items: center;
    padding: 0.625rem 0.75rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
}
.svc-check-card:hover {
    border-color: #93c5fd;
    background: rgba(239,246,255,0.6);
}
.svc-checkbox-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}
.svc-radio-row {
    display: flex;
    gap: 0.75rem;
}
.svc-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 0.75rem;
    padding: 1.25rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
}
.svc-upload-area:hover {
    border-color: #60a5fa;
    background: rgba(239,246,255,0.3);
}
.svc-preview-grid {
    margin-top: 0.75rem;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
}
/* Parts row */
.parts-row {
    display: grid;
    grid-template-columns: 64px 1fr 90px;
    gap: 0.5rem;
}
/* Two-column form grid */
.svc-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}
/* Personnel grid */
.svc-personnel-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}
/* Footer buttons */
.svc-footer-btns {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
}
.svc-footer-left,
.svc-footer-right {
    display: flex;
    gap: 0.5rem;
}
.svc-footer-left > button,
.svc-footer-right > button {
    flex: 1;
    justify-content: center;
}
.svc-btn-draft {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border: 1px solid #fde68a;
    color: #92400e;
    background: #fffbeb;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.svc-btn-draft:hover { background: #fef3c7; }
.svc-btn-clear {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    color: #374151;
    background: #f9fafb;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.svc-btn-clear:hover { background: #f3f4f6; }
.svc-btn-cancel {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    color: #374151;
    background: white;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.svc-btn-cancel:hover { background: #f9fafb; }
.svc-btn-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1.25rem;
    background: linear-gradient(to right, #16a34a, #059669);
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.15s;
    white-space: nowrap;
}
.svc-btn-submit:hover { opacity: 0.9; }

/* ── Tablet and up (640px+) ── */
@media (min-width: 640px) {
    #modal-container {
        border-radius: 1rem !important;
        max-width: 56rem;
        margin: auto;
        max-height: 90dvh;
    }
    .svc-footer-btns {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    .svc-footer-left > button,
    .svc-footer-right > button {
        flex: none;
    }
    .parts-row {
        grid-template-columns: 80px 1fr 110px;
    }
}

/* ── Desktop (1024px+) ── */
@media (min-width: 1024px) {
    .svc-grid {
        grid-template-columns: 1fr 1fr;
    }
    .svc-personnel-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<script>
$(document).ready(function () {
    const medtech_signature = $("#signature-data");
    const canvas = $("#signature-pad")[0];
    const signature = new SignaturePad(canvas);

    $("#service-form").submit(function(e){
        if(!signature.isEmpty()){
            medtech_signature.val(signature.toDataURL())
        }
        if(medtech_signature.val() == ""){
            e.preventDefault();
            alert('Please provide your signature');
        }
    });
});
</script>