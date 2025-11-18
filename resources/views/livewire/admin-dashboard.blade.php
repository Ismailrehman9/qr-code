<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header with Logout -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Dashboard Overview</h2>
            <p class="mt-2 text-gray-600">Real-time giveaway analytics and management</p>
        </div>
        <div class="flex items-center gap-4 ">
            <div class="flex flex-col items-end">
                <span class="text-sm text-gray-600">Welcome, {{ auth()->user()->name }}</span>
                <a href="{{ route('admin.password.edit') }}"
                   class="mt-1 inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.105.895-2 2-2s2 .895 2 2v1h1a2 2 0 012 2v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4a2 2 0 012-2h1v-1c0-1.105.895-2 2-2h0zM12 11V7a4 4 0 018 0v4" />
                    </svg>
                    Update Password
                </a>
            </div>
            <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
</form>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Submissions -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Submissions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalSubmissions) }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">Out of {{ number_format($totalQRCodes) }} seats</span>
            </div>
        </div>

        <!-- Today's Submissions -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Today</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($todaySubmissions) }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-green-600 font-medium">{{ now()->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- WhatsApp Opt-ins -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">WhatsApp Opt-ins</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($whatsappOptIns) }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">{{ $totalSubmissions > 0 ? round(($whatsappOptIns / $totalSubmissions) * 100, 1) : 0 }}% conversion rate</span>
            </div>
        </div>

        <!-- Unique Seats -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Unique Seats</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($uniqueSeats) }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-gray-500">Active QR codes used</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->

    <div class="mb-8 flex flex-wrap gap-4">
           @php
        $sheetsService = app(\App\Services\GoogleSheetsService::class);
    @endphp
    
    @if($sheetsService->isConfigured())
        <a href="{{ $sheetsService->getSheetUrl() }}" 
           target="_blank"
           class="inline-flex items-center px-4 py-2 font-semibold rounded-lg transition shadow-md" 
           style="background-color: #34A853; color: white;">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3h5v2h-5V6zm0 4h5v2h-5v-2zm0 4h5v2h-5v-2zM7 6h3v2H7V6zm0 4h3v2H7v-2zm0 4h3v2H7v-2z"/>
            </svg>
            Google Sheet
        </a>
    @endif
        <button wire:click="exportToCSV" 
                class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export CSV
        </button>

        {{-- <button wire:click="resetQRCodes" 
                class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Reset Expired QR Codes
        </button>

        <button wire:click="loadDashboardData" 
                class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh Data
        </button> --}}
    </div>

    <!-- Quick Actions Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- QR Code Management -->
        <div class="rounded-xl shadow-lg p-6 text-white" style="background: linear-gradient(to bottom right, rgb(168, 85, 247), rgb(79, 70, 229));">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">QR Code Management</h3>
                    <p class="mb-4" style="color: rgba(255, 255, 255, 0.9);">Generate, manage, and track all your QR codes</p>
                    <a href="{{ route('admin.qr-codes') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white font-semibold rounded-lg hover:bg-gray-50 transition shadow-md" style="color: rgb(147, 51, 234);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        Manage QR Codes
                    </a>
                </div>
                <div class="hidden lg:block" style="opacity: 0.2;">
                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Submissions Management -->
        <div class="rounded-xl shadow-lg p-6 text-white" style="background: linear-gradient(to bottom right, rgb(34, 197, 94), rgb(22, 163, 74));">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-2">User Submissions</h3>
                    <p class="mb-4" style="color: rgba(255, 255, 255, 0.9);">View all registered users and their data</p>
                    <a href="{{ route('admin.submissions') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white font-semibold rounded-lg hover:bg-gray-50 transition shadow-md" style="color: rgb(22, 163, 74);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        View Submissions
                    </a>
                </div>
                <div class="hidden lg:block" style="opacity: 0.2;">
                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

  <!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Age Bracket Distribution -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Age Distribution</h3>
        <canvas id="ageBracketChart"></canvas>
    </div>

    <!-- Submissions by Hour -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Submissions (Last 24 Hours)</h3>
        <canvas id="submissionsChart"></canvas>
    </div>
</div>

    <!-- Recent Submissions Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Recent Submissions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentSubmissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->submitted_at->format('M d, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $submission->seat_qr_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->phone }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->age }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($submission->whatsapp_optin)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Yes
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        No
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No submissions yet. Share those QR codes!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Wait for page to fully load
window.addEventListener('load', function() {
    console.log('Page loaded, initializing charts...');
    
    // Age Bracket Chart
    const ageBracketCanvas = document.getElementById('ageBracketChart');
    if (ageBracketCanvas) {
        console.log('Age bracket canvas found');
        
        const ageBracketData = {!! json_encode($ageBracketStats) !!};
        console.log('Age bracket data:', ageBracketData);
        
        const labels = Object.keys(ageBracketData);
        const data = Object.values(ageBracketData);
        
        // Check if there's data
        const hasData = data.some(value => value > 0);
        
        if (hasData) {
            new Chart(ageBracketCanvas, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#818CF8',
                            '#A78BFA',
                            '#C084FC',
                            '#E879F9',
                            '#F472B6',
                            '#FB923C'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
            console.log('Age bracket chart created');
        } else {
            // Show "No data" message
            ageBracketCanvas.parentElement.innerHTML = '<div class="flex items-center justify-center h-64"><p class="text-gray-500">No data available yet. Submit some forms to see the age distribution.</p></div>';
        }
    } else {
        console.error('Age bracket canvas not found!');
    }

    // Submissions by Hour Chart
    const submissionsCanvas = document.getElementById('submissionsChart');
    if (submissionsCanvas) {
        console.log('Submissions canvas found');
        
        const submissionsData = {!! json_encode($submissionsByHour) !!};
        console.log('Submissions data:', submissionsData);
        
        const hours = submissionsData.map(item => item.hour);
        const counts = submissionsData.map(item => item.count);
        
        // Check if there's data
        const hasData = counts.some(value => value > 0);
        
        if (hasData) {
            new Chart(submissionsCanvas, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Submissions',
                        data: counts,
                        borderColor: '#6366F1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: '#6366F1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    }
                }
            });
            console.log('Submissions chart created');
        } else {
            // Show "No data" message
            submissionsCanvas.parentElement.innerHTML = '<div class="flex items-center justify-center h-64"><p class="text-gray-500">No submissions in the last 24 hours.</p></div>';
        }
    } else {
        console.error('Submissions canvas not found!');
    }
});
</script>