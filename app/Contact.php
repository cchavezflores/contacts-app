<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'sticky_phone_number_id',
    ];

    /** A Contact belongs to a Team */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /** A contact might have many custom attributes */
    public function customAttributes()
    {
        return $this->hasMany(CustomAttribute::class);
    }
}
