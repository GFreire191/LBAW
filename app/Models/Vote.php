<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;




class Vote extends Model
{

    protected $table = 'vote';
    use HasFactory;

    protected $fillable = ['user_id', 'parent_id', 'parent_type', 'vote_status'];

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the user that owns the vote.
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent of the vote.
     */

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }
    
}
