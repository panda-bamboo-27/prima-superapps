<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ItemCollection;
use App\Http\Resources\ItemCategoryResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //create validator class
        $validator = Validator::make($request->all(),[
            'number_per_page'   => 'required|integer|min:4',
            'order_column_by'   => ['required',Rule::in(
                'name',
                'price_per_unit',
                'unit',
                'vendor_item_code',
                'vendor_item_category',
                'vendor_id',
                'vendor_code',
                'vendor_name',
                'item_category_id',
                'item_category_name',
                'user_id',
                'user_name',
                'created_at')],
            'order_type'        => ['required',Rule::in('asc','desc')],
            'users'             => 'nullable|array',
            'vendors'           => 'nullable|array',
            'item_categories'   => 'nullable|array'
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

        // users using in pagination
        $users = $request->users;

        // vendors using in pagination
        $vendors = $request->vendors;
        
        // item_categories using in pagination
        $itemCategories = $request->item_categories;

        // column and order type using in pagination
        $order_column_by = $request->order_column_by;
        $order_type = $request->order_type;

        // fetch models
        $item = Item::orderBy($order_column_by,$order_type);

        // check if there is user_id applied to the filter
        if ($users != null) {
            $item->whereHas('user',function($q) use ($users){
                $q->whereIn('users.id',$users);
            });
        }

        if ($request->keyword != null) {
            $item->where('name','like',"%" . strtolower($request->keyword) . "%");
        }

        $item->with('user',);

        if ($request->with_trashed === 'yes') {
            $item->withTrashed();
        }
        
        // apply to the models
        $item = $item->cursorPaginate($perPage = $number_per_page);

        return response()->json([
            'message'   => 'Item fetched.',
            'data'      =>  new ItemCollection($item),
            'meta'      =>  [
                'count' => $item->count(),
                'next_cursor' => $item->nextCursor() ? $item->nextCursor()->encode() : null,
                'next_page_url' => $item->nextPageUrl(),
                'previous_cursor' => $item->previousCursor() ? $item->previousCursor()->encode() : null,
                'previous_page_url' => $item->previousPageUrl(),
                'per_page' => $item->perPage(),
                'on_first_page' => $item->onFirstPage(),
                'on_last_page' => $item->onlastPage(),
                'has_pages' => $item->hasPages(),
                'has_more_pages' => $item->hasMorePages(),
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
            'name'  => 'required|max:80|unique:item_categories,name',
            'description'  => 'nullable|max:80',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    =>  $validator->errors()
            ], 422);
        }

        $item = new ItemCategory();

        $item->name = $request->name;
        $item->description = $request->description;
        $item->user_id = $user->id;

        $item->save();

        return response()->json([
            'message'   => 'Item successfully added.',
            'data'      => new ItemCategoryResource($item)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemCategory $item)
    {
        return response()->json([
            'message'   => 'Item successfully fetched.',
            'data'      => new ItemCategoryResource($item)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemCategory $item)
    {
        $user = Auth::user();

        //create validator class
        $validator = Validator::make($request->all(),[
            'name'  => 'required|max:80|unique:item_categories,name,'.$item->id,
            'description'  => 'nullable|max:80',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    =>  $validator->errors()
            ], 422);
        }

        $item->name = $request->name;
        $item->description = $request->description;

        $item->save();

        return response()->json([
            'message'   => 'Item successfully updated.',
            'data'      => new ItemCategoryResource($item)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemCategory $item)
    {   
        $item->delete();

        return response()->json([
            'message'   => 'Item successfully deleted.',
            'data'      => new ItemCategoryResource($item)
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($itemId)
    {   
        $item = ItemCategory::withTrashed()->find($itemId);

        if ($item == null) {
            return response()->json([
                'message'   => 'Item not found.',
            ], 404);
        }

        $item->restore();

        return response()->json([
            'message'   => 'Item successfully restored.',
            'data'      => new ItemCategoryResource($item)
        ], 200);
    }
}
