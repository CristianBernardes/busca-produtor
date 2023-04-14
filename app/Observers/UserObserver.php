<?php

namespace App\Observers;

use App\Models\User;
use App\Repositories\UserRepository;

/**
 *
 */
class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $userRepository = new UserRepository;

        $userRepository->createAPasswordResetToken($user->email, 8760);
    }
}
