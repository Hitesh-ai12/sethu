<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white shadow-md rounded-lg">
        <!-- ✅ Logo Image -->
        <div class="flex justify-center">
            <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="h-16">
        </div>

        <h2 class="text-2xl font-bold text-center">Forget Password</h2>

        <p class="text-sm text-center text-gray-600">Verify your email or phone</p>

        <!-- ✅ Forget Password Form -->
        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf

            <!-- ✅ Email or Phone -->
            <div>
                <label class="block text-sm font-medium">Email or Phone</label>
                <input type="text" name="email" class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
            </div>

            <!-- ✅ Submit Button -->
            <button type="submit" class="w-full py-2 text-white bg-orange-500 rounded hover:bg-orange-600">
                Verify
            </button>
        </form>
    </div>
</body>
</html>
