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
     * @param null $minDistance The minimum distance (in kilometers).
     * @param null $maxDistance The maximum distance (in kilometers).
     * @return mixed
     */
    public function searchProducersByLocation(
        User $user,
        $minDistance = null,
        $maxDistance = null
    ) {

        $distance = null;
        $newMinDistance = $minDistance;
        $newMaxDistance = $maxDistance;

        if ($minDistance && $maxDistance) {

            if ($minDistance > $maxDistance) {
                $newMinDistance = $maxDistance;
                $newMaxDistance = $minDistance;
            }

            $distance = [$newMinDistance, $newMaxDistance];
        }

        $producers = $this->model()::select(
            'id',
            'producer_name',
            'city',
            'state',
            'whatsapp_phone',
            'volume_in_liters',
        );

        if ($user->is_admin) {

            $producers->addSelect('latitude', 'longitude');
        }

        $producers->addSelect(
            DB::raw('ROUND(ST_DISTANCE_SPHERE(coordinates, POINT(' . $user->latitude . ', ' . $user->longitude . ')) / 1000, 2) AS distance')
        );

        return $producers->when(!$user->is_admin, function ($query) use ($newMaxDistance) {

            return $query->having('distance', '<=', min($newMaxDistance ?? 500, 500));
        })->when($distance, function ($query, $distance) {

            return $query->havingRaw('distance BETWEEN ? AND ?', $distance);
        })
            ->orderBy('distance')
            ->get();
    }
}
