<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Giveaway System</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="text-center">
            <div class="mb-8">
                <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                    <span class="text-6xl">🎭</span>
                </div>
                <h1 class="text-5xl font-bold text-white mb-4">Interactive Giveaway System</h1>
                <p class="text-xl text-white/90 mb-8">Scan your seat's QR code to participate!</p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-block bg-white text-indigo-600 font-bold py-4 px-8 rounded-lg hover:bg-gray-100 transform hover:scale-105 transition duration-200 shadow-lg">
                    Admin Dashboard
                </a>
            </div>

            <div class="mt-12 text-white/80 text-sm">
                <p>Built with Laravel & Livewire</p>
            </div>
        </div>
    </div>
</body>
</html>
