@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-5">
    <h2 class="text-2xl font-bold mb-4">Manage Events & Webinars</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Add Event Form -->
    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="mb-4">
            <label class="block font-bold mb-2">Image</label>
            <input type="file" name="image" class="border p-2 w-full rounded">
            @error('image')
                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-2">Event Link</label>
            <input type="url" name="link" class="border p-2 w-full rounded">
            @error('link')
                <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Event</button>
    </form>

<!-- Events List -->
<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="border px-4 py-2">Image</th>
            <th class="border px-4 py-2">Link</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($events as $event)
            <tr>
                <td class="border px-4 py-2">
                    <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image" class="h-16 w-16">
                </td>
                <td class="border px-4 py-2">
                    <a href="{{ $event->link }}" target="_blank" class="text-blue-500">{{ $event->link }}</a>
                </td>
                <td class="border px-4 py-2">
                    <form action="{{ route('events.destroy', $event->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center border px-4 py-2">No events found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination Links -->
<div class="mt-4">
    {{ $events->links() }}
</div>

</div>
@endsection
