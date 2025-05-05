<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class Person extends Model
{
    protected $table = 'people';

    public function names(): HasMany
    {
        return $this->hasMany(PersonName::class, $foreignKey = "owner_id");
    }

    
    public function companies(): belongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_people', 'person_id', 'company_id');
    }
    

    public function companies_roles()
    {
        return $this->belongsToMany(Company::class, 'company_people')
                    ->withPivot('role_id')
                    ->with('roles');
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'company_people', 'person_id', 'role_id');
    }
    public function latestName()
    {
        return $this->hasOne(PersonName::class, 'owner_id')->latestOfMany();
    }

    public function getLatestNameValue()
    {
        return $this->latestName?->name;
    }

    public function companyCount()
    {
        return $this->companies->count();
    }

    public function getIdCodeDetails()
    {
            $idCode = $this->id_code;

            // Validate input
            if (!preg_match('/^\d{11}$/', $idCode)) {
                //return ['error' => 'Invalid Estonian personal code format'];
                return [
                    'gender' => null,
                    'year'   => null,
                    'month'  => null,
                    'day'    => null,
                    'age'    => null
                ];                
            }
        
            // Extract first digit to determine century and gender
            $firstDigit = (int) $idCode[0];
            $yearFragment = substr($idCode, 1, 2);
            $month = substr($idCode, 3, 2);
            $day = substr($idCode, 5, 2);
        
            // Determine century
            switch ($firstDigit) {
                case 1:
                case 2:
                    $century = 1800;
                    break;
                case 3:
                case 4:
                    $century = 1900;
                    break;
                case 5:
                case 6:
                    $century = 2000;
                    break;
                case 7:
                case 8:
                    $century = 2100;
                    break;
                default:
                    return [
                        'gender' => null,
                        'year'   => null,
                        'month'  => null,
                        'day'    => null,
                        'age'    => null
                    ];
            }
        
            // Determine gender
            $gender = ($firstDigit % 2 === 1) ? 'M' : 'N';
        
            // Calculate full year
            $year = $century + (int) $yearFragment;
            
            try {
                $birthDate = Carbon::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $year, $month, $day));
            } catch (\Exception $e) {
                return [
                    'gender' => null,
                    'year'   => null,
                    'month'  => null,
                    'day'    => null,
                    'age'    => null
                ];
            }
        
            $age = $birthDate->age;
            return [
                'gender' => $gender,
                'year'   => str_pad($year, 4, '0', STR_PAD_LEFT),
                'month'  => str_pad($month, 2, '0', STR_PAD_LEFT),
                'day'    => str_pad($day, 2, '0', STR_PAD_LEFT),
                'age'    => $age
            ];
        
    }


    public function getCompanyRoles()
    {

        $roles = Role::all()->keyBy('id');

        $pairs = $this->companies_roles->map(function ($company) use ($roles) {
            $role = $roles->get($company->pivot->role_id);

            // Get latest name from company_names where owner_id = $company->id
            $latestName = DB::table('company_names')
                ->where('owner_id', $company->id)
                ->orderByDesc('valid_from')
                ->value('name');

            return [
                'company_name' => $latestName,
                'role_description' => $role ? $role->description : null,
            ];
        });

        return $pairs;
    }
}