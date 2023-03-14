<?php

namespace App\Repositories;

use App\Models\Producer;
use App\Models\User;

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
    public function searchProducersByLocation(User $user, ?string $minDistance, ?string $maxDistance)
    {
        $query = $this->model()::select(DB::raw('*, (6371 * acos(cos(radians(' . $user->latitude . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $user->longitude . ')) + sin(radians(' . $user->latitude . ')) * sin(radians(latitude)))) AS distance'))
            ->when(!$user->is_admin, function ($query) use ($maxDistance) {
                // limit distance for non-admin users
                return $query->having('distance', '<=', min($maxDistance ?? 500, 500));
            })
            ->when($minDistance && $maxDistance && $minDistance <= $maxDistance, function ($query) use ($minDistance, $maxDistance) {
                // filter producers within the given distance range
                return $query->havingBetween('distance', [$minDistance, $maxDistance]);
            }, function ($query) {
                // return all producers ordered by distance for admins
                return $query->orderBy('distance');
            });

        return $query->get();
    }
}
