<?php

namespace App\Jobs;

use Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StartChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $session_id,
        public int $user_id,
        public ?string $message,
        public ?json $response
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(StartChat $startChat): void
    {
        $session_id = 'x';
        $user_id = Auth::id();
        $message = $this->messageTemplate ?? 'Hi :name, kita lihat profil kamu cocok nih. Boleh chat sebentar?';
        $message = str_replace(':name', $talent->name ?? 'there', $message);
        $response = $startChat->response;

        $startChat->startOrContinue($session_id, $user_id, $message);
    }
}
