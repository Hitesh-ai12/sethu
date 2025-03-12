<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
        @vite(['resources/js/app.js'])

    </script>
<style>
.a{
    text-align: center;
}
</style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
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

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-md sticky top-0 w-full p-4 flex justify-between items-center">
                <h1 class="text-lg font-semibold">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Search..." class="border p-2 rounded-md">

                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <span>{{ Auth::user()->business_name ?? 'Guest' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-md overflow-hidden border z-50">
                            <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>

                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100" id="logoutBtn">Logout</button>
                        </div>
                    </div>
                </div>
            </header>
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

<script>
    $(document).ready(function () {
        $('#logoutBtn').click(function () {
            $.ajax({
                url: "{{ route('logout') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // console.log("Logout Response:", response);
                    // alert(response.message);

                    Swal.fire({
                        icon: "success",
                        title: "Logout Successful",
                        text: "Redirecting to Login...",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                },
                error: function (xhr) {
                    console.error("Logout failed:", xhr.responseText);
                }
            });
        });
    });
</script>
</body>
</html>
