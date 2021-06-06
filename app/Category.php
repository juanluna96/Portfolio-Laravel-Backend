<?php

namespace App;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'color_text', 'color_bg', 'logo', 'image'
    ];

    /**
     * The proyects that belong to the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function proyects()
    {
        return $this->belongsToMany(Proyect::class, 'categories_proyects')->as('categories_proyects')->withTimestamps();
    }

    /**
     * The languages that belong to the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'categories_languages_descriptions')->as('categories_languages')->withPivot('description');
    }
}
