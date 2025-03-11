<?php

namespace App\Observers;

use App\Jobs\SyncPositionToOtherDb;
use App\Models\Position;

class PositionObserver
{
    /**
     * Handle the Position "created" event.
     */
    public function created(Position $position): void
    {
        $this->dispatchSyncJobs($position, 'create');
    }
    /**
     * Handle the Position "updated" event.
     */
    public function updated(Position $position): void
    {
        $this->dispatchSyncJobs($position, 'update');
    }

    protected function dispatchSyncJobs(Position $position, $action): void
    {
        $dbs = ['sangkien'];
        foreach ($dbs as $db) {
            SyncPositionToOtherDb::dispatch($position, $db, $action);
        }
    }
}
