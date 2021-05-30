<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
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
