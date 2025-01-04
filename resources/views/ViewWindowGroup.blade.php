<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/ViewWindowGroup.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        <div class="mt-12 pt-4 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <div class="mt-12 max-w-7xl mx-auto">
                <!-- Window Group Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-window-maximize text-3xl text-blue-600"></i>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $windowGroup->name }}</h1>
                                <span class="text-md text-gray-500 dark:text-gray-400">Window Details</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Description</h2>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $windowGroup->description }}
                        </p>
                    </div>
                </div>
                <!-- Assign Users Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Assign Users</h2>
                    </div>
                    <div class="px-6 py-4">
                        <form action="{{ route('windowGroups.assignUser', ['id' => $windowGroup->id]) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select User</label>
                                <select id="user_id" name="user_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    @foreach ($allUsers as $user)
                                        @if (!$users->contains($user))
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Assign User</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Users with Access Card -->
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="px-6 py-8 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Users with Access</h2>
                    </div>
                    <div class="px-6 py-4">
                        @if ($users->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                User
                                            </th>
                                            <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $user->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <form action="{{ route('windowGroups.removeUser', ['id' => $windowGroup->id, 'user_id' => $user->id]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-trash-alt"></i> Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-700 dark:text-gray-300">No users have access to this window group.</p>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </x-slot>
</x-Dashboard>

<style>
    .text-indigo-600:hover .fas {
        transform: scale(1.2);
        transition: transform 0.2s ease-in-out;
    }
</style>