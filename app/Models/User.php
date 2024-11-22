<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'bio',
        'profile_picture_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the questions for the user.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the answers for the user.
     */

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the comments for the user.
     */

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the votes for the user.
     */

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * The questions that the user follows.
     */
    public function followedQuestions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'followquestion', 'user_id', 'question_id');
    }

    /**
     * The tags that the user follows.
     */
    public function followedTags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'followtag', 'user_id', 'tag_id');

    }

    public function isFollowing($questionId)
    {
    return $this->followedQuestions()->where('question_id', $questionId)->exists();
    }
    /**
     * User notifications. TODO
     */
     
    


    
    
}
