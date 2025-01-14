<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\VendorCollection;
use App\Http\Resources\VendorResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {   
    //     $validator = Validator::make($request->all(),[
    //         'page'  =>  'required|integer|min:1',
    //         'number_per_page'   => 'required|integer|min:10',
    //         'order_column_by'   => ['required',Rule::in('name','price','vendor_name')],
    //         'order_type'    =>  ['required',Rule::in('asc','desc')],
    //         'vendors'   => 'nullable|integer',
    //         'item_categories'   => 'nullable|integer',
    //         'price_min'   => 'nullable|integer|lte:price_max',
    //         'price_max'   => 'nullable|integer|gte:price_min|required_with:price_min',
    //     ]);

        
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message'   => 'Validation Error',
    //             'errors'    =>  $validator->errors()
    //         ], 422);
    //     }

        
    //     // required
    //     $page = $request->page;
    //     $number_per_page = $request->$number_per_page;

    //     //optional
    //     $vendors = $request->vendors;
    //     $item_categories = $request->$item_categories;

    //     $price_min = $request->price_min;
    //     $price_max = $request->price_max;

    //     $order_column_by = $request->order_column_by;
    //     $order_type = $request->order_type;

    //     $vendors = Vendor::
        
    //     return new VendorCollection(Vendor::all());
    // }

    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        /*
        //create validator class
        $validator = Validator::make($request->all(),[
            'number_per_page'   => 'required|integer|min:4',
            'order_column_by'   => ['required',Rule::in('vendor_code','vendor_name','email','user_id')],
            'order_type'        => ['required',Rule::in('asc','desc')],
            'users'             => 'nullable|array',
        ]);


        //if validation fails then return the error(s).
        if ($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    =>  $validator->errors()
            ], 422);
        }

        // number per page using in pagination
        $number_per_page = $request->number_per_page;

        // number per page using in pagination
        $users = $request->users;

        // column and order type using in pagination
        $order_column_by = $request->order_column_by;
        $order_type = $request->order_type;


        // fetch models
        $vendors = Vendor::with('users');

        // check if there is user_id applied to the filter
        if ($users != null) {
            $vendors->whereHas('users',function($q) use ($users){
                $q->whereIn('users.id',$users);
            });
        }
        

        
        // apply to the models
        $vendors->orderBy($order_column_by,$order_type);
        
        $vendors = $vendors->cursorPaginate($perPage = $number_per_page);

        // do pagination
        
        // $vendors = DB::table('vendors')
        //                 ->select('vendors.*','users.name','users.email as user_email')
        //                 ->join('users', 'users.id', '=', 'vendors.user_id')
        //                 ->orderBy('id')
        //                 ->cursorPaginate(15);

        */

        $vendors = User::orderBy('created_at','desc')->cursorPaginate(10);

        return response()->json([
            // 'sql'       => $sql,
            'message'   => 'Vendors fetched.',
            'data'      =>  $vendors,
        ], 200);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        return new VendorResource($vendor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
    }
}
