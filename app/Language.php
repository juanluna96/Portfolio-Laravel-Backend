<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /**
     * Get all of the proyects for the Language
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proyects()
    {
        return $this->belongsToMany(Proyect::class, 'proyects_languages')->withPivot('title', 'description');
    }

    /**
     * The categories that belong to the Language
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_languages_descriptions')->withPivot('description');
    }
}
