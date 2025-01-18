<!-- On-Hold Tickets Section -->
<div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-extrabold text-gray-700 dark:text-white mb-6 text-center">
        On-Hold Tickets
    </h2>
    <!-- Search Bar -->
    <div class="mb-4 flex justify-center">
        <input type="text" id="search-on-hold" class="px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Search for tickets..." />
    </div>

    <table id="on-hold-tickets" class="w-full border-none bg-white dark:bg-gray-800 border rounded-lg shadow-md">
        <thead class="bg-gray-200 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider border-b">
                    Ticket Code
                </th>
                <th id="sort-action" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider border-b">
                    Action</span>
                </th>
            </tr>
        </thead>
        <tbody id="on-hold-tickets-body">
            <tr>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-300" colspan="2">No on-hold tickets available.</td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        <button id="previous-on-hold" class="px-4 py-2 bg-gray-600 text-white rounded-lg" disabled>Previous</button>
        <span id="on-hold-page-number" class="mx-4 text-gray-700 dark:text-gray-300">Page 1</span>
        <button id="next-on-hold" class="px-4 py-2 bg-gray-600 text-white rounded-lg">Next</button>
    </div>
</div>




<script>
    // Pagination variables for On-Hold Tickets
    let onHoldPage = 1;
    let onHoldTicketsPerPage = 20;  
    let totalOnHoldPages = 1;
    let searchQuery = '';  // Store search query

    // Get On-Hold Tickets with Search
    function getOnHoldTickets(page = 1) {
        $.ajax({
            url: `{{ route('allTicketsOnHold', ['window_id' => $window->id]) }}?page=${page}&per_page=${onHoldTicketsPerPage}&search=${searchQuery}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    totalOnHoldPages = response.total_pages;

                    // Check if there are tickets to display
                    if (response.tickets.length === 0) {
                        $('#on-hold-tickets-body').html(
                            `<tr>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300" colspan="2">No on-hold tickets available.</td>
                            </tr>`
                        );
                    } else {
                        $('#on-hold-tickets-body').html(
                            response.tickets.map(ticket =>
                                `<tr>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">${ticket.code}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                        <button onclick="handleTicket(${ticket.code})">Handle Ticket</button>
                                    </td>
                                </tr>`
                            ).join('')
                        );
                    }

                    $('#on-hold-page-number').text(`Page ${page}`);
                    $('#previous-on-hold').prop('disabled', page === 1);
                    $('#next-on-hold').prop('disabled', page === totalOnHoldPages);
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

    // Handle Previous/Next Pagination for On-Hold Tickets
    $('#previous-on-hold').on('click', function() {
        if (onHoldPage > 1) {
            onHoldPage--;
            getOnHoldTickets(onHoldPage);
        }
    });

    $('#next-on-hold').on('click', function() {
        if (onHoldPage < totalOnHoldPages) {
            onHoldPage++;
            getOnHoldTickets(onHoldPage);
        }
    });

    // Handle Search Input
    $('#search-on-hold').on('input', function() {
        searchQuery = $(this).val(); // Get the search query
        onHoldPage = 1; // Reset to the first page when searching
        getOnHoldTickets(onHoldPage); // Fetch tickets based on search query
    });


</script>