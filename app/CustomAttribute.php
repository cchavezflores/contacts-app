<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomAttribute extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
        'contact_id'
    ];

    /** A custom attribute belongs to a contact */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
