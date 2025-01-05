<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Queue;
use App\Models\Ticket;
use App\Models\Window; 
use App\Models\WindowAccess; 
class QueueController extends Controller
{
    // Managing Queues
    // definition of ('admin.queue.list)
    public function adminQueues(Request $request)
    {
        $query = Queue::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $queues = $query->with('Windows')->paginate(10);
        $Windows = Window::all();

        return view('admin.queues', compact('queues', 'Windows'));
    }

    //Create/ Delete a queue a new queue and redirect to route('admin.queue.list')
    public function createQueue(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:queues,name',
            'window_groups' => 'array',
            'window_groups.*.name' => 'string',
            'window_groups.*.description' => 'string',
        ]);
    
        $queue = new Queue();
        $queue->name = $request->name;
        $queue->save();
    
        if($request->window_groups != null){
            foreach ($request->window_groups as $WindowData) {
                $Window = new Window($WindowData);
                $queue->Windows()->save($Window);
            }
        }
    
        return redirect()->route('admin.queue.list')->with('success', 'Queue created successfully.');
    }
    public function deleteQueue($id)
    {
        $queue = Queue::findOrFail($id);
        $queue->delete();

        return redirect()->route('admin.queue.list')->with('success', 'Queue deleted successfully.');
    }

    //To see associated queue windows and other data
    public function viewQueue($id)
    {
        $queue = Queue::with('Windows')->findOrFail($id);
        $WindowIds = $queue->Windows->pluck('id');
        $uniqueUserIds = WindowAccess::whereIn('window_id', $WindowIds)->pluck('user_id')->unique();
        $uniqueUsers = User::whereIn('id', $uniqueUserIds)->get();
        $accessList = WindowAccess::whereIn('window_id', $WindowIds)->get();
        $userWindows = WindowAccess::with('Window')->whereIn('user_id', $uniqueUserIds)->get()->groupBy('user_id');
        
        return view('admin.QueueWindows', compact('queue', 'uniqueUsers', 'accessList', 'userWindows'));
    }

    //view a window  from a queue to see who are the users who has access
    public function viewWindow($id)
    {
        $window = Window::findOrFail($id);
        $users = $window->users;
        $allUsers = User::all(); 

        return view('admin.window', compact('window', 'users', 'allUsers'));
    }

    public function createWindow(Request $request, $queue_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $queue = Queue::findOrFail($queue_id);
        $Window = new Window([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $queue->Windows()->save($Window);

        return redirect()->route('admin.queue.view', ['id' => $queue_id])->with('success', 'Window group added successfully.');
    }


    public function removeWindow($id)
    {
        $Window = Window::findOrFail($id);
        $Window->delete();

        return redirect()->route('admin.queue.view', ['id' => $Window->queue_id])->with('success', 'Window group removed successfully.');
    }

    //Adding and removing a users from a window group
    public function assignUserToWindow(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
    
        $Window = Window::findOrFail($id);
        $Window->users()->attach($request->user_id,["queue_id" => $Window->queue_id]);

        return redirect()->route('admin.window.view', ['id' => $id])->with('success', 'User assigned successfully.');
    }


    public function removeUserFromWindow($id, $user_id)
    {
        $Window = Window::findOrFail($id);
        $Window->users()->detach($user_id);

        return redirect()->route('admin.window.view', ['id' => $id])->with('success', 'User removed successfully.');
    }

    //Closing and opening of queue and windows For user and not admin
    public function updateAccess(Request $request, $user_id, $queue_id)
    {
        $accessList = WindowAccess::where('user_id', $user_id)
                                        ->where('queue_id', $queue_id)
                                        ->get();
    
        foreach ($accessList as $access) {
            $access->can_close_own_window = $request->input('can_close_own_window', false);
            $access->can_close_any_window = $request->input('can_close_any_window', false);
            $access->can_close_queue = $request->input('can_close_queue', false);
            $access->can_clear_queue = $request->input('can_clear_queue', false);
            $access->save();
        }
    
        return response()->json(['success' => true, 'message' => 'Access privileges updated successfully.']);
    }



    //User 
    public function myQueuesAndWindows()
    {
        $accountId = Session::get('account_id');
    
        $user = User::where('account_id', $accountId)->firstOrFail();
    
        // Get all window groups the user is related to
        $windows = $user->Windows()->with('queue')->get();
    
        // Get all queues related to those window groups
        $queues = Queue::whereHas('Windows', function ($query) use ($windows) {
            $query->whereIn('id', $windows->pluck('id'));
        })->get();
    
        return view('user.MyQueues', compact('queues', 'windows'));
    }

    public function manageQueue($id)
    {
        $queue = Queue::with('windows')->findOrFail($id);
        return view('user.queuedetails', compact('queue'));
    }


    public function toggleWindow(Request $request, $id)
    {
        $user = Auth::user();
        $Window = Window::findOrFail($id);
        $queueId = $request->input('queue_id');
        $accessExists = WindowAccess::where('user_id', $user->id)
                                         ->where('queue_id', $queueId)
                                         ->where(function ($query) {
                                             $query->where('can_close_own_window', true)
                                                   ->orWhere('can_close_any_window', true);
                                         })
                                         ->exists();
    
        if (!$accessExists) {
            return response()->json(['success' => false, 'message' => 'You do not have the required privileges to perform this action.'], 403);
        }
    
        $Window->status = $Window->status === 'open' ? 'closed' : 'open';
        $Window->save();
    
        return response()->json(['success' => true, 'message' => 'Window status updated successfully.']);
    }

    public function toggleQueue($id)
    {
        $user = Auth::user();
        $queue = Queue::findOrFail($id);
        $accessExists = WindowAccess::where('user_id', $user->id)
                                    ->where('queue_id', $id)
                                    ->where('can_close_queue', true)
                                    ->exists();
    
        if (!$accessExists) {
            return response()->json(['success' => false, 'message' => 'You do not have the required privileges to perform this action.'], 403);
        }
    
        $queue->status = $queue->status === 'open' ? 'closed' : 'open';
        $queue->save();
    
        return response()->json(['success' => true, 'message' => 'Queue status updated successfully.']);
    }
    
    public function clearQueue($id)
    {
        $user = Auth::user();
        $queue = Queue::findOrFail($id);
        $accessExists = WindowAccess::where('user_id', $user->id)
                                    ->where('queue_id', $id)
                                    ->where('can_clear_queue', true)
                                    ->exists();
    
        if (!$accessExists) {
            return response()->json(['success' => false, 'message' => 'You do not have the required privileges to perform this action.'], 403);
        }
    
        $waitingItems = $queue->Ticket()->where('status', 'Waiting')->get();
    
        if ($waitingItems->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'No items with status "Waiting" found.']);
        }
    
        $queue->Ticket()->where('status', 'Waiting')->update([
            'status' => 'Completed'
        ]);
    
        return response()->json(['success' => true, 'message' => 'Queue cleared successfully.']);
    }
}
