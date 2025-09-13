<?php

namespace App\Jobs;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\JobOpening;
use App\Models\Talent;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Laravel\Prompts\Progress;

use function Laravel\Prompts\progress;

class FeederAll implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $progress = new Progress("Feeding User..", 6);
        User::feedAll();
        $progress->progress = 1;
        $progress->label("Feeding Talent..");
        Talent::feedAll();
        $progress->progress = 2;
        $progress->label("Feeding Company..");
        Company::feedAll();
        $progress->progress = 3;
        $progress->label("Feeding Candidate..");
        Candidate::feedAll();
        $progress->progress = 4;
        $progress->label("Feeding Job Opening..");
        JobOpening::feedAll();
        $progress->progress = 5;
    }
}
