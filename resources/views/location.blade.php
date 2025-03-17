@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h2 class="text-2xl font-bold mb-4">Manage Locations</h2>

    <!-- Add Location Button -->
    <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
        Add Location
    </button>

    <!-- Location List -->
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Location Name</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody id="locationTable">
            <!-- Data will be loaded dynamically -->
        </tbody>
    </table>
</div>

<!-- Add Location Modal -->
<div id="addModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Add Location</h2>
        <input type="text" id="new_location_name" class="border p-2 w-full rounded" placeholder="Enter Location Name">
        <div class="mt-4">
            <button onclick="addLocation()" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
            <button onclick="closeModal('addModal')" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
        </div>
    </div>
</div>

<!-- Edit Location Modal -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Edit Location</h2>
        <input type="hidden" id="edit_location_id">
        <input type="text" id="edit_location_name" class="border p-2 w-full rounded">
        <div class="mt-4">
            <button onclick="updateLocation()" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
            <button onclick="closeModal('editModal')" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById("addModal").classList.remove("hidden");
    }

    function openEditModal(id, name) {
        document.getElementById("edit_location_id").value = id;
        document.getElementById("edit_location_name").value = name;
        document.getElementById("editModal").classList.remove("hidden");
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add("hidden");
    }

    function fetchLocations() {
        fetch("{{ route('get.locations') }}")
        .then(response => response.json())
        .then(data => {
            let rows = "";
            data.locations.forEach((location, index) => {
                rows += `
                    <tr id="row-${location.id}">
                        <td class="border px-4 py-2">${index + 1}</td>
                        <td class="border px-4 py-2" id="name-${location.id}">${location.name}</td>
                        <td class="border px-4 py-2">
                            <button onclick="openEditModal(${location.id}, '${location.name}')" class="bg-green-500 text-white px-3 py-1 rounded">Edit</button>
                            <button onclick="deleteLocation(${location.id})" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById("locationTable").innerHTML = rows;
        });
    }

    function addLocation() {
        let locationName = document.getElementById("new_location_name").value;
        if (locationName === "") {
            alert("Please enter a location name");
            return;
        }

        fetch("{{ route('add.location') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ name: locationName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("new_location_name").value = "";
                closeModal("addModal");
                fetchLocations();
            } else {
                alert("Something went wrong!");
            }
        });
    }

    function updateLocation() {
        let id = document.getElementById("edit_location_id").value;
        let newName = document.getElementById("edit_location_name").value;

        fetch(`{{ url('/update-location/') }}/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ name: newName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`name-${id}`).innerText = newName;
                closeModal("editModal");
            } else {
                alert("Error updating location!");
            }
        });
    }

    function deleteLocation(id) {
        fetch(`{{ url('/delete-location/') }}/${id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`row-${id}`).remove();
            } else {
                alert("Error deleting location!");
            }
        });
    }

    document.addEventListener("DOMContentLoaded", fetchLocations);
</script>

@endsection
