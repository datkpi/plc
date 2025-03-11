<?php

namespace App\Observers;

use App\Models\User;
use App\Jobs\SyncUserToOtherDb;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->dispatchSyncJobs($user, 'create');
    }
    /**
     * Handle the Position "updated" event.
     */
    public function updated(User $user): void
    {
        $this->dispatchSyncJobs($user, 'update');
    }

    protected function dispatchSyncJobs(User $user, $action): void
    {
        $dbs = ['sangkien'];
        foreach ($dbs as $db) {
            SyncUserToOtherDb::dispatch($user, $db, $action);
        }
    }
}
