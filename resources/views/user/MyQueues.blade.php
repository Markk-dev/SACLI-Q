<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-4 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <div class="mt-8 p-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
                <!-- Header Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">User  Queues and Window Groups</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">List of queues and windows you have access to.</p>
                </div>

                <!-- Queues Section -->
                <div class="mb-12">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white ">Queues</h2>
                    <div  class="text-white mb-8">Manage Queue and Window Availability</div>
                    @if ($queues->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($queues as $queue)
                                <a href="{{ route('queue.manage', ['id' => $queue->id]) }}" class="block p-6 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-300">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $queue->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Manage this queue</p>
                                    <div class="mt-2 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                    {{ $queue->status === 'open' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    <div class="w-2 h-2 rounded-full mr-2 
                                        {{ $queue->status === 'open' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                    {{ ucfirst($queue->status) }}
                                </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No queues found.</p>
                    @endif
                </div>

                <!-- Window Groups Section -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Your Windows</h2>
                    <div class="text-white mb-8">Setup your window and start queuing</div>
                    @if ($windows->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($windows as $window)
                                <a href="{{ route('QueuingDashboard', ['id' => $window->id]) }}" class="block p-6 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-300">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $window->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                        Belongs to Queue: <span class="font-semibold">{{ $window->queue->name }}</span>
                                    </p>
                                    <div class="mt-2 inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                    {{ $window->status === 'open' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    <div class="w-2 h-2 rounded-full mr-2 
                                        {{ $window->status === 'open' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                    {{ ucfirst($window->status) }}
                                </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">No window groups found.</p>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>
</x-Dashboard>