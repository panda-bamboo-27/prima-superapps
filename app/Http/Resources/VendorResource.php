<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'vendor_code'     => $this->vendor_code,
            'vendor_name'     => $this->vendor_name,
            'address'         => $this->address,
            'contact_phone_1' => $this->contact_phone_1,
            'contact_phone_2' => $this->contact_phone_2,
            'email'           => $this->email,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'deleted_at'      => $this->deleted_at,
        ];
    }
}
