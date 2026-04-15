<div id="editProfileOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900">Edit Account Info</h2>
            <button onclick="toggleEditProfile(false)" class="p-2 hover:bg-gray-100 rounded-full transition">
                <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
            </button>
        </div>
        
        <form id="editProfileForm" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">First Name</label>
                    <input type="text" name="first_name" id="editFirstName" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[var(--ph-blue)] outline-none transition" required>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-500 uppercase">Last Name</label>
                    <input type="text" name="last_name" id="editLastName" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[var(--ph-blue)] outline-none transition" required>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Contact No.</label>
                <input type="tel" name="contact_no" id="editContact" placeholder="09xxxxxxxxx" 
                       pattern="[0-9]{11}" title="Please enter 11-digit mobile number"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[var(--ph-blue)] outline-none transition">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-500 uppercase">Email Address</label>
                <input type="email" name="email" id="editEmail" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed" readonly>
                <p class="text-[10px] text-gray-400">Email cannot be changed. Contact admin for assistance.</p>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="toggleEditProfile(false)" class="flex-1 px-6 py-3 border border-gray-200 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-[var(--ph-blue-dark)] text-white rounded-xl text-sm font-semibold hover:opacity-90 transition shadow-lg shadow-blue-900/20">Save Changes</button>
            </div>
        </form>
    </div>
</div>