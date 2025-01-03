<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/QueuingDashboard.blade.php -->
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

            <div class="grid grid-rows-1 grid-cols-2 gap-x-3">
                <div class="grid grid-cols-1 gap-3">
                    <!-- Window Input Section -->
                    <section class=" p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Your Window</h2>
                        <form id="window-form" class="space-y-6 ">
                            <div class="flex items-center space-x-4">
                                <label for="window-name" class="w-12/12 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Window Name:
                                </label>
                                <input 
                                    type="text" 
                                    id="window-name" 
                                    name="window-name" 
                                    placeholder="{{ $window->window_name ?? 'Ex: Window 1' }}"
                                    class="flex-grow w-9/12 px-3 py-2 border border-gray-400 focus:border-green-600 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"
                                >
                                <button type="submit" class="p-2 w-3/12 bg-green-600 text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 rounded-md">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </section>

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
                </div>
                
                <div class="grid grid-cols-1">
                    <!-- Currently Handling Section -->
                    <section class="p-8 bg-green-50 dark:bg-green-900 border-2 border-green-500 rounded-lg shadow-lg">
                        <h2 class="text-4xl font-extrabold text-green-800 dark:text-green-200 mb-6 text-center">Currently Handling</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-2xl">
                            <p class="w-full flex items-center justify-between text-green-800 dark:text-green-100 bg-white dark:bg-gray-800 p-4 rounded-md shadow-md">
                                <strong class="block">Ticket:</strong> 
                                <span id="current-ticket-number" class="font-semibold text-green-600 dark:text-green-300">N/A</span>
                            </p>
                            <p class="w-full flex items-center justify-between text-green-800 dark:text-green-100 bg-white dark:bg-gray-800 p-4 rounded-md shadow-md">
                                <strong class="block">Name:</strong> 
                                <span id="current-ticket-name" class="font-semibold text-green-600 dark:text-green-300">N/A</span>
                            </p>
                        </div>
                    </section>
                    
                </div>
            </div>


            <!-- Actions Section -->
            <section class="mt-6 p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <button 
                        id="next-ticket"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Move to the next ticket in the queue">
                        Next
                    </button>
                    <button 
                        id="next-ticket-hold"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Get a ticket that is on hold">
                        Get from hold
                    </button>
                    <button 
                        id="complete-ticket"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Mark the current ticket as completed">
                        Complete
                    </button>
                    <button 
                        id="call-ticket"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Call the current ticket to the window">
                        Call
                    </button>
                    <button 
                        id="hold-ticket"
                        class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Put the current ticket on hold">
                        Put on hold
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

<script type="module">
 $(document).ready(function() {
        let btnNext = $('#next-ticket');
        var token = "{{ session('token') }}";
        var windowGroupId = "{{ $windowGroup->id }}";

        //Update Window Name
        $('#window-form').on('submit', function(event) {
            event.preventDefault();
            var token = "{{ session('token') }}";
            var windowName = $('#window-name').val();

            $.ajax({
                url: "{{ route('updateWindowName', ['id' => $windowGroup->id]) }}",
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                },
                data: {
                    window_name: windowName,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                        alert('Successfully updated window name');
                    } else {
                        alert('Failed to update window name');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("Error while updating window name");
                }
            });
        });

        //Get Next Person on the queue
        btnNext.on('click', function(event) {
            event.preventDefault();
            var req = $.get({
                url: "{{ route('getNextTicketForWindow', ['windowGroupId' => $windowGroup->id]) }}",
                success: function(response) {
                    console.log(response);
                    // btbNext.prop('disabled', true);

                    if(response.success) {
                        alert(response['message']);
                    } else {
                        alert(response['message']);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert("Error while fetching data");
                }
            });
        });

        function getCurrentTicketForWindow(windowGroupId) {
            $.ajax({
                url: "{{ route('getCurrentTicketForWindow', ['windowGroupId' => $windowGroup->id]) }}",
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        $('#current-ticket-number').text(response.ticket_number ?? 'N/A');
                        $('#current-ticket-name').text(response.name ?? 'N/A');

                        $('#next-ticket').prop('disabled', true).addClass('opacity-60'); 
                        $('#next-ticket-hold').prop('disabled', false).addClass('opacity-60');
                        $('#complete-ticket').prop('disabled', false).removeClass('opacity-60');
                        $('#call-ticket').prop('disabled', false).removeClass('opacity-60');
                        $('#hold-ticket').prop('disabled', false).removeClass('opacity-60');
                    } else {
                        console.log(response.message);
                        $('#current-ticket-number').text('N/A');
                        $('#current-ticket-name').text('N/A');

                        $('#next-ticket').prop('disabled', false).removeClass('opacity-60');
                        $('#next-ticket-hold').prop('disabled', false).addClass('opacity-60');
                        $('#complete-ticket').prop('disabled', true).addClass('opacity-60');
                        $('#call-ticket').prop('disabled', true).addClass('opacity-60');
                        $('#hold-ticket').prop('disabled', true).addClass('opacity-60');
                    }
                },
                error: function(xhr, status, error) {
                    $('#next-ticket').prop('disabled', false).removeClass('opacity-60');
                    $('#next-ticket-hold').prop('disabled', false).addClass('opacity-60');
                    $('#complete-ticket').prop('disabled', true).addClass('opacity-60');
                    $('#call-ticket').prop('disabled', true).addClass('opacity-60');
                    $('#hold-ticket').prop('disabled', true).addClass('opacity-60');

                    $('#current-ticket-number').text('N/A');
                    $('#current-ticket-name').text('N/A');
                    alert("Error while fetching current tickets");
                }
            });
        }

        getCurrentTicketForWindow(windowGroupId);
    });
</script>