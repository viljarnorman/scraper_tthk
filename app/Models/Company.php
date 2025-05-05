<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsToMany;

class Company extends Model
{
    protected $table = 'companies';

    public function person(): belongsToMany
    {
        return $this->belongsToMany(Person::class, 'company_people', 'company_id', 'person_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'company_people')
                    ->withPivot('person_id');
    }
}
