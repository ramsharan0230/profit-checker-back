<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'=> $this->name,
            'quantity'=> $this->quantity,
            'trade_price'=> $this->trade_price,
            'retail_price'=> $this->retail_price,
            'mpn'=> $this->mpn,
            'sku'=> $this->sku,
            'status'=> $this->status?"Active":"InActive",
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
