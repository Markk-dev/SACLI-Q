<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
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
use App\Events\DashboardEvent;
use App\Events\NewTicketEvent;

class PublicController extends Controller
{
    
    //Public Methods
    public function liveQueue($code)
    {
        try {
            $queue = Queue::with('Windows')->where('code', $code)->firstOrFail();

            // Pass the fetched data to the view
            return view('public.LiveQueue', compact('queue'));

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No queue found with the given code.',
            ], 404);
        }
    }
    

    function ticketing($code){
        try {
            $queue = Queue::where('code', $code)->firstOrFail();
            return view('public.Ticketing',compact('queue'));

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No queue found with the given code.',
            ], 404);
        }
    }


    public function ticketingSubmit(Request $request)
    {
        // Validate the request data
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'window_id' => 'required|exists:windows,id',
            'name' => 'nullable|string|max:255',
        ], [
            'window_id.required' => 'Please select what window to queue',
            'window_id.exists' => 'The selected window group does not exist',
        ]);
    
        try {
            // Fetch the queue and window
            $window = Window::where('id', $request->window_id)
                ->where('queue_id', $request->queue_id)
                ->firstOrFail();
    
    
            // Get the number of tickets generated today for the specific window
            $ticketsGeneratedToday = Ticket::where('window_id', $request->window_id)
                ->whereDate('created_at', Carbon::today())
                ->count();

            // Check if the ticket limit has been reached
            if ($ticketsGeneratedToday >= $window->limit) {
                // If the limit is reached, close the window and return an error
                $window->status = 'closed';
                $window->save();
    
                return back()->withErrors(['error' => 'The ticket limit for today has been reached. The window has been closed.']);
            }
    
            // Generate a unique 6-character code
            $code = $this->generateUniqueCode();
            
            // Get the next ticket number for the window
            $ticket_number = Ticket::where('window_id', $request->window_id)->max('ticket_number');
            $ticket_number = $ticket_number ? $ticket_number + 1 : 1;
    
            // Create a new Ticket record
            $Ticket = Ticket::create([
                'queue_id' => $request->queue_id,
                'ticket_number' => $ticket_number,
                'window_id' => $request->window_id,
                'name' => $request->name,
                'status' => "Waiting",
                'code' => $code,
            ]);
    
            // Broadcast the new ticket event
            broadcast(new NewTicketEvent($Ticket->queue_id));
    
            return redirect()->route('ticketing.success', ['id' => $Ticket->id]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the ticket.']);
        }
    }
    
    public function ticketingSuccess($id)
    {
        $Ticket = Ticket::with('window')->findOrFail($id);
        $Queue = Queue::findOrFail($Ticket->queue_id);
        return view('public.TicketReceipt', compact('Ticket', 'Queue'));
    }

    /**
     * Generate a unique 6-character alphanumeric code.
     */
    private function generateUniqueCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        do {
            $code = substr(str_shuffle($characters), 0, 6); // Generate random 6-character code
        } while (Ticket::where('code', $code)->exists()); // Ensure uniqueness

        return $code;
    }

    public function info(Request $request)
    {
        $ticketCode = $request->input('ticketCode'); // Get the ticket code from the request
        
        // Search for the ticket by code
        $ticket = Ticket::with('queue')->with('window')->where('code', $ticketCode)->first();
        
        if (!$ticket) {
            // If no ticket is found, return with an error message
            return view('public.info', ['message' => 'Ticket not found.']);
        }
    
        // Initialize ticket position
        $ticketPosition = null;
    
        // Check if the ticket status is 'Waiting'
        if ($ticket->status == 'Waiting') {
            // Get the position of the ticket in the queue based on its 'created_at'
            $ticketPosition = Ticket::where('queue_id', $ticket->queue_id)
                                    ->where('status', 'Waiting')
                                    ->orderBy('created_at', 'asc')  // Oldest ticket is first
                                    ->pluck('id'); // Get list of ticket IDs in order of waiting
    
            // Find the position of the current ticket in the ordered list
            $ticketPosition = $ticketPosition->search($ticket->id) + 1; // Position is 1-based index
        }
    
        // Pass the ticket and position to the view
        return view('public.info', compact('ticket', 'ticketPosition'));
    }
    
    
}
