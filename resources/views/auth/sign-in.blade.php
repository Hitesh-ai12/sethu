<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white shadow-md rounded-lg">
        <!-- ✅ Logo Image -->
        <div class="flex justify-center">
            <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="h-16">
        </div>

        <h2 class="text-2xl font-bold text-center">Sign In</h2>
        <p class="text-sm text-center text-gray-600">Welcome back</p>

        <!-- ✅ Sign-In Form -->
        <form action="{{ route('sign-in') }}" method="POST" class="space-y-4">
            @csrf

            <!-- ✅ Email or Phone -->
            <div>
                <label class="block text-sm font-medium">Email or Phone</label>
                <input type="text" name="email" class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
            </div>

            <!-- ✅ Password -->
            <div>
                <label class="block text-sm font-medium">Password</label>
                <div class="relative">
                    <input type="password" name="password" class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">👁️</span>
                </div>
            </div>

            <!-- ✅ Forgot Password -->
            <div class="text-right">
                <a href="{{ route('password.request') }}" class="text-sm text-orange-500">Forgot password?</a>
            </div>

            <!-- ✅ Sign-In Button -->
            <button type="submit" class="w-full py-2 text-white bg-orange-500 rounded hover:bg-orange-600">
                Sign In
            </button>
        </form>

        <!-- ✅ OR Separator -->
        <div class="flex items-center justify-center">
            <span class="text-sm text-gray-500">Or</span>
        </div>

        <!-- ✅ Social Logins -->
        <button class="w-full p-2 border rounded hover:bg-gray-200">Sign in with Google</button>
        <button class="w-full p-2 border rounded hover:bg-gray-200">Sign in with Apple</button>

        <!-- ✅ Sign-Up Link -->
        <p class="text-center text-sm">
            Don’t have an account?
            <a href="{{ route('sign-up') }}" class="text-orange-500 font-bold">Sign up</a>
        </p>
    </div>
</body>
</html>
