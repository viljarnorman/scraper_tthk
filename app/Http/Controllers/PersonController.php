<?php
namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


// Kontroller inimeste nimekirja haldamiseks

class PersonController extends Controller
{
    // Meetod, mis tagastab inimeste nimekirja vaate
    public function index(Request $request) 
    {
        // Laeb kõik rollid andmebaasist ja teisendab need lihtsamasse massiivi kujule
        $roleData = Role::all()->map(function ($role) {
            return [
                'description' => $role->description,
                'name' => $role->name,
            ];
        })->toArray();

        


        // Ehitab inimeste päringu koos seotud 'latestName' ja 'companies' suhetega
        $peopleQuery = Person::with(['latestName', 'companies']);

        // Kui päringus on otsingusõna, lisatakse filtrid
        if ($request->search) {
            $searchTerm = $request->search;

            // Otsitakse isikukoodi või seotud nimede järgi
            $peopleQuery->where(function ($query) use ($searchTerm) {
                $query->where('id_code', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('names', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Kui päringus on rollifilter, lisatakse see päringusse
        if ($request->role) {
            $selected_roles = $request->input('role');
            Log::debug('$request->role: ' , ['data' => json_encode($selected_roles)]);

            $peopleQuery->whereHas('roles', function ($query) use ($selected_roles) {
                $query->whereIn('roles.name', $selected_roles);
            });

        }


        // Logib valitud rollid silumiseks
        Log::debug('$peopleQuery.toSql(): ' . $peopleQuery->toSql());
        Log::debug('$peopleQuery.getBindings(): ' . implode(', ', $peopleQuery->getBindings()));


        // Lehitseb tulemused 20 kaupa
        $people = $peopleQuery->paginate(20);

        Log::debug('$people.count: ' . count($people));

        // Tagastab vaate koos andmetega
        return view('people', [
            'tmpl_people' => $people,
            'tmpl_role_data' => $roleData,
            'tmpl_heading' => 'Inimesed'
        ]);

        
    }
}