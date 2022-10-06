<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $fillable = [
        'name',
        'user_id'
    ];

    /**
     * Get the user that owns the file
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
