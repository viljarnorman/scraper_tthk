<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Company;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CompanyViewController extends Controller {
   
    public function companies(Request $request) {

        

        // päringu loomine - select ja join osa
        $query = Company::select(
            'c.reg_code as company_reg_code',
            'c.id as id', 
            'cn.name as company_name',
            'ct.name as company_type_name',
            DB::raw("
                date_format(c.founded_at,'%d.%m.%Y') as company_founded_at
            "),
            DB::raw("
                (
                    select group_concat(g.name separator ', ')
                    from company_groups cg
                    join groups g on (g.id = cg.group_id)
                    where cg.company_id = c.id
                ) as groups                
            "),
            DB::raw("
            (
                select group_concat(concat(r.year,' = ',ifnull(r.turnover,'?'),'€') order by r.year separator '<br>')
                from reports r
                where r.company_id = c.id
            ) as company_turnovers
            "),
            DB::raw("
            (
                select group_concat(concat(r.year,' = ',ifnull(r.profit,'?'),'€') order by r.year separator '<br>')
                from reports r
                where r.company_id = c.id
            ) as company_profits
            "),
            DB::raw("
            (
                select group_concat(concat(cc.key,': ',ifnull(cc.value,'?')) order by cc.key,cc.value separator '<br>')
                from company_contacts cc
                where
                   cc.company_id = c.id
                   and (
                        (cc.valid_from is null and cc.valid_to is null)
                        or (now() between cc.valid_from and cc.valid_to)
                        or (cc.valid_to is null and now() >= cc.valid_from)
                        or (cc.valid_from is null and now() <= cc.valid_to)
                   )
            ) as company_contacts
            ")

        )
        ->from('companies as c')
        ->join('company_names as cn', 'cn.owner_id', '=', 'c.id')
        ->join('company_types as ct', 'ct.id', '=', 'c.company_type_id');
        
        // ettevõtte nime järgi otsing
        $company_name = $request->input('input_company_name');        
        if (isset($company_name)) {
            $query->where('cn.name','like','%'.$company_name.'%');
        } 
        
        // ettevõtte gruppide järgi otsing
        $req_groups = $request->input('input_groups');  
        if (isset($req_groups)) {
            Log::debug('$req_groups.count: ' . count($req_groups));
            $subQuery = DB::table('company_groups as cg')
                ->join('groups as g','g.id','=','cg.group_id')
                ->selectRaw(1)
                ->whereColumn('cg.company_id','=','c.id')
                ->whereIn('g.name',$req_groups);
            
            $query->whereExists($subQuery);
        }        

       // ettevõtte tüübi järgi otsing
       $req_comp_types = $request->input('input_comp_types');  
       if (isset($req_comp_types)) {
           $query->whereIn('ct.name',$req_comp_types);
       }    
       
       // käibe vahemiku järgi otsing
       $req_turnover_start = $request->input('input_turnover_start');
       $req_turnover_end = $request->input('input_turnover_end');
       if (isset($req_turnover_start) or isset($req_turnover_end)) {
            Log::debug('req_turnover_start: ' . '[' . $req_turnover_start . ']');
            Log::debug('req_turnover_end: ' . '[' . $req_turnover_end . ']');

            $subQuery = DB::table('reports as r')
                ->selectRaw(1)
                ->whereColumn('r.company_id','=','c.id');

            if (isset($req_turnover_start))
                $subQuery = $subQuery->where('r.turnover','>=', $req_turnover_start);
            if (isset($req_turnover_end))
                $subQuery = $subQuery->where('r.turnover','<=', $req_turnover_end);

            $query->whereExists($subQuery);
       } 


        // genereeritud sql lause logimine
        Log::debug('$query.toSql(): ' . $query->toSql());
        Log::debug('$query.getBindings(): ' . implode(', ', $query->getBindings()));

        // päringu käivitamine
        $companies = $query->paginate(20);

        // muud päringud (select listide sisu leidmiseks)
        $groups = DB::select('select name from groups order by name');
        $comp_types = DB::select('select name, description from company_types order by name');

        return view('companies_view_1',data: [
                'tmpl_companies'=>$companies, 
                'tmpl_company_name'=>$company_name, 
                'tmpl_groups'=>$groups, 
                'tmpl_groups_selected'=>$req_groups, 
                'tmpl_comp_types'=>$comp_types,
                'tmpl_comp_types_selected'=>$req_comp_types,
                'tmpl_turnover_start'=>$req_turnover_start,
                'tmpl_turnover_end'=>$req_turnover_end,
                'tmpl_heading' => 'Ettevõtted'
        ]);
    }


    
    public function company(int $id)
    {
        $company = Company::find($id);
        //dd($company);
        return view('company_view', ['data' => $company]);
    }

    

     


   public function index() {
      $companies = DB::select('select * from company_names limit 30');
      return view('companies_view',['tmpl_companies'=>$companies, 'tmpl_company_name'=>null]);
   }

   public function search(Request $request)
    {
    $company_name = $request->input('input_company_name');
    $companies = DB::select('select * from company_names where name like ?',['%'.$company_name.'%']);
   
   return view('companies_view',['tmpl_companies'=>$companies,'tmpl_company_name'=>$company_name]);

    
    }
}
