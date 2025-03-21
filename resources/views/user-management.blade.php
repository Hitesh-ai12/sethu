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
                        <!-- View Button -->
                        <button onclick="openModal({{ $user }})"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">View</button>

                        @if($user->role == 'teacher' || $user->role == 'student')
                            <button onclick="confirmDelete({{ $user->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                        @endif

                        @if($user->status == 'pending')
                            <!-- Approve Button: Show when status is 'pending' -->
                            <button onclick="changeStatus({{ $user->id }}, 'approved')"
                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Approve</button>
                        @else
                            <!-- Pending Button: Show when status is 'approved' -->
                            <button onclick="changeStatus({{ $user->id }}, 'pending')"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Pending</button>
                        @endif
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
    function confirmDelete(userId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/delete-user/${userId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          Swal.fire({
                              title: "Deleted!",
                              text: "User has been deleted.",
                              icon: "success",
                              timer: 2000,
                              showConfirmButton: false
                          });
                          toastr.success("User deleted successfully!");
                          setTimeout(() => location.reload(), 2000);
                      } else {
                          Swal.fire("Error!", data.message, "error");
                          toastr.error(data.message);
                      }
                  }).catch(error => {
                      Swal.fire("Error!", "Something went wrong!", "error");
                      toastr.error("Something went wrong!");
                  });
            }
        });
    }
    function changeStatus(userId, newStatus) {
    Swal.fire({
        title: `Are you sure you want to set this user as ${newStatus}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, change it!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/change-status/${userId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ status: newStatus })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                    
                      Swal.fire({
                          title: "Updated!",
                          text: `User status has been changed to ${newStatus}.`,
                          icon: "success",
                          timer: 2000,
                          showConfirmButton: false
                      });
                      toastr.success(`User status changed to ${newStatus}!`);
                      setTimeout(() => location.reload(), 2000);
                  } else {
                      Swal.fire("Error!", data.message, "error");
                      toastr.error(data.message);
                  }
              }).catch(error => {
                  Swal.fire("Error!", "Something went wrong!", "error");
                  toastr.error("Something went wrong!");
              });
        }
    });
}

</script>

@endsection
