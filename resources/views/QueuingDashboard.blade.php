<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-6 sm:ml-64 bg-gray-50 dark:bg-gray-900 min-h-screen rounded-lg shadow-lg">
            <!-- Header Section -->
            <header class="mb-8">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-window-maximize text-4xl text-indigo-600"></i>
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $windowGroup->name }}</h1>
                        <p class="text-md text-gray-600 dark:text-gray-400">
                            Belongs to Queue: <span class="font-semibold">{{ $windowGroup->queue->name }}</span>
                        </p>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Combined Description Section -->
                <section class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Details</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-2">
                        <strong>Window Group:</strong> {{ $windowGroup->name }} - {{ $windowGroup->description }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300">
                        <strong>Queue:</strong> {{ $windowGroup->queue->name }} 
                    </p>
                </section>
            
                <!-- Window Input Section -->
                <section class="p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Your Window</h2>
                    <div class="flex items-center space-x-4">
                        <label for="window-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Window Name:
                        </label>
                        <input type="text" id="window-name" name="window-name" placeholder="Window 1"
                            class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </section>
            </div>
            

            <!-- Currently Handling Section -->
            <section class="mt-6 p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Currently Handling</h2>
                <div class="grid grid-cols-2 gap-4">
                    <p class="text-gray-600 dark:text-gray-300">
                        <strong>Ticket:</strong> {{ $currentTicket->ticket_number ?? 'N/A' }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300">
                        <strong>Name:</strong> {{ $currentTicket->name ?? 'N/A' }}
                    </p>
                </div>
            </section>

            <!-- Actions Section -->
            <section class="mt-6 p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group"
                        title="Move to the next ticket in the queue">
                        Next
                    </button>
                    <button 
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group"
                        title="Call the current ticket to the window">
                        Call
                    </button>
                    <button 
                        class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group"
                        title="Put the current ticket on hold">
                        Hold
                    </button>
                    <button 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group"
                        title="Mark the current ticket as completed">
                        Complete
                    </button>
                </div>
            </section>

            <!-- Queue Management Section -->
            <section class="mt-6 p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Queue Management</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded shadow-lg relative group"
                        title="Open windows associated with this window group">
                        Open Windows: {{ $windowGroup->name }}
                    </button>
                    <button 
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded shadow-lg relative group"
                        title="Open the queue associated with this window group">
                        Open Queue: {{ $windowGroup->queue->name }}
                    </button>
                </div>
            </section>
        </div>
    </x-slot>
</x-Dashboard>
