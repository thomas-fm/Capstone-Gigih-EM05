<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //
    }
    public function create()
    {

    }
    public function store()
    {
        
    }
    public function show(User $user)
    {
        //
    }
    public function edit()
    {
        return view('change-password');
    }
    public function update(Request $request)
    {
        $user = User::where('id',Auth::user()->id)->first();
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->update();
        return redirect()->route('dashboard')->with('You Have Been Change Your Password');
    }
    public function destroy()
    {
      //
    }
}
