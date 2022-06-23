<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        //
    }
    public function create()
    {

    }
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'fullname ' => 'required',
            'description' => 'required',
            'phone ' => 'required',
            'city ' => 'required',
            'province ' => 'required',
        ]);
        if(request()->file('photo')){
            $foto = request()->file('photo')->store('foto');
            $request->photo = $foto;
        }
        $user = UserProfile::updateOrCreate(['user_id' => Auth::user()->id], $request->all());
        $user->save();
        return redirect()->route('dashboard')->with('You Have Been Edit Your Profile');
    }
    public function show(UserProfile $userProfile)
    {
        //
    }
    public function edit()
    {
        $user = UserProfile::where('user_id', '=',Auth::user()->id)->first();
        return view('edit-profile',compact('user'));
    }
    public function update(Request $request)
    {
        //
    }
    public function destroy()
    {
      //
    }
}
