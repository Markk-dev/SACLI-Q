<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/SelectWindowGroup.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-4 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <div class="mt-8 p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">User Queues and Window Groups</h1>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">List of Queues and windows you have access to</h2>
                <div class="mt-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Queues</h2>
                    @if ($queues->isNotEmpty())
                        <ul class="list-disc list-inside">
                            @foreach ($queues as $queue)
                                <li class="text-gray-700 dark:text-gray-300">{{ $queue->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-700 dark:text-gray-300">No queues found.</p>
                    @endif
                </div>
                <section class="mt-12">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Window Groups</h2>
                    @if ($windowGroups->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($windowGroups as $windowGroup)
                                <a href="{{ route('QueuingDashboard', ['id' => $windowGroup->id]) }}" class="block p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $windowGroup->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                        Belongs to Queue: <span class="font-semibold">{{ $windowGroup->queue->name }}</span>
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No window groups found.</p>
                    @endif
                </section>
            </div>
        </div>
    </x-slot>
</x-Dashboard>