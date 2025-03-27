@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h2 class="text-2xl font-bold mb-4">Manage Subjects</h2>

    <!-- Add Subject Button -->
    <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
        Add Subject
    </button>

    <!-- Subject List -->
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Subject Name</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody id="subjectTable">
            <!-- Data will be loaded dynamically -->
        </tbody>
    </table>
</div>

<!-- Add Subject Modal -->
<div id="addModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Add Subject</h2>
        <input type="text" id="new_subject_name" class="border p-2 w-full rounded" placeholder="Enter Subject Name">
        <div class="mt-4">
            <button onclick="addSubject()" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
            <button onclick="closeModal('addModal')" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Edit Subject</h2>
        <input type="hidden" id="edit_subject_id">
        <input type="text" id="edit_subject_name" class="border p-2 w-full rounded">
        <div class="mt-4">
            <button onclick="updateSubject()" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
            <button onclick="closeModal('editModal')" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
        </div>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById("addModal").classList.remove("hidden");
    }

    function openEditModal(id, name) {
        document.getElementById("edit_subject_id").value = id;
        document.getElementById("edit_subject_name").value = name;
        document.getElementById("editModal").classList.remove("hidden");
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add("hidden");
    }

    function fetchSubjects() {
        fetch("{{ route('get.subjects') }}")
        .then(response => response.json())
        .then(data => {
            let rows = "";
            data.subjects.forEach((subject, index) => {
                rows += `
                    <tr id="row-${subject.id}">
                        <td class="border px-4 py-2">${index + 1}</td>
                        <td class="border px-4 py-2" id="name-${subject.id}">${subject.name}</td>
                        <td class="border px-4 py-2">
                            <button onclick="openEditModal(${subject.id}, '${subject.name}')" class="bg-green-500 text-white px-3 py-1 rounded">Edit</button>
                            <button onclick="deleteSubject(${subject.id})" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById("subjectTable").innerHTML = rows;
        });
    }

    function addSubject() {
        let subjectName = document.getElementById("new_subject_name").value;
        if (subjectName === "") {
            alert("Please enter a subject name");
            return;
        }

        fetch("{{ route('add.subject') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ name: subjectName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById("new_subject_name").value = "";
                closeModal("addModal");
                fetchSubjects();
            } else {
                alert("Something went wrong!");
            }
        });
    }

    function updateSubject() {
        let id = document.getElementById("edit_subject_id").value;
        let newName = document.getElementById("edit_subject_name").value;

        fetch(`{{ url('/update-subject/') }}/${id}`, {
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
                alert("Error updating subject!");
            }
        });
    }

    function deleteSubject(id) {
        fetch(`{{ url('/delete-subject/') }}/${id}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`row-${id}`).remove();
            } else {
                alert("Error deleting subject!");
            }
        });
    }

    document.addEventListener("DOMContentLoaded", fetchSubjects);
</script>

@endsection
