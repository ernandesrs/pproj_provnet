<?php

namespace App\Jobs;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UnbanUsersJob implements ShouldQueue
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
        $bans = Ban::whereNull("done_at")->where("type", "=", Ban::TYPE_TEMP)->get();
        if ($bans->count() == 0) {
            return;
        }

        /** @var Ban $ban */
        foreach ($bans as $ban) {
            if (strtotime("+ {$ban->days}days", strtotime($ban->created_at)) <= time()) {
                /** @var User $user */
                $user = $ban->user();
                $user->status = User::STATUS_ACTIVE;
                $user->save();

                $ban->done_at = date("Y-m-d H:i:s");
                $ban->save();
            }
        }
    }
}
