<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The proyects that belong to the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function proyects()
    {
        return $this->belongsToMany(Proyect::class, 'categories_proyects');
    }

    /**
     * The languages that belong to the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'categories_languages_descriptions')->withPivot('description');
    }
}
