
<!-- MODAL: Create Account -->
<div id="accountModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-xl font-bold">Create Login Account</h3>
            <button id="closeAccountModal" class="text-gray-400"><i class="fas fa-times"></i></button>
        </div>
        <form id="accountForm" class="p-6 space-y-4">
            <input type="hidden" id="accEmpId">
            <div><label class="text-sm font-medium">Email (Login)</label><input type="email" id="accEmail" required class="w-full border rounded-lg px-3 py-2 mt-1"></div>
            <div><label class="text-sm font-medium">Password</label><input type="password" id="accPassword" required class="w-full border rounded-lg px-3 py-2"></div>
            <div><label class="text-sm font-medium">Confirm Password</label><input type="password" id="accConfirm" required class="w-full border rounded-lg px-3 py-2"></div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-medium">Create Account & Enable Login</button>
        </form>
    </div>
</div>

<!-- MODAL: Edit Employee -->
<div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between">
            <h3 class="text-xl font-bold">Edit Employee</h3>
            <button id="closeEditModal" class="text-gray-400"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" class="p-6 space-y-5">
            <input type="hidden" id="editId">
            <div class="flex gap-4">
                <div class="flex-1"><label>Profile Pic</label><input type="file" id="editProfile" accept="image/*" class="text-sm mt-1"></div>
                <div class="flex-1"><label>Signature</label><input type="file" id="editSig" accept="image/*" class="text-sm mt-1"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label>First Name *</label><input type="text" id="editFirstName" required class="w-full border rounded px-3 py-2"></div>
                <div><label>Last Name *</label><input type="text" id="editLastName" required class="w-full border rounded px-3 py-2"></div>
            </div>
            <div><label>Phone</label><input type="text" id="editPhone" class="w-full border rounded px-3 py-2"></div>
            <div><label>Viber</label><input type="text" id="editViber" class="w-full border rounded px-3 py-2"></div>
            <div><label>Social URL</label><input type="text" id="editSocial" class="w-full border rounded px-3 py-2"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label>Position</label><input type="text" id="editPosition" class="w-full border rounded px-3 py-2"></div>
                <div><label>Department</label><select id="editDept" class="w-full border rounded px-3 py-2"><option>Tech Engineering</option><option>Product Specialist</option><option>Information Technology</option><option>Marketing</option><option>Sales</option><option>Human Resource</option></select></div>
            </div>
            <div><label>Status</label><select id="editStatus" class="w-full border rounded px-3 py-2"><option value="active">Active</option><option value="on_leave">On Leave</option><option value="inactive">Inactive</option></select></div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg">Update Details</button>
        </form>
    </div>
</div>

<!-- MODAL: View Profile  -->
<div id="viewModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between">
            <h3 class="text-xl font-bold">Employee Profile</h3>
            <button onclick="closeViewModal()" class="text-gray-400"><i class="fas fa-times"></i></button>
        </div>
        <div id="viewContent" class="p-6"></div>
    </div>
</div>