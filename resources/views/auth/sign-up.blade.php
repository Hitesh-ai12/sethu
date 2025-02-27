<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold text-center">Create an account</h2>
        <p class="text-sm text-center text-gray-600">Lorem ipsum dolor sit amet consectetur.</p>

        <!-- ✅ AJAX Form -->
        <form id="registerForm" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium">Business Name</label>
                <input type="text" name="business_name" id="business_name"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Phone</label>
                <input type="text" name="phone" id="phone"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="terms" id="terms" class="mr-2" required>
                <label class="text-sm">I agree to the <a href="#" class="text-orange-500">terms and conditions</a></label>
            </div>

            <button type="submit" class="w-full py-2 text-white bg-orange-500 rounded hover:bg-orange-600">
                Sign Up
            </button>
        </form>

        <p class="text-center text-sm">
            Already have an account?
            <a href="{{ route('sign-in') }}" class="text-orange-500">Login</a>
        </p>
    </div>

    <!-- ✅ AJAX Form Submission -->
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('api.register') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire("Success!", response.message, "success");
                        $('#registerForm')[0].reset();
                    },
                    error: function(xhr) {
                        Swal.fire("Error!", xhr.responseJSON.message, "error");
                    }
                });
            });
        });
    </script>
</body>
</html>
