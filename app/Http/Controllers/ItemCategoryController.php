<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'order_column_by'   => ['required',Rule::in('name','user_id','created_at')],
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
        $item_category = ItemCategory::orderBy($order_column_by,$order_type);

        // check if there is user_id applied to the filter
        if ($users != null) {
            $item_category->whereHas('user',function($q) use ($users){
                $q->whereIn('users.id',$users);
            });
        }

        if ($request->keyword != null) {
            $item_category->where('name','like',"%" . strtolower($request->keyword) . "%");
        }

        $item_category->with('user');

        if ($request->with_trashed) {
            $item_category->withTrashed();
        }
        
        // apply to the models
        $item_category = $item_category->cursorPaginate($perPage = $number_per_page);

        return response()->json([
            'message'   => 'Item Categories fetched.',
            'data'      =>  new VendorCollection($item_category),
            'meta'      =>  [
                'count' => $item_category->count(),
                'next_cursor' => $item_category->nextCursor() ? $item_category->nextCursor()->encode() : null,
                'next_page_url' => $item_category->nextPageUrl(),
                'previous_cursor' => $item_category->previousCursor() ? $item_category->previousCursor()->encode() : null,
                'previous_page_url' => $item_category->previousPageUrl(),
                'per_page' => $item_category->perPage(),
                'on_first_page' => $item_category->onFirstPage(),
                'on_last_page' => $item_category->onlastPage(),
                'has_pages' => $item_category->hasPages(),
                'has_more_pages' => $item_category->hasMorePages(),
            ]
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
    public function show(ItemCategory $itemCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemCategory $itemCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemCategory $itemCategory)
    {
        //
    }
}
