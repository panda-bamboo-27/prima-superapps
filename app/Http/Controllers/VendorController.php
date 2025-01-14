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
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        //create validator class
        $validator = Validator::make($request->all(),[
            'number_per_page'   => 'required|integer|min:4',
            'order_column_by'   => ['required',Rule::in('vendor_code','vendor_name','email','user_id','created_at')],
            'order_type'        => ['required',Rule::in('asc','desc')],
            'users'             => 'nullable|array',
            'keyword'           => 'nullable',
            'with_trashed'      => [Rule::in('yes','no')]
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
        $vendors = Vendor::orderBy($order_column_by,$order_type);

        // check if there is user_id applied to the filter
        if ($users != null) {
            $vendors->whereHas('user',function($q) use ($users){
                $q->whereIn('users.id',$users);
            });
        }

        if ($request->keyword != null) {
            $vendors->where('vendor_name','like',"%" . strtolower($request->keyword) . "%")
                    ->orWhere('vendor_code','like',"%" . strtolower($request->keyword) . "%");
        }

        $vendors->with('user');

        if ($request->with_trashed) {
            $vendors->withTrashed();
        }
        
        // apply to the models
        $vendors = $vendors->cursorPaginate($perPage = $number_per_page);

        return response()->json([
            'message'   => 'Vendors fetched.',
            'data'      =>  new VendorCollection($vendors),
            'meta'      =>  [
                'count' => $vendors->count(),
                'next_cursor' => $vendors->nextCursor() ? $vendors->nextCursor()->encode() : null,
                'next_page_url' => $vendors->nextPageUrl(),
                'previous_cursor' => $vendors->previousCursor() ? $vendors->previousCursor()->encode() : null,
                'previous_page_url' => $vendors->previousPageUrl(),
                'per_page' => $vendors->perPage(),
                'on_first_page' => $vendors->onFirstPage(),
                'on_last_page' => $vendors->onlastPage(),
                'has_pages' => $vendors->hasPages(),
                'has_more_pages' => $vendors->hasMorePages(),
            ]
        ], 200);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        //create validator class
        $validator = Validator::make($request->all(),[
            'vendor_code'  => 'required||size:10|starts_with:V|unique:vendors,vendor_code',
            'vendor_name'  => 'required|max:100',
            'address'  => 'nullable|max:100',
            'contact_phone_1'  => 'nullable|max:20',
            'contact_phone_2'  => 'nullable|max:20',
            'email'  => 'nullable|max:60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    =>  $validator->errors()
            ], 422);
        }

        $vendor = new Vendor();

        $vendor->vendor_code = $request->vendor_code;
        $vendor->vendor_name = $request->vendor_name;
        $vendor->address = $request->address ?? null;
        $vendor->contact_phone_1 = $request->contact_phone_1 ?? null;
        $vendor->contact_phone_2 = $request->contact_phone_2 ?? null;
        $vendor->email = $request->email ?? null;
        $vendor->user_id = $user->id;

        $vendor->save();

        return response()->json([
            'message'   => 'Vendor successfully added.',
            'data'      => new VendorResource($vendor)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        return response()->json([
            'message'   => 'Vendor successfully fetched.',
            'data'      => new VendorResource($vendor)
        ], 422);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $user = Auth::user();

        //create validator class
        $validator = Validator::make($request->all(),[
            'vendor_code'       => 'required||size:10|starts_with:V|unique:vendors,vendor_code,' . $vendor->id,
            'vendor_name'       => 'required|max:100',
            'address'           => 'required|max:100',
            'contact_phone_1'   => 'nullable|max:20',
            'contact_phone_2'   => 'nullable|max:20',
            'email'             => 'nullable|max:60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    =>  $validator->errors()
            ], 422);
        }

        $vendor->vendor_code = $request->vendor_code;
        $vendor->vendor_name = $request->vendor_name;
        
        $vendor->address = $request->address ?? null;
        $vendor->contact_phone_1 = $request->contact_phone_1 ?? null;
        $vendor->contact_phone_2 = $request->contact_phone_2 ?? null;
        $vendor->email = $request->email ?? null;

        $vendor->save();

        return response()->json([
            'message'   => 'Vendor successfully updated.',
            'data'      => new VendorResource($vendor)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {   
        $vendor->delete();

        return response()->json([
            'message'   => 'Vendor successfully deleted.',
            'data'      => new VendorResource($vendor)
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($vendorId)
    {   
        $vendor = Vendor::withTrashed()->find($vendorId);

        if ($vendor == null) {
            return response()->json([
                'message'   => 'Vendor not found.',
            ], 404);
        }

        $vendor->restore();

        return response()->json([
            'message'   => 'Vendor successfully restored.',
            'data'      => new VendorResource($vendor)
        ], 200);
    }
}
