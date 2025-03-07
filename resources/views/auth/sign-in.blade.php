<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white shadow-md rounded-lg">
        <div class="flex justify-center">
            <img src="{{ asset('storage/sethu.png') }}" alt="Logo" class="h-16">
        </div>

        <h2 class="text-2xl font-bold text-center">Sign In</h2>
        <p class="text-sm text-center text-gray-600">Welcome back</p>

        <!-- Loader Overlay (Improved) -->
        <div id="loader-overlay" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-white border-t-orange-500 rounded-full animate-spin"></div>
            </div>
        </div>

        <form id="login-form" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium">Email or Phone</label>
                <input type="text" id="email" name="email" class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" class="w-full p-2 border rounded focus:ring-2 focus:ring-orange-400 focus:outline-none" required>
                    <span id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer">👁️</span>
                </div>
            </div>

            <!--div class="text-right">
                <a href="{{ route('password.request') }}" class="text-sm text-orange-500">Forgot password?</a>
            </div-->

            <button type="submit" class="w-full py-2 text-white bg-orange-500 rounded hover:bg-orange-600">
                Sign In
            </button>
        </form>

        <!--div class="flex items-center justify-center">
            <span class="text-sm text-gray-500">Or</span>
        </div>

        <button class="w-full p-2 border rounded hover:bg-gray-200">Sign in with Google</button>
        <button class="w-full p-2 border rounded hover:bg-gray-200">Sign in with Apple</button>

        <p class="text-center text-sm">
            Don’t have an account?
            <a href="{{ route('sign-up') }}" class="text-orange-500 font-bold">Sign up</a>
        </p-->
    </div>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        $("#login-form").submit(function (e) {
    e.preventDefault();

    let email = $("#email").val();
    let password = $("#password").val();
    let csrfToken = $('meta[name="csrf-token"]').attr("content");

    $("#login-form input, #login-form button").prop("disabled", true);
    $("#login-form").css({ "pointer-events": "none", "opacity": "0.5" });

    $("#loader-overlay").removeClass("hidden");

    $.ajax({
        url: "{{ route('login') }}",
        type: "POST",
        data: {
            email: email,
            password: password,
            _token: csrfToken
        },
        success: function (response) {
            $("#loader-overlay").addClass("hidden");

            Swal.fire({
                icon: "success",
                title: "Login Successful",
                text: "Redirecting...",
                timer: 1000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = response.redirect;
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Login Failed",
                text: xhr.responseJSON?.message || "Invalid credentials"
            });

            $("#loader-overlay").addClass("hidden");
            $("#login-form input, #login-form button").prop("disabled", false);
            $("#login-form").css({ "pointer-events": "auto", "opacity": "1" });
        }
    });
});


        $("#toggle-password").click(function () {
            let passwordField = $("#password");
            let type = passwordField.attr("type") === "password" ? "text" : "password";
            passwordField.attr("type", type);
        });
    });
</script>
</body>
</html>
