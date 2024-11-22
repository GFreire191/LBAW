<?php

namespace App\Policies;

use App\Models\Vote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;


class VotePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function store(): bool
    {
        return Auth::check();
    }

    public function update(): bool
    {
        return Auth::check();
    }

    public function delete(): bool
    {
        return Auth::check();
    }

    public function vote_question(): bool
    {
        return Auth::check();
    }

    public function vote_answer(): bool
    {
        return Auth::check();
    }

}
