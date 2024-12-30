<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Queue;
use App\Models\WindowGroup; 
use App\Models\WindowGroupAcesss; 
class QueueController extends Controller
{
    // Add these methods
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

    public function deleteQueue($id)
    {
        $queue = Queue::findOrFail($id);
        $queue->delete();

        return redirect()->route('manageQueues')->with('success', 'Queue deleted successfully.');
    }

    //To see associated queue window groups and data

    public function viewQueue($id)
    {
        $queue = Queue::with('windowGroups')->findOrFail($id);
        return view('ViewQueueWindowGroups', compact('queue'));
    }
    
    //view window groups list, functions add and delete 
    public function viewWindowGroup($id)
    {
        $windowGroup = WindowGroup::findOrFail($id);
        $users = $windowGroup->users;
        $allUsers = User::all(); // Users with access to the window
        return view('ViewWindowGroup', compact('windowGroup', 'users', 'allUsers'));
    }
    
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
}
