@component('layouts.admin')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Update Password</h2>
            <p class="mt-2 text-gray-600">Keep your admin account secure by updating your password.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-6">
            @csrf

            <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Current Password
                </label>
                <input type="password"
                       id="current_password"
                       name="current_password"
                       required
                       class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        New Password
                    </label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 text-sm text-indigo-900">
                <p class="font-semibold mb-1">Password requirements:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Minimum 8 characters</li>
                    <li>At least one uppercase letter, lowercase letter, number, and symbol</li>
                    <li>Cannot be a commonly compromised password</li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition shadow-lg">
                    Update Password
                </button>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex-1 inline-flex items-center justify-center px-6 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endcomponent

