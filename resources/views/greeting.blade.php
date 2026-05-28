<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Greeting System - Laravel 12 Testbench Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-12 max-w-2xl">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2"> User Greeting System</h1>
            <p class="text-gray-600 mb-8">Demonstrating Laravel 12 with Orchestral Testbench</p>
            
            @if(isset($greeting))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                    <p class="font-bold">Your Greeting:</p>
                    <p class="text-lg">{{ $greeting }}</p>
                    @if(isset($usedCache) && $usedCache)
                        <p class="text-sm mt-2"> (Served from cache)</p>
                    @endif
                </div>
            @endif
            
            <form method="POST" action="{{ url('/greet') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Name *
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $name ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter your name"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" 
                               name="use_cache" 
                               value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="text-sm text-gray-700">Enable caching (greeting saved for 1 hour)</span>
                    </label>
                </div>
                
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Get Greeting 
                    </button>
                    
                    <button type="button"
                            onclick="clearCache()"
                            class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Clear Cache 
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-600 mb-3"> Feature Highlights:</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✓ Time-based greetings (Morning/Afternoon/Evening/Night)</li>
                    <li>✓ Redis/Cache integration with 1-hour TTL</li>
                    <li>✓ Configurable message suffix</li>
                    <li>✓ Bulk greeting API endpoint</li>
                    <li>✓ Fully tested with Orchestral Testbench</li>
                </ul>
            </div>
        </div>
        
        <!-- API Testing Section -->
        <div class="bg-white rounded-lg shadow-xl p-8 mt-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4"> API Test Console</h2>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulk Greeting (JSON):</label>
                    <textarea id="bulkNames" rows="2" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg font-mono text-sm"
                              placeholder='["John", "Jane", "Bob"]'>["John", "Jane", "Bob"]</textarea>
                    <button onclick="testBulkGreeting()" 
                            class="mt-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-3 rounded text-sm">
                        Test Bulk API
                    </button>
                    <pre id="bulkResult" class="mt-3 bg-gray-100 p-3 rounded text-sm overflow-x-auto hidden"></pre>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        async function clearCache() {
            const name = document.querySelector('input[name="name"]').value;
            if (!name) {
                alert('Please enter a name first');
                return;
            }
            
            try {
                const response = await fetch('{{ url("/clear-cache") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: name })
                });
                
                const data = await response.json();
                alert(data.message);
            } catch (error) {
                alert('Error clearing cache');
            }
        }
        
        async function testBulkGreeting() {
            try {
                const names = JSON.parse(document.getElementById('bulkNames').value);
                const response = await fetch('{{ url("/api/bulk-greet") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ names: names })
                });
                
                const data = await response.json();
                const resultElement = document.getElementById('bulkResult');
                resultElement.textContent = JSON.stringify(data, null, 2);
                resultElement.classList.remove('hidden');
            } catch (error) {
                alert('Invalid JSON format or API error');
            }
        }
    </script>
</body>
</html>