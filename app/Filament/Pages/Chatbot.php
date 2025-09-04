<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class Chatbot extends Page
{
    // use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.chatbot';
    public array $chat_dummy_data = [];
    public array $list_dummy_data = [];

    public function mount(): void
    {
        $this->list_dummy_data = [
                "id" => Auth::user()->id,
                "sessions" => [
                    "ac82nc023kl",
                    "cvi10msd",
                ],
            ];
    }

    public function fetchChatDataDummy(string $sessionId): void
    {
          $data = [
                "ac82nc023kl" => [
                    [
                        "actor" => "system",
                        "message" => "Act as HR Assistant",
                    ],
                    [
                        "actor" => "user",
                        "message" => "Tolong carikan kandidat untuk perusahaan A.",
                    ],
                    [
                        "actor" => "Lisa",
                        "message" => "Baik, saya akan mencari kandidat sesuai kebutuhan perusahaan A. Apakah ada kriteria khusus?",
                    ],
                    [
                        "actor" => "user",
                        "message" => "Ya, harus punya pengalaman minimal 2 tahun.",
                    ],
                    [
                        "actor" => "Lisa",
                        "message" => "Baik, saya catat. Kandidat untuk perusahaan A harus berpengalaman minimal 2 tahun.",
                    ],
                ],
                "cvi10msd" => [
                    [
                        "actor" => "system",
                        "message" => "Act as HR Assistant",
                    ],
                    [
                        "actor" => "user",
                        "message" => "Sekarang carikan kandidat untuk perusahaan B dan C.",
                    ],
                    [
                        "actor" => "Lisa",
                        "message" => "Untuk perusahaan B, apakah ada preferensi khusus?",
                    ],
                    [
                        "actor" => "user",
                        "message" => "B perusahaan butuh kandidat fresh graduate.",
                    ],
                    [
                        "actor" => "Lisa",
                        "message" => "Dicatat. Untuk perusahaan C, bagaimana kriterianya?",
                    ],
                    [
                        "actor" => "user",
                        "message" => "C harus punya kemampuan manajerial.",
                    ],
                    [
                        "actor" => "Lisa",
                        "message" => "Oke, saya sudah catat kriteria kandidat untuk perusahaan B dan C.",
                    ],
                ],
            ];

            $this->chat_dummy_data = $data[$sessionId];
    }


    
}
