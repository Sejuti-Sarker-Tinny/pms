<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Image;
class UserController extends Controller
{
    public function userprofile(){
        return view('layouts.website.user.profile');
    }
    public function editprofile($slug){
        $data = User::where('user_slug', $slug)->first();
        return view('layouts.website.user.edit_profile', compact('data'));
    }
    public function editprofilePhoto($slug){
        $data = User::where('user_slug', $slug)->first();
        return view('layouts.website.user.edit_profile_photo', compact('data'));
    }
    public function updateUserInfo(Request $request ){
        //return $request->all();
        $id = $request->id;
        $this->validate($request,[
            'name'=>'required|max:255',
            'phone'=>'required|max:255',
            'address'=>'required|max:255',
        ]);

        $update = User::where('id', $id)->update([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);
        if($update){
            return back()->with('success', 'Information Update Successfully');
        }else{
            return back()->with('error','Customer information edit process failed!');

        }
    }
    public function updateUserPhoto(Request $request ){
        //return $request->all();
        $id = $request->id;
        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $imageName='customer_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/customer/'.$imageName);

          $update=  User::where('id', $id)->update([
              'photo'=>$imageName,
            ]);
        }
        if($update){
            return back()->with('success', 'Information Update Successfully');
        }else{
            return back()->with('error','Customer information edit process failed!');

        }
    }

    public function changePassword(Request $request,$id){


       $this->validate( $request,[
        'newpassword'=>'string|min:8|max:32',
    ]);
    $hashpassword = Auth::user()->password;
   // return $hashpassword;

        if(\Hash::check($request->oldpassword, $hashpassword)){
            if(!\Hash::check($request->newpassword, $hashpassword)){
                User::where('id', $id)->update([
                    'password'=>Hash::make($request->newpassword),
                ]);
                return back()->with('success', 'Account Successfully Updated');
            }else{
                return back()->with('error', 'New Password Can not be same password');
            }
        }else{
            return back()->with('error', 'Old Password Dose Not  match');
        }
    }
    public function ChangePasswordform($slug){
      //  return $slug;
        $data = User::where('user_slug', $slug)->first();
        //return $data;
        return view('layouts.website.user.password_change', compact('data'));
    }
}

