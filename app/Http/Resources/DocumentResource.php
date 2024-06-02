<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'document_date' => Carbon::parse($this->document_date)->format('d/m/Y'),
            'due_date' => Carbon::parse($this->due_date)->format('d/m/Y'),
            'price_htva' => $this->price_htva,
            'price_vvat' => $this->price_vvat,
            'price_total' => $this->price_total,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i:s'),
            'customer' => $this->customer,
            'documenttype' => $this->documenttype,
            'products' => $this->products,
        ];
    }
}
