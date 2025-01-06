<?php

use App\Events\UserQueued;
use App\Events\DashboardEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\QueuingDashboardController;
use App\Http\Middleware\ValidateUser;
use App\Http\Middleware\IsAdmin;

// Broadcast route
Route::get('/broadcast', [QueuingDashboardController::class, 'broadcastEvent'])->name('broadcast.event');

// Main routes
Route::get('/', [MainController::class, 'index'])->name('index');//Working
Route::get('/login', [MainController::class, 'index'])->name('index');//Working
Route::post('/login', [MainController::class, 'login'])->name('login');
Route::get('/logout', [MainController::class, 'logout'])->name('logout')->middleware(ValidateUser::class);

// Dashboard and user management routes
Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard')->middleware(ValidateUser::class);
Route::get('/users', [MainController::class, 'users'])->name('user.list')->middleware(IsAdmin::class);
Route::post('/create-account', [MainController::class, 'createAccount'])->name('user.save')->middleware(IsAdmin::class);
Route::delete('/delete-account/{id}', [MainController::class, 'deleteAccount'])->name('user.delete')->middleware(IsAdmin::class);

// Managing queues (Admin)
Route::get('/queues', [QueueController::class, 'adminQueues'])->name('admin.queue.list')->middleware(ValidateUser::class);
Route::post('/create-queue', [QueueController::class, 'createQueue'])->name('admin.queue.create')->middleware(IsAdmin::class);
Route::delete('/queues/{id}', [QueueController::class, 'deleteQueue'])->name('admin.queue.delete');
Route::get('/queue/{id}', [QueueController::class, 'viewQueue'])->name('admin.queue.view')->middleware(IsAdmin::class);//Also functions as window.list

//Managing Windows of a queue(Admin)
Route::get('/window/{id}', [QueueController::class, 'viewWindow'])->name('admin.window.view')->middleware(IsAdmin::class);
Route::post('/window/add/{queue_id}', [QueueController::class, 'createWindow'])->name('admin.window.create')->middleware(IsAdmin::class);
Route::delete('/window/remove/{id}', [QueueController::class, 'removeWindow'])->name('admin.window.delete')->middleware(IsAdmin::class);
Route::post('/window/{id}/assignUser', [QueueController::class, 'assignUserToWindow'])->name('admin.window.user.add')->middleware(IsAdmin::class);
Route::delete('/window/{id}/removeUser/{user_id}', [QueueController::class, 'removeUserFromWindow'])->name('admin.window.user.remove')->middleware(IsAdmin::class);

//Managing a single queue based on access
Route::post('/update-access/{user_id}/{queue_id}', [QueueController::class, 'updateAccess'])->name('update-access');
Route::get('/queue/settings/{id}', [QueueController::class, 'manageQueue'])->name('queue.manage')->middleware(IsAdmin::class);
Route::post('/queues/{id}/toggle', [QueueController::class, 'toggleQueue'])->name('queue.toggle')->middleware(ValidateUser::class);
Route::post('/queues/{id}/clear', [QueueController::class, 'clearQueue'])->name('queue.clear')->middleware(ValidateUser::class);
Route::post('/window-groups/{id}/toggle', [QueueController::class, 'toggleWindow'])->name('window.toggle')->middleware(ValidateUser::class);



//Non admin controls
// User-specific queue routes
Route::get('/my-queues', [QueueController::class, 'myQueuesAndWindows'])->name('myQueues')->middleware(ValidateUser::class);
Route::get('/queuing-dashboard/{id}', [QueuingDashboardController::class, 'dashboard'])->name('QueuingDashboard')->middleware(ValidateUser::class);

// Queueing routes
Route::get('/SACLI-Q/live/{id}', [QueuingDashboardController::class, 'liveQueue'])->name('liveQueue');
Route::get('/SACLI-Q/ticketing/{id}', [QueuingDashboardController::class, 'ticketing'])->name('ticketing');
Route::post('/SACLI-Q/ticketing/submit', [QueuingDashboardController::class, 'ticketingSubmit'])->name('ticketing.submit');
Route::get('/SACLI-Q/ticketing/success/{id}', [QueuingDashboardController::class, 'ticketingSuccess'])->name('ticketing.success');

// API routes
Route::post('api/set-window/{id}', [QueuingDashboardController::class, 'updateWindow'])->name('updateWindowName'); // Update window name
Route::get('api/ticket/current/{window_id}', [QueuingDashboardController::class, 'getCurrentTicketForWindow'])->name('getCurrentTicketForWindow'); // Get current ticket for a window
Route::get('api/ticket/next/{window_id}', [QueuingDashboardController::class, 'getNextTicketForWindow'])->name('getNextTicketForWindow'); // Get next ticket for a window
Route::get('api/ticket/complete/{window_id}', [QueuingDashboardController::class, 'setToComplete'])->name('setToComplete'); // Set ticket to complete
Route::get('api/ticket/on-hold/{window_id}', [QueuingDashboardController::class, 'getNextOnHoldTicket'])->name('getFromTicketsOnHold'); // Get next on-hold ticket
Route::get('api/ticket/set-to-hold/{window_id}', [QueuingDashboardController::class, 'putTicketOnHold'])->name('setToHold'); // Set ticket to on hold
Route::get('api/ticket/all-on-hold/{window_id}', [QueuingDashboardController::class, 'getAllTicketsOnHold'])->name('allTicketsOnHold'); // Get all on-hold tickets
Route::get('api/ticket/upcoming/{window_id}', [QueuingDashboardController::class, 'getUpcomingTicketsCount'])->name('getUpcomingTicketsCount'); // Get all tickets for a window group
Route::get('api/ticket/completed/{window_id}', [QueuingDashboardController::class, 'getAllCompletedTickets'])->name('allTicketsCompleted'); // Get all completed tickets for a window group
Route::get('api/queue/{id}', [QueuingDashboardController::class, 'getLiveData'])->name('getLiveData'); // Get all completed tickets for a window group



// Fallback route
Route::fallback(function () {
    return redirect()->route('index');
});
