<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Answer extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $table = 'answer';
    
    protected $fillable = ['content', 'user_id', 'question_id', 'correct'];


    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the user that owns the answer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question that owns the answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the comments for the answer.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'parent');
    }

    /**
     * Get the votes for the answer.
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'parent');
    }



    
}
