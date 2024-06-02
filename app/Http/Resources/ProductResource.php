<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'ean_code' => $this->ean_code,
            'quantity' => $this->quantity,
            'buying_price' => $this->buying_price,
            'selling_price' => $this->selling_price,
            'margin' => $this->margin,
            'discount' => $this->discount,
            'description' => $this->description,
            'comment' => $this->comment,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
        ];
    }
}
