<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /** A team has many users */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /** A team has many contacts */
    public function contacts()
    {
        return $this->hasMany(Contact::class)->with('customAttributes');
    }
}
