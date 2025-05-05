<x-layout_people
  :form_role_data="$tmpl_role_data"
  :form_heading="$tmpl_heading"

>
    <table class="table-auto w-full border-separate border-spacing-y-2">
        <tr>
           <th>Nimi</th>
           <th>Isikukood</th>
           <th>Ettevõtted</th>
           <th></th>
        </tr>
        @foreach ($tmpl_people as $person)
            <tr x-data="{ open: false }" class="odd:bg-white even:bg-gray-100">        
                <td>{{ $person->getLatestNameValue() }}</td>
                <td>{{ $person->id_code }}</td>
                <td>{{ $person->companyCount() }}</td>
                <td>
                    <button @click="open = true" class="text-blue-600 hover:underline">Ava</button>

                    <!-- Modal inside the row -->
                    @php
                        $idCodeDetails = $person->getIdCodeDetails();
                        $companyRoles = $person->getCompanyRoles();
                    @endphp     
                    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div @click.away="open = false" class="bg-white p-4 rounded shadow-lg w-1/3">
                        <table class="table-auto w-full border-separate border-spacing-y-2">
                            <tr>
                            <td class="font-bold">Nimi:</td>
                            <td>{{ $person->getLatestNameValue() }}</td>
                            </tr>
                            <tr>
                            <td class="font-bold">Isikukood:</td>
                            <td>{{ $person->id_code }}</td>
                            </tr>
                            <tr>
                            <td class="font-bold">Sugu:</td>
                            <td>{{ $idCodeDetails['gender'] }}</td>
                            </tr>
                            <tr>
                            <td class="font-bold">Sünnipäev:</td>
                            <td>{{ $idCodeDetails['day'] === null ? null : $idCodeDetails['day'] . '.' . $idCodeDetails['month'] . '.' . $idCodeDetails['year'] }}</td>
                            </tr>
                            <tr>
                            <td class="font-bold">Vanus:</td>
                            <td>{{ $idCodeDetails['age'] }}</td>
                            </tr>
                            <tr>
                            <td class="font-bold">Ettevõtted ja rollid:</td>
                            <td>
                                    @if ($companyRoles && $companyRoles->isNotEmpty())
                                        <table class="table-auto w-full border-separate border-spacing-y-2">
                                        @foreach ($companyRoles as $companyRole)
                                            <tr>
                                                <td>{{ $companyRole['company_name'] }}</td>
                                                <td>{{ $companyRole['role_description'] }}</td>
                                            </tr>
                                        @endforeach
                                        </table>        
                                    @else
                                        Puuduvad
                                    @endif
                            </td>
                            </tr>
                            
                        </table>
                        <button @click="open = false" class="mt-4 px-4 py-2 bg-gray-300 rounded">Sulge</button>
                        </div>
                    </div>  



                </td>
                    
                
            </tr>

            
        </td>
        @endforeach
        </tr>
     </table>

<!-- Pagination links -->
<div>
    {{ $tmpl_people->appends(request()->query())->links() }}
</div>

</x-layout_people>

