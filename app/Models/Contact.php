<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'birthdate',
        'phone',
        'address',
        'cc_number',
        'cc_network',
        'email',
        'file_id',
        'user_id'
    ];

    /**
     * Get the user that owns the Contact
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
