<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <!-- Icon -->
            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-yellow-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h3 id="confirmTitle" class="text-xl font-bold text-gray-800 text-center mb-2">
                Konfirmasi
            </h3>
            
            <!-- Message -->
            <p id="confirmMessage" class="text-gray-600 text-center mb-6">
                Apakah Anda yakin?
            </p>
            
            <!-- Buttons -->
            <div class="flex gap-3">
                <button id="confirmCancel" 
                        class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition-colors">
                    Batal
                </button>
                <button id="confirmOk" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white rounded-lg font-semibold transition-all shadow-lg">
                    Oke
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Alert Modal -->
<div id="alertModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]" style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <!-- Icon (dynamic based on type) -->
            <div id="alertIcon" class="flex items-center justify-center w-16 h-16 mx-auto rounded-full mb-4">
                <!-- Icon will be inserted here -->
            </div>
            
            <!-- Title -->
            <h3 id="alertTitle" class="text-xl font-bold text-gray-800 text-center mb-2">
                Pemberitahuan
            </h3>
            
            <!-- Message -->
            <p id="alertMessage" class="text-gray-600 text-center mb-6">
                Pesan alert
            </p>
            
            <!-- Button -->
            <button id="alertOk" 
                    class="w-full px-4 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white rounded-lg font-semibold transition-all shadow-lg">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    // Custom Confirm Dialog
    window.customConfirm = function(message, title = 'Konfirmasi') {
        return new Promise((resolve) => {
            const modal = document.getElementById('confirmModal');
            const titleEl = document.getElementById('confirmTitle');
            const messageEl = document.getElementById('confirmMessage');
            const okBtn = document.getElementById('confirmOk');
            const cancelBtn = document.getElementById('confirmCancel');
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            modal.style.display = 'flex';
            
            const handleOk = () => {
                modal.style.display = 'none';
                cleanup();
                resolve(true);
            };
            
            const handleCancel = () => {
                modal.style.display = 'none';
                cleanup();
                resolve(false);
            };
            
            const cleanup = () => {
                okBtn.removeEventListener('click', handleOk);
                cancelBtn.removeEventListener('click', handleCancel);
            };
            
            okBtn.addEventListener('click', handleOk);
            cancelBtn.addEventListener('click', handleCancel);
        });
    };

    // Custom Alert Dialog
    window.customAlert = function(message, title = 'Pemberitahuan', type = 'info') {
        return new Promise((resolve) => {
            const modal = document.getElementById('alertModal');
            const titleEl = document.getElementById('alertTitle');
            const messageEl = document.getElementById('alertMessage');
            const iconEl = document.getElementById('alertIcon');
            const okBtn = document.getElementById('alertOk');
            
            // Set icon based on type
            let iconHTML = '';
            let iconBg = '';
            
            switch(type) {
                case 'success':
                    iconBg = 'bg-green-100';
                    iconHTML = `<svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>`;
                    break;
                case 'error':
                    iconBg = 'bg-red-100';
                    iconHTML = `<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>`;
                    break;
                case 'warning':
                    iconBg = 'bg-yellow-100';
                    iconHTML = `<svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>`;
                    break;
                default:
                    iconBg = 'bg-blue-100';
                    iconHTML = `<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>`;
            }
            
            iconEl.className = `flex items-center justify-center w-16 h-16 mx-auto rounded-full mb-4 ${iconBg}`;
            iconEl.innerHTML = iconHTML;
            titleEl.textContent = title;
            messageEl.textContent = message;
            modal.style.display = 'flex';
            
            const handleOk = () => {
                modal.style.display = 'none';
                okBtn.removeEventListener('click', handleOk);
                resolve(true);
            };
            
            okBtn.addEventListener('click', handleOk);
        });
    };

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const confirmModal = document.getElementById('confirmModal');
            const alertModal = document.getElementById('alertModal');
            if (confirmModal && confirmModal.style.display === 'flex') {
                document.getElementById('confirmCancel').click();
            }
            if (alertModal && alertModal.style.display === 'flex') {
                document.getElementById('alertOk').click();
            }
        }
    });
</script>