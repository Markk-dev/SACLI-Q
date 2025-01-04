<?php

use App\Events\UserQueued;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\QueuingDashboardController;
use App\Http\Middleware\ValidateUser;
use App\Http\Middleware\IsAdmin;

// Broadcast route
Route::get('/broadcast', function () {
    broadcast(new UserQueued());
});

// Main routes
Route::get('/', [MainController::class, 'index'])->name('index');
Route::get('/login', [MainController::class, 'index'])->name('index');
Route::post('/login', [MainController::class, 'login'])->name('login');
Route::get('/logout', [MainController::class, 'logout'])->name('logout')->middleware(ValidateUser::class);

// Dashboard and user management routes
Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard')->middleware(ValidateUser::class);
Route::get('/users', [MainController::class, 'users'])->name('users')->middleware(IsAdmin::class);
Route::post('/create-account', [MainController::class, 'createAccount'])->name('createAccount')->middleware(IsAdmin::class);
Route::delete('/delete-account/{id}', [MainController::class, 'deleteAccount'])->name('deleteAccount')->middleware(IsAdmin::class);

// Queue management routes
Route::get('/manage-queues', [QueueController::class, 'manageQueues'])->name('manageQueues')->middleware(ValidateUser::class);
Route::post('/create-queue', [QueueController::class, 'createQueue'])->name('createQueue')->middleware(IsAdmin::class);
Route::delete('/queues/{id}', [QueueController::class, 'deleteQueue'])->name('queue.delete');
Route::get('/queue/{id}', [QueueController::class, 'viewQueue'])->name('queue.view')->middleware(IsAdmin::class);


Route::post('/update-access/{user_id}/{queue_id}', [QueueController::class, 'updateAccess'])->name('update-access');Route::get('/queue/settings/{id}', [QueueController::class, 'manageQueue'])->name('queue.manage')->middleware(ValidateUser::class);
Route::post('/queues/{id}/toggle', [QueueController::class, 'toggleQueue'])->name('queue.toggle');
Route::post('/queues/{id}/clear', [QueueController::class, 'clearQueue'])->name('queue.clear');
Route::post('/window-groups/{id}/toggle', [QueueController::class, 'toggleWindow'])->name('window.toggle');

// Window group management routes
Route::get('/window-group/{id}', [QueueController::class, 'viewWindowGroup'])->name('windowGroups.view')->middleware(IsAdmin::class);
Route::post('/windowGroups/add/{queue_id}', [QueueController::class, 'addWindowGroup'])->name('windowGroups.add');
Route::delete('/windowGroups/remove/{id}', [QueueController::class, 'removeWindowGroup'])->name('windowGroups.remove');
Route::post('/windowGroups/{id}/assignUser', [QueueController::class, 'assignUserToWindowGroup'])->name('windowGroups.assignUser');
Route::delete('/windowGroups/{id}/removeUser/{user_id}', [QueueController::class, 'removeUserFromWindowGroup'])->name('windowGroups.removeUser');

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
Route::get('api/ticket/current/{windowGroupId}', [QueuingDashboardController::class, 'getCurrentTicketForWindow'])->name('getCurrentTicketForWindow'); // Get current ticket for a window
Route::get('api/ticket/next/{windowGroupId}', [QueuingDashboardController::class, 'getNextTicketForWindow'])->name('getNextTicketForWindow'); // Get next ticket for a window
Route::get('api/ticket/complete/{windowGroupId}', [QueuingDashboardController::class, 'setToComplete'])->name('setToComplete'); // Set ticket to complete
Route::get('api/ticket/on-hold/{windowGroupId}', [QueuingDashboardController::class, 'getNextOnHoldTicket'])->name('getFromTicketsOnHold'); // Get next on-hold ticket
Route::get('api/ticket/set-to-hold/{windowGroupId}', [QueuingDashboardController::class, 'putTicketOnHold'])->name('setToHold'); // Set ticket to on hold
Route::get('api/ticket/all-on-hold/{windowGroupId}', [QueuingDashboardController::class, 'getAllTicketsOnHold'])->name('allTicketsOnHold'); // Get all on-hold tickets
Route::get('api/ticket/upcoming/{windowGroupId}', [QueuingDashboardController::class, 'getUpcomingTicketsCount'])->name('getUpcomingTicketsCount'); // Get all tickets for a window group
Route::get('api/ticket/completed/{windowGroupId}', [QueuingDashboardController::class, 'getAllCompletedTickets'])->name('allTicketsCompleted'); // Get all completed tickets for a window group

// Fallback route
Route::fallback(function () {
    return redirect()->route('index');
});