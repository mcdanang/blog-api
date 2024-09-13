<?php

namespace App\Console\Commands;

use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;

class SendWelcomeEmailCommand extends Command
{
    protected $signature = 'email:welcome {user_id}';
    protected $description = 'Send a welcome email to a user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::findOrFail($userId);

        SendWelcomeEmail::dispatch($user);

        $this->info("Welcome email job dispatched for user {$user->email}");
    }
}
