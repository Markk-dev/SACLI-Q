<?php

use App\Events\UserQueued;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\QueuingDashboardController;
use App\Http\Middleware\ValidateUser;
use App\Http\Middleware\IsAdmin;

Route::get('/broadcast', function () {
    broadcast(new UserQueued());
});


Route::get('/', [MainController::class, 'index']) -> name('index');
Route::post('/login', [MainController::class, 'login']) -> name('login');
Route::get('/logout', [MainController::class, 'index']) -> name('logout')->middleware(ValidateUser::class);
// Add logout later

//Dashboard, user management, queue management, window
Route::get('/dashboard', [MainController::class, 'dashboard']) -> name('dashboard')->middleware(ValidateUser::class);
Route::get('/users', [MainController::class, 'users']) -> name('users')->middleware(IsAdmin::class);
    Route::post('/create-account', [MainController::class, 'createAccount']) -> name('createAccount')->middleware(IsAdmin::class);
    Route::delete('/delete-account/{id}', [MainController::class, 'deleteAccount'])->name('deleteAccount')->middleware(IsAdmin::class);
Route::get('/manage-queues', [QueueController::class, 'manageQueues']) -> name('manageQueues')->middleware(ValidateUser::class);
    Route::post('/create-queue', [QueueController::class, 'createQueue'])->name('createQueue')->middleware(IsAdmin::class);
    Route::delete('/queues/{id}', [QueueController::class, 'deleteQueue'])->name('queue.delete');
    Route::get('/queue/{id}', [QueueController::class, 'viewQueue'])->name('queue.view')->middleware(IsAdmin::class); //View queue window groups and data
        //Managing window groups and users
        Route::get('/window-group/{id}', [QueueController::class, 'viewWindowGroup'])->name('windowGroups.view')->middleware(IsAdmin::class);
        Route::post('/windowGroups/add/{queue_id}', [QueueController::class, 'addWindowGroup'])->name('windowGroups.add');
        Route::delete('/windowGroups/remove/{id}', [QueueController::class, 'removeWindowGroup'])->name('windowGroups.remove');
        Route::post('/windowGroups/{id}/assignUser', [QueueController::class, 'assignUserToWindowGroup'])->name('windowGroups.assignUser');
        Route::delete('/windowGroups/{id}/removeUser/{user_id}', [QueueController::class, 'removeUserFromWindowGroup'])->name('windowGroups.removeUser');
Route::get('/my-queues', [QueueController::class, 'myQueuesAndWindows']) -> name('myQueues')->middleware(ValidateUser::class);
    Route::get('/queuing-dashboard/{id}', [QueuingDashboardController::class, 'show'])->name('QueuingDashboard')->middleware(ValidateUser::class);;

//Routes for queueing

Route::get('/SACLI-Q/live/{id}', [QueuingDashboardController::class, 'liveQueue'])->name('liveQueue');
Route::get('/SACLI-Q/ticketing/{id}', [QueuingDashboardController::class, 'ticketing'])->name('ticketing');
Route::post('/SACLI-Q/ticketing/submit', [QueuingDashboardController::class, 'ticketingSubmit'])->name('ticketing.submit');
Route::get('/SACLI-Q/ticketing/submit', function () {
    return redirect()->route('ticketing');
});



//Fallback route when an invalid url is entered
// Route::fallback(function () {
//     return redirect()->route('index');
// });