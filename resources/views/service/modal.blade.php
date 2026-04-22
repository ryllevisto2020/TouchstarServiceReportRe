<!-- Service Modal -->
<div id="service-modal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
        </div>

        <!-- Modal Container -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">Complete Service Report</h3>
                        <p class="mt-1 text-blue-100 text-sm">Fill out the required fields to complete the service report</p>
                    </div>
                    <button type="button" onclick="closeServiceModal()" class="text-white/80 hover:text-white focus:outline-none transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Form -->
            <form id="service-form" method="POST" action="/service/add" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="machine-id" name="machine_id" value="">
                
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Service Type -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Type of Service <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                        <input type="checkbox" name="service_type[]" value="PMS" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-sm font-medium">PMS</span>
                                    </label>
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                        <input type="checkbox" name="service_type[]" value="Troubleshooting" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-sm font-medium">Troubleshooting</span>
                                    </label>
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                        <input type="checkbox" name="service_type[]" value="Installation" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-sm font-medium">Installation</span>
                                    </label>
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                        <input type="checkbox" name="service_type[]" value="Warranty" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-sm font-medium">Warranty</span>
                                    </label>
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                        <input type="checkbox" name="service_type[]" value="Calibration" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-sm font-medium">Calibration</span>
                                    </label>
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                        <input type="checkbox" id="others-checkbox" class="rounded text-blue-600 focus:ring-blue-500">
                                        <span class="ml-3 text-sm font-medium">Others</span>
                                    </label>
                                </div>

                                <!-- Hidden input for Others -->
                                <div id="others-input" class="mt-3 hidden">
                                    <input type="text" 
                                        name="service_type[]" 
                                        placeholder="Please specify..." 
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>
                            </div>

                            <!-- Identification -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Identification/Verification <span class="text-red-500">*</span></label>
                                <textarea name="identification" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Enter identification details..." required></textarea>
                            </div>

                            <!-- Root Cause -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Root Cause/Findings <span class="text-red-500">*</span></label>
                                <textarea name="root_cause" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Describe the root cause..." required></textarea>
                            </div>

                            <!-- Action Taken -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Action Taken <span class="text-red-500">*</span></label>
                                <textarea name="action_taken" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Describe actions taken..." required></textarea>
                            </div>
                        </div>
                        

                        <!-- Right Column -->
                        <div class="space-y-6">
                             <!-- Equipment Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Equipment Status <span class="text-red-500">*</span></label>
                                <div class="flex space-x-6">
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                        <input type="radio" name="equipment_status" value="Operational" class="text-blue-600 focus:ring-blue-500" required>
                                        <span class="ml-3 text-sm font-medium">Operational</span>
                                    </label>
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                        <input type="radio" name="equipment_status" value="Not Operational" class="text-blue-600 focus:ring-blue-500" required>
                                        <span class="ml-3 text-sm font-medium">Not Operational</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Recommendations -->

                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-2">Recommendations</label>
                                <textarea name="recommendations" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Enter recommendations..."></textarea>
                            </div>

                            <!-- Parts Replaced -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Parts Replaced</label>
                                <div id="parts-container" class="space-y-3">
                                    <div class="grid grid-cols-3 gap-3">
                                        <input type="number" name="qty[]" placeholder="Qty" class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                        <input type="text" name="particulars[]" placeholder="Particulars" class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                        <input type="text" name="si_dr_no[]" placeholder="S.I./D.R. No." class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    </div>
                                </div>
                                <button type="button" id="add-part" class="mt-3 flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                    <i class="fas fa-plus-circle mr-2"></i> Add Another Part
                                </button>
                            </div>

                            <!-- Before Images -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">
                                    <i class="fas fa-camera mr-2 text-blue-500"></i> Before Images
                                    <span class="text-xs text-gray-500 ml-1">(Max 5 images)</span>
                                </label>
                                
                                <div id="before-upload-area" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <div class="flex flex-col sm:flex-row justify-center text-sm text-gray-600">
                                        <span class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                            Click to upload or drag and drop
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 20MB each</p>
                                    <div id="before-upload-status" class="text-xs text-green-600 mt-2 hidden upload-status">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <span class="count">0</span> files ready
                                    </div>
                                    <input id="before-images" name="before_images[]" type="file" class="hidden" multiple accept="image/*">
                                </div>
                                <div id="before-image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3"></div>
                            </div>

                            <!-- After Images -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">
                                    <i class="fas fa-camera mr-2 text-blue-500"></i> After Images
                                    <span class="text-xs text-gray-500 ml-1">(Max 5 images)</span>
                                </label>
                                
                                <div id="after-upload-area" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <div class="flex flex-col sm:flex-row justify-center text-sm text-gray-600">
                                        <span class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                            Click to upload or drag and drop
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 20MB each</p>
                                    <div id="after-upload-status" class="text-xs text-green-600 mt-2 hidden upload-status">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <span class="count">0</span> files ready
                                    </div>
                                    <input id="after-images" name="after_images[]" type="file" class="hidden" multiple accept="image/*">
                                </div>
                                <div id="after-image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3"></div>
                            </div>

                            <!-- Service Images -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">
                                    <i class="fas fa-camera mr-2 text-blue-500"></i> Service Images
                                    <span class="text-xs text-gray-500 ml-1">(Max 10 images)</span>
                                </label>
                                
                                <div id="service-upload-area" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <div class="flex flex-col sm:flex-row justify-center text-sm text-gray-600">
                                        <span class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                            Click to upload or drag and drop
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 20MB each</p>
                                    <div id="service-upload-status" class="text-xs text-green-600 mt-2 hidden upload-status">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <span class="count">0</span> files ready
                                    </div>
                                    <input id="service-images" name="images[]" type="file" class="hidden" multiple accept="image/*">
                                </div>
                                <div id="service-image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3"></div>
                            </div>

                            <!-- Calibration Images -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-3">
                                    <i class="fas fa-camera mr-2 text-blue-500"></i> Calibration Images
                                    <span class="text-xs text-gray-500 ml-1">(Max 10 images)</span>
                                </label>
                                
                                <div id="calibration-upload-area" class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <div class="flex flex-col sm:flex-row justify-center text-sm text-gray-600">
                                        <span class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                                            Click to upload or drag and drop
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 20MB each</p>
                                    <div id="calibration-upload-status" class="text-xs text-green-600 mt-2 hidden upload-status">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <span class="count">0</span> files ready
                                    </div>
                                    <input id="calibration-images" name="calibration_images[]" type="file" class="hidden" multiple accept="image/*">
                                </div>
                                <div id="calibration-image-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Signature Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <label class="block text-sm font-semibold text-gray-800 mb-4">
                            <i class="fas fa-signature mr-2 text-blue-500"></i> MedTech E-Signature <span class="text-red-500">*</span>
                        </label>
                        
                        <!-- Signature Capture Area -->
                        <div class="border border-gray-300 rounded-xl p-5 bg-white">
                            <div class="flex flex-col space-y-4">
                                <!-- Signature Canvas -->
                                <div class="border border-gray-300 rounded-lg bg-white overflow-hidden">
                                    <canvas id="signature-pad" class="touch-none bg-white"></canvas>
                                </div>
                                
                                <!-- Signature Controls -->
                                <div class="flex space-x-3">
                                    <button type="button" id="clear-signature" class="flex-1 py-2.5 px-4 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-eraser mr-2"></i> Clear
                                    </button>
                                    <button type="button" id="undo-signature" class="flex-1 py-2.5 px-4 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-undo mr-2"></i> Undo
                                    </button>
                                </div>
                                
                                <!-- Signature Preview -->
                                <div id="signature-preview" class="hidden">
                                    <p class="text-sm text-gray-600 mb-2">Signature Preview:</p>
                                    <div class="border border-gray-300 rounded-lg p-3 bg-white">
                                        <img id="signature-image" class="max-h-12 mx-auto" alt="Signature preview">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden input to store signature data -->
                        <input type="hidden" id="signature-data" name="medtech_signature"  required>
                    </div>

                    <!-- Personnel Section -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                <i class="fas fa-user-check mr-2 text-blue-500"></i> Approved By (MedTech) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="approved_by" placeholder="Enter name of MedTech approver" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                            <p class="text-xs text-gray-500 mt-2">The person who will approve this service report</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">
                                <i class="fas fa-user-cog mr-2 text-blue-500"></i> Service Engineer
                            </label>
                            <div class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50">
                                <div class="text-gray-700 font-medium" id="service-engineer-name">
                                    {{-- {{ Auth::user()->name ?? 'Current User' }} --}}
                                    {{$employee_details->emp_first_name}} {{$employee_details->emp_last_name}}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-building mr-1"></i> {{$employee_details->emp_position}}
                                </div>
                            </div>
                            <!-- Hidden fields for form submission -->
                            <input type="hidden" name="service_engineer" value="{{$employee_details->emp_first_name}} {{$employee_details->emp_last_name}}">
                            <input type="hidden" name="service_engineer_department" value="{{$employee_details->emp_deparment}}">
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div id="draft-status-indicator"></div>
                    
                    <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 sm:space-x-3">
                        <div class="flex space-x-2">
                            <button type="button" id="save-draft-btn" 
                                    class="px-4 py-2 border border-yellow-300 text-yellow-700 bg-yellow-50 rounded-lg font-medium hover:bg-yellow-100 hover:border-yellow-400 transition-all">
                                <i class="fas fa-save mr-2"></i>Save Draft
                            </button>
                            
                            <button type="button" id="clear-draft-btn" 
                                    class="px-4 py-2 border border-gray-300 text-gray-700 bg-gray-50 rounded-lg font-medium hover:bg-gray-100 transition-all hidden">
                                <i class="fas fa-times mr-2"></i>Clear Draft
                            </button>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button type="button" onclick="closeServiceModal()" 
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all">
                                Cancel
                            </button>
                                                        
                            <button type="submit" id="submit-service-btn" 
                                    class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-700 border border-transparent rounded-lg text-white font-medium hover:from-green-700 hover:to-emerald-800 focus:outline-none focus:ring-2 focus:ring-green-500 shadow-md transition-all">
                                <i class="fas fa-check-circle mr-2"></i>Complete Service
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
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