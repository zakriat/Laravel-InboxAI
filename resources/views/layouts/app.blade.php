<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AI Email Parser</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Optional: Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900 antialiased h-full flex flex-col">
    <div class="container mx-auto px-4 py-6 flex-grow">
        <header class="mb-6 border-b pb-4 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-primary">Email Parser</h1>
            <nav class="space-x-4">
                <a href="{{ route('emails.index') }}" class="text-blue-500 hover:text-blue-700 transition duration-300 font-semibold">
                    View Emails
                </a>
                <a href="{{ route('emails.parsed') }}" class="text-blue-500 hover:text-blue-700 transition duration-300 font-semibold">
                    View Parsed Emails
                </a>
            </nav>
        </header>

        <main class="bg-white shadow-md rounded-lg p-6">
            @yield('content')
        </main>

        <footer class="mt-8 text-center text-gray-500 text-sm">
            &copy; {{ now()->year }} AI Email Parser. All rights reserved.
        </footer>
    </div>
</body>
</html>
