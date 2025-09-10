<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Livewire\Attributes\On;

class Chatbot extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static string $view = 'filament.pages.chatbot';

    public ?string $activeChatId = 'ac82nc023kl'; // Inisialisasi chat ID yang aktif
    public string $input = '';
    public array $messages = [];
    public array $chatList = [];

    // Data simulasi untuk chat history. Nantinya lo bisa ambil dari database.
    private array $simulatedChatHistory = [
        'ac82nc023kl' => [
            'title' => 'Initial Chat with LISA',
            'snippet' => 'Halo! 👋 Ada yang bisa ku bantu?',
            'messages' => [
                ['sender' => 'assistant', 'text' => 'Halo! 👋 Ada yang bisa ku bantu?', 'time' => '08:06 PM'],
            ],
        ],
        'cvi10msd' => [
            'title' => 'Follow-up with Talent',
            'snippet' => 'Selamat datang kembali.',
            'messages' => [
                ['sender' => 'assistant', 'text' => 'Selamat datang kembali.', 'time' => '08:00 PM'],
            ],
        ],
    ];

    public function mount(): void
    {
        // Isi daftar chat di sidebar
        $this->chatList = collect($this->simulatedChatHistory)
            ->map(fn ($data, $id) => [
                'id' => $id,
                'title' => $data['title'],
                'snippet' => $data['snippet'],
            ])->all();

        // Load chat awal
        $this->loadChat($this->activeChatId);
    }

    // Method untuk mengirim pesan
    public function sendMessage(): void
    {
        $text = trim($this->input);
        if ($text === '') {
            return;
        }

        // Tambahkan pesan user ke array
        $this->messages[] = [
            'sender' => 'user',
            'text' => $text,
            'time' => date('h:i A'),
        ];

        $this->input = '';

        // Dispatch event untuk auto-scroll setelah pesan terkirim
        $this->dispatch('message-sent');

        // Ini bagian lo nanti bisa integrasi ke API LISA
        // Contoh:
        // $response = Http::post('https://api.lisa.com/chat', ['message' => $text]);
        // $this->messages[] = ['sender' => 'assistant', 'text' => $response['text'], 'time' => date('h:i A')];

        // Simulasi balasan asisten
        $this->messages[] = [
            'sender' => 'assistant',
            'text' => 'Terima kasih, sedang saya proses...',
            'time' => date('h:i A'),
        ];
    }

    // Method untuk mengganti chat session
    #[On('load-chat')]
    public function loadChat($chatId): void
    {
        $this->activeChatId = $chatId;
        $this->messages = $this->simulatedChatHistory[$chatId]['messages'] ?? [];
    }

    // Method untuk membuat chat baru
    public function newChat(): void
    {
        $newId = uniqid();
        $this->activeChatId = $newId;
        $this->messages = [];
        $this->chatList[] = [
            'id' => $newId,
            'title' => 'New Chat',
            'snippet' => 'Start a new conversation.',
        ];
    }
     public function checkNewMessages(): void
    {
        // --- SIMULASI LOGIC BARU ---
        //
        // Ini adalah contoh sederhana untuk menguji fitur polling.
        // Di aplikasi sebenarnya, ini adalah tempat lo
        // melakukan panggilan ke database atau API untuk cek
        // apakah ada pesan baru untuk 'activeChatId' saat ini.
        //
        // Contoh:
        // $latestMessages = ChatMessage::where('chat_id', $this->activeChatId)
        //                               ->where('created_at', '>', $this->latestTimestamp)
        //                               ->get();
        // $this->messages = array_merge($this->messages, $latestMessages->toArray());

        // Contoh simulasi: tambahkan pesan asisten setiap 5 detik.
        if (count($this->messages) % 2 !== 0 && now()->format('s') % 5 === 0) {
            $this->messages[] = [
                'sender' => 'assistant',
                'text' => 'LISA di sini. Apakah ada lagi yang bisa saya bantu, Jun?',
                'time' => date('h:i A'),
            ];

            // Dispatch event untuk auto-scroll
            $this->dispatch('message-sent');
        }
    }
}
