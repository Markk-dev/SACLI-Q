<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/QueueDetails.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-4 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <!-- Main Container -->
            <div class="mt-8 max-w-7xl mx-auto">
                <!-- Queue Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $queue->name }}</h1>
                                <div class="mt-2 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                    {{ $queue->status === 'open' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    <div class="w-2 h-2 rounded-full mr-2 
                                        {{ $queue->status === 'open' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                    {{ ucfirst($queue->status) }}
                                </div>
                            </div>
                            <!-- Quick Actions -->
                            <div class="flex space-x-3">
                                <button class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 
                                    dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 
                                    rounded-lg transition-colors duration-150 close-queue" data-id="{{ $queue->id }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $queue->status === 'open' ? "Close":"Open" }} Queue
                                </button>
                                <button class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 
                                    dark:bg-red-900 dark:hover:bg-red-800 text-red-600 dark:text-red-200 
                                    rounded-lg transition-colors duration-150 clear-queue" data-id="{{ $queue->id }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Clear Queue
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Window Groups Section -->
                    <div class="px-6 py-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Window Groups</h2>
                        </div>

                        @if ($queue->windows->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Window Group
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Toggle
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($queue->windows as $window)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $window->name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $window->status == 'open' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                        {{ ucfirst($window->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" 
                                                            class="sr-only peer toggle-window" 
                                                            data-id="{{ $window->id }}" 
                                                            data-queue-id="{{ $queue->id }}"
                                                            {{ $window->status == 'open' ? 'checked' : '' }}>
                                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer 
                                                            dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-blue-300 
                                                            dark:peer-focus:ring-blue-800 peer-checked:after:translate-x-full 
                                                            peer-checked:after:border-white after:content-[''] after:absolute 
                                                            after:top-0.5 after:left-[2px] after:bg-white after:rounded-full 
                                                            after:h-5 after:w-5 after:transition-all dark:border-gray-600 
                                                            peer-checked:bg-blue-600">
                                                        </div>
                                                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                            {{ $window->status == 'open' ? 'Open' : 'Closed' }}
                                                        </span>
                                                    </label>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No Window Groups</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No window groups have been created for this queue yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-window').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const id = this.getAttribute('data-id');
                const queueId = this.getAttribute('data-queue-id');

                fetch(`/window-groups/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        queue_id: queueId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Window status updated successfully.');
                        location.reload();
                    } else {
                        alert('Failed to update window status.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the window status.');
                });
            });
        });

        document.querySelector('.close-queue').addEventListener('click', function () {
            const id = this.getAttribute('data-id');

            fetch(`/queues/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Queue status updated successfully.');
                    location.reload();
                } else {
                    alert('Failed to update queue status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the queue status.');
            });
        });

        document.querySelector('.clear-queue').addEventListener('click', function () {
            const id = this.getAttribute('data-id');

            fetch(`/queues/${id}/clear`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Queue cleared successfully.');
                    location.reload();
                } else {
                    alert('Failed to clear queue.');
                }
            })
            .catch(error => {
                
                console.error('Error:', error);
                alert('An error occurred while clearing the queue.');
            });
        });
    });
</script>