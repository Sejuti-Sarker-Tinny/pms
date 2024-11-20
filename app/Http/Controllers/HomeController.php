<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\SubCategory;

use App\Models\Review;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller{

    public function subCategoryFoodItem(Request $request, $slug)
    {

        // $data = $request->all();
        // return $data;
        // $purchaseQuery = FoodItem::query();
        // $search   = $request->input('search', null);
        $price_range   = $request->input('price_range', null);
        $spice_level   = $request->input('spice_level', null);
        $sugar_level   = $request->input('sugar_level', null);
        //return $spice_level;

        $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();

        $subcategories = $subcategories->foodItem;
        // return $subcategories;
        if ($price_range!='' && $spice_level!='' && $sugar_level!='') {
            $price = explode('-', $price_range);
            //dd($price);
            $price[0] = floor($price[0]);
            $price[1] = ceil($price[1]);
            $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();
            $subcategoriesfood = $subcategories->foodItem;
            $subcategories =  $subcategoriesfood->whereBetween('price', $price)->where('spice_level', $spice_level)->where('sugar_level', $sugar_level);
            // return $subcategories;
        }else if ($price_range!='' && $spice_level=='' && $sugar_level!='') {
            $price = explode('-', $price_range);
            //dd($price);
            $price[0] = floor($price[0]);
            $price[1] = ceil($price[1]);
            $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();
            $subcategoriesfood = $subcategories->foodItem;
            $subcategories =  $subcategoriesfood->whereBetween('price', $price)->where('sugar_level', $sugar_level);
            // return $subcategories;
        }else if ($price_range=='' && $spice_level!='' && $sugar_level!='') {
            //$price = explode('-', $price_range);
            //dd($price);
            //$price[0] = floor($price[0]);
            //$price[1] = ceil($price[1]);
            $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();
            $subcategoriesfood = $subcategories->foodItem;
            $subcategories =  $subcategoriesfood->where('spice_level', $spice_level)->where('sugar_level', $sugar_level);
            // return $subcategories;
        }else if ($price_range!='' && $spice_level!='' && $sugar_level=='') {
            $price = explode('-', $price_range);
            //dd($price);
            $price[0] = floor($price[0]);
            $price[1] = ceil($price[1]);
            $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();
            $subcategoriesfood = $subcategories->foodItem;
            $subcategories =  $subcategoriesfood->whereBetween('price', $price)->where('spice_level', $spice_level);
            // return $subcategories;
        }else if ($price_range=='' && $spice_level=='' && $sugar_level!='') {
            //$price = explode('-', $price_range);
            //dd($price);
            //$price[0] = floor($price[0]);
            //$price[1] = ceil($price[1]);
            $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();
            $subcategoriesfood = $subcategories->foodItem;
            $subcategories =  $subcategoriesfood->where('sugar_level', $sugar_level);
            // return $subcategories;
        }else if ($price_range!='' && $spice_level=='' && $sugar_level=='') {
            $price = explode('-', $price_range);
            //dd($price);
            $price[0] = floor($price[0]);
            $price[1] = ceil($price[1]);
            $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();
            $subcategoriesfood = $subcategories->foodItem;
            $subcategories =  $subcategoriesfood->whereBetween('price', $price);
            // return $subcategories;
        }else if ($price_range=='' && $spice_level!='' && $sugar_level=='') {
            //$price = explode('-', $price_range);
            //dd($price);
            //$price[0] = floor($price[0]);
            //$price[1] = ceil($price[1]);
            $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();
            $subcategoriesfood = $subcategories->foodItem;
            $subcategories =  $subcategoriesfood->where('spice_level', $spice_level);
            // return $subcategories;
        }else if ($price_range=='' && $spice_level=='' && $sugar_level=='') {
            //$price = explode('-', $price_range);
            //dd($price);
            //$price[0] = floor($price[0]);
            //$price[1] = ceil($price[1]);
            $subcategories = SubCategory::with('foodItem', 'category')->where('sub_cat_slug', $slug)->first();
            $subcategoriesfood = $subcategories->foodItem;
            $subcategories =  $subcategoriesfood->all();
            // return $subcategories;
        }


        return view('website.shop', compact(['subcategories','spice_level','sugar_level']));
    }
    public function singlesubCategoryFoodItem($slug)
    {
        $fooditem = FoodItem::with('category', 'subcategory')->where('food_item_slug', $slug)->first();
        return view('website.single_food', compact(['fooditem']));
    }


    
    
    
    public function add_review(){
    
        return view('admin.review.add');
    
    
    }

    public function submit_review(Request $request){

        $this->validate($request,[
            'review'=>'required',
        ],[
            'review.required'=>'Review is required.',
        ]);

        $customer = Auth::user()->id;

        $insert = Review::insert([
            'customer_id'=>$customer,
            'review'=>$request->review,
            'created_at'=>Carbon::now()->toDateTimeString(),
        ]);


        if($insert){
 
            Session::flash('success','Review successfully added!');
            return redirect()->route('add_review');

        }else{
            Session::flash('error','Review addtion process failed!');

            return redirect()->route('add_review');
        }

    }


    public function review(){


        $review = Review::orderBy('id', 'DESC')->get();
        return view('website.review', compact('review'));
    
    
    }



    public function rating_submit(Request $request){

        $id = $request->id;
        $rating_point = $request->rate;
        $food = FoodItem::where('id', $id)->first();
        $number_of_total_ratings = $food->number_of_total_ratings+1;
        $total_rating_point = $food->total_rating_point+$rating_point;
        $update = FoodItem::where('id', $id)->update([
            'number_of_total_ratings'=>$number_of_total_ratings,
            'total_rating_point'=>$total_rating_point,
            'updated_at'=>Carbon::now()->toDateTimeString(),
        ]);
        $review_no = $number_of_total_ratings;
        $rating = number_format($total_rating_point/$review_no,1);
        if($update){
            return response()->json(['success'=>'Your Rating Point Submitted Successfully.',
                                        'total_review'=>$review_no,
                                        'rating'=>$rating,
                                    ]);
        }else{
            return response()->json(['fail'=>'0']);
        }


    }

}


