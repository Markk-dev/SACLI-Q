<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class MainController extends Controller
{
    function index(){
       return view('Login');
    }

    function dashboard(){
        return view('Homepage');
     }


     //Management
     function users(Request $request){
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('account_id', 'LIKE', "%{$search}%");
        }

        $users = $query->paginate(10);

        return view('UserManagement', ['users' => $users]);
    }

     function createAccount(Request $request){
        $request->validate([
            'name' => 'required',
            'account_id' => 'required|unique:users,account_id',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);

        //Create a new user and save to the database.
        $user = new User();
        $user->name = $request->name;
        $user->account_id = $request->account_id;
        $user->password = bcrypt($request->password);
        $success = $user->save();

        if ($success) {
            return redirect()->back()->with('success', 'Account created successfully.');
        } else {
            return redirect()->back()->withErrors([
                'error' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('account_id', 'name'));
        }
     }

     //Queues
     function queues(){
        return view('QueuingDashboard');
     }

     //Joining a queue as a window
     function queueWindow(){
        return view('QueueWindow');
     }
    function login(Request $request){
        $request->validate([
            'account_id' => 'required',
            'password' => 'required'
        ]);

        
        //Validate the user account 
        if (Auth::attempt(['account_id' => $request->account_id, 'password' => $request->password])) {
            $user = Auth::user();

            session(['account_id' => $user->account_id, 'access_type'=>$user->access_type, 'name'=>$user->name, 'user_id'=>$user->id]);
            return redirect()->intended('dashboard'); 
        }
            return redirect()->back()->withErrors([
                'error' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('account_id'));
        
    }

    function deleteAccount($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users')->with('success', 'Account deleted successfully.');
    }
}
