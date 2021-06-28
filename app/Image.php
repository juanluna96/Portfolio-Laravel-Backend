<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'size', 'url_image', 'proyect_id'
    ];

    /**
     * Get the proyect that owns the Image
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyect()
    {
        return $this->belongsTo(Proyect::class);
    }
}
