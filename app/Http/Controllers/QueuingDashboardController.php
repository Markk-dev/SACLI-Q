<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Queue;
use App\Models\Queued;
use App\Models\WindowGroup; 
use App\Models\WindowGroupAccess; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


class QueuingDashboardController extends Controller
{
    //Showing the dashboard
    public function dashboard(Request $request, $id){
        $user_id = Auth::user()->id;
        // Fetch the WindowGroup and its associated Queue using the provided ID
        $windowGroup = WindowGroup::with('queue')->findOrFail($id);

        $window = WindowGroupAccess::where('user_id', $user_id)
        ->where('window_group_id', $windowGroup->id)->first();

        if($windowGroup == null){
            return redirect()->route('myQueues')->with('error', 'You do not have access to this window.');
        }else{
            // Pass the fetched data to the view
            return view('QueuingDashboard', compact('windowGroup', 'window'));
        }
    }

    public function liveQueue($id)
    {
        $queue = Queue::with('windowGroups')->findOrFail($id);

        // Get people who are queued on each WindowGroup with the status of "Calling"
        $windowGroups = $queue->windowGroups->map(function ($windowGroup) {
            $windowGroup->queuedPeople = Queued::where('window_group_id', $windowGroup->id)
                                                ->where('status', 'Calling')
                                                ->with('user') // Eager load the user handling the queued person
                                                ->get();

            // Join with window_group_access to get window_name
            $windowGroupAccess = WindowGroupAccess::where('window_group_id', $windowGroup->id)
                                                  ->first();
            $windowGroup->window_name = $windowGroupAccess ? $windowGroupAccess->window_name : null;

            return $windowGroup;
        });
        
        // Get 7 oldest queued records with status "Waiting"
        $queued = Queued::where('queue_id', $id)
                        ->where('status', 'Waiting')
                        ->orderBy('created_at', 'asc')
                        ->take(8)
                        ->with('user') // Eager load the user handling the queued person
                        ->get();

        // Pass the fetched data to the view
        return view('LiveQueue', compact('queue', 'windowGroups', 'queued'));
    }
    function ticketing($id){
        $queue = Queue::with('windowGroups')->findOrFail($id);

        return view('Ticketing',compact('queue'));
    }

    public function ticketingSubmit(Request $request){
        // Validate the request data
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'window_group' => 'required|exists:window_groups,id',
            'name' => 'nullable|string|max:255',
        ]);

        // Generate a unique 5-digit hex code
        do {
            $code = strtoupper(Str::random(5));
        } while (Queued::where('code', $code)
                        ->whereDate('created_at', Carbon::today())
                        ->exists());
        // Create a new queued record
        $queued = new Queued();
        $queued->queue_id = $request->queue_id;
        $queued->window_group_id = $request->window_group;
        $queued->name = $request->name;
        $queued->status = 'Waiting';
        $queued->code = $code;
        $queued->save();

        return view('TicketReceipt', compact('code', 'queued'));
    }




    //API Methods
    public function getWindowGroupsWithQueuedPeople($id)
    {
        $queue = Queue::with('windowGroups')->findOrFail($id);

        // Get people who are queued on each WindowGroup with the status of "Calling"
        $windowGroups = $queue->windowGroups->map(function ($windowGroup) {
            $windowGroup->queuedPeople = Queued::where('window_group_id', $windowGroup->id)
                                                ->where('status', 'Waiting')
                                                ->with('user') // Eager load the user handling the queued person
                                                ->get();
            return $windowGroup;
        });

        return response()->json($windowGroups);
    }

    public function getWindowGroupsWithQueuedPeopleOnHold($id) {
        $queue = Queue::with('windowGroups')->findOrFail($id);

        // Get people who are queued on each WindowGroup with the status of "Calling"
        $windowGroups = $queue->windowGroups->map(function ($windowGroup) {
            $windowGroup->queuedPeople = Queued::where('window_group_id', $windowGroup->id)
                                                ->where('status', 'On Hold')
                                                ->with('user') // Eager load the user handling the queued person
                                                ->get();
            return $windowGroup;
        });

        return response()->json($windowGroups);
    }

    //For setting window name
    public function updateWindow(Request $request, $id)  {
        $windowGroupId = $id;
        $user_id = Auth::user()->id;
 
 
         $window = WindowGroupAccess::where('user_id', $user_id)
             ->where('window_group_id', $windowGroupId)->first();
 
         if($window == null){
             return response()->json([
                'success'=>false, 
                'message' => 'You do not have access to this window.'
            ], 200);
         }else{
             $window->window_name = $request->window_name;
             $window->save();
 
             return response()->json([
                'success'=>true, 
                'message' => 'Window Name Updated'
            ], 200);
         }
     }

    //For button "Next Ticket"
    public function getNextTicketForWindow($windowGroupId)
    {
        //Check if user is authenticated
        $user_id = Auth::user()->id;

        //Number of On Called by Window
        $onCalled = Queued::where('window_group_id', $windowGroupId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->count();

        if($onCalled > 0){
            return response()->json([
                'success'=>false, 
                'message' => 'Please finish the current ticket first.'], 200);
        }

        //Gets the next ticket for the given window group
        $queued = Queued::where('window_group_id', $windowGroupId)
                        ->where('status', 'Waiting')
                        ->orderBy('created_at', 'asc')
                        ->first();

        //Check is user has access to the window
        $userHasAccess = WindowGroupAccess::where('user_id', $user_id)
            ->where('window_group_id', $windowGroupId)->first();

        if (!$userHasAccess || $userHasAccess == null) {
            return response()->json([
                'success'=>false, 
                'message' => 'You do not have access to this window.
            '], 200);

        }else if($userHasAccess->window_name == null && $userHasAccess->window_name == ''){
            return response()->json([
                'success'=>false, 
                'message' => 'Please Enter your window name first'
            ], 200);

        }else if($queued == null){
            return response()->json([
                'success'=>false, 
                'message' => 'No more tickets to call.'
            ], 200);
        }
        else{
            //Update Ticket Status
            $queued->status = 'Calling';
            $queued->handled_by = $user_id;
            $queued->save();

            return response()->json([
                'success'=>true, 
                'data' =>$queued
            ], 200);
        }
        
    }

   
   //Get Current Ticket For Window
    public function getCurrentTicketForWindow($windowGroupId)
    {
       $user_id = Auth::user()->id;
       $onCall = Queued::where('window_group_id', $windowGroupId)
           ->where('handled_by', $user_id)
           ->where('status', 'Calling')
           ->first();

       if ($onCall) {
           return response()->json([
               'success' => true,
               'ticket_number' => $onCall->code,
               'name' => $onCall->name
           ]);
       } else {
           return response()->json([
               'success' => false,
               'message' => 'No tickets are currently being called.'
           ]);
       }
   }
}
