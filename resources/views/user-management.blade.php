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
                        <td class="border px-4 py-2 a">{{ $user->id }}</td>
                        <td class="border px-4 py-2 a">{{ $user->name }}</td>
                        <td class="border px-4 py-2 a">{{ $user->email }}</td>
                        <td class="border px-4 py-2 a">{{ $user->mobile_number }}</td>
                        <td class="border px-4 py-2 a">{{ ucfirst($user->role) }}</td>
                        <td class="border px-4 py-2 a">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded">Edit</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded approve">Approve</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded dcline">Dcline</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "paging": true,
                "searching": true,
                "responsive": true,
                "autoWidth": false,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[0, "desc"]],
            });
        });
    </script>
@endsection
