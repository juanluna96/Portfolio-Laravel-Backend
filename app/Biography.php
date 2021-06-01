<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Biography extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description_en', 'description_es', 'stacks_description_en', 'stacks_description_es', 'phone_1', 'phone_2', 'email_1', 'email_2', 'user_id'
    ];

    /**
     * Get the user that owns the Biography
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
