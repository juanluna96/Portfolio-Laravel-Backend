<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyect extends Model
{
    /**
     * Get the user that owns the Proyect
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The categories that belong to the Proyect
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_proyects');
    }

    /**
     * Get the company that owns the Proyect
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all of the images for the Proyect
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get all of the languages for the Proyect
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'proyects_languages')->withPivot('title', 'description');
    }
}
