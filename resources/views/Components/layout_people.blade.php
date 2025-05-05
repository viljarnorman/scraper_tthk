<!DOCTYPE html>
<html lang="et" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="h-full">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <nav class="w-64 bg-gray-800 text-white flex flex-col p-4">
            <!-- Sidebar content -->
            <div class="flex items-center space-x-3">
                <img class="size-8" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Logo">
                <span class="text-lg font-bold">Scraper</span>
            </div>

            <div class="mt-6 flex flex-col space-y-2">
                <x-nav-link href="/companies" :active="request()->is('/companies')">Ettevõtted</x-nav-link>
                <x-nav-link href="/people" :active="request()->is('/people')">Inimesed</x-nav-link>
            </div>

            <!-- Filters -->
            <div class="mt-6 flex flex-col space-y-4">
                <form action="{{ route('people') }}" method="GET">
                    <input type="text" name="search" placeholder="Sisesta nimi või isikukood"
                           class="w-full p-2 rounded-md text-gray-900 border border-black">
                    
                    <!-- Select roles -->
                    <select class="w-full p-2 rounded-md text-gray-900" name="role[]" id="roles" multiple>
                        @foreach($attributes['form_role_data'] as $role_data)
                            <option value="{{ $role_data['name'] }}" 
                                {{ in_array($role_data['name'], request()->input('role', [])) ? 'selected' : '' }}>
                                {{ $role_data['description'] . ' (' . $role_data['name'] . ')'}}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="w-full p-2 rounded-md bg-blue-600 text-white">Filtreeri</button>
                </form>
            </div>

        </nav>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow p-4">
                <h1 class="text-2xl font-bold text-gray-900">{{$attributes['form_heading']}}</h1>
            </header>

            <main class="flex-1 p-6 bg-gray-100">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Tom Select JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        $(document).ready(function() {
            new TomSelect("#roles",{
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
            
        });
    </script>


</body>
</html>

