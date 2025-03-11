<?php

namespace App\Observers;

use App\Jobs\SyncDepartmentToOtherDb;
use App\Models\Department;

class DepartmentObserver
{

    /**
     * Handle the Department "created" event.
     */
    public function created(Department $department): void
    {
        $this->dispatchSyncJobs($department, 'create');
    }
    /**
     * Handle the Department "updated" event.
     */
    public function updated(Department $department): void
    {
        $this->dispatchSyncJobs($department, 'update');
    }

    protected function dispatchSyncJobs(Department $department, $action): void
    {
        $dbs = ['sangkien'];
        foreach ($dbs as $db) {
            SyncDepartmentToOtherDb::dispatch($department, $db, $action);
        }
    }
}
