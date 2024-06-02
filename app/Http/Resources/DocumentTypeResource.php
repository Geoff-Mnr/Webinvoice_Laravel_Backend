<?php

namespace App\Http\Resources;



use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DocumentTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference, 
            'name' => $this->name,
            'status' => $this->status === 'A' ? 'Actif' : 'Inactif',
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
        ];
    }
}
