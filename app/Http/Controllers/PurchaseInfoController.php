<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseInfo;
use App\Models\User;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Image;
use File;

class PurchaseInfoController extends Controller{
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
        $PurchaseInfo = PurchaseInfo::orderBy('purchase_info_id', 'DESC')->get();
        return view('admin.purchase.all', compact('PurchaseInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        return view('admin.purchase.add');
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
            //'product_name' => 'required|max:255|unique:products,product_name',
            'challan_number' => 'required|max:255',
            'bill_number' => 'required|max:255',
            'product_price_per_unit' => 'numeric|required',
            'purchase_info_photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[

        ]);

        $purchase_info_slug = $request->challan_number . '-' . time();
        $created_by = Auth::user()->name;

        $product_quantity = $request->carton_number*$request->box_per_carton*$request->pata_per_box*$request->product_unit_per_pata;
        $product_total_price = $product_quantity*$request->product_price_per_unit;

        $id = PurchaseInfo::insertGetId([
            'challan_number'=>$request->challan_number,
            'bill_number'=>$request->bill_number,
            'purchase_date'=>$request->purchase_date,
            'purchase_type'=>$request->purchase_type,
            'supplier_id'=>$request->supplier_id,
            'product_id'=>$request->product_id,
            'carton_number'=>$request->carton_number,
            'box_per_carton'=>$request->box_per_carton,
            'pata_per_box'=>$request->pata_per_box,
            'product_unit_per_pata'=>$request->product_unit_per_pata,
            'product_quantity'=>$product_quantity,
            'product_price_per_unit'=>$request->product_price_per_unit,
            'product_total_price'=>$product_total_price,
            'purchase_remarks'=>$request->purchase_remarks,
            'purchase_info_slug'=>$purchase_info_slug,
            'created_by'=>$created_by,
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('purchase_info_photo')) {
            $image=$request->file('purchase_info_photo');
            $imageName='purchase_info_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/purchase-info/'.$imageName);

            PurchaseInfo::where('purchase_info_id', $id)->update([
              'purchase_info_photo'=>$imageName,
            ]);
        }

        $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');
        
        if($StockProductQuantity==''){
            Stock::insert([
                'product_id'=>$request->product_id,
                'product_quantity'=>$product_quantity,
                'created_at'=>Carbon::now()->toDateTimeString(),
            ]);
        }else{
            Stock::where('product_id', $request->product_id)->update([
                'product_quantity'=>$product_quantity+$StockProductQuantity,
                'created_at'=>Carbon::now()->toDateTimeString(),
            ]);
        }
        

        return redirect()->route('purchase-info.index')->with('success', 'Purchase info added successfully');
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
        $data = PurchaseInfo::where('purchase_info_slug', $slug)->first();
        return view('admin.purchase.edit', compact('data'));
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
        $id = $request->purchase_info_id;
        $this->validate($request, [
            'challan_number' => 'required|max:255',
            'bill_number' => 'required|max:255',
            'product_price_per_unit' => 'numeric|required',
            'purchase_info_photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[

        ]);

        $purchase_info_slug = $request->challan_number . '-' . time();
        $updated_by = Auth::user()->name;

        $product_quantity = $request->carton_number*$request->box_per_carton*$request->pata_per_box*$request->product_unit_per_pata;
        $product_total_price = $product_quantity*$request->product_price_per_unit;

        $update = PurchaseInfo::where('purchase_info_id', $id)->update([
            'challan_number'=>$request->challan_number,
            'bill_number'=>$request->bill_number,
            'purchase_date'=>$request->purchase_date,
            'purchase_type'=>$request->purchase_type,
            'supplier_id'=>$request->supplier_id,
            'product_id'=>$request->product_id,
            // 'carton_number'=>$request->carton_number,
            // 'box_per_carton'=>$request->box_per_carton,
            // 'pata_per_box'=>$request->pata_per_box,
            // 'product_unit_per_pata'=>$request->product_unit_per_pata,
            // 'product_quantity'=>$product_quantity,
            'product_price_per_unit'=>$request->product_price_per_unit,
            'product_total_price'=>$product_total_price,
            'purchase_remarks'=>$request->purchase_remarks,
            'purchase_info_slug'=>$purchase_info_slug,
            'updated_by'=>$updated_by,
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('purchase_info_photo')){
            $image=$request->file('purchase_info_photo');
            $imageName='purchase_info_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/purchase-info/'.$imageName);

            PurchaseInfo::where('purchase_info_id', $id)->update([
              'purchase_info_photo'=>$imageName,
            ]);  
        }

        // $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');
        // Stock::where('product_id', $request->product_id)->update([
        //     'product_quantity'=>$product_quantity+$StockProductQuantity,
        //     'updated_at'=>Carbon::now()->toDateTimeString(),
        // ]);

        if($update){
            Session::flash('success','Purchase information updated successfully!');
            return redirect()->route('purchase-info.index');
        }else{
            //Session::flash('error','Purchase information edit process failed!');
            return back()->with('error', 'Purchase information edit process failed!');
        }
        //$product = Product::where('product_id', $id)->first();
       // return $product;
        // if ($product) {
        //     if ($product) {
        //         $product->product_id = $id;
        //         $product->purchase_info_slug = $request->product_name . '-' . time();
        //         $product->product_name = $request->product_name;
        //         $product->product_details = $request->product_details;
        //         $product->product_wholesale_price = $request->product_wholesale_price;
        //         $product->product_retail_price = $request->product_retail_price;
        //         $product->updated_by = Auth::user()->name;
        //         if ($request->hasFile('purchase_info_photo')) {
        //             $file = $request->file('purchase_info_photo');
        //             $ext = $file->getClientOriginalExtension();
        //             $filename = uniqid() . '.' . $ext;
        //             $file->move('uploads/product/', $filename);
        //             $product->purchase_info_photo =  'uploads/product/' . $filename;
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
        $product = PurchaseInfo::where('purchase_info_id', $id)->first();
        $path = 'uploads/purchase-info/'.$product['purchase_info_photo'];
        if(File::exists($path)){
            File::delete($path);
        }
        $delete = PurchaseInfo::where('purchase_info_id', $id)->delete();
        if($delete){
            Session::flash('success','Purchase deleted successfully!');
            return redirect()->route('purchase-info.index');
        }else{
            Session::flash('error','Purchase delete process failed!');
            return redirect()->route('purchase-info.index');
        }
    }

    public function stock()
    {
        $stock = Stock::orderBy('id', 'DESC')->get();
        return view('admin.stock.all', compact('stock'));
    }

}
