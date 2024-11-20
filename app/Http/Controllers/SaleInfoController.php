<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleInfo;
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
use PDF;

class SaleInfoController extends Controller{
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
        $SaleInfo = SaleInfo::orderBy('sale_info_id', 'DESC')->get();
        return view('admin.sale.all', compact('SaleInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sale.add');
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
            'product_quantity' => 'numeric|required',
            'product_discount_in_percentage' => 'numeric|required',
            'sale_info_photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[

        ]);

        $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');

        if($StockProductQuantity==''){
            return redirect()->route('sale-info.create')->with('error', 'This product is not available in the stock');
        }elseif($request->product_quantity>$StockProductQuantity){
            return redirect()->route('sale-info.create')->with('error', 'You have requested more quantity than available in the stock');
        }


        $sale_info_slug = 'SALE'.uniqid().'-'.time();
        $invoice_number = $request->sale_date.'-'.uniqid();
        $created_by = Auth::user()->name;

        if($request->sale_type=='retail'){
            $product_price_per_unit = Product::where('product_id', $request->product_id)->value('product_retail_price');            
        }elseif($request->sale_type=='wholesale'){
            $product_price_per_unit = Product::where('product_id', $request->product_id)->value('product_wholesale_price');            
        }

        $product_total_price = $request->product_quantity*$product_price_per_unit;
        if($request->product_discount_in_percentage!=''){
            $discount_price_by_percentage = ($product_total_price*$request->product_discount_in_percentage)/100;
        }else{
            $discount_price_by_percentage=0;
        }
        $product_total_price_after_discount = $product_total_price - $discount_price_by_percentage;

        $id = SaleInfo::insertGetId([
            'invoice_number'=>$invoice_number,
            'sale_date'=>$request->sale_date,
            'sale_type'=>$request->sale_type,
            'product_id'=>$request->product_id,
            'product_quantity'=>$request->product_quantity,
            'product_price_per_unit'=>$product_price_per_unit,
            'product_discount_in_percentage'=>$request->product_discount_in_percentage,
            'product_total_price'=>$product_total_price,
            'payment_status'=>$request->payment_status,
            'product_total_price'=>$product_total_price,
            'product_total_price_after_discount'=>$product_total_price_after_discount,
            'sale_remarks'=>$request->sale_remarks,
            'sale_info_slug'=>$sale_info_slug,
            'created_by'=>$created_by,
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);

        if($request->hasFile('sale_info_photo')) {
            $image=$request->file('sale_info_photo');
            $imageName='sale_info_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200,200)->save('uploads/sale-info/'.$imageName);

            SaleInfo::where('sale_info_id', $id)->update([
              'sale_info_photo'=>$imageName,
            ]);
        }

        // $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');
        // Stock::where('product_id', $request->product_id)->update([
        //     'product_quantity'=>$StockProductQuantity-$request->product_quantity,
        //     'updated_at'=>Carbon::now()->toDateTimeString(),
        // ]);

        $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');

            Stock::where('product_id', $request->product_id)->update([
                'product_quantity'=>$StockProductQuantity-$request->product_quantity,
                'updated_at'=>Carbon::now()->toDateTimeString(),
            ]);
        

        return redirect()->route('sale-info.index')->with('success', 'sale info added successfully');
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
        $data = SaleInfo::where('sale_info_slug', $slug)->first();
        return view('admin.sale.edit', compact('data'));
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
        $id = $request->sale_info_id;
        $this->validate($request, [
            'product_quantity' => 'numeric|required',
            'product_discount_in_percentage' => 'numeric|required',
            'returned_product_quantity' => 'numeric',
            'sale_info_photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ],[

        ]);

        $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');

        // if($StockProductQuantity==''){
        //     return redirect()->route('sale-info-edit')->with('error', 'This product is not available in the stock');
        // }elseif($request->product_quantity>$StockProductQuantity){
        //     return redirect()->route('sale-info-edit')->with('error', 'You have requested more quantity than available in the stock');
        // }


        if($request->returned_product_quantity==0){
            $sale_info_slug = 'SALE'.uniqid().'-'.time();
            $invoice_number = $request->sale_date.'-'.uniqid();
            $updated_by = Auth::user()->name;
    
            if($request->sale_type=='retail'){
                $product_price_per_unit = Product::where('product_id', $request->product_id)->value('product_retail_price');            
            }elseif($request->sale_type=='wholesale'){
                $product_price_per_unit = Product::where('product_id', $request->product_id)->value('product_wholesale_price');            
            }
    
            $product_total_price = $request->product_quantity*$product_price_per_unit;
            if($request->product_discount_in_percentage!=''){
                $discount_price_by_percentage = ($product_total_price*$request->product_discount_in_percentage)/100;
            }else{
                $discount_price_by_percentage=0;
            }
            $product_total_price_after_discount = $product_total_price - $discount_price_by_percentage;
    
            $update = SaleInfo::where('sale_info_id', $id)->update([
                'invoice_number'=>$invoice_number,
                'sale_date'=>$request->sale_date,
                'sale_type'=>$request->sale_type,
                //'product_id'=>$request->product_id,
                //'product_quantity'=>$request->product_quantity,
                'product_price_per_unit'=>$product_price_per_unit,
                'product_discount_in_percentage'=>$request->product_discount_in_percentage,
                'product_total_price'=>$product_total_price,
                'payment_status'=>$request->payment_status,
                'product_total_price'=>$product_total_price,
                'product_total_price_after_discount'=>$product_total_price_after_discount,
                'sale_remarks'=>$request->sale_remarks,
                'sale_info_slug'=>$sale_info_slug,
                'updated_by'=>$updated_by,
                'updated_at'=>Carbon::now()->toDateTimeString(),
            ]);
    
            if($request->hasFile('sale_info_photo')){
                $image=$request->file('sale_info_photo');
                $imageName='sale_info_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
                Image::make($image)->resize(200,200)->save('uploads/sale-info/'.$imageName);
    
                SaleInfo::where('sale_info_id', $id)->update([
                  'sale_info_photo'=>$imageName,
                ]);  
            }
    
            // $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');
            // Stock::where('product_id', $request->product_id)->update([
            //     'product_quantity'=>$StockProductQuantity-$product_quantity,
            //     'updated_at'=>Carbon::now()->toDateTimeString(),
            // ]);
    
            if($update){
                Session::flash('success','Sale information updated successfully!');
                return redirect()->route('sale-info.index');
            }else{
                //Session::flash('error','sale information edit process failed!');
                return back()->with('error', 'Sale information edit process failed!');
            }
            //$product = Product::where('product_id', $id)->first();
           // return $product;
            // if ($product) {
            //     if ($product) {
            //         $product->product_id = $id;
            //         $product->sale_info_slug = $request->product_name . '-' . time();
            //         $product->product_name = $request->product_name;
            //         $product->product_details = $request->product_details;
            //         $product->product_wholesale_price = $request->product_wholesale_price;
            //         $product->product_retail_price = $request->product_retail_price;
            //         $product->updated_by = Auth::user()->name;
            //         if ($request->hasFile('sale_info_photo')) {
            //             $file = $request->file('sale_info_photo');
            //             $ext = $file->getClientOriginalExtension();
            //             $filename = uniqid() . '.' . $ext;
            //             $file->move('uploads/product/', $filename);
            //             $product->sale_info_photo =  'uploads/product/' . $filename;
            //         }
            //         $product->update();
            //         return redirect()->route('product.index')->with('success', 'Product updated successfully');
            //     } else {
            //         return back()->with('error', 'Data Not Faound');
            //     }
    
            //}

        }elseif($request->returned_product_quantity>$request->product_quantity){
            return redirect()->route('sale-info-edit')->with('error', 'You want to return more quantity than you have saled!');
        }elseif($request->returned_product_quantity<=$request->product_quantity){
            Stock::where('product_id', $request->product_id)->update([
                'product_quantity'=>$StockProductQuantity+$request->returned_product_quantity,
                'updated_at'=>Carbon::now()->toDateTimeString(),
            ]);

            $SalesInfoUpdatedQuantity = $request->product_quantity-$request->returned_product_quantity;

            $sale_info_slug = 'SALE'.uniqid().'-'.time();
            $invoice_number = $request->sale_date.'-'.uniqid();
            $updated_by = Auth::user()->name;
    
            if($request->sale_type=='retail'){
                $product_price_per_unit = Product::where('product_id', $request->product_id)->value('product_retail_price');            
            }elseif($request->sale_type=='wholesale'){
                $product_price_per_unit = Product::where('product_id', $request->product_id)->value('product_wholesale_price');            
            }
    
            $product_total_price = $SalesInfoUpdatedQuantity * $product_price_per_unit;
            if($request->product_discount_in_percentage!=''){
                $discount_price_by_percentage = ($product_total_price*$request->product_discount_in_percentage)/100;
            }else{
                $discount_price_by_percentage=0;
            }
            $product_total_price_after_discount = $product_total_price - $discount_price_by_percentage;
            //
            $abs_product_quantity=abs($request->product_quantity-$request->returned_product_quantity);
            // SaleInfo::where('sale_info_id', $id)->update([
            //     'product_quantity'=>$request->product_quantity,
            //     'updated_by'=>$updated_by,
            //     'updated_at'=>Carbon::now()->toDateTimeString(),
            // ]);
            //
            $update = SaleInfo::where('sale_info_id', $id)->update([
                'invoice_number'=>$invoice_number,
                'sale_date'=>$request->sale_date,
                'sale_type'=>$request->sale_type,
                //'product_id'=>$request->product_id,
                'product_quantity'=>$abs_product_quantity,
                'product_price_per_unit'=>$product_price_per_unit,
                'product_discount_in_percentage'=>$request->product_discount_in_percentage,
                'product_total_price'=>$product_total_price,
                'payment_status'=>$request->payment_status,
                'product_total_price'=>$product_total_price,
                'product_total_price_after_discount'=>$product_total_price_after_discount,
                'sale_remarks'=>$request->sale_remarks,
                'sale_info_slug'=>$sale_info_slug,
                'updated_by'=>$updated_by,
                'updated_at'=>Carbon::now()->toDateTimeString(),
            ]);
    
            if($request->hasFile('sale_info_photo')){
                $image=$request->file('sale_info_photo');
                $imageName='sale_info_'.$id.'_'.time().'.'.$image->getClientOriginalExtension();
                Image::make($image)->resize(200,200)->save('uploads/sale-info/'.$imageName);
    
                SaleInfo::where('sale_info_id', $id)->update([
                  'sale_info_photo'=>$imageName,
                ]);  
            }
    
            // $StockProductQuantity= Stock::where('product_id', $request->product_id)->value('product_quantity');
            // Stock::where('product_id', $request->product_id)->update([
            //     'product_quantity'=>$StockProductQuantity-$product_quantity,
            //     'updated_at'=>Carbon::now()->toDateTimeString(),
            // ]);
    
            if($update){
                Session::flash('success','Sale information updated successfully!');
                return redirect()->route('sale-info.index');
            }else{
                //Session::flash('error','sale information edit process failed!');
                return back()->with('error', 'Sale information edit process failed!');
            }
            //$product = Product::where('product_id', $id)->first();
           // return $product;
            // if ($product) {
            //     if ($product) {
            //         $product->product_id = $id;
            //         $product->sale_info_slug = $request->product_name . '-' . time();
            //         $product->product_name = $request->product_name;
            //         $product->product_details = $request->product_details;
            //         $product->product_wholesale_price = $request->product_wholesale_price;
            //         $product->product_retail_price = $request->product_retail_price;
            //         $product->updated_by = Auth::user()->name;
            //         if ($request->hasFile('sale_info_photo')) {
            //             $file = $request->file('sale_info_photo');
            //             $ext = $file->getClientOriginalExtension();
            //             $filename = uniqid() . '.' . $ext;
            //             $file->move('uploads/product/', $filename);
            //             $product->sale_info_photo =  'uploads/product/' . $filename;
            //         }
            //         $product->update();
            //         return redirect()->route('product.index')->with('success', 'Product updated successfully');
            //     } else {
            //         return back()->with('error', 'Data Not Faound');
            //     }
    
            //}

           
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = SaleInfo::where('sale_info_id', $id)->first();
        $path = 'uploads/sale-info/'.$product['sale_info_photo'];
        if(File::exists($path)){
            File::delete($path);
        }
        $delete = SaleInfo::where('sale_info_id', $id)->delete();
        if($delete){
            Session::flash('success','Sale information deleted successfully!');
            return redirect()->route('sale-info.index');
        }else{
            Session::flash('error','Sale information delete process failed!');
            return redirect()->route('sale-info.index');
        }
    }

    public function view($sale_info_slug)
    {
        //$pdf = PDF::loadView('invoice.sale-info-invoice', $id);
        //$pdf = PDF::view('invoice.sale-info-invoice', $id);
        $data = SaleInfo::where('sale_info_slug', $sale_info_slug)->first();
        return view('admin.invoice.sale-info-invoice', compact('data'));
    }
}