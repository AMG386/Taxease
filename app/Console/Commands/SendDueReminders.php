<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use App\Notifications\DueReturnNotification;
use App\Models\User;

class SendDueReminders extends Command
{
    protected $signature = 'taxease:send-due-reminders';
    protected $description = 'Send due reminders to users for upcoming deadlines';

    public function handle(): int
    {
        $now = now();
        $due = Reminder::where('is_sent', false)->where('due_at', '<=', $now)->get();

        foreach ($due as $rem) {
            $user = User::find($rem->user_id);
            if (!$user) continue;
            $user->notify(new DueReturnNotification($rem->title, $rem->message ?? ''));
            $rem->is_sent = true;
            $rem->save();
            $this->info("Sent reminder #{$rem->id} to user {$user->id}");
        }
        return self::SUCCESS;
    }
}
