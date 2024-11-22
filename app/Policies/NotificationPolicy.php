<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationPolicy
{
    
    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user): bool
    {
        return Auth::check();
    }


}
