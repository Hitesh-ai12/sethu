@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">User Management</h1>

    <div class="overflow-x-auto">
        <table id="userTable" class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Email</th>
                    <th class="border px-4 py-2">Phone</th>
                    <th class="border px-4 py-2">Role</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="border px-4 py-2">{{ $user->id }}</td>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2">{{ $user->mobile_number }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($user->role) }}</td>
                        <td class="border px-4 py-2">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Edit</button>
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded approve">Approve</button>
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded decline">Decline</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
