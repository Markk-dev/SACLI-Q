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

    public function ticketingSubmit(Request $request)
    {
        // Validate the request data
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'window_group' => 'required|exists:window_groups,id',
            'name' => 'nullable|string|max:255',
        ], [
            'window_group.required' => 'Please select what window to queue',
            'window_group.exists' => 'The selected window group does not exist',
        ]);

        // Generate a unique 6-character hex code
        do {
            $code = strtoupper(dechex(random_int(1048576, 16777215))); // Generates a 6-char hex
        } while (Queued::where('code', $code)
                        ->whereDate('created_at', Carbon::today())
                        ->exists());
    
        try {
            // Create a new queued record
            $queued = Queued::create([
                'queue_id' => $request->queue_id,
                'window_group_id' => $request->window_group,
                'name' => $request->name,
                'status' => "Waiting", 
                'code' => $code,
            ]);
    
            return redirect()->route('ticketing.success', ['id' => $queued->id]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the ticket.']);
        }
    }
    
    public function ticketingSuccess($id)
    {
        $queued = Queued::findOrFail($id);
        return view('TicketReceipt', compact('queued'));
    }
    


    //API Methods
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
            $queued->called_at = Carbon::now();
            $queued->save();

            return response()->json([
                'success'=>true, 
                'message'=> "Successfully called the next ticket",
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
           ->whereDate('created_at', Carbon::today())
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

    
   //Set the completed status of the ticket
   public function setToComplete($windowGroupId)
    {
        $user_id = Auth::id();
        $queued = Queued::where('window_group_id', $windowGroupId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->first();

        if ($queued) {
            $queued->status = 'Completed';
            $queued->completed_at = Carbon::now();
            $queued->save();

            return response()->json(['success' => true, 'message' => 'Ticket marked as completed.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No ticket found to complete.']);
        }
    }

    //Get the next ticket that is currently on hold
    public function getNextOnHoldTicket($windowGroupId)
    {
        //Check if user is authenticated
        $user_id = Auth::user()->id;

        //Ensure that the user is not handling a ticket currently 
        $onCall = Queued::where('window_group_id', $windowGroupId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->count();

        if($onCall > 0){
            return response()->json([
                'success'=>false, 
                'message' => 'Please finish the current ticket first.'], 200);
        }

        //Gets the next ticket for the given window group by status of On Hold
        $queued = Queued::where('window_group_id', $windowGroupId)
                        ->where('status', 'On Hold')
                        ->orderBy('created_at', 'asc')
                        ->first();

        //Checks if is user has access to the window
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
                'message'=> "Successfully called the next ticket",
                'data' =>$queued
            ], 200);
        }
        
    }

    //Set the ticket to on hold status
    public function putTicketOnHold($windowGroupId)
    {
        $user_id = Auth::id();
        $queued = Queued::where('window_group_id', $windowGroupId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->first();

        if ($queued) {
            $queued->status = 'On Hold';
            $queued->save();

            return response()->json(['success' => true, 'message' => 'Ticket put on hold.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No ticket found to put on hold.']);
        }
    }


    //Get all tickets that are currently on hold (10)
    public function getAllTicketsOnHold($windowGroupId)
    {
        $tickets = Queued::where('window_group_id', $windowGroupId)
                         ->where('status', 'On Hold')
                         ->orderBy('completed_at', 'desc')
                         ->get();

        return response()->json(['success' => true, 'tickets' => $tickets]);
    }

    //get the count of all tickets that are upcoming
    public function getUpcomingTicketsCount($windowGroupId)
    {
        $upcomingTicketsCount = Queued::where('window_group_id', $windowGroupId)
                                      ->where('status', 'Waiting')
                                      ->count();
    
        return response()->json(['success' => true, 'upcoming_tickets_count' => $upcomingTicketsCount]);
    }

    //get 10 most recent completed tickets
    public function getAllCompletedTickets($windowGroupId)
    {
        $tickets = Queued::where('window_group_id', $windowGroupId)
                         ->where('status', 'Completed')
                         ->orderBy('completed_at', 'desc')
                         ->limit(10)
                         ->get();

        return response()->json(['success' => true, 'tickets' => $tickets]);
    }
}
