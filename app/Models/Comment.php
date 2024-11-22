<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Comment extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $table = 'comment';

    protected $fillable = ['content', 'user_id', 'parent_id', 'parent_type'];

    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent of the comment.
     */

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }


}
