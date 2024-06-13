<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\UserResource;


class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
        'title' => $this->title,
        'comment' => $this->comment,
        'description' => $this->description,
        'status' => $this->status,
        'is_active' => $this->is_active,
        'created_by' => $this->created_by,
        'updated_by' => $this->updated_by,
        'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
        'updated_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
        'users' => $this->whenLoaded('users', function () {
            return $this->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->username,
                    'email' => $user->email,
                    'pivot' => [
                        'message' => $user->pivot->message,
                        'response' => $user->pivot->response,
                        'status' => $user->pivot->status,
                        'created_by' => $user->pivot->created_by,
                        'updated_by' => $user->pivot->updated_by,
                    ],
                ];
            });
        }),
        ];
    }
}