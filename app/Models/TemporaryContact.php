<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryContact extends Model
{
    protected $table = 'temporary_contacts';

    protected $fillable = [
        'name',
        'birthdate',
        'phone',
        'address',
        'cc_number',
        'cc_network',
        'email',
        'user_id'
    ];

    protected $casts = [
        'error' => 'array'
    ];

    /**
     * Get the user that owns the temporary contact
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
