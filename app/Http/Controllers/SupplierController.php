<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Image;
use File;

class SupplierController extends Controller{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }


    public function index(){
        $all = User::orderBy('id', 'DESC')->where('role_id','3')->get();
        return view('admin.supplier.all',compact('all'));
    }

    public function add(){
        return view('admin.supplier.add');
    }

    public function edit($slug){
        $allData = User::where('user_slug', $slug)->first();
        return view('admin.supplier.edit', compact('allData'));
    }

    public function submit(Request $request){
        $this->validate($request,[
            'name'=>'required|max:255',
            'email'=>'required|max:255|unique:users,email',
            'phone'=>'required|max:255',
            'organization'=>'max:255',
            'designation'=>'max:255',
            'password'=>'max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            //'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=500,max_height=500', // (2048kb max size limit)
        ],[
            'name.required'=>'Your name is required.',
            'name.max'=>'Your name must not be greater than 255 characters.',
            'email.required'=>'Your email is required.',
            'email.max'=>'Email must not be greater than 255 characters.',
            'phone.required'=>'Phone is required.',
            'phone.max'=>'Phone must not be greater than 255 characters.',
            'organization.max'=>'Organization name must not be greater than 255 characters.',
            'designation.max'=>'Designation must not be greater than 255 characters.',
            'password.max'=>'Password must not be greater than 255 characters.',
        ]);
        $slug = 'SUPPLIER'.uniqid();
        $id = User::insertGetId([
            'name'=>$request->name,
            'role_id'=>$request->role_id,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'organization'=>$request->organization,
            'designation'=>$request->designation,
            'address'=>$request->address,
            'remarks'=>$request->remarks,
            'user_slug' => $slug,
            'password'=>Hash::make($request->password),
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);
        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $imageName='supplier_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/supplier/'.$imageName);

            User::where('id', $id)->update([
              'photo'=>$imageName,
            ]);
        }

        if($id){
            Session::flash('success','Supplier information added successfully!');
            return redirect()->route('all_supplier');
        }else{
            Session::flash('error','Supplier information addition process failed!');
            return redirect()->route('edit_supplier');
        }
    }

    public function update(Request $request){
        $id = $request->id;
        $this->validate($request,[
            'name'=>'required|max:255',
            'email'=>'required|max:255|unique:users,email,'.$id.',id',
            'phone'=>'required|max:255',
            'organization'=>'max:255',
            'designation'=>'max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            //'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=500,max_height=500', // (2048kb max size limit)
        ],[
            'name.required'=>'Your name is required.',
            'name.max'=>'Your name must not be greater than 255 characters.',
            'email.required'=>'Your email is required.',
            'email.max'=>'Email must not be greater than 255 characters.',
            'phone.required'=>'Phone is required.',
            'phone.max'=>'Phone must not be greater than 255 characters.',
            'organization.max'=>'Organization name must not be greater than 255 characters.',
            'designation.max'=>'Designation must not be greater than 255 characters.',
        ]);

        $update = User::where('id', $id)->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'organization'=>$request->organization,
            'designation'=>$request->designation,
            'address'=>$request->address,
            'remarks'=>$request->remarks,
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);
        if($request->hasFile('photo')){
            $image=$request->file('photo');
            $imageName='supplier_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/supplier/'.$imageName);

            User::where('id', $id)->update([
              'photo'=>$imageName,
            ]);
        }

        if($update){
            Session::flash('success','Supplier information updated successfully!');
            return redirect()->route('all_supplier');
        }else{
            Session::flash('error','Supplier information edit process failed!');
            return redirect()->route('edit_supplier');
        }
    }

    public function delete(Request $request){
        $supplier = User::where('user_slug', $request->modal_id)->first();
        $path = 'uploads/supplier/'.$supplier['photo'];
        if(File::exists($path)){

            File::delete($path);
        }
        $delete = User::where('id', $supplier->id)->delete();
        if($delete){
            Session::flash('success','Supplier account deleted successfully!');
            return redirect()->route('all_supplier');
        }else{
            Session::flash('error','Supplier account delete process failed!');
            return redirect()->route('all_supplier');
        }
    }



    public function ban($slug){
        $allData = User::where('user_slug', $slug)->first();

        $update = User::where('id', $allData['id'])->update([
            'ban_status_on'=>'1',
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($update){
            Session::flash('success','This supplier banned successfully!');
            return redirect()->route('all_supplier');
        }else{
            Session::flash('error','This supplier ban process failed!');
            return redirect()->route('all_supplier');
        }

    }

    public function unban($slug){

        $allData = User::where('user_slug', $slug)->first();

        $update = User::where('id', $allData['id'])->update([
            'ban_status_on'=>'0',
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($update){
            Session::flash('success','This supplier unbanned successfully!');
            return redirect()->route('all_supplier');
        }else{
            Session::flash('error','This supplier unban process failed!');
            return redirect()->route('all_supplier');
        }

    }

    public function send_email($slug){
        $allData = User::where('user_slug', $slug)->first();
        return view('admin.supplier.send-email', compact('allData'));
    }

    public function submit_send_email(Request $request){
        $id = $request->id;

        $this->validate($request,[
            'details'=>'required|max:255',
        ],[
            'details.required'=>'Email is required.',
        ]);

        $allData = User::where('id', $id)->first();
        $details = [
            'name' => $allData->name,
            'title' => 'Smart Pharmacy',
            'detailinfo' => $request['details'],
        ];

        try{
            Mail::to($allData->email)->send(new SupplierMail($details));
            Session::flash('success','Email sent successfully!');
            return redirect()->route('all_supplier');
        }
        catch (\Exception $e) {
            Session::flash('error','Email sending failed!');
            return redirect()->route('all_supplier');
        }

    }

}
