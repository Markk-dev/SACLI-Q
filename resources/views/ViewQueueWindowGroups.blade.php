<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/ViewQueue.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-4 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <div class="mt-8 p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-list text-3xl text-blue-600"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $queue->name }}</h1>
                        <span class="text-md text-gray-500 dark:text-gray-400">Click Window Groups to see who has access</span>
                    </div>
                </div>

                <div class="mt-4">
                    @if ($queue->windowGroups && $queue->windowGroups->isNotEmpty())
                        @foreach ($queue->windowGroups as $windowGroup)
                            <div class="flex items-center p-6 mb-4 border border-gray-300 rounded-lg shadow-md bg-white dark:bg-gray-800 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                                <a href="{{ route('windowGroups.view', ['id' => $windowGroup->id]) }}" class="flex-1">
                                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $windowGroup->name }}</h2>
                                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $windowGroup->description }}
                                    </p>
                                </a>
                                <form action="{{ route('windowGroups.remove', ['id' => $windowGroup->id]) }}" method="POST" class="ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-transparent border border-red-600 rounded-lg hover:bg-red-50 hover:text-red-800 dark:border-red-500 dark:text-red-500 dark:hover:bg-red-900 dark:hover:text-red-300">
                                        <i class="fas fa-trash-alt mr-2"></i> Remove
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <div class="p-6 text-center border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <p class="text-gray-700 dark:text-gray-300">No window groups available.</p>
                        </div>
                    @endif
                </div>
                

                <hr class="my-4">

                <div class="mt-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Add New Window Group</h2>
                    <form action="{{ route('windowGroups.add', ['queue_id' => $queue->id]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name"  class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input required placeholder="Accounting"type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="mb-4">
                            <label for="description"  class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <input required type="text" placeholder="For Payment processes for tuition and other fees" id="description" name="description" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Add Window Group</button>
                        </div>
                    </form>
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