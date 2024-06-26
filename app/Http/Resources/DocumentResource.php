<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\DocumentTypeResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $status = $this->status;
        switch ($status) {
            case 'P':
                $status = 'Payé';
                break;
            case 'E':
                $status = 'En cours';
                break;
            case 'N':
                $status = 'Impayé';
                break;
            default:
                $status = 'Inconnu'; // Optionnel, pour gérer les cas inattendus
        }

        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer' => CustomerResource::make($this->customer),
            'documenttype_id' => $this->documenttype_id,
            'documenttype' => DocumentTypeResource::make($this->documenttype),
            'product_id' => $this->product_id,
            'product' => ProductResource::make($this->product),
            'reference_number' => $this->reference_number,
            'document_date' => Carbon::parse($this->document_date)->format('m/d/Y'),
            'due_date' => Carbon::parse($this->due_date)->format('m/d/Y'),
            'price_htva' => $this->price_htva,
            'price_vvat' => $this->price_vvat,
            'price_total' => $this->price_total,
            'price_tvac' => $this->price_tvac,
            'status' => $status,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y H:i:s'),
            'customer' => $this->customer,
            'documenttype' => $this->documenttype,
            'products' => $this->products,
        ];
    }
}
