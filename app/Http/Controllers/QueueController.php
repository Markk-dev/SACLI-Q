<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Queue;
use App\Models\Queued;
use App\Models\WindowGroup; 
use App\Models\WindowGroupAccess; 
class QueueController extends Controller
{
    // Managing Queues
    //View 
    public function manageQueues(Request $request)
    {
        $query = Queue::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $queues = $query->with('windowGroups')->paginate(10);
        $windowGroups = WindowGroup::all();

        return view('QueueManagement', compact('queues', 'windowGroups'));
    }

    //Create a new queue
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
            foreach ($request->window_groups as $windowGroupData) {
                $windowGroup = new WindowGroup($windowGroupData);
                $queue->windowGroups()->save($windowGroup);
            }
        }
    
        return redirect()->route('manageQueues')->with('success', 'Queue created successfully.');
    }

    //Delete a queue
    public function deleteQueue($id)
    {
        $queue = Queue::findOrFail($id);
        $queue->delete();

        return redirect()->route('manageQueues')->with('success', 'Queue deleted successfully.');
    }

    //To see associated queue window groups and other data
    public function viewQueue($id)
    {
        $queue = Queue::with('windowGroups')->findOrFail($id);
        $windowGroupIds = $queue->windowGroups->pluck('id');
        $uniqueUserIds = WindowGroupAccess::whereIn('window_group_id', $windowGroupIds)->pluck('user_id')->unique();
        $uniqueUsers = User::whereIn('id', $uniqueUserIds)->get();
        $accessList = WindowGroupAccess::whereIn('window_group_id', $windowGroupIds)->get();
        $userWindows = WindowGroupAccess::with('windowGroup')->whereIn('user_id', $uniqueUserIds)->get()->groupBy('user_id');
        return view('ViewQueueWindowGroups', compact('queue', 'uniqueUsers', 'accessList', 'userWindows'));
    }
    //view a window group of a queue to see who are the users who has access
    public function viewWindowGroup($id)
    {
        $windowGroup = WindowGroup::findOrFail($id);
        $users = $windowGroup->users;
        $allUsers = User::all(); 
        return view('ViewWindowGroup', compact('windowGroup', 'users', 'allUsers'));
    }
    //Add another window group to a queue
    public function addWindowGroup(Request $request, $queue_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $queue = Queue::findOrFail($queue_id);
        $windowGroup = new WindowGroup([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        $queue->windowGroups()->save($windowGroup);

        return redirect()->route('queue.view', ['id' => $queue_id])->with('success', 'Window group added successfully.');
    }

    //Delete a window group from the queue
    public function removeWindowGroup($id)
    {
        $windowGroup = WindowGroup::findOrFail($id);
        $windowGroup->delete();

        return redirect()->route('queue.view', ['id' => $windowGroup->queue_id])->with('success', 'Window group removed successfully.');
    }

    //Adding and removing a users from a window group
    public function assignUserToWindowGroup(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
    
        $windowGroup = WindowGroup::findOrFail($id);
        $windowGroup->users()->attach($request->user_id);
    
        return redirect()->route('windowGroups.view', ['id' => $id])->with('success', 'User assigned successfully.');
    }

    //Removing a user from the window group
    public function removeUserFromWindowGroup($id, $user_id)
    {
        $windowGroup = WindowGroup::findOrFail($id);
        $windowGroup->users()->detach($user_id);

        return redirect()->route('windowGroups.view', ['id' => $id])->with('success', 'User removed successfully.');
    }

    
    public function myQueuesAndWindows()
    {
        $accountId = Session::get('account_id');
        if (!$accountId) {
            return redirect()->route('login')->with('error', 'User not logged in.');
        }
    
        $user = User::where('account_id', $accountId)->firstOrFail();
    
        // Get all window groups the user is related to
        $windowGroups = $user->windowGroups()->with('queue')->get();
    
        // Get all queues related to those window groups
        $queues = Queue::whereHas('windowGroups', function ($query) use ($windowGroups) {
            $query->whereIn('id', $windowGroups->pluck('id'));
        })->get();
    
        return view('MyQueues', compact('queues', 'windowGroups'));
    }

    //Closing and opening of queue and windows For user and not admin
    public function updateAccess(Request $request, $user_id, $queue_id)
    {
        $accessList = WindowGroupAccess::where('user_id', $user_id)
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

    public function manageQueue($id)
    {
        $queue = Queue::with('windowGroups')->findOrFail($id);
        return view('QueueDetails', compact('queue'));
    }


    public function toggleWindow(Request $request, $id)
    {
        $user = Auth::user();
        $windowGroup = WindowGroup::findOrFail($id);
        $queueId = $request->input('queue_id');
        $access = WindowGroupAccess::where('user_id', $user->id)
                                    ->where('queue_id', $queueId)
                                    ->first();
    
        if (!$access || (!$access->can_close_own_window && !$access->can_close_any_window)) {
            return response()->json(['success' => false, 'message' => 'You do not have the required privileges to perform this action.'], 403);
        }
    
        $windowGroup->status = $windowGroup->status === 'open' ? 'closed' : 'open';
        $windowGroup->save();
    
        return response()->json(['success' => true, 'message' => 'Window status updated successfully.']);
    }

    public function toggleQueue($id)
    {
        $user = Auth::user();
        $queue = Queue::findOrFail($id);
        $access = WindowGroupAccess::where('user_id', $user->id)
                                    ->where('queue_id', $id)
                                    ->first();
    
        if (!$access || !$access->can_close_queue) {
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
        $access = WindowGroupAccess::where('user_id', $user->id)
                                    ->where('queue_id', $id)
                                    ->first();
    
        if (!$access || !$access->can_clear_queue) {
            return response()->json(['success' => false, 'message' => 'You do not have the required privileges to perform this action.'], 403);
        }
    
        $waitingItems = $queue->queued()->where('status', 'Waiting')->get();
    
        if ($waitingItems->isEmpty()) {
            return response()->json(['success' => true, 'message' => 'No items with status "Waiting" found.']);
        }
    
        $queue->queued()->where('status', 'Waiting')->update([
            'status' => 'Completed'
        ]);
    
        return response()->json(['success' => true, 'message' => 'Queue cleared successfully.']);
    }
}
