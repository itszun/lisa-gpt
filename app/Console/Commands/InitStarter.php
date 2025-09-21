<?php

namespace App\Console\Commands;

use App\Jobs\FeederAll;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitStarter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter:init {--seed} {--feed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init All dummy and configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running migrations...');
        Artisan::call('migrate:fresh', ['--force' => true]);
        $this->info(Artisan::output());

        // 2. Jalankan Seeder (jika opsi --seed diberikan)
        if ($this->option('seed')) {
            $this->info('Seeding database...');
            Artisan::call('db:seed', ['--force' => true]);
            $this->info(Artisan::output());
        }

        if ($this->option('feed')) {
            $this->info('Feeding vector database...');
            try {
                FeederAll::dispatchSync(1);
            } catch (\Throwable $th) {
                $this->error("Failed: ".$th->getMessage());
            }
        }
        // 3. Jalankan Feeder Vector Database (contoh)

        $this->info('Starter setup complete!');

        Artisan::call('shield:install admin');
        Artisan::call('shield:install talent');
        Artisan::call('shield:install company');

    }
}
