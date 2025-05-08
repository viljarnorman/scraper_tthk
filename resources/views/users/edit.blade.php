<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Users</title>
   </head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

<div class="max-w-4xl mx-auto p-4">
    <p>Edit user</p>
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        <span>Name: </span><input name="name" value="{{ $user->name }}" required><br/>
        <span>Email: </span><input name="email" type="email" value="{{ $user->email }}" required><br/>
        <span>Role: </span><select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>            
        </select><br/>
        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>