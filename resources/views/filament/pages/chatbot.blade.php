<x-filament-panels::page>
    <div
        x-data="chatApp({
            // set ke null untuk dummy reply; isi dengan route kalo udah ada backend, contoh: 
            {{-- '{{ route('chatbot.ask') }}' --}}
            endpoint: null,
            persist: true, // simpan chat ke localStorage biar ga ke-reset
        })"
        x-init="init()"
        class="space-y-4"
    >

        <!-- Header kecil -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">Lisa</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Ada yang bisa kubantu? 😉</p>
            </div>

            <div class="flex items-center gap-2">
                <button
                    class="px-3 py-2 text-sm rounded-xl border hover:bg-gray-50 dark:hover:bg-gray-800"
                    @click="toggleCompact()"
                    x-text="compact ? 'Mode Normal' : 'Mode Compact'"
                ></button>
                <button
                    class="px-3 py-2 text-sm rounded-xl border hover:bg-gray-50 dark:hover:bg-gray-800"
                    @click="clearChat()"
                    title="Hapus semua pesan"
                >Clear</button>
            </div>
        </div>

        <!-- Chat Box -->
        <div
            id="chat-box"
            class="h-[65vh] overflow-y-auto bg-white dark:bg-gray-900 rounded-2xl p-4 shadow border"
            :class="compact ? 'p-3' : 'p-4'"
            wire:ignore
        >
            <template x-if="messages.length === 0">
                <div class="text-center text-gray-500 dark:text-gray-400 mt-10">
                    Belum ada pesan, Yuk mulai tanya ke Lisa
                </div>
            </template>

            <template x-for="m in messages" :key="m.id">
                <div class="mb-3 flex" :class="m.sender === 'user' ? 'justify-end' : 'justify-start'">
                    <div class="flex items-start gap-2 max-w-[80%]">
                        <template x-if="m.sender === 'Lisa'">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 grid place-items-center">🤖</div>
                        </template>
                        <div
                            class="p-3 rounded-2xl"
                            :class="m.sender === 'user'
                                ? 'bg-blue-600 text-white'
                                : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100'"
                        >
                            <div class="text-xs opacity-70 mb-1" x-text="m.sender === 'user' ? 'You' : 'Lisa'"></div>
                            <div class="whitespace-pre-wrap leading-relaxed" x-text="m.text"></div>

                            <div class="flex items-center gap-2 mt-2 text-[11px] opacity-70">
                                <span x-text="formatTime(m.at)"></span>
                                <button
                                    class="underline"
                                    @click="copy(m.text)"
                                    title="Copy"
                                >Copy</button>
                            </div>
                        </div>
                        <template x-if="m.sender === 'user'">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 grid place-items-center">🧑‍💻</div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- Typing indicator -->
            <template x-if="loading">
                <div class="mb-2 flex justify-start">
                    <div class="flex items-center gap-2 max-w-[80%]">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 grid place-items-center">🤖</div>
                        <div class="px-3 py-2 rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                            <span class="animate-pulse">Lisa is typing…</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input Area -->
        <div class="flex items-end gap-2">
            <textarea
                x-model="message"
                @keydown.enter.prevent="sendMessage()"
                @input="autoResize($event)"
                rows="1"
                placeholder="Tulis pesan & Enter buat kirim…"
                class="flex-1 rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white resize-none p-3"
            ></textarea>

            <button
                class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                :disabled="loading || message.trim()===''"
                @click="sendMessage()"
            >
                Kirim
            </button>
        </div>
    </div>

    <script>
        function chatApp({ endpoint = null, persist = true } = {}) {
            return {
                endpoint,
                persist,
                messages: [],
                message: '',
                loading: false,
                compact: false,

                init() {
                    // seed message
                    const saved = this.persist ? JSON.parse(localStorage.getItem('chatbot_messages') ?? '[]') : []
                    this.messages = saved.length ? saved : [
                        { id: Date.now(), sender: 'Lisa', text: 'Halo! 👋 Ada yang bisa ku bantu?', at: new Date().toISOString() }
                    ]
                    this.$nextTick(() => this.scrollToBottom())
                },

                save() {
                    if (!this.persist) return
                    localStorage.setItem('chatbot_messages', JSON.stringify(this.messages))
                },

                clearChat() {
                    this.messages = []
                    this.save()
                },

                toggleCompact() {
                    this.compact = !this.compact
                },

                formatTime(iso) {
                    try {
                        const d = new Date(iso)
                        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                    } catch { return '' }
                },

                copy(text) {
                    navigator.clipboard?.writeText(text)
                },

                autoResize(e) {
                    e.target.style.height = 'auto'
                    e.target.style.height = (e.target.scrollHeight) + 'px'
                },

                push(sender, text) {
                    this.messages.push({
                        id: Date.now() + Math.random(),
                        sender,
                        text,
                        at: new Date().toISOString(),
                    })
                    this.save()
                    this.$nextTick(() => this.scrollToBottom())
                },

                scrollToBottom() {
                    const box = document.getElementById('chat-box')
                    if (!box) return
                    box.scrollTop = box.scrollHeight
                },

                async sendMessage() {
                    const text = this.message.trim()
                    if (!text) return

                    // tampilkan pesan user
                    this.push('user', text)
                    this.message = ''
                    this.loading = true

                    // dummy reply kalau endpoint null
                    if (!this.endpoint) {
                        await new Promise(r => setTimeout(r, 500))
                        this.push('Lisa', `kamu nanya: “${text}”. (Di sini nanti muncul jawaban dari backend kamu)`)
                        this.loading = false
                        return
                    }

                    // contoh panggil backend JSON (optional; aktifin kalau endpoint udah ada)
                    try {
                        const res = await fetch(this.endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                // kalau route web, aktifkan CSRF:
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ message: text }),
                        })
                        if (!res.ok) throw new Error('Request failed')
                        const data = await res.json()
                        this.push('Lisa', data.reply ?? '(no reply)')
                    } catch (e) {
                        this.push('Lisa', '⚠️ Gagal ambil jawaban dari server.')
                        console.error(e)
                    } finally {
                        this.loading = false
                    }
                },
            }
        }
    </script>
</x-filament-panels::page>
