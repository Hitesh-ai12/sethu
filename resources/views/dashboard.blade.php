@extends('layouts.app')

@section('content')

<!-- Welcome Section -->
<div class="bg-white p-6 shadow-md rounded-md">
    <h2 class="text-2xl font-bold">Good Morning, Harish!</h2>
    <p class="text-gray-500">05 Nov, 2024 | 9:30 AM</p>
    <p class="mt-3 text-gray-600">"A dream does not become reality through magic; it takes sweat, determination, and hard work." – Colin Powell</p>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 gap-4 mt-6">
    <div class="bg-white p-6 shadow-md rounded-md">
        <h3 class="text-lg font-semibold">Quick Actions</h3>
        <p class="text-gray-500">Attendance</p>
        <div class="flex justify-between mt-2">
            <span>Check-in</span>
            <span>9:30 AM</span>
        </div>
        <div class="flex justify-between mt-2">
            <span>Check-out</span>
            <span>6:30 AM</span>
        </div>
    </div>

    <div class="bg-white p-6 shadow-md rounded-md">
        <h3 class="text-lg font-semibold">Users Overview</h3>
        <p class="text-gray-500">Leave Statistics</p>
        <div class="mt-3">
            <p><span class="font-bold">12/20</span> days Annual Leave</p>
            <p><span class="font-bold">9/10</span> days Sick Leave</p>
            <p><span class="font-bold">9/10</span> days Mental Health</p>
        </div>
        <button class="mt-4 bg-orange-500 text-white py-2 px-4 rounded-md">Apply for Leave</button>
    </div>
</div>

<!-- Notifications and Announcements -->
<div class="grid grid-cols-2 gap-4 mt-6">
    <div class="bg-white p-6 shadow-md rounded-md">
        <h3 class="text-lg font-semibold">Notifications</h3>
        <ul class="mt-2 text-gray-600">
            <li>✅ Good Morning Harish, Have a Great Day!</li>
            <li>📜 Your Leave Request has been Approved!</li>
            <li>🎉 Two days off for Diwali!</li>
        </ul>
    </div>

    <div class="bg-white p-6 shadow-md rounded-md">
        <h3 class="text-lg font-semibold">Announcements</h3>
        <ul class="mt-2 text-gray-600">
            <li>🎉 Diwali Announcement - 2 days off!</li>
        </ul>
    </div>
</div>

@endsection
