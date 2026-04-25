<?php

namespace App\Jobs;

use App\Mail\AdminBroadcastMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendAdminBroadcastJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $audience,
        public string $subjectLine,
        public string $bodyHtml,
        public array $userIds = [],
    ) {
    }

    public function handle(): void
    {
        $query = User::query()->whereNotNull('email');

        if ($this->audience === 'writers') {
            $query->whereHas('stories');
        } elseif ($this->audience === 'selected' && ! empty($this->userIds)) {
            $query->whereIn('id', $this->userIds);
        }

        $query->chunkById(200, function ($users) {
            foreach ($users as $user) {
                Mail::to($user->email)->queue(new AdminBroadcastMail($this->subjectLine, $this->bodyHtml));
            }
        });
    }
}
