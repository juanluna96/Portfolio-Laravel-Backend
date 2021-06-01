<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'position_es', 'position_en', 'image'
    ];

    /**
     * Get all of the proyects for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proyects()
    {
        return $this->hasMany(Proyect::class);
    }
}
