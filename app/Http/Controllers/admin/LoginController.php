<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    public function index(){
        return view('admin.login');
    }

    
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('admin.login')->withErrors($validator)->withInput();
        }
    
        // Authenticate using 'admin' guard
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('admin')->user(); // Get logged-in user
    
            // Ensure the authenticated user is an admin
            if ($user->role !== 'admin') {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error', 'Unauthorized access!');
            }
    
            return redirect()->route('admin.dashboard');
        }
    
        return redirect()->route('admin.login')->with('error', 'Either email or password is incorrect');
    }
    
    
    

    public function logout(){
        Auth::guard()->logout();
        return redirect()->route('admin.login');

    }

}
