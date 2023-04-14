<?php

namespace App\Observers;

use App\Mail\CreateAPasswordResetToken;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PasswordResetTokenObserver
{
    /**
     * Handle the PasswordResetToken "created" event.
     */
    public function created(PasswordResetToken $passwordResetToken): void
    {
        $user = User::where('email', $passwordResetToken->email)->first();

        $title = $user->first_access == 1 ? 'Criar nova senha' : 'Redefinição de senha';

        Mail::to($passwordResetToken->email)->send(new CreateAPasswordResetToken($passwordResetToken->email, $passwordResetToken->token, $title));
    }
}
