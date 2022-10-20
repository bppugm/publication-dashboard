<?php

namespace App\Observers;

use App\Models\Data;
use App\Events\DataUpdated;

class DataObserver
{
    /**
     * Handle the Data "updated" event.
     *
     * @param  \App\Models\Data  $data
     * @return void
     */
    public function updated(Data $data)
    {
        event(new DataUpdated($data));
    }
}
