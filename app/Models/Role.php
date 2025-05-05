<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'roles'; 
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'company_people', 'role_id', 'person_id')
                    ->withPivot('company_id');
    }
}
