<!DOCTYPE html>
<html lang="et" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
     <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

     <style>
        /* Custom styling for the second line with a smaller font */
        .tom-select .option span.second-line {
          font-size: 0.5em;
          color: #888;
        }
      </style>     
</head>
<body class="h-full">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <nav class="w-64 bg-gray-800 text-white flex flex-col p-4">
            <div class="flex items-center space-x-3">
                <img class="size-8" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Logo">
                <span class="text-lg font-bold">Scraper</span>
            </div>
            <div class="mt-6 flex flex-col space-y-2">
                <x-nav-link  href="/companies" :active="request()->is('/companies')">Ettevõtted</x-nav-link>
                <x-nav-link  href="/people" :active="request()->is('/people')">Inimesed</x-nav-link>
                
            </div>
            
            <!-- Filters -->
            <div class="mt-6 flex flex-col space-y-4"> 


               <!-- <input type="text" placeholder="Nimi või registrikood" class="w-full p-2 rounded-md text-gray-900"> -->

            <form action="{{ route('companies') }}" method="GET">
                <input type="text" name="input_company_name" placeholder="Sisesta Ettevõtte nimi" value="{{$attributes['form_company_name']}}"
                       class="w-full p-2 rounded-md text-gray-900 border border-black " >
           
                <select class="w-full p-2 rounded-md text-gray-900" name="input_groups[]" placeholder="Tegevusvaldkond" id="input_groups" multiple>
                    @foreach ($attributes['form_groups'] as $form_group)
                        @php
                            $is_selected = null;
                            if (isset($attributes['form_groups_selected']) && in_array($form_group->name, $attributes['form_groups_selected'])) {
                                $is_selected = ' selected';
                            }
                        @endphp
                        <option {{$is_selected}}>{{$form_group->name}}</option>
                    @endforeach
                </select> 
                

                
                <select class="w-full p-2 rounded-md text-gray-900" name="input_comp_types[]" placeholder="Äritüübid"id="input_comp_types" multiple>
                    @foreach ($attributes['form_comp_types'] as $form_comp_type)
                        @php
                            $is_selected = null;
                            if (isset($attributes['form_comp_types_selected']) && in_array($form_comp_type->name, $attributes['form_comp_types_selected'])) {
                                $is_selected = ' selected';
                            }
                        @endphp
                        <option value="{{$form_comp_type->name}}" {{$is_selected}}>{{$form_comp_type->description}}</option>
                    @endforeach
                </select> 
                
                
                <input type="text" name="input_turnover_start" placeholder="Käive alates" value="{{$attributes['form_turnover_start']}}"
                       class="w-full p-2 rounded-md text-gray-900 border border-black " 
                       pattern="^[ \t\r\n\f]*-?\d+[ \t\r\n\f]*$" title="Väärtus peab olema number"
                       >

                <input type="text" name="input_turnover_end" placeholder="Käive kuni" value="{{$attributes['form_turnover_end']}}"
                    class="w-full p-2 rounded-md text-gray-900 border border-black " 
                    pattern="^[ \t\r\n\f]*-?\d+[ \t\r\n\f]*$" title="Väärtus peab olema number"                    
                    >

                       <!--<label for="range" class="text-sm">Käive</label>-->
                <!--<input id="range" type="range" min="0" max="1000000" value="500000" class="w-full cursor-pointer">-->

                <input type="submit" class="border border-black p-2 rounded-md ml-2" value="Otsi"/>
                <button class="border border-black p-2 rounded-md ml-2" onclick="location.href='{{route('companies')}}'" type="button">Tühista</button>

            </form>
            </div>

            <div class="mt-auto flex items-center space-x-3 border-t border-gray-700 pt-4">
                <div>
                    <p class="text-sm font-medium">Viljar Norman</p>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow p-4">
                <h1 class="text-2xl font-bold text-gray-900">{{$attributes['heading']}}</h1>
            </header>
            <main class="flex-1 p-6 bg-gray-100">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        $(document).ready(function() {
            new TomSelect("#input_groups",{
                plugins: ['remove_button'],
                create: false,
                hideSelected: true,  // Hides selected item from dropdown
                closeAfterSelect: true,  // Closes dropdown after selecting
                allowEmptyOption: true,  // Allows empty selection
                searchField: ['text','value'],
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });

            new TomSelect("#input_comp_types",{
                plugins: ['remove_button'],
                create: false,
                hideSelected: true,  // Hides selected item from dropdown
                closeAfterSelect: true,  // Closes dropdown after selecting
                allowEmptyOption: true,  // Allows empty selection
                searchField: ['text','value'],
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                render:{
                    option: function(data, escape){
                        return (
                            '<div>' + 
                                '<span>' + escape(data.value) + '</span><br>' +
                                '<span style="font-size:0.8em">' + escape(data.text) + '</span>' +
                            '</div>'
                        );
                    },
                    item: function(data, escape){
                        return (
                            '<div>' + escape(data.value) + '</div>'
                        );
                    }
                }
            });
            
        });
    </script>
</body>
</html>

