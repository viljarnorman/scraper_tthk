<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Users</title>
   </head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

<div class="max-w-4xl mx-auto p-4">
    <p>Add new user</p>
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <input name="name" placeholder="Name" required><br/>
        <input name="email" type="email" placeholder="Email" required><br/>
        <input name="password" type="password" placeholder="Password" required><br/>
        <input name="password_confirmation" type="password" placeholder="Confirm Password" required><br/>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br/>
        <button type="submit">Create</button>
    </form>
    
</div>
</body>
</html>