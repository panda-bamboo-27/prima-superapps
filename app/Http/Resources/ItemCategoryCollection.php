<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ItemCategoryResource;

class ItemCategoryCollection extends ResourceCollection
{


    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'item_categories'  => $this->collection->map(function($itemCategory) {
                return new ItemCategoryResource($itemCategory);
            }),
        ];
    }
}
