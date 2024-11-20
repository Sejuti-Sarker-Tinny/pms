<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Image;
use File;

class ProductsController extends Controller{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('product_id', 'DESC')->get();
        return view('admin.product.all', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required|max:255|unique:products,product_name',
            //'product_wholesale_price' => 'numeric|required',
            'product_wholesale_price' => 'required',
            //'product_retail_price' => 'numeric|required',
            'product_retail_price' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[

        ]);

        $product_slug = $request->product_name . '-' . time();
        $created_by = Auth::user()->name;
        $id = Product::insertGetId([
            'product_name'=>$request->product_name,
            'product_slug'=>$product_slug,
            'product_details'=>$request->product_details,
            'product_wholesale_price'=>$request->product_wholesale_price,
            'product_retail_price'=>$request->product_retail_price,
            'created_by'=>$created_by,
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('product_photo')) {
            $image=$request->file('product_photo');
            $imageName='product_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/product/'.$imageName);

            Product::where('product_id', $id)->update([
              'product_photo'=>$imageName,
            ]);
        }

        return redirect()->route('product.index')->with('success', 'Product added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $Product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $data = Product::where('product_slug', $slug)->first();
        return view('admin.product.edit', compact(['data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->product_id;
        $this->validate($request, [
            'product_name' => 'required|max:255|unique:products,product_name,' . $id . ',product_id',
            'product_wholesale_price' => 'required',
            //'product_wholesale_price' => 'numeric|required',
            'product_retail_price' => 'required',
            //'product_retail_price' => 'numeric|required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[

        ]);

        $product_slug = $request->product_name . '-' . time();
        $updated_by = Auth::user()->name;

        $update = Product::where('product_id', $id)->update([
            'product_name'=>$request->product_name,
            'product_slug'=>$product_slug,
            'product_details'=>$request->product_details,
            'product_wholesale_price'=>$request->product_wholesale_price,
            'product_retail_price'=>$request->product_retail_price,
            'updated_by'=>$updated_by,
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('product_photo')){
            $image=$request->file('product_photo');
            $imageName='product_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/product/'.$imageName);

            Product::where('product_id', $id)->update([
              'product_photo'=>$imageName,
            ]);  
        }

        if($update){
            Session::flash('success','Product information updated successfully!');
            return redirect()->route('product.index');
        }else{
            //Session::flash('error','Product information edit process failed!');
            return back()->with('error', 'Product information edit process failed!');
        }
        //$product = Product::where('product_id', $id)->first();
       // return $product;
        // if ($product) {
        //     if ($product) {
        //         $product->product_id = $id;
        //         $product->product_slug = $request->product_name . '-' . time();
        //         $product->product_name = $request->product_name;
        //         $product->product_details = $request->product_details;
        //         $product->product_wholesale_price = $request->product_wholesale_price;
        //         $product->product_retail_price = $request->product_retail_price;
        //         $product->updated_by = Auth::user()->name;
        //         if ($request->hasFile('product_photo')) {
        //             $file = $request->file('product_photo');
        //             $ext = $file->getClientOriginalExtension();
        //             $filename = uniqid() . '.' . $ext;
        //             $file->move('uploads/product/', $filename);
        //             $product->product_photo =  'uploads/product/' . $filename;
        //         }
        //         $product->update();
        //         return redirect()->route('product.index')->with('success', 'Product updated successfully');
        //     } else {
        //         return back()->with('error', 'Data Not Faound');
        //     }

        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::where('product_id', $id)->first();
        $path = 'uploads/product/'.$product['product_photo'];
        if(File::exists($path)){
            File::delete($path);
        }
        $delete = Product::where('product_id', $id)->delete();
        if($delete){
            Session::flash('success','Product deleted successfully!');
            return redirect()->route('product.index');
        }else{
            Session::flash('error','Product delete process failed!');
            return redirect()->route('product.index');
        }
    }
}