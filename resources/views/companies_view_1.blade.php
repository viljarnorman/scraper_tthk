<x-layout 
   :heading="$tmpl_heading"
   :form_groups="$tmpl_groups"
   :form_groups_selected="$tmpl_groups_selected"
   :form_comp_types="$tmpl_comp_types"
   :form_comp_types_selected="$tmpl_comp_types_selected"
   :form_company_name="$tmpl_company_name"
   :form_turnover_start="$tmpl_turnover_start"
   :form_turnover_end="$tmpl_turnover_end"
>




   
    <div>
    </div>
      <table class="table-auto w-full border-separate border-spacing-y-2">
         <tr>
            <th class="px-6 py-3">Ettevõtte registrikood</th>
            <th class="px-6 py-3 whitespace-nowrap">Ettevõtte nimi</th>
            <th class="px-6 py-3 whitespace-nowrap">Äritüüp</th>
            <th class="px-6 py-3 whitespace-nowrap">Tegevusvaldkond</th>
            <th class="px-6 py-3 whitespace-nowrap">Käive</th>
            <th class="px-6 py-3"></th>
         </tr>
         @foreach ($tmpl_companies as $company)
         <tr x-data="{ open: false }" class="odd:bg-white even:bg-gray-100">
            <td>{{ $company->company_reg_code }}</td>
            <td>{{ $company->company_name }}</td>
            <td>{{ $company->company_type_name }}</td>
            <td>{{ $company->groups }}</td>
            <td class="whitespace-nowrap text-sm">{!! $company->company_turnovers !!}</td>
            <td>
                <!--
               <a href="{{ url('/company/' . $company->id) }}" style="color: blue; text-decoration: underline;">
                  Ava
               </a> -->

               <button @click="open = true" class="text-blue-600 hover:underline">Ava</button>

               <!-- Modal inside the row -->
               <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                 <div @click.away="open = false" class="bg-white p-4 rounded shadow-lg w-1/3">
                   <table class="table-auto w-full border-separate border-spacing-y-2">
                     <tr>
                        <td class="font-bold">Ettevõtte nimi:</td>
                        <td>{{ $company->company_name }}</td>
                     </tr>
                     <tr>
                        <td class="font-bold">Registrikood:</td>
                        <td>{{ $company->company_reg_code }}</td>
                     </tr>
                     <tr>
                        <td class="font-bold">Asutamisaeg:</td>
                        <td>{{ $company->company_founded_at }}</td>
                     </tr>
                     <tr>
                        <td class="font-bold">Käive:</td>
                        <td>{!! $company->company_turnovers !!}</td>
                     </tr>
                     <tr>
                        <td class="font-bold">Kasum:</td>
                        <td>{!! $company->company_profits !!}</td>
                     </tr>
                     <tr>
                        <td class="font-bold">Kontaktid:</td>
                        <td>{!! $company->company_contacts !!}</td>
                     </tr>
                     
                   </table>
                   <button @click="open = false" class="mt-4 px-4 py-2 bg-gray-300 rounded">Sulge</button>
                 </div>
               </div>             
            </td>
               
         </tr>
         @endforeach
      </table>

<!-- Pagination links -->
<div>
   {{ $tmpl_companies->appends(request()->query())->links() }}
</div>

</x-layout>