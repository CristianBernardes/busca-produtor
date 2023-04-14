<?php

namespace App\Observers;

use App\Models\Producer;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class ProducerObserver
{
    /**
     * Handle the Producer "creating" event.
     */
    public function creating(Producer $producer): void
    {
        $this->createUpdateCordinates($producer);
    }

    /**
     * Handle the Producer "updating" event.
     */
    public function updating(Producer $producer): void
    {
        $this->createUpdateCordinates($producer);
    }

    /**
     * @param Producer $producer
     * @return void
     */
    private function createUpdateCordinates(Producer $producer): void
    {
        $latitude = $producer->latitude;
        $longitude = $producer->longitude;

        $producer->coordinates = DB::raw("POINT($latitude, $longitude)");
    }
}
