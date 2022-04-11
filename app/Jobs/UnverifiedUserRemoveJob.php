<?php

namespace App\Jobs;

use App\Models\User;
use App\Support\Thumb;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UnverifiedUserRemoveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->handle();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $limitDate = date("Y-m-d H:i:s", strtotime("- 30days", time()));
        $users = User::whereNull("email_verified_at")
            ->where("created_at", "<=", $limitDate)
            ->get();
        if ($users->count() == 0)
            return;

        /** @var User $user */
        foreach ($users as $user) {
            if ($photo = $user->photo) {
                Storage::delete($photo);
                (new Thumb("photos"))->clear($user->photo);
            }

            $user->delete();
        }
    }
}
