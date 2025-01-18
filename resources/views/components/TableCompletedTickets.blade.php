<style>
    /* Default arrow on hover */
    th .sort-arrow::after {
        content: '↕'; /* Default arrow */
        font-size: 12px;
        margin-left: 5px;
        transition: content 0.3s ease;
    }

    /* Sorting arrows when sorted */
    .sort-asc .sort-arrow::after {
        content: '↑'; /* Up arrow for ascending */
    }

    .sort-desc .sort-arrow::after {
        content: '↓'; /* Down arrow for descending */
    }

    /* Hover effect */
    th:hover {
        background-color: #f3f4f6; /* Light background on hover */
        cursor: pointer;
    }

    /* Hover effect when the header is dark mode */
    th.dark:hover {
        background-color: #4b5563; /* Darker background in dark mode */
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white mb-6 text-center">
        Recently Completed Tickets
    </h2>
    <table id="completed-tickets" class="w-full border-none bg-white dark:bg-gray-800 border rounded-lg shadow-md">
        <thead class="bg-gray-200 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider border-b">
                    Ticket Code
                </th>
                <th id="sort-ticket_number" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider border-b">
                    Ticket Number <span class="sort-arrow"></span>
                </th>
                <th id="sort-created_at" class="px-6 py-3 text-left text-sm font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider border-b">
                    Completed At <span class="sort-arrow"></span>
                </th>
            </tr>
        </thead>
        <tbody id="completed-tickets-body">
            <tr>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-300" colspan="3">No completed tickets available.</td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
        <button id="previous-completed" class="px-4 py-2 bg-gray-600 text-white rounded-lg" disabled>Previous</button>
        <span id="completed-page-number" class="mx-4 text-gray-700 dark:text-gray-300">Page 1</span>
        <button id="next-completed" class="px-4 py-2 bg-gray-600 text-white rounded-lg">Next</button>
    </div>
</div>

<script>
    // Pagination for Completed Tickets (similar logic)
    let completedPage = 1;
    let completedTicketsPerPage = 20;  // Adjust per page as needed
    let totalCompletedPages = 1;

    let sortBy = 'completed_at'; // Default sort by 'completed_at'
    let sortOrder = 'desc'; // Default sort order 'desc'

    function getCompletedTickets(page = 1) {
        $.ajax({
            url: `{{ route('allCompletedTickets', ['window_id' => $window->id]) }}?page=${page}&per_page=${completedTicketsPerPage}&sort_by=${sortBy}&sort_order=${sortOrder}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    totalCompletedPages = response.total_pages;
                    $('#completed-tickets-body').html(
                        response.tickets.map(ticket =>
                            `<tr>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">${ticket.code}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">${ticket.ticket_number}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">${ticket.completed_at}</td>
                            </tr>`
                        ).join('')
                    );
                    $('#completed-page-number').text(`Page ${page}`);
                    $('#previous-completed').prop('disabled', page === 1);
                    $('#next-completed').prop('disabled', page === totalCompletedPages);
                } else {
                    $('#completed-tickets-body').html(
                        `<tr>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300" colspan="3">No completed tickets available.</td>
                        </tr>`
                    );
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Error while fetching completed tickets");
            }
        });
    }

    // Sorting for completed tickets
    $('#sort-created_at').on('click', function() {
        // Toggle sorting order
        sortBy = 'completed_at';
        sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
        // Toggle arrow classes
        toggleSortArrow($(this), sortOrder);
        getCompletedTickets(completedPage); // Fetch sorted data
    });

    $('#sort-ticket_number').on('click', function() {
        // Toggle sorting order
        sortBy = 'ticket_number';
        sortOrder = (sortOrder === 'asc') ? 'desc' : 'asc';
        // Toggle arrow classes
        toggleSortArrow($(this), sortOrder);
        getCompletedTickets(completedPage); // Fetch sorted data
    });

    function toggleSortArrow(element, order) {
        // Reset arrow for all headers
        $('#sort-created_at').removeClass('sort-asc sort-desc');
        $('#sort-ticket_number').removeClass('sort-asc sort-desc');

        console.log("haha")

        // Apply appropriate class based on order
        if (order === 'asc') {
            console.log("haha")
            element.addClass('sort-asc');
        } else {
            console.log("haha")
            element.addClass('sort-desc');
        }
    }

    $('#previous-completed').on('click', function() {
        if (completedPage > 1) {
            completedPage--;
            getCompletedTickets(completedPage);
        }
    });

    $('#next-completed').on('click', function() {
        if (completedPage < totalCompletedPages) {
            completedPage++;
            getCompletedTickets(completedPage);
        }
    });

    // Initial fetch on page load
    getCompletedTickets(completedPage);
</script>
