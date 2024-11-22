<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Question extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $table = 'question';

    protected $fillable = ['user_id', 'title', 'content'];
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the user that owns the question.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the comments for the question.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'parent');
    }

    /**
     * Get the votes for the question.
     */

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'parent');
    }

    /**
     * Get the tags for the question.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'questiontag', 'question_id', 'tag_id');
    }

    /**
     * The users that follow the question.
     */

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followquestion', 'question_id', 'user_id');
    }

    
    





}