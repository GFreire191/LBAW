<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $table = 'tag';

    protected $fillable = ['name'];

    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * Get the questions for the tag.
     */

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'questiontag', 'tag_id', 'question_id');

    }

    /** Get the Users That Follow */

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followtag', 'tag_id', 'user_id');
    }

    
    public static function getTags()
    {
        return Tag::all();
    }


}
