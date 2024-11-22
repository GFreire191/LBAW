<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


class UserPolicy
{

	public function store(): bool
    {
        return Auth::check();
    }
    
    public function store(): bool
    {
        return Auth::check();
    }

    public function create(): bool
    {
        return Auth::check();
    }

}