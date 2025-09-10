<x-filament-panels::page>
    <div x-data="chatApp(
        '{{ $user_id }}'
    )" class="h-[calc(100vh-12rem)] flex gap-4">
        <div class="transition-all duration-300 ease-in-out" :class="isCompact ? 'w-24' : 'w-1/4'">
            <div class="max-h-screen overflow-y-auto h-full flex flex-col p-2 rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold dark:text-gray-100" x-show="!isCompact">{{$user_id}}</h2>
                    <button @click="isCompact = !isCompact"
                        class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-400">
                        <svg x-show="!isCompact" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 16.5L12 10.5L5.25 16.5" />
                        </svg>
                        <svg x-show="isCompact" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 5.25v13.5m0 0l-5.25-5.25M12 18.75l5.25-5.25" />
                        </svg>
                    </button>
                </div>
                <button @click="newChat()"
                    class="px-4 py-2 mb-4 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                    <span x-show="!isCompact">New Chat</span>
                    <svg x-show="isCompact" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
                <div class="flex-1 overflow-y-auto space-y-2">
                    <template x-for="chat in chatList" :key="chat.session_id">
                        <div class="cursor-pointer py-2 px-3 rounded-md transition-colors duration-200"
                            @click="loadChat(user_id, chat.session_id)"
                            :class="{
                                 'bg-gray-700 text-gray-100': chat.session_id === activeChatId,
                                 'hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-300': chat.id !== activeChatId }">
                            <div x-show="!isCompact" class="font-medium text-sm" x-text="chat.title"></div>
                            {{-- <div x-show="!isCompact" class="text-xs truncate opacity-75" x-text="chat.session_id"></div> --}}
                            <div x-show="isCompact" class="text-center" x-text="chat.title.charAt(0)"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col rounded-lg bg-white shadow dark:bg-gray-800">
            <div class="flex-1 overflow-y-auto p-4 space-y-4" x-ref="messagesContainer">
                <template x-if="messages.length === 0">
                    <div class="text-center text-gray-400 italic mt-8">Start a new conversation with LISA.</div>
                </template>
                <template x-for="message, key in messages" :key="key">
                    <div class="flex" :class="[
                        message.role === 'user' ? 'justify-end' : 'justify-start',
                        ['system','tool'].includes(message.role) ? 'hidden' : '',
                        message.content === null ? 'hidden' : '',
                        ]">
                        <div class="max-w-xl px-4 py-2 rounded-lg"
                            :class="message.role === 'user' ?
                            'bg-primary-500 text-gray-900 dark:bg-primary-500 dark:text-gray-200' :
                                'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-200'">
                            <p x-html="MarkedParse(message.content)"></p>
                            <div class="text-xs mt-1 opacity-75" x-text="'--:--'">--:--</div>
                        </div>

                    </div>
                </template>
            </div>

            <form @submit.prevent="sendMessage()" class="p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-2">
                    <input type="text" x-model="input" placeholder="Tulis pesan & Enter buat kirim..."
                        class="flex-1 bg-gray-100 text-gray-900 border-none rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none dark:bg-gray-700 dark:text-gray-100"
                        x-ref="input" />
                    <button type="submit"
                        class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
    <input type="hidden" id="CHAT_BASE_URL" name="CHAT_BASE_URL" value="{{$chat_base_url}}">
</x-filament-panels::page>
<script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>
<script>
    const ChatAPI = {
        baseUrl: document.getElementById('CHAT_BASE_URL').value,
        async GetSessionMessages(user_id, session_id) {
            return await fetch(this.baseUrl + "/api/session/messages" +
                `?user=${user_id}&session_id=${session_id}`)
        },
        GetSession(user_id) {
            return fetch(this.baseUrl + "/api/sessions" + "?user=" + user_id, {
                headers: {
                    'Accept': 'application/json' // Optional: if you expect a JSON response
                },
            })
        },
        async CreateSession(user_id, message, prompt) {
            return await fetch(this.baseUrl + "/api/sessions", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' // Optional: if you expect a JSON response
                },
                body: JSON.stringify({
                    "user": user_id,
                    "message": message,
                    "system_prompt": prompt
                })
            })
        },
        async PostChat(user_id, session_id, message) {
            return await fetch(this.baseUrl + "/api/chat", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    "user": user_id,
                    "message": message,
                    "session_id": session_id
                })
            })
        },
    }
</script>

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.data('chatApp', (user_id) => ({
            isCompact: false,
            user_id: user_id,
            input: '',
            _cacheMessages: [],
            messages: [], // Ini akan diisi dari API
            chatList: [], // Ini juga dari API
            activeChatId: null,

            // Inisialisasi data & event listeners
            init() {
                this.fetchChatList();
                this.$nextTick(() => {
                    this.$refs.input.focus();
                });

                // Event listener untuk auto-scroll setelah pesan terkirim
                // Lo bisa panggil fungsi ini manual setelah sukses fetch pesan
                // window.addEventListener('message-sent', () => {
                //     this.autoScroll();
                // });
            },
            MarkedParse(string) {
                return marked.parse(string)
            },

            // Fetch daftar chat dari API
            async fetchChatList(select = null) {
                try {
                    const response = await ChatAPI.GetSession(this.user_id).then(response =>
                        response.json())
                    this.chatList = response.sessions
                    console.log(this.chatList, response)
                    if (this.chatList.length > 0) {
                        this.loadChat(this.user_id, select); // Load chat pertama secara default
                    }
                } catch (error) {
                    console.error('Error fetching chat list:', error);

                }
            },

            // Fungsi untuk memulai chat baru
            async newChat() {
                try {
                    this.loadChat(this.user_id, null);
                } catch (error) {
                    console.error('Error creating new chat:', error);
                }
            },

            // Fungsi untuk memuat chat lama
            async loadChat(user_id, session_id = null) {
                this.activeChatId = session_id;
                if(session_id === null) {
                    this.messages = []
                    return
                }
                try {
                    const response = await ChatAPI.GetSessionMessages(user_id, session_id)
                        .then(response => response.json());
                    this._cacheMessages[session_id] = response.messages;
                    this.messages = []
                    this.messages = this._cacheMessages[session_id]
                    this.$nextTick(() => this.autoScroll());
                } catch (error) {
                    console.error('Error loading chat:', error);
                }
            },


            // Fungsi untuk mengirim pesan
            async sendMessage() {
                if (this.input.trim() === '') return;

                const tempMessage = {
                    role: 'user',
                    content: this.input,
                    time: new Date().toLocaleTimeString('id-ID'),
                    id: 'temp_' + Date.now()
                };
                this.messages.push(tempMessage);
                this.$nextTick(() => this.autoScroll());

                const messageToSend = this.input;
                this.input = '';

                try {
                    const response = await ChatAPI.PostChat(this.user_id, this.activeChatId, messageToSend)
                        .then(response => response.json())

                    if (response.answer) {
                        if(response.new_session_id) {
                            this.fetchChatList(response.new_session_id)
                            return
                        }
                        const newMessage = response.answer
                        this.messages.push({
                            role: "assistant",
                            content: newMessage,
                            time: '--:--',
                            id: 'temp_' + Date.now()
                        });
                        this.$nextTick(() => this.autoScroll());
                    } else {
                        console.error('Failed to send message.');
                        this.messages = this.messages.filter(msg => msg.id !== tempMessage.id);
                        // Tampilkan pesan error ke user
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    this.messages = this.messages.filter(msg => msg.id !== tempMessage.id);
                    // Tampilkan pesan error ke user
                }
            },

            // Fungsi untuk auto-scroll
            autoScroll() {
                this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
            }
        }));


    });
</script>
