<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Queue;
use App\Models\Queued;
use App\Models\WindowGroup; 
use App\Models\WindowGroupAcesss; 
use Illuminate\Support\Str;
use Carbon\Carbon;


class QueuingDashboardController extends Controller
{
    public function show(Request $request, $id){
    {
        // Fetch the WindowGroup and its associated Queue using the provided ID
        $windowGroup = WindowGroup::with('queue')->findOrFail($id);

        // dd($windowGroup);
        // Pass the fetched data to the view
        return view('QueuingDashboard', compact('windowGroup'));
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
}
