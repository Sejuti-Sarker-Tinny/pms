<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodItem;
use App\Models\OrderNumber;
use App\Models\OrderDetails;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Image;
use File;
use Auth;

class ProductController extends Controller{
    public function cart()
    {
        return view('cart');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart($id)
    {
        $product = FoodItem::findOrFail($id);
          
        $cart = session()->get('cart', []);
  
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "name" => $product->food_item_name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->food_item_img
            ];
        }
          
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Food item added to cart successfully!');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Food item removed successfully');
        }
    }

    public function place_order(Request $request){


        //$item = $request->item;    
    
        $customer = Auth::user()->id;
        $t=0;

        foreach($request->item['id'] as $item){
            $t=$t+1;
            //echo $t.'/';
        }
        //echo '='.$t;
        // echo $t;
        
        $insertOrderId = OrderNumber::insertGetId([
            'order_total_price'=>$request->total,
            'created_at'=>Carbon::now()->toDateTimeString()
        ]);

        if ($insertOrderId) {

            $c=0;
            //foreach($item as $items){
                while($c<$t){
                //if($c<$t){
                $insert = OrderDetails::insert([
                    'customer_id'=>$customer,
                    'food_id'=>$request->item['id'][$c],
                    'order_quantity'=>$request->item['qty'][$c],
                    'total_price'=>$request->item['subtotal'][$c],
                    'order_number_id'=>$insertOrderId,
                    'created_at'=>Carbon::now()->toDateTimeString()
                ]);

                $c=$c+1;
            //  }else{
            //      break;
            //  }
            }                
            return redirect('/')->with('success', 'Order placed successfully!');
            // return $insertOrderId;
        } else {
            return back()->with('error', 'Something went Wrong');
        }

     }

    public function orders(){
        $all = OrderDetails::orderBy('id', 'DESC')->get();
        return view('admin.order.all', compact('all'));
    }


    public function order_deliver($id){
        $delivered = OrderDetails::where('id', $id)->update([
            'order_deliver_status'=>'1'
        ]);
        
        
        
        if($delivered){
            return back()->with('success', 'Order delivered successfully!');
    
        } else {
            
            return back()->with('error', 'Something went Wrong');
        }
    
    }


    public function customer_profile($id){
        
        $data = User::where('id', $id)->first();
        
        if($data){
            return view('admin.order.view_customer_profile', compact('data'));
    
        } else {            
            return back()->with('error', 'Something went Wrong');
        
        }
    }




}



