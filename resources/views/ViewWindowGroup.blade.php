<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/ViewWindowGroup.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-4 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <div class="mt-8 p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-window-maximize text-3xl text-blue-600"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $windowGroup->name }}</h1>
                        <span class="text-md text-gray-500 dark:text-gray-400">Window Group Details</span>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="p-6 mb-4 border border-gray-300 rounded-lg shadow-md bg-white dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Description</h2>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            {{ $windowGroup->description }}
                        </p>
                    </div>

                    <div class="p-4 mb-4 border border-gray-300 rounded-md dark:border-gray-600">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Users with Access</h2>
                        @if ($users->isNotEmpty())
                            <ul class="list-disc list-inside">
                                @foreach ($users as $user)
                                    <li class="text-gray-700 dark:text-gray-300 flex justify-between items-center">
                                        {{ $user->name }}
                                        <form action="{{ route('windowGroups.removeUser', ['id' => $windowGroup->id, 'user_id' => $user->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash-alt"></i> Remove
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-700 dark:text-gray-300">No users have access to this window group.</p>
                        @endif
                    </div>

                    <div class="p-4 mb-4 border border-gray-300 rounded-md dark:border-gray-600">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Assign Users</h2>
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