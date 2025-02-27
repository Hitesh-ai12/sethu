<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Sidebar + Content Wrapper -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md p-5">
            <h2 class="text-xl font-bold text-orange-600">Sethu</h2>
            <ul class="mt-5">
                <li class="py-2"><a href="{{ route('dashboard') }}" class="text-gray-700">Dashboard</a></li>
                <li class="py-2"><a href="{{ route('user.management') }}" class="text-gray-700">User Management</a></li>
                <li class="py-2"><a href="{{ route('resource.management') }}" class="text-gray-700">Resource Management</a></li>
                <li class="py-2"><a href="{{ route('announcements') }}" class="text-gray-700">Announcements</a></li>
                <li class="py-2"><a href="{{ route('notifications') }}" class="text-gray-700">Notifications</a></li>
                <li class="py-2"><a href="{{ route('settings') }}" class="text-gray-700">Settings</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-md sticky top-0 w-full p-4 flex justify-between items-center">
                <h1 class="text-lg font-semibold">Dashboard</h1>
                <div class="flex items-center">
                    <input type="text" placeholder="Search..." class="border p-2 rounded-md">
                    <span class="ml-4">Harish V</span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
