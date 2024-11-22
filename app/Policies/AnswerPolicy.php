<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
 
class AnswerPolicy
{

    /**
     * Determine whether the user can view the model. Anyone can see a question.
     */

    public function show(User $user, Answer $answer): bool
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
    public function update(User $user, Answer $answer): bool
    {
            
            return Auth::user()->id === $answer->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Answer $answer): bool
    {
        return ($user->id === $answer->user_id || Auth::user()->is_moderator);
    }

    public function markCorrect(User $user, Answer $answer)
    {
        return $user->id === $answer->question->user_id;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Answer $answer): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Answer $answer): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Answer $answer): bool
    {
        //
    }
}
