<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
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
