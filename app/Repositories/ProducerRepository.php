<?php

namespace App\Repositories;

use App\Models\Producer;
use App\Models\User;
use DB;

/**
 * Class ProducerRepository
 * @package App\Repositories
 */
class ProducerRepository extends AbstractRepository
{
    /**
     * Returns the model class for this repository.
     *
     * @return string
     */
    public function model(): mixed
    {
        return Producer::class;
    }

    /**
     * Searches for producers within a given distance range, optionally limited to a minimum and maximum distance.
     *
     * @param User $user The authenticated user.
     * @param string|null $minDistance The minimum distance (in kilometers).
     * @param string|null $maxDistance The maximum distance (in kilometers).
     * @return mixed
     */
    public function searchProducersByLocation(
        User $user,
        int $minDistance = null,
        int $maxDistance = null
    ) {

        if ($minDistance === null || $minDistance === 0 && $maxDistance) {
            $minDistance = 1;
        } else {
            $minDistance = null;
        }

        if ($user->is_admin) {

            $producers =  $this->model()::select(
                '*',
                DB::raw('ROUND(6371 * acos(cos(radians(' . $user->latitude . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $user->longitude . ')) + sin(radians(' . $user->latitude . ')) * sin(radians(latitude))), 2) AS distance')
            );
        } else {

            $producers =  $this->model()::select(
                'id',
                'producer_name',
                'city',
                'state',
                'whatsapp_phone',
                'volume_in_liters',
                DB::raw('ROUND(6371 * acos(cos(radians(' . $user->latitude . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $user->longitude . ')) + sin(radians(' . $user->latitude . ')) * sin(radians(latitude))), 2) AS distance')
            );
        }

        return $producers->when(!$user->is_admin, function ($query) use ($maxDistance) {
            // limit distance for non-admin users
            return $query->having('distance', '<=', min($maxDistance ?? 500, 500));
        })->when($minDistance && $maxDistance && $minDistance <= $maxDistance, function ($query) use ($minDistance, $maxDistance) {
            // filter producers within the given distance range
            return $query->havingRaw('distance BETWEEN ? AND ?', [$minDistance, $maxDistance]);
        })
            ->orderBy('distance')
            ->get();
    }
}
