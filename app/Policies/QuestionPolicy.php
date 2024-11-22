<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
class QuestionPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the model. Anyone can see a question.
     */

    public function show(User $user, Question $question): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Question $question): bool
{
        if(Auth::user()->id === $question->user_id){
            return true;
        } 
        return false;
}

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user,Question $question): bool
    {
        return (Auth::user()->id === $question->user_id || Auth::user()->is_moderator);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Question $question): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Question $question): bool
    {
        //
    }


    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }


    public function follow(User $user, Question $question): bool
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            throw new AuthorizationException('You must be logged in to follow a question.');
        }

        // Check if the user is not the owner of the question
        if ($user->id === $question->user_id) {
            throw new AuthorizationException('You cannot follow your own question.');
        }

        // Check if the user is not already following the question
        if ($user->isFollowing($question->id)) {
            throw new AuthorizationException('You are already following this question.');
        }

        return true;
    }

    
    public function unfollow(User $user, Question $question): bool
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            throw new AuthorizationException('You must be logged in to unfollow a question.');
        }

        // Check if the user is not the owner of the question
        if ($user->id === $question->user_id) {
            throw new AuthorizationException('You cannot unfollow your own question.');
        }

        // Check if the user is already following the question
        if (!$user->isFollowing($question->id)) {
            throw new AuthorizationException('You are not following this question.');
        }

        return true;
    }

    
}
