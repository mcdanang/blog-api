<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Blog</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="container mx-auto px-4">
        <header class="flex justify-between items-center py-6 border-b border-gray-300">
            <div class="text-3xl font-bold text-red-600">
                <!-- Replace with your blog's logo or name -->
                My Blog
            </div>
            <nav>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-gray-800 hover:bg-red-600 hover:text-white px-4 py-2 rounded transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-800 hover:bg-red-600 hover:text-white px-4 py-2 rounded transition">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="text-gray-800 hover:bg-red-600 hover:text-white px-4 py-2 ml-4 rounded transition">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <main class="text-center py-24">
            <h1 class="text-5xl font-extrabold text-gray-900 mb-6">
                Welcome to My Blog
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Explore interesting articles and stay updated with the latest trends. Join us today!
            </p>
            @guest
                <a href="{{ route('register') }}"
                    class="bg-red-600 text-white px-6 py-3 rounded-full hover:bg-red-700 transition">
                    Register
                </a>
                <a href="{{ route('login') }}"
                    class="bg-gray-800 text-white px-6 py-3 ml-4 rounded-full hover:bg-gray-900 transition">
                    Log in
                </a>
            @endguest
        </main>
    </div>
</body>

</html>
