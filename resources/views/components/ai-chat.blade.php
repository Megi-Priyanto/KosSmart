{{-- resources/views/components/ai-chat.blade.php --}}

<div x-data="aiChat()" class="fixed bottom-6 right-6 z-[9999] font-sans">

    <!-- Chat Button -->
    <button @click="toggleChat"
        x-show="!isOpen"
        x-transition.scale.origin.bottom.right
        class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-[#d97706] to-[#b45309] text-white rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
        <span class="material-symbols-rounded text-3xl">smart_toy</span>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="absolute bottom-0 right-0 w-[350px] sm:w-[400px] h-[500px] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-100"
        style="display: none;">

        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 p-4 text-white flex justify-between items-center rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <span class="material-symbols-rounded text-amber-400">robot_2</span>
                </div>
                <div>
                    <h3 class="font-bold text-sm tracking-wide">KosSmart Assistant</h3>
                    <p class="text-xs text-gray-300 opacity-80 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Online
                    </p>
                </div>
            </div>
            <button @click="toggleChat" class="text-gray-300 hover:text-white transition-colors p-1 hover:bg-white/10 rounded-lg">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 p-4 overflow-y-auto bg-gray-50 flex flex-col gap-4 scroll-smooth" id="chat-messages" x-ref="messagesContainer">
            <!-- Welcome Message (Bot) -->
            <div class="flex flex-col gap-1 max-w-[85%] self-start">
                <div class="bg-white text-gray-800 p-3 rounded-2xl rounded-tl-sm shadow-sm text-sm leading-relaxed border border-gray-100">
                    Halo! 👋 Saya asisten virtual KosSmart. Ada yang bisa saya bantu untuk mencari kos idamanmu?
                </div>
                <span class="text-[10px] text-gray-400 ml-1" x-text="currentTime()"></span>
            </div>

            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex flex-col gap-1 max-w-[85%]" :class="msg.role === 'user' ? 'self-end' : 'self-start'">
                    <!-- Chat Bubble -->
                    <div class="p-3 rounded-2xl text-sm leading-relaxed shadow-sm break-words whitespace-pre-line"
                        :class="msg.role === 'user' 
                            ? 'bg-amber-600 text-white rounded-tr-sm' 
                            : 'bg-white text-gray-800 rounded-tl-sm border border-gray-100'"
                        x-html="formatMessage(msg.content)">
                    </div>
                    <!-- Time -->
                    <span class="text-[10px] text-gray-400"
                        :class="msg.role === 'user' ? 'mr-1 text-right' : 'ml-1'"
                        x-text="msg.time">
                    </span>
                </div>
            </template>

            <!-- Loading Indicator -->
            <div x-show="isLoading" class="flex flex-col gap-1 max-w-[85%] self-start" style="display: none;">
                <div class="bg-white p-4 rounded-2xl rounded-tl-sm shadow-sm border border-gray-100 flex items-center gap-2">
                    <div class="flex gap-1">
                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                    <span class="text-xs text-gray-500 ml-1 opacity-70">Sedang mengetik...</span>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-100">
            <form @submit.prevent="sendMessage" class="flex items-end gap-2 relative">
                <textarea
                    x-model="newMessage"
                    @keydown.enter.prevent="handleEnter"
                    placeholder="Tulis pesan..."
                    class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 resize-none overflow-hidden transition-all placeholder:text-gray-400"
                    rows="1"
                    x-ref="chatInput"
                    @input="resizeTextarea"></textarea>
                <button type="submit"
                    :disabled="!newMessage.trim() || isLoading"
                    class="absolute right-2 bottom-2 w-8 h-8 flex items-center justify-center rounded-lg bg-amber-600 text-white disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-amber-700 transition-colors">
                    <span class="material-symbols-rounded text-lg" :class="{'opacity-0': isLoading}">send</span>
                    <span x-show="isLoading" class="material-symbols-rounded text-lg absolute animate-spin">sync</span>
                </button>
            </form>
            <div class="text-center mt-2">
                <span class="text-[9px] text-gray-400 uppercase tracking-widest font-semibold flex items-center justify-center gap-1">
                    Powered by <span class="text-amber-500">KosSmart AI</span>
                </span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('aiChat', () => ({
            isOpen: false,
            isLoading: false,
            newMessage: '',
            messages: [],

            toggleChat() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    setTimeout(() => {
                        this.$refs.chatInput.focus();
                        this.scrollToBottom();
                    }, 100);
                }
            },

            currentTime() {
                const now = new Date();
                return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
            },

            handleEnter(e) {
                if (e.shiftKey) {
                    // Biarkan baris baru
                } else {
                    this.sendMessage();
                }
            },

            resizeTextarea() {
                const el = this.$refs.chatInput;
                el.style.height = 'auto';
                el.style.height = Math.min(el.scrollHeight, 100) + 'px'; // Max height
            },

            formatMessage(text) {
                // Sederhana: ubah \n jadi <br>, bold markdown jadi <strong>
                return text
                    .replace(/\n/g, '<br>')
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\*(.*?)\*/g, '<em>$1</em>');
            },

            scrollToBottom() {
                setTimeout(() => {
                    const container = this.$refs.messagesContainer;
                    container.scrollTop = container.scrollHeight;
                }, 50);
            },

            async sendMessage() {
                const messageText = this.newMessage.trim();
                if (!messageText || this.isLoading) return;

                // Tambahkan pesan user
                this.messages.push({
                    role: 'user',
                    content: messageText,
                    time: this.currentTime()
                });

                this.newMessage = '';
                this.$refs.chatInput.style.height = 'auto';
                this.isLoading = true;
                this.scrollToBottom();

                try {
                    // Request ke endpoint API AI
                    const response = await fetch('{{ route("public.kos.chat") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            message: messageText
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.messages.push({
                            role: 'bot',
                            content: data.reply,
                            time: this.currentTime()
                        });
                    } else {
                        this.messages.push({
                            role: 'bot',
                            content: data.reply || "Maaf, terjadi kesalahan saat menghubungi server.",
                            time: this.currentTime()
                        });
                    }
                } catch (error) {
                    this.messages.push({
                        role: 'bot',
                        content: "Koneksi terputus. Gagal mengirim pesan.",
                        time: this.currentTime()
                    });
                } finally {
                    this.isLoading = false;
                    this.scrollToBottom();
                    setTimeout(() => this.$refs.chatInput.focus(), 50);
                }
            }
        }));
    });
</script>
@endpush