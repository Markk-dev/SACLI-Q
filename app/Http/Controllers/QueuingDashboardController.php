<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Queue;
use App\Models\Ticket;
use App\Models\Window; 
use App\Models\WindowAccess; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


class QueuingDashboardController extends Controller
{
    //Showing the dashboard
    public function dashboard(Request $request, $id){
        $user_id = Auth::user()->id;
        // Fetch the Window and its associated Queue using the provided ID
        $window = Window::with('queue')->findOrFail($id);

        //This is 
        $windowAccess = WindowAccess::where('user_id', $user_id)
        ->where('window_id', $window->id)->first();

        if($window == null || $windowAccess==null){
            return redirect()->route('myQueues')->with('error', 'You do not have access to this window.');
        }else{
            // Pass the fetched data to the view
            return view('user.QueuingDashboard', compact('windowAccess', 'window'));
        }
    }



    //Public Methods
    public function liveQueue($id)
    {
        $queue = Queue::with('Windows')->findOrFail($id);

        // Get people who are Ticket on each Window with the status of "Calling"
        $Windows = $queue->Windows->map(function ($Window) {
            $Window->TicketPeople = Ticket::where('window_id', $Window->id)
                                                ->where('status', 'Calling')
                                                ->with('user') // Eager load the user handling the Ticket person
                                                ->get();

            // Join with window_access to get window_name
            $WindowAccess = WindowAccess::where('window_id', $Window->id)
                                                  ->first();
            $Window->window_name = $WindowAccess ? $WindowAccess->window_name : null;

            return $Window;
        });
        
        // Get 7 oldest Ticket records with status "Waiting"
        $Ticket = Ticket::where('queue_id', $id)
                        ->where('status', 'Waiting')
                        ->orderBy('created_at', 'asc')
                        ->take(8)
                        ->with('user') // Eager load the user handling the Ticket person
                        ->get();

        // Pass the fetched data to the view
        return view('public.LiveQueue', compact('queue', 'Windows', 'Ticket'));
    }
    function ticketing($id){
        $queue = Queue::with('Windows')->findOrFail($id);

        return view('public.Ticketing',compact('queue'));
    }

    public function ticketingSubmit(Request $request)
    {
        // Validate the request data
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'window' => 'required|exists:windows,id',
            'name' => 'nullable|string|max:255',
        ], [
            'window.required' => 'Please select what window to queue',
            'window.exists' => 'The selected window group does not exist',
        ]);

        // Generate a unique 6-character hex code
        do {
            $code = strtoupper(dechex(random_int(1048576, 16777215))); // Generates a 6-char hex
        } while (Ticket::where('code', $code)
                        ->exists());
    
        try {
            // Create a new Ticket record
            $Ticket = Ticket::create([
                'queue_id' => $request->queue_id,
                'window_id' => $request->window,
                'name' => $request->name,
                'status' => "Waiting", 
                'code' => $code,
            ]);
    
            return redirect()->route('ticketing.success', ['id' => $Ticket->id]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the ticket.']);
        }
    }
    
    public function ticketingSuccess($id)
    {
        $Ticket = Ticket::with('window')->findOrFail($id);
    
        return view('public.TicketReceipt', compact('Ticket'));
    }


    //API Methods
    //For setting window name
    public function updateWindow(Request $request, $id)
{
    $request->validate([
        'window_name' => 'required|string|max:255',
    ]);

    $WindowId = $id;
    $user_id = Auth::user()->id;

    $window = WindowAccess::where('user_id', $user_id)
        ->where('window_id', $WindowId)
        ->first();

    if ($window === null) {
        return response()->json([
            'success' => false,
            'message' => 'You do not have access to this window.',
        ], 403);
    }

    $window->window_name = $request->window_name;
    $window->save();

    return response()->json([
        'success' => true,
        'message' => 'Window Name Updated',
    ], 200);
}


     //For button "Next Ticket"
    public function getNextTicketForWindow($WindowId)
    {
        //Check if user is authenticated
        $user_id = Auth::user()->id;

        //Number of On Called by Window
        $onCalled = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->count();

        if($onCalled > 0){
            return response()->json([
                'success'=>false, 
                'message' => 'Please finish the current ticket first.'], 200);
        }

        //Gets the next ticket for the given window group
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('status', 'Waiting')
                        ->orderBy('created_at', 'asc')
                        ->first();

        //Check is user has access to the window
        $userHasAccess = WindowAccess::where('user_id', $user_id)
            ->where('window_id', $WindowId)->first();

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

        }else if($Ticket == null){
            return response()->json([
                'success'=>false, 
                'message' => 'No more tickets to call.'
            ], 200);
        }
        else{
            //Update Ticket Status
            $Ticket->status = 'Calling';
            $Ticket->handled_by = $user_id;
            $Ticket->called_at = Carbon::now();
            $Ticket->save();

            return response()->json([
                'success'=>true, 
                'message'=> "Successfully called the next ticket",
                'data' =>$Ticket
            ], 200);
        }
        
    }

   
   //Get Current Ticket For Window
    public function getCurrentTicketForWindow($WindowId)
    {
       $user_id = Auth::user()->id;
       $onCall = Ticket::where('window_id', $WindowId)
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
   public function setToComplete($WindowId)
    {
        $user_id = Auth::id();
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->first();

        if ($Ticket) {
            $Ticket->status = 'Completed';
            $Ticket->completed_at = Carbon::now();
            $Ticket->save();

            return response()->json(['success' => true, 'message' => 'Ticket marked as completed.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No ticket found to complete.']);
        }
    }

    //Get the next ticket that is currently on hold
    public function getNextOnHoldTicket($WindowId)
    {
        //Check if user is authenticated
        $user_id = Auth::user()->id;

        //Ensure that the user is not handling a ticket currently 
        $onCall = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->count();

        if($onCall > 0){
            return response()->json([
                'success'=>false, 
                'message' => 'Please finish the current ticket first.'], 200);
        }

        //Gets the next ticket for the given window group by status of On Hold
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('status', 'On Hold')
                        ->orderBy('created_at', 'asc')
                        ->first();

        //Checks if is user has access to the window
        $userHasAccess = WindowAccess::where('user_id', $user_id)
            ->where('window_id', $WindowId)->first();

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

        }else if($Ticket == null){
            return response()->json([
                'success'=>false, 
                'message' => 'No more tickets to call.'
            ], 200);
        }
        else{
            //Update Ticket Status
            $Ticket->status = 'Calling';
            $Ticket->handled_by = $user_id;
            $Ticket->save();

            return response()->json([
                'success'=>true, 
                'message'=> "Successfully called the next ticket",
                'data' =>$Ticket
            ], 200);
        }
        
    }

    //Set the ticket to on hold status
    public function putTicketOnHold($WindowId)
    {
        $user_id = Auth::id();
        $Ticket = Ticket::where('window_id', $WindowId)
                        ->where('handled_by', $user_id)
                        ->where('status', 'Calling')
                        ->first();

        if ($Ticket) {
            $Ticket->status = 'On Hold';
            $Ticket->save();

            return response()->json(['success' => true, 'message' => 'Ticket put on hold.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No ticket found to put on hold.']);
        }
    }


    //Get all tickets that are currently on hold (10)
    public function getAllTicketsOnHold($WindowId)
    {
        $tickets = Ticket::where('window_id', $WindowId)
                         ->where('status', 'On Hold')
                         ->orderBy('completed_at', 'desc')
                         ->get();

        return response()->json(['success' => true, 'tickets' => $tickets]);
    }

    //get the count of all tickets that are upcoming
    public function getUpcomingTicketsCount($WindowId)
    {
        $upcomingTicketsCount = Ticket::where('window_id', $WindowId)
                                      ->where('status', 'Waiting')
                                      ->count();
    
        return response()->json(['success' => true, 'upcoming_tickets_count' => $upcomingTicketsCount]);
    }

    //get 10 most recent completed tickets
    public function getAllCompletedTickets($WindowId)
    {
        $tickets = Ticket::where('window_id', $WindowId)
                         ->where('status', 'Completed')
                         ->orderBy('completed_at', 'desc')
                         ->limit(10)
                         ->get();

        return response()->json(['success' => true, 'tickets' => $tickets]);
    }
}
