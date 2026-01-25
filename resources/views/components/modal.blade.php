<!-- Global Alpine Modal Component -->
<div x-data x-show="$store.modal.show" 
     x-cloak
     @keydown.escape.window="$store.modal.close()"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">
    
    <div @click.away="$store.modal.close()"
         x-show="$store.modal.show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full border border-slate-700 overflow-hidden">
        
        <div class="p-6">
            <!-- Icon -->
            <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full mb-4"
                 :class="{
                     'bg-red-500/20': $store.modal.type === 'delete',
                     'bg-blue-500/20': $store.modal.type === 'info',
                     'bg-yellow-500/20': $store.modal.type === 'warning',
                     'bg-green-500/20': $store.modal.type === 'success'
                 }">
                
                <!-- Delete Icon -->
                <template x-if="$store.modal.type === 'delete'">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </template>
                
                <!-- Info Icon -->
                <template x-if="$store.modal.type === 'info'">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                
                <!-- Warning Icon -->
                <template x-if="$store.modal.type === 'warning'">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </template>
                
                <!-- Success Icon -->
                <template x-if="$store.modal.type === 'success'">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-slate-100 text-center mb-2" x-text="$store.modal.title"></h3>
            
            <!-- Message -->
            <p class="text-slate-300 text-center mb-6 text-sm" x-text="$store.modal.message"></p>
            
            <!-- Buttons -->
            <div class="flex gap-3">
                <!-- Cancel Button (only for confirm/delete modals) -->
                <template x-if="$store.modal.type === 'delete' || $store.modal.showCancel">
                    <button @click="$store.modal.close()" 
                            class="flex-1 px-4 py-3 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded-lg font-semibold transition-colors">
                        Batal
                    </button>
                </template>
                
                <!-- Confirm Button -->
                <button @click="$store.modal.confirm()" 
                        class="px-4 py-3 rounded-lg font-semibold transition-all shadow-lg"
                        :class="{
                            'flex-1 bg-red-600 hover:bg-red-700 text-white': $store.modal.type === 'delete',
                            'w-full bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white': $store.modal.type !== 'delete'
                        }"
                        x-text="$store.modal.confirmText">
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add this to your CSS (in layout or separate file) -->
<style>
    [x-cloak] { 
        display: none !important; 
    }
</style>