<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Chatbot extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static string $view = 'filament.pages.chatbot';

    public ?string $activeChatId = 'ac82nc023kl'; // Inisialisasi chat ID yang aktif
    public string $input = '';
    public array $messages = [];
    public array $chatList = [];
    public string $user_id = "01@user";
    public string $chat_base_url = "";

    public function mount(): void
    {
        $this->user_id = Auth::user()->chat_user_id;
        $this->chat_base_url = config('chatbot.host');
    }

}
