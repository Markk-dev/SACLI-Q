<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/QueueManagement.blade.php -->
<x-Dashboard>
    <x-slot name="content">         
        <div class="mt-8 p-4 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <div class="mt-8 p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            
                <div class="flex items-center space-x-4">
                    <i class="fas fa-list text-3xl text-blue-600"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Queue Management</h1>
                        <span class="text-md text-gray-500 dark:text-gray-400">Create and manage queues</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <form method="GET" action="{{ route('manageQueues') }}" class="w-full sm:w-auto sm:mr-4">
                        <div class="flex items-center w-full sm:w-auto mt-4">
                            <input type="text" name="search" placeholder="Search queues..." class="w-full sm:w-96 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Search</button>
                        </div>
                    </form>
                    <button id="toggleModalButton" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Queue
                    </button>
                </div>

                <hr>
                
                <!-- Create Queue Modal -->
                <div id="createQueueModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Create New Queue</h1>
                                <form id="createQueueForm" class="mt-4" action="{{ route('createQueue') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Queue Name</label>
                                        <input required type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        @error('name')
                                            <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                
                                    <hr class="border-t-2 border-gray-200 dark:border-gray-700" />
            
                                    <div id="windowGroupsContainer" class="mt-4">
                                        {{-- Group 1 --}}
                                        <div class="window-group mb-4 flex w-1/1">
                                            <div class="mb-4 flex">
                                                <div class="flex items-center justify-between w-1/3">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Window Group 1</h3>
                                                </div>
                                            </div>
                                            <div class="flex items-center flex-col w-2/3 text-left">
                                                <label for="window_group_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 w-full">Name</label>
                                                    <input placeholder="Cashier" required type="text" id="window_group_name" name="window_groups[0][name]" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                    @error('window_groups.0.name')
                                                        <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                                    @enderror
                                        
                                                    <label placeholder="Queue to pay for Tuition or other cashier services" for="window_group_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-2 w-full">Description</label>
                                                    <input required type="text" id="window_group_description" name="window_groups[0][description]" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                    @error('window_groups.0.description')
                                                        <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                            <button type="button" onclick="deleteWindowGroup(event)"  class="removeWindowGroupButton w-16  text-gray-600 absolute right-0">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <hr class="border-t-2 border-gray-200 dark:border-gray-700" />
                                        </div>
                                        {{-- Window group 2 .... --}}
                                    </div>
                                
                                    <div class="flex justify-center mt-4">
                                        <button type="button"  id="addWindowGroupButton" class="w-full bg-green-600 px-4 py-2 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <i class="fas fa-plus mr-2"></i> Add Window Group
                                        </button>
                                    </div>
                                    <div class="flex justify-end mt-6">
                                        <button type="button" id="closeModalButton" class=" w-1/2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 mr-2">Cancel</button>
                                        <button type="submit" class="w-1/2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Create Queue</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table for Queues --}}
                <div class="mt-8 relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-900 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-8/12">Queue Name</th>
                                <th scope="col" class="px-6 py-3 w-2/12">Action</th>
                                <th scope="col" class="px-6 py-3 w-2/12">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($queues as $queue)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $queue->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{route('queue.view',['id'=>$queue->id])}}" class="text-grey-600 hover:text-indigo-500 flex items-center">
                                            <i class="fas fa-eye mr-2"></i> View
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('queue.delete', ['id' => $queue->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                                                <i class="fas fa-trash-alt mr-2"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $queues->links() }}
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

<script>
    document.getElementById('toggleModalButton').addEventListener('click', function() {
        var modal = document.getElementById('createQueueModal');
        modal.classList.remove('hidden');
    });

    document.getElementById('closeModalButton').addEventListener('click', function() {
        var modal = document.getElementById('createQueueModal');
        modal.classList.add('hidden');
    });

    document.getElementById('addWindowGroupButton').addEventListener('click', function() {
        var container = document.getElementById('windowGroupsContainer');
        var index = container.getElementsByClassName('window-group').length;
        
        var newGroup = document.createElement('div');
        newGroup.className = 'window-group mb-4 flex w-1/1';
        newGroup.innerHTML = `
            <div class="mb-4 flex">
                <div class="flex items-center justify-between w-1/3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Window Group ${index + 1}</h3>
                </div>
            </div>
            <div class="flex items-center flex-col w-2/3 text-left">
                <label for="window_group_name_${index}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 w-full">Name</label>
                <input required type="text" id="window_group_name_${index}" name="window_groups[${index}][name]" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                <div id="error_name_${index}" class="mt-2 text-sm text-red-500"></div>
                
                <label for="window_group_description_${index}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-2 w-full">Description</label>
                <input required type="text" id="window_group_description_${index}" name="window_groups[${index}][description]" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                <div id="error_description_${index}" class="mt-2 text-sm text-red-500"></div>
            </div>
            <button type="button" onclick="deleteWindowGroup(event)"  class="removeWindowGroupButton w-16  text-gray-600 absolute right-0">
                <i class="fas fa-times"></i>
            </button>
        <hr class="border-t-2 border-gray-200 dark:border-gray-700" />
        `;

        // Append the new group to the container
        container.appendChild(newGroup);

    });


    function deleteWindowGroup(event) {

        // Remove the clicked window group
        const groupToRemove = event.target.closest('.window-group');
        if (groupToRemove) {
            groupToRemove.remove();
        }

        // Update all Window Group labels and related attributes
        const groups = document.querySelectorAll('.window-group');
        console.log(groups.length);

        groups.forEach((group, index) => {
            // Update the header text
            const header = group.querySelector('h3');
            if (header) {
                header.textContent = `Window Group ${index + 1}`;
            }

            // Update input IDs and names
            const nameInput = group.querySelector('input[id^="window_group_name_"]');
            const descriptionInput = group.querySelector('input[id^="window_group_description_"]');
            const errorNameDiv = group.querySelector('div[id^="error_name_"]');
            const errorDescriptionDiv = group.querySelector('div[id^="error_description_"]');

            if (nameInput) {
                nameInput.id = `window_group_name_${index}`;
                nameInput.name = `window_groups[${index}][name]`;
            }

            if (descriptionInput) {
                descriptionInput.id = `window_group_description_${index}`;
                descriptionInput.name = `window_groups[${index}][description]`;
            }

            if (errorNameDiv) {
                errorNameDiv.id = `error_name_${index}`;
            }

            if (errorDescriptionDiv) {
                errorDescriptionDiv.id = `error_description_${index}`;
            }
        });
    }
</script>