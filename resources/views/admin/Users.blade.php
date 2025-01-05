<x-Dashboard>
    <x-slot name="content">         
        <div class="mt-8 p-4 sm:ml-64 dark:bg-gray-700 min-h-screen">
            <div class="mt-8 p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
            
                <div class="flex items-center space-x-4">
                    <i class="fas fa-users text-3xl text-blue-600"></i>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">User Management</h1>
                        <span class="text-md text-gray-500 dark:text-gray-400">Create and manage employee accounts</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-center">
                    <form method="GET" action="{{ route('user.list') }}" class="flex-auto sm:w-auto sm:mr-4">
                        <div class="flex items-center w-100 sm:w-auto mt-4">
                            <input type="text" name="search" placeholder="Search users..." class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                            <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Search</button>
                        </div>
                    </form>
                    <button id="toggleModalButton" class="mt-4 sm:mt-0 flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create New User
                    </button>
                </div>

                <!-- Modal -->
                <div id="createUserModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Create New User</h1>
                                <form id="createUserForm" class="mt-4" action="{{ route('user.save') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Employee Name</label>
                                        <input required type="text" id="name" name="name" autocomplete="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        @error('name')
                                            <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account ID</label>
                                        <input required type="text" id="account_id" autocomplete="account_id" name="account_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        @error('account_id')
                                            <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                        <input required type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        @error('password')
                                            <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                                        <input required type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                        @error('password_confirmation')
                                            <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" id="closeModalButton" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 mr-2">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Create User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table for Users --}}
                <div class="mt-5 relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class=" w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class=" text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-900 dark:text-gray-400">                 <tr>
                                <th scope="col" class="px-6 py-3">
                                    User Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Account ID
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Access Type
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $user->account_id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ ucfirst($user->access_type) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->access_type == 'admin')
                                            <span class="text-gray-500 cursor-not-allowed">Edit</span>
                                            <span class="text-gray-500 cursor-not-allowed ml-4">Delete</span>
                                        @else
                                            <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('user.delete', ['id' => $user->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                                            </form>                                        
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>                    
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>         
    </x-slot>
</x-Dashboard>

<script>
    document.getElementById('toggleModalButton').addEventListener('click', function() {
        var modal = document.getElementById('createUserModal');
        modal.classList.remove('hidden');
    });

    document.getElementById('closeModalButton').addEventListener('click', function() {
        var modal = document.getElementById('createUserModal');
        modal.classList.add('hidden');
    });
</script>