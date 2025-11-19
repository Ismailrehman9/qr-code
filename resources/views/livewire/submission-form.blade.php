<div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        @if($showSuccess)
            <!-- Success State with Numerology Reading -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Thank You! ✨</h2>
                    <p class="text-gray-600 mb-6">You're all set for the giveaway!</p>
                </div>

                <!-- Numerology Reading Section -->
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 mb-6 border-2 border-purple-200">
                    <div class="text-4xl mb-3">🔮</div>
                    <p class="text-lg text-gray-800 font-medium italic leading-relaxed">
                        {{ $numerologyReading ?: "Your age suggests a year of new beginnings and opportunities. Embrace the change!" }}
                    </p>
                </div>

                <div class="space-y-3">
                    <p class="text-sm text-gray-600">
                        Enjoy the show and good luck with the giveaway!
                    </p>
                    @if($whatsapp_optin)
                        <a href="https://wa.me/{{ config('app.whatsapp_number', '923001234567') }}" 
                           target="_blank"
                           class="inline-flex items-center justify-center w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Follow us on WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        @else
            <!-- Form State - ONLY THIS SHOWS -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🎭</span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Join the Giveaway!</h1>
                    <p class="text-gray-600">Seat #{{ $seat_qr_id }}</p>
                </div>

                @if (session()->has('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="submit" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                        <input type="text" 
                               id="name" 
                               wire:model="name" 
                               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition"
                               placeholder="John Doe">
                        @error('name') 
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" 
                               id="phone" 
                               wire:model="phone" 
                               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition"
                               placeholder="+92 300 1234567">
                        @error('phone') 
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                        <input type="email" 
                               id="email" 
                               wire:model="email" 
                               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition"
                               placeholder="john@example.com">
                        @error('email') 
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth *</label>
                        <input type="date" 
                               id="date_of_birth" 
                               wire:model="date_of_birth" 
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition">
                        @error('date_of_birth') 
                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-700 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror
                    </div>

                    <!-- WhatsApp Opt-in -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" 
                                   id="whatsapp_optin" 
                                   wire:model="whatsapp_optin" 
                                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        </div>
                        <label for="whatsapp_optin" class="ml-3 text-sm text-gray-700">
                            I'd like to receive updates and exclusive offers via WhatsApp
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-4 px-6 rounded-lg hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition duration-200 shadow-lg">
                        Join Giveaway 🎁
                    </button>
                </form>

                <p class="mt-6 text-center text-xs text-gray-500">
                    * Required fields. One entry per guest. By submitting, you agree to our terms.
                </p>
            </div>
        @endif
    </div>
</div>