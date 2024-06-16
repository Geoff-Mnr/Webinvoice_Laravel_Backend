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
            'status' => $this->status === 'N' ? 'Ouvert' : 'Fermé',
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($this->created_at)->format('d/m/Y H:i:s'),
            'createdBy' => new UserResource($this->whenLoaded('createdBy')),
            'users' => $this->whenLoaded('users', function () {
                return $this->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'profile_picture' => $user->profile_picture,
                        'role_name' => $user->role->name,
                        'pivot' => [
                            'message' => $user->pivot->message,
                            'response' => $user->pivot->response,
                            'status' => $user->pivot->status,
                            'created_by' => $user->pivot->created_by,
                            'updated_by' => $user->pivot->updated_by,
                            'created_at' => Carbon::parse($user->pivot->created_at)->format('d/m/Y H:i:s'),
                            'updated_at' => Carbon::parse($user->pivot->updated_at)->format('d/m/Y H:i:s'),
                        ],
                    ];
                });
            }),
        ];
    }
}
