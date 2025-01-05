<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/ViewQueuewindows.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-12 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <!-- Main Container -->
            <div class="p-8 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg">
                <!-- Header -->
                <div class="mb-12">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $queue->name }}</h1>
                    <p class="text-lg text-gray-500 dark:text-gray-400">Manage access and details for this queue.</p>
                </div>

                <!-- Window Groups Section -->
                <div class="mb-12">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Window Groups</h2>
                    @if ($queue->windows && $queue->windows->isNotEmpty())
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($queue->windows as $window)
                                <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-md hover:shadow-lg transition-transform duration-200 hover:scale-105">
                                    <a href="{{ route('admin.window.view', ['id' => $window->id]) }}" class="block p-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                                                <i class="fas fa-window-restore text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $window->name }}</h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $window->description }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    <form action="{{ route('admin.window.delete', ['id' => $window->id]) }}" method="POST" class="border-t border-gray-200 dark:border-gray-600">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full py-3 text-sm font-semibold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No window groups available.</p>
                    @endif
                </div>

                <!-- Add Window Group Form -->
                <div class="mb-12">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Add New Window Group</h2>
                    <form action="{{ route('admin.window.create', ['queue_id' => $queue->id]) }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" id="name" name="name" class="w-full p-3 border border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-800 focus:ring focus:ring-blue-300 dark:focus:ring-blue-500 transition" required>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea id="description" name="description" class="w-full p-3 border border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-800 focus:ring focus:ring-blue-300 dark:focus:ring-blue-500 transition" rows="3" required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-300 transition">
                                Add Window Group
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Users with Access -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Users with Access</h2>
                    </div>
                    <div class="p-6">
                        @if ($uniqueUsers->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">User</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Close Own</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Close Any</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Close Queue</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Clear Queue</th>
                                            <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($uniqueUsers as $user)
                                            @php $access = $accessList->firstWhere('user_id', $user->id); @endphp
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                <td class="px-6 text-center py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $user->name }}
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        @foreach ($userWindows->get($user->id, collect()) as $windowAccess)
                                                            <div>{{ $windowAccess->window->name }}</div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" {{ $access->can_close_own_window ? 'checked' : '' }} class="form-checkbox" data-id="{{ $user->id }}" data-field="can_close_own_window">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" {{ $access->can_close_any_window ? 'checked' : '' }} class="form-checkbox" data-id="{{ $user->id }}" data-field="can_close_any_window">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" {{ $access->can_close_queue ? 'checked' : '' }} class="form-checkbox" data-id="{{ $user->id }}" data-field="can_close_queue">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" {{ $access->can_clear_queue ? 'checked' : '' }} class="form-checkbox" data-id="{{ $user->id }}" data-field="can_clear_queue">
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition update-access" data-user-id="{{ $user->id }}" data-queue-id="{{ $queue->id }}">Update</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">No users have access to this queue.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.update-access').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                const queueId = this.getAttribute('data-queue-id');
                const canCloseOwnWindow = document.querySelector(`input[data-id="${userId}"][data-field="can_close_own_window"]`).checked;
                const canCloseAnyWindow = document.querySelector(`input[data-id="${userId}"][data-field="can_close_any_window"]`).checked;
                const canCloseQueue = document.querySelector(`input[data-id="${userId}"][data-field="can_close_queue"]`).checked;
                const canClearQueue = document.querySelector(`input[data-id="${userId}"][data-field="can_clear_queue"]`).checked;


                fetch(`/update-access/${userId}/${queueId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        can_close_own_window: canCloseOwnWindow,
                        can_close_any_window: canCloseAnyWindow,
                        can_close_queue: canCloseQueue,
                        can_clear_queue: canClearQueue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Access privileges updated successfully.');
                    } else {
                        alert('Failed to update access privileges.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating access privileges.');
                });
            });
        });
    });
</script>