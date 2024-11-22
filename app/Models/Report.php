<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $table = 'report';
    protected $fillable = ['motive', 'user_id', 'reportable_id', 'reportable_type'];



    use HasFactory;

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }


    
}
