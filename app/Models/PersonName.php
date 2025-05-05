<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonName extends Model
{
    protected $table = 'people_names';

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, $foreginKey = "owner_id");
    }
}
