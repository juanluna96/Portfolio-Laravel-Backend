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
        return $this->belongsToMany(Proyect::class, 'proyects_languages')->as('language_proyects')->withPivot('title', 'description');
    }

    /**
     * The categories that belong to the Language
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_languages_descriptions')->as('categories_languages')->withPivot('description');
    }
}
