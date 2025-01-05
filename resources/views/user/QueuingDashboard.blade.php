<!-- filepath: /d:/XAMPP/htdocs/SACLIQueue/resources/views/QueuingDashboard.blade.php -->
<x-Dashboard>
    <x-slot name="content">
        <div class="mt-8 p-6 sm:ml-64 bg-gray-50 dark:bg-gray-900 min-h-screen rounded-lg shadow-lg">
            <!-- Header Section -->
            <header class="mb-8">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-window-maximize text-4xl text-indigo-600"></i>
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">{{ $window->name }}</h1>
                        <p class="text-md text-gray-600 dark:text-gray-400">
                            Belongs to Queue: <span class="font-semibold">{{ $window->queue->name }}</span>
                        </p>
                    </div>
                </div>
            </header>

            <div class="grid grid-rows-1 grid-cols-2 gap-x-3">
                <div class="grid grid-cols-1 gap-3">
                    <!-- window Input Section -->
                    <section class=" p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Your window</h2>
                        <form id="window-form" class="space-y-6 ">
                            <div class="flex items-center space-x-4">
                                <label for="window-name" class="w-12/12 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    window Name:
                                </label>
                                <input 
                                type="text" 
                                id="window-name" 
                                name="window_name" 
                                placeholder="{{ $windowAccess->window_name?? 'Ex: window 1' }}"
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
                            <strong>window For:</strong> {{ $window->name }} - {{ $window->description }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-300">
                            <strong>Queue:</strong> {{ $window->queue->name }} 
                        </p>
                        <div class="mt-6 p-4 bg-gray-700 text-white rounded-lg shadow-lg">
                            <h2 class="text-xl font-bold mb-2">Number of tickets left: </h2>
                            <strong id="upcoming-tickets-count" class="text-2xl font-semibold"></strong>
                        </div>
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
                        class="flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Move to the next ticket in the queue">
                        <i class="fa-solid fa-play w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Get Next Ticket</span>
                    </button>
                    
                    <button 
                        id="next-ticket-hold"
                        class="flex items-center justify-center space-x-2 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Get a ticket that is on hold">
                        <i class="fa-solid fa-clock w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Get from hold</span>
                    </button>
                    
                    <button 
                        id="complete-ticket"
                        class="flex items-center justify-center space-x-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Mark the current ticket as completed">
                        <i class="fa-solid fa-check w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Complete</span>
                    </button>
                    
                    <button 
                        id="hold-ticket"
                        class="flex items-center justify-center space-x-2 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Put the current ticket on hold">
                        <i class="fa-solid fa-pause w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Put on hold</span>
                    </button>
                    
                    <button 
                        id="call-ticket"
                        class="flex items-center justify-center space-x-2 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-4 rounded shadow-lg relative group transition duration-300 ease-in-out transform hover:scale-105"
                        title="Call the current ticket to the window">
                        <i class="fa-solid fa-volume-high w-5 h-5 flex items-center justify-center"></i>
                        <span class="whitespace-nowrap">Call</span>
                    </button>
                </div>
            </section>

            {{-- <!-- Queue Management Section -->
            <section class="mt-6 p-6 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Queue Management</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded shadow-lg relative group"
                        title="Open windows associated with this window group">
                        Open windows: {{ $window->name }}
                    </button>
                    <button 
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded shadow-lg relative group"
                        title="Open the queue associated with this window group">
                        Open Queue: {{ $window->queue->name }}
                    </button>
                </div>
            </section> --}}


            <!-- Add HTML elements to display the tickets -->
            <div class="mt-10 grid grid-cols-2 gap-6">
                <!-- On-Hold Tickets Section -->
                <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-lg mb-10">
                    <h2 class="text-2xl font-extrabold text-gray-700 dark:text-white mb-6 text-center">
                        🎟️ On-Hold Tickets
                    </h2>
                    <table id="on-hold-tickets" class="w-full rounded-lg bg-white dark:bg-gray-800  border-none rounded-lg shadow-md">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 rounded-lg py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider border-b">
                                    Ticket Code
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">No on-hold tickets available.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            
                <!-- Completed Tickets Section -->
                <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white mb-6 text-center">
                        ✅ Recently Completed Tickets
                    </h2>
                    <table id="completed-tickets" class="w-full border-none bg-white dark:bg-gray-800 border rounded-lg shadow-md">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm  rounded-lg font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider border-b">
                                    Ticket Code
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">No completed tickets available.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            

        </div>
    </x-slot>
</x-Dashboard>

<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>

<script>
$(document).ready(function() {
    var token = "{{ session('token') }}";
    var window_id = "{{ $window->id }}";

    function getCurrentTicketForWindow(window_id) {
        
        $.ajax({
            url: "{{ route('getCurrentTicketForWindow', ['window_id' => $window->id]) }}",
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response.success) {
                    $('#current-ticket-number').text(response.ticket_number ?? 'N/A');
                    $('#current-ticket-name').text(response.name ?? 'N/A');

                    $('#next-ticket').prop('disabled', true).addClass('opacity-60'); 
                    $('#next-ticket-hold').prop('disabled', true).addClass('opacity-60');

                    $('#complete-ticket').prop('disabled', false).removeClass('opacity-60');
                    $('#hold-ticket').prop('disabled', false).removeClass('opacity-60');
                    $('#call-ticket').prop('disabled', false).removeClass('opacity-60');
                } else {
                    $('#current-ticket-number').text('N/A');
                    $('#current-ticket-name').text('N/A');
                    
                    $('#next-ticket').prop('disabled', false).removeClass('opacity-60'); 
                    $('#next-ticket-hold').prop('disabled', false).removeClass('opacity-60');

                    $('#complete-ticket').prop('disabled', true).addClass('opacity-60');
                    $('#hold-ticket').prop('disabled', true).addClass('opacity-60');
                    $('#call-ticket').prop('disabled', true).addClass('opacity-60');
                }
            },
            error: function(xhr, status, error) {
                $('#next-ticket').prop('disabled', false).removeClass('opacity-60'); 
                $('#next-ticket-hold').prop('disabled', false).removeClass('opacity-60');

                $('#complete-ticket').prop('disabled', true).addClass('opacity-60');
                $('#hold-ticket').prop('disabled', true).addClass('opacity-60');
                $('#call-ticket').prop('disabled', true).addClass('opacity-60');

                $('#current-ticket-number').text('N/A');
                $('#current-ticket-name').text('N/A');
                alert("Error while fetching current tickets");
            }
        });
    }

    function getTables() {
        $.ajax({
            url: "{{ route('getUpcomingTicketsCount', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    console.log(response);
                    $('#upcoming-tickets-count').text(response.upcoming_tickets_count);
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching upcoming tickets count");
            }
        });

        $.ajax({
            url: "{{ route('allTicketsCompleted', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    console.log(response);
                    $('#completed-tickets tbody').html(
                        response.tickets.map(ticket =>
                            `<tr class="hover:bg-green-50 dark:hover:bg-green-900 transition-colors">
                                <td class="px-6 py-3 border-b text-gray-700 dark:text-gray-300 text-center font-medium">${ticket.code}</td>
                            </tr>`
                        ).join('')
                    );
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching completed tickets");
            }
        });

        $.ajax({
            url: "{{ route('allTicketsOnHold', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    console.log(response);
                    $('#on-hold-tickets tbody').html(
                        response.tickets.map(ticket =>
                            `<tr class="hover:bg-yellow-50 dark:hover:bg-yellow-900 transition-colors">
                                <td class="px-6 py-3 border-b text-gray-700 dark:text-gray-300 text-center font-medium">${ticket.code}</td>
                            </tr>`
                        ).join('')
                    );
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching on-hold tickets");
            }
        });
    }

    //Updating Window name
    $('#window-form').on('submit', function (e) {
        e.preventDefault();

        const windowId = {{ $window->id ?? 'null' }};
        const windowName = $('#window-name').val();

        $.ajax({
            url: "{{ route('updateWindowName', ['id' => $window->id]) }}",
            method: 'POST',
            data: {
                window_name: windowName,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while updating the window.');
            }
        });
    });


    //Controls

    $('#next-ticket').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('getNextTicketForWindow', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                console.log(response);
                if(response.success) {
                    getCurrentTicketForWindow(window_id);
                    getTables(window_id);
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

    $('#next-ticket-hold').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('getFromTicketsOnHold', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                console.log(response);
                if(response.success) {
                    getCurrentTicketForWindow(window_id);
                    getTables(window_id);
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

    $('#complete-ticket').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('setToComplete', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                console.log(response);
                if(response.success) {
                    getCurrentTicketForWindow(window_id);
                    getTables(window_id);
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

    $('#hold-ticket').on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('setToHold', ['window_id' => $window->id]) }}",
            method: 'GET',
            success: function(response) {
                console.log(response);
                if(response.success) {
                    getCurrentTicketForWindow(window_id);
                    getTables(window_id);
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


    getTables();
    getCurrentTicketForWindow(window_id);

    setInterval(() => {
        getCurrentTicketForWindow(window_id);
        getTables(window_id);
    }, 15000);
});
</script>