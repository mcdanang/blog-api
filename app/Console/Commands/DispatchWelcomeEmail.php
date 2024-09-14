<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Console\Command;

class DispatchWelcomeEmail extends Command
{
    protected $signature = 'email:send-welcome {user_id}';
    protected $description = 'Send a welcome email to a user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if ($user) {
            SendWelcomeEmail::dispatch($user);
            $this->info('Welcome email dispatched successfully.');
        } else {
            $this->error('User not found.');
        }
    }
}
