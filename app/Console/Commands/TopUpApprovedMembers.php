<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TopUpNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TopUpApprovedMembers extends Command
{
    protected $signature = 'contributions:topup';
    protected $description = 'Top up approved members with contributions every second for testing';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $approvedUsers = User::whereHas('profile', function($query) {
            $query->where('approval_status', 'approved');
        })->get();

        $totalUsers = $approvedUsers->count();

        foreach ($approvedUsers as $index => $user) {
            // Debugging output
            $this->info("Topping up for user: " . $user->id);

            // Add contribution with a randomly generated code
            $user->contributions()->create([
                'amount' => 300,
                'description' => 'Monthly Contribution',
                'date' => Carbon::now(),
                'code' => Str::random(10), // Generate a random 10-character code
            ]);

            // Send email
            Mail::to($user->email)->send(new TopUpNotification($user, 300));

            // Sleep for 1 second
            sleep(1);

            // Break the loop if you want to test with only a few users
            // if ($index >= 5) break; // Uncomment to limit to first 5 users
        }

        $this->info('Top-up process completed successfully.');
        return 0;
    }
}
